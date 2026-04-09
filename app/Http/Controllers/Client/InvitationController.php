<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Gallery;
use App\Models\Invitation;
use App\Models\InvitationDetail;
use App\Models\Music;
use App\Models\Order;
use App\Models\Package;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function index()
    {
        // Mengambil semua undangan milik klien yang sedang login
        $invitations = Invitation::with(['template', 'details'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.invitations.index', compact('invitations'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();
        $templates = Template::with('category')->where('is_active', true)->get();

        return view('customer.invitations.create', compact('packages', 'templates'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'slug' => 'required|string|alpha_dash|max:255|unique:invitations,slug',
                'package_id' => 'required|exists:packages,id',
                'template_id' => 'required|exists:templates,id',
            ],
            [
                'slug.unique' => 'Maaf, nama link ini sudah dipakai orang lain. Silakan cari nama lain.',
                'slug.alpha_dash' => 'Link hanya boleh berisi huruf, angka, strip (-), dan underscore (_). Tanpa spasi.',
            ],
        );

        $invitation = Invitation::create([
            'user_id' => Auth::id(),
            'template_id' => $request->template_id,
            'slug' => strtolower($request->slug),
            'status' => 'draft',
        ]);

        InvitationDetail::create([
            'invitation_id' => $invitation->id,
            'content' => json_encode([]),
        ]);

        Order::create([
            'order_number' => 'INV-' . time() . strtoupper(Str::random(5)),
            'user_id' => Auth::id(),
            'invitation_id' => $invitation->id,
            'package_id' => $request->package_id,
            'amount' => Package::find($request->package_id)->price,
            'status' => 'pending',
        ]);

        return redirect()->route('customer.invitations.edit', $invitation->id)->with('success', 'Langkah 1 Selesai! Silakan lengkapi data acara dan mempelai Anda.');
    }

    public function edit($id)
    {
        $invitation = Invitation::with(['details', 'template', 'music', 'orders.package'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $musics = Music::orderBy('category')->orderBy('title')->get();
        $templates = Template::where('is_active', true)->get();
        $content = json_decode($invitation->details->content ?? '{}', true);
        $masterBanks = Bank::where('is_active', true)->orderBy('name', 'asc')->get();

        $currentOrder = $invitation->orders->last();
        $packageLogic = [];
        $currentPackageName = 'Custom';
        $currentPackagePrice = 0;

        if ($currentOrder && $currentOrder->package) {
            $features = is_string($currentOrder->package->features) ? json_decode($currentOrder->package->features, true) : $currentOrder->package->features;
            $packageLogic = $features['logic'] ?? [];
            $currentPackageName = $currentOrder->package->name;
            $currentPackagePrice = $currentOrder->package->price;
        }

        $upgradePackages = Package::where('is_active', true)->where('price', '>', $currentPackagePrice)->orderBy('price', 'asc')->get();

        return view('customer.invitations.edit', compact('invitation', 'templates', 'musics', 'content', 'packageLogic', 'currentPackageName', 'currentPackagePrice', 'upgradePackages', 'masterBanks'));
    }

    public function update(Request $request, $id)
    {
        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($id);
        $oldContent = json_decode($invitation->details->content ?? '{}', true);

        // 🔥 VALIDASI SEMUA INPUT TERMASUK FITUR BARU 🔥
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'couple_order' => 'required|in:groom_first,bride_first',
            'cover_greeting' => 'nullable|string|max:50',
            'quotes' => 'nullable|string',

            'groom_name' => 'nullable|string|max:255',
            'groom_nickname' => 'nullable|string|max:255',
            'groom_father' => 'nullable|string|max:255',
            'groom_mother' => 'nullable|string|max:255',
            'groom_ig' => 'nullable|string|max:255',

            'bride_name' => 'nullable|string|max:255',
            'bride_nickname' => 'nullable|string|max:255',
            'bride_father' => 'nullable|string|max:255',
            'bride_mother' => 'nullable|string|max:255',
            'bride_ig' => 'nullable|string|max:255',

            'groom_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
            'bride_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',

            'turut_mengundang' => 'nullable|string',

            'akad_date' => 'nullable|date',
            'akad_time' => 'nullable|string',
            'akad_location' => 'nullable|string',
            'akad_address' => 'nullable|string',
            'akad_map' => 'nullable|url',

            'events' => 'nullable|array',

            'enable_dresscode' => 'nullable|boolean',
            'dresscode' => 'nullable|string',

            // VALIDASI ADAB, PROTOKOL, LIVESTREAM, & QR
            'enable_health_protocol' => 'nullable|boolean',
            'enable_adab_walimah' => 'nullable|boolean',
            'enable_qr_attendance' => 'nullable|boolean',
            'is_livestream_active' => 'nullable|boolean',
            'live_stream_platform' => 'nullable|string|in:youtube,tiktok,zoom,instagram',
            'live_stream_link' => 'nullable|url',

            'youtube_links' => 'nullable|array',
            'youtube_links.*' => 'nullable|url',

            'love_stories' => 'nullable|array',
            'love_stories.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:3048',
            'banks' => 'nullable|array',
            'gallery_files.*' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5048',
        ]);

        $coverImagePath = $oldContent['cover_image'] ?? null;
        if ($request->hasFile('cover_image')) {
            if ($coverImagePath && Storage::disk('public')->exists($coverImagePath)) {
                Storage::disk('public')->delete($coverImagePath);
            }
            $coverImagePath = $request->file('cover_image')->store('covers/' . $invitation->id, 'public');
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('galleries/' . $invitation->id, 'public');
                Gallery::create([
                    'invitation_id' => $invitation->id,
                    'file_path' => $path,
                    'type' => 'photo',
                ]);
            }
        }

        if ($invitation->template_id != $request->template_id) {
            $invitation->update(['template_id' => $request->template_id]);
        }

        $groomPhotoPath = $oldContent['groom_photo'] ?? null;
        if ($request->hasFile('groom_photo')) {
            if ($groomPhotoPath && Storage::disk('public')->exists($groomPhotoPath)) {
                Storage::disk('public')->delete($groomPhotoPath);
            }
            $groomPhotoPath = $request->file('groom_photo')->store('profiles/' . $invitation->id, 'public');
        }

        $bridePhotoPath = $oldContent['bride_photo'] ?? null;
        if ($request->hasFile('bride_photo')) {
            if ($bridePhotoPath && Storage::disk('public')->exists($bridePhotoPath)) {
                Storage::disk('public')->delete($bridePhotoPath);
            }
            $bridePhotoPath = $request->file('bride_photo')->store('profiles/' . $invitation->id, 'public');
        }

        if ($request->has('music_id')) {
            $invitation->update(['music_id' => $request->music_id]);
        }

        $loveStoriesData = [];
        $inputLoveStories = $request->love_stories ?? [];
        $oldLoveStories = $oldContent['love_stories'] ?? [];

        foreach ($inputLoveStories as $index => $story) {
            if (empty($story['title']) && empty($story['description'])) {
                continue;
            }
            $imagePath = $oldLoveStories[$index]['image'] ?? null;
            if ($request->hasFile("love_stories.{$index}.image")) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file("love_stories.{$index}.image")->store('lovestories/' . $invitation->id, 'public');
            }
            $loveStoriesData[] = [
                'year' => $story['year'] ?? '',
                'title' => $story['title'] ?? '',
                'description' => $story['description'] ?? '',
                'image' => $imagePath,
            ];
        }
        foreach ($oldLoveStories as $oldIndex => $oldStory) {
            if (!isset($inputLoveStories[$oldIndex]) && !empty($oldStory['image'])) {
                if (Storage::disk('public')->exists($oldStory['image'])) {
                    Storage::disk('public')->delete($oldStory['image']);
                }
            }
        }

        $processTurutMengundang = function ($text) {
            if (empty($text)) {
                return [];
            }
            $text = str_replace(["\r\n", "\r", "\n"], ',', $text);
            $arr = explode(',', $text);
            return array_values(array_filter(array_map('trim', $arr)));
        };

        // 🔥 KUMPULKAN DATA KESELURUHAN 🔥
        $contentData = [
            'couple_order' => $request->couple_order,
            'cover_greeting' => $request->cover_greeting ?? 'Kepada Yth.',
            'quotes' => $request->quotes,

            // Toggles Umum
            'is_turut_mengundang_active' => $request->has('is_turut_mengundang_active'),
            'is_event_active' => $request->has('is_event_active'),
            'is_story_active' => $request->has('is_story_active'),
            'is_gallery_active' => $request->has('is_gallery_active'),
            'is_gift_active' => $request->has('is_gift_active'),
            'is_wishes_active' => $request->has('is_wishes_active'),
            'is_guest_info_active' => $request->has('is_guest_info_active'),

            // Data Mempelai
            'groom_photo' => $groomPhotoPath,
            'bride_photo' => $bridePhotoPath,
            'groom_name' => $request->groom_name,
            'groom_nickname' => $request->groom_nickname,
            'groom_father' => $request->groom_father,
            'groom_mother' => $request->groom_mother,
            'groom_ig' => $request->groom_ig,

            'bride_name' => $request->bride_name,
            'bride_nickname' => $request->bride_nickname,
            'bride_father' => $request->bride_father,
            'bride_mother' => $request->bride_mother,
            'bride_ig' => $request->bride_ig,
            'turut_mengundang' => $processTurutMengundang($request->turut_mengundang),

            // Akad Statis
            'akad_date' => $request->akad_date,
            'akad_time' => $request->akad_time,
            'akad_location' => $request->akad_location,
            'akad_address' => $request->akad_address,
            'akad_map' => $request->akad_map,

            // Resepsi Dinamis Array
            'events' => collect($request->events)->values()->toArray(),

            'enable_dresscode' => $request->has('enable_dresscode'),
            'dresscode' => $request->dresscode,

            // 🔥 DATA WALIMAH & PREMIUM 🔥
            'enable_health_protocol' => $request->has('enable_health_protocol'),
            'enable_adab_walimah' => $request->has('enable_adab_walimah'),
            'is_livestream_active' => $request->has('is_livestream_active'),
            'live_stream_platform' => $request->live_stream_platform,
            'live_stream_link' => $request->live_stream_link,
            'enable_qr_attendance' => $request->has('enable_qr_attendance'),

            'youtube_links' => array_values(array_filter($request->youtube_links ?? [])),
            'love_stories' => $loveStoriesData,
            'cover_image' => $coverImagePath,

            'banks' => collect($request->banks)
                ->filter(function ($bank) {
                    return !empty($bank['name']) && !empty($bank['account_number']);
                })
                ->values()
                ->toArray(),
        ];

        InvitationDetail::updateOrCreate(['invitation_id' => $invitation->id], ['content' => json_encode($contentData)]);

        return redirect()->back()->with('success', 'Semua perubahan data berhasil disimpan!');
    }

    public function uploadGallery(Request $request, $id)
    {
        $invitation = Invitation::with('orders.package')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $currentOrder = $invitation->orders->last();
        $features = is_string($currentOrder->package->features) ? json_decode($currentOrder->package->features, true) : $currentOrder->package->features;
        $maxPhotos = $features['logic']['gallery_limit'] ?? 5;

        $currentPhotoCount = Gallery::where('invitation_id', $invitation->id)->where('type', 'photo')->count();

        $request->validate([
            'files.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            if ($currentPhotoCount + count($files) > $maxPhotos) {
                return back()->with('error', "Gagal! Batas maksimal paket Anda adalah $maxPhotos foto. Saat ini sudah ada $currentPhotoCount foto.");
            }

            foreach ($files as $file) {
                $path = $file->store('galleries/' . $invitation->id, 'public');
                Gallery::create([
                    'invitation_id' => $invitation->id,
                    'file_path' => $path,
                    'type' => 'photo',
                ]);
            }
        }

        return back()->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    public function destroy(Invitation $invitation)
    {
        if ($invitation->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($invitation->galleries as $gallery) {
            if (Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
        }

        $invitation->delete();

        return redirect()->route('customer.invitations.index')->with('success', 'Undangan berhasil dihapus permanen.');
    }

    public function deleteGallery($id)
    {
        $gallery = Gallery::whereHas('invitation', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->query('slug'));
        $exists = Invitation::where('slug', $slug)->exists();

        return response()->json([
            'available' => !$exists,
            'slug' => $slug,
        ]);
    }

    public function show($slug, Request $request)
    {
        $invitation = Invitation::where('slug', $slug)->firstOrFail();

        if ($invitation->status !== 'active') {
            if (Auth::id() !== $invitation->user_id) {
                return response()->view('errors.unpaid_invitation', ['message' => 'Maaf, undangan ini belum diaktifkan oleh pemilik acara.'], 403);
            }
            session()->flash('warning', 'Ini adalah Mode Preview. Tamu tidak bisa melihat undangan ini sampai Anda melakukan pembayaran.');
        }

        return view('templates.' . $invitation->template->view_path, compact('invitation'));
    }
}
