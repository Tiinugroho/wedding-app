<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
        // Beserta relasi template dan detailnya agar efisien
        $invitations = Invitation::with(['template', 'details'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.invitations.index', compact('invitations'));
    }

    /**
     * Menampilkan halaman form pembuatan undangan baru
     */
    public function create()
    {
        $packages = Package::where('is_active', true)->orderBy('price', 'asc')->get();
        $templates = Template::with('category')->where('is_active', true)->get();

        return view('customer.invitations.create', compact('packages', 'templates'));
    }

    /**
     * Menyimpan data awal undangan ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
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

        // 2. Buat Undangan Induk (Status: Draft)
        $invitation = Invitation::create([
            'user_id' => Auth::id(),
            'template_id' => $request->template_id,
            'slug' => strtolower($request->slug),
            'status' => 'draft', // Dibuat draft dulu sampai dia bayar / melengkapi data
        ]);

        // 3. Buatkan juga baris kosong di tabel invitation_details agar tidak error saat di-edit
        InvitationDetail::create([
            'invitation_id' => $invitation->id,
            'content' => json_encode([]),
        ]);

        // 4. Catat juga pesanan (Order) jika Anda ingin mengintegrasikan dengan pembayaran nanti
        Order::create([
            'order_number' => 'INV-' . time() . strtoupper(Str::random(5)),
            'user_id' => Auth::id(),
            'invitation_id' => $invitation->id,
            'package_id' => $request->package_id,
            'amount' => Package::find($request->package_id)->price,
            'status' => 'pending',
        ]);

        // 5. Arahkan langsung ke halaman Edit untuk isi data mempelai
        return redirect()->route('customer.invitations.edit', $invitation->id)->with('success', 'Langkah 1 Selesai! Silakan lengkapi data acara dan mempelai Anda.');
    }

    /**
     * Menampilkan halaman form pengisian data (Edit Undangan)
     */
    public function edit($id)
    {
        $invitation = Invitation::with(['details', 'template', 'music', 'orders.package'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $musics = Music::orderBy('category')->orderBy('title')->get();
        $content = json_decode($invitation->details->content ?? '{}', true);

        // Ambil data order / paket saat ini
        $currentOrder = $invitation->orders->last();
        $packageLogic = [];
        $currentPackageName = 'Custom';
        $currentPackagePrice = 0; // Default harga 0

        if ($currentOrder && $currentOrder->package) {
            $features = is_string($currentOrder->package->features) ? json_decode($currentOrder->package->features, true) : $currentOrder->package->features;
            $packageLogic = $features['logic'] ?? [];
            $currentPackageName = $currentOrder->package->name;
            $currentPackagePrice = $currentOrder->package->price;
        }

        // AMBIL DAFTAR PAKET UNTUK UPGRADE (Hanya yang harganya lebih mahal dari paket saat ini)
        $upgradePackages = Package::where('is_active', true)->where('price', '>', $currentPackagePrice)->orderBy('price', 'asc')->get();

        return view('customer.invitations.edit', compact('invitation', 'musics', 'content', 'packageLogic', 'currentPackageName', 'currentPackagePrice', 'upgradePackages'));
    }

    /**
     * Menyimpan pembaruan data mempelai, acara, dan musik
     */
    public function update(Request $request, $id)
    {
        $invitation = Invitation::where('user_id', Auth::id())->findOrFail($id);

        // 1. Validasi Data Teks & File Sekaligus
        $request->validate([
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'gallery_files.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto baru
        ]);

        // 2. Logika Simpan Foto Baru (Jika Ada)
        if ($request->hasFile('gallery_files')) {
            // Ambil limit paket
            $currentOrder = $invitation->orders->last();
            $features = is_string($currentOrder->package->features) ? json_decode($currentOrder->package->features, true) : $currentOrder->package->features;
            $maxPhotos = $features['logic']['gallery_limit'] ?? 5;

            // Hitung jumlah foto yang sudah ada di database
            $currentPhotoCount = Gallery::where('invitation_id', $invitation->id)->where('type', 'photo')->count();
            $newFiles = $request->file('gallery_files');

            // Cek kuota paket
            if ($currentPhotoCount + count($newFiles) > $maxPhotos) {
                return back()->with('error', "Gagal simpan foto! Kuota paket Anda hanya $maxPhotos foto. Sisa kuota: " . ($maxPhotos - $currentPhotoCount));
            }

            // Proses simpan ke Storage
            foreach ($newFiles as $file) {
                $path = $file->store('galleries/' . $invitation->id, 'public');

                Gallery::create([
                    'invitation_id' => $invitation->id,
                    'file_path' => $path,
                    'type' => 'photo',
                ]);
            }
        }

        // 3. Update Backsound Musik
        if ($request->has('music_id')) {
            $invitation->update(['music_id' => $request->music_id]);
        }

        // 4. Kumpulkan Data Teks
        $contentData = [
            'groom_name' => $request->groom_name,
            'groom_nickname' => $request->groom_nickname,
            'groom_parents' => $request->groom_parents,
            'groom_ig' => $request->groom_ig,
            'bride_name' => $request->bride_name,
            'bride_nickname' => $request->bride_nickname,
            'bride_parents' => $request->bride_parents,
            'bride_ig' => $request->bride_ig,
            'akad_date' => $request->akad_date,
            'akad_time' => $request->akad_time,
            'akad_location' => $request->akad_location,
            'akad_address' => $request->akad_address,
            'akad_map' => $request->akad_map,
            'resepsi_date' => $request->resepsi_date,
            'resepsi_time' => $request->resepsi_time,
            'resepsi_location' => $request->resepsi_location,
            'resepsi_address' => $request->resepsi_address,
            'resepsi_map' => $request->resepsi_map,
        ];

        // 5. Simpan/Update Detail JSON
        InvitationDetail::updateOrCreate(['invitation_id' => $invitation->id], ['content' => json_encode($contentData)]);

        return redirect()->back()->with('success', 'Semua perubahan data dan foto berhasil disimpan!');
    }

    public function uploadGallery(Request $request, $id)
    {
        $invitation = Invitation::with('orders.package')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // 1. Ambil Limit dari Paket
        $currentOrder = $invitation->orders->last();
        $features = is_string($currentOrder->package->features) ? json_decode($currentOrder->package->features, true) : $currentOrder->package->features;
        $maxPhotos = $features['logic']['gallery_limit'] ?? 5;

        // 2. Hitung jumlah foto yang sudah diupload
        $currentPhotoCount = Gallery::where('invitation_id', $invitation->id)->where('type', 'photo')->count();

        // 3. Validasi
        $request->validate([
            'files.*' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB per foto
        ]);

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            // Cek apakah upload baru akan melebihi batas
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
        // Pastikan hanya pemilik yang bisa menghapus
        if ($invitation->user_id !== auth()->id()) {
            abort(403);
        }

        // Hapus semua foto galeri fisik sebelum hapus data database
        foreach ($invitation->galleries as $gallery) {
            if (Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
        }

        $invitation->delete();

        return redirect()->route('customer.invitations.index')->with('success', 'Undangan berhasil dihapus permanen.');
    }

    /**
     * Menghapus hanya satu foto di galeri (Fungsi Manual)
     */
    public function deleteGallery($id)
    {
        // Cari galeri yang terhubung dengan undangan milik user ini
        $gallery = Gallery::whereHas('invitation', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    // Method create, store, edit, update akan kita buat setelah ini...
}
