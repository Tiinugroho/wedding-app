<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\Category;
use App\Models\Package; // Pastikan Model Package di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        // Load relasi category dan package sekaligus untuk performa (Eager Loading)
        $templates = Template::with(['category', 'package'])
            ->latest()
            ->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $categories = Category::all();
        $packages = Package::all(); // Ambil semua data paket
        return view('admin.templates.create', compact('categories', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'package_id' => 'required|exists:packages,id', // Validasi Paket pengganti Price
            'view_path' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'gallery_limit' => 'required|integer|min:0',
        ]);

        $data = $request->except(['thumbnail', 'has_video', 'has_love_story', 'gallery_limit']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Ini adalah kapasitas DESAIN template, bukan batasan paket.
        $data['required_fields'] = [
            'has_video' => $request->has('has_video'),
            'has_love_story' => $request->has('has_love_story'),
            'gallery_limit' => (int) $request->gallery_limit,
        ];

        $data['is_active'] = $request->is_active ?? 1;

        Template::create($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil ditambahkan.');
    }

    public function edit(Template $template)
    {
        $categories = Category::all();
        $packages = Package::all(); // Ambil semua data paket
        return view('admin.templates.edit', compact('template', 'categories', 'packages'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'package_id' => 'required|exists:packages,id', // Validasi Paket
            'view_path' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
            'gallery_limit' => 'required|integer|min:0',
        ]);

        $data = $request->except(['thumbnail', 'has_video', 'has_love_story', 'gallery_limit']);

        if ($request->hasFile('thumbnail')) {
            if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                Storage::disk('public')->delete($template->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data['required_fields'] = [
            'has_video' => $request->has('has_video'),
            'has_love_story' => $request->has('has_love_story'),
            'gallery_limit' => (int) $request->gallery_limit,
        ];

        $data['is_active'] = $request->is_active ?? 1;

        $template->update($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil diperbarui.');
    }

    public function destroy(Template $template)
    {
        if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
            Storage::disk('public')->delete($template->thumbnail);
        }
        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template berhasil dihapus.');
    }
}
