<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        // Mengambil template beserta relasi kategori
        $templates = Template::with('category')->latest()->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.templates.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'view_path' => 'required|string|max:255', // Contoh: themes.modern.index
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:5048',
            'gallery_limit' => 'required|integer|min:0',
        ]);

        $data = $request->except(['thumbnail', 'has_video', 'has_love_story', 'gallery_limit']);
        
        // 1. Upload Thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // 2. Susun JSON required_fields dengan cerdas dari input terpisah
        $data['required_fields'] = [
            'has_video' => $request->has('has_video') ? true : false,
            'has_love_story' => $request->has('has_love_story') ? true : false,
            'gallery_limit' => (int) $request->gallery_limit,
        ];

        // 3. Set harga default ke 0 (karena harga diatur di Paket)
        $data['price'] = $request->price ?? 0;
        $data['is_active'] = $request->is_active ?? 1;

        Template::create($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil ditambahkan.');
    }

    public function edit(Template $template)
    {
        $categories = Category::all();
        return view('admin.templates.edit', compact('template', 'categories'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'view_path' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'gallery_limit' => 'required|integer|min:0',
        ]);

        $data = $request->except(['thumbnail', 'has_video', 'has_love_story', 'gallery_limit']);
        
        // 1. Upload Thumbnail baru jika ada, dan hapus yang lama
        if ($request->hasFile('thumbnail')) {
            if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                Storage::disk('public')->delete($template->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // 2. Update JSON required_fields
        $data['required_fields'] = [
            'has_video' => $request->has('has_video') ? true : false,
            'has_love_story' => $request->has('has_love_story') ? true : false,
            'gallery_limit' => (int) $request->gallery_limit,
        ];

        $data['price'] = $request->price ?? 0;
        $data['is_active'] = $request->is_active ?? 1;

        $template->update($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil diperbarui.');
    }

    public function destroy(Template $template)
    {
        // Hapus file gambar dari storage
        if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
            Storage::disk('public')->delete($template->thumbnail);
        }
        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil dihapus.');
    }
}