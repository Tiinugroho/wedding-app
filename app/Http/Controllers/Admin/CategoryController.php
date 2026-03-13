<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Tambahkan ini untuk fungsi Str::slug()

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Memastikan slug unik di tabel categories
            'slug' => 'required|string|max:255|unique:categories,slug'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug), // Pastikan formatnya selalu ramah URL
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Mengecualikan ID kategori ini saat mengecek keunikan slug
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}