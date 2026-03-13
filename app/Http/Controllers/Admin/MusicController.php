<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusicController extends Controller
{
    public function index()
    {
        $musics = Music::latest()->get();
        return view('admin.musics.index', compact('musics'));
    }

    public function create()
    {
        return view('admin.musics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:mp3,wav,ogg,m4a|max:25000', // Dinaikkan ke 25MB
        ], [
            'title.required' => 'Judul lagu wajib diisi.',
            'category.required' => 'Kategori musik wajib dipilih.',
            'file_path.required' => 'File audio wajib diunggah.',
            'file_path.file' => 'Gagal mengunggah file.',
            'file_path.mimes' => 'Format file harus berupa: mp3, wav, ogg, atau m4a.',
            'file_path.max' => 'Ukuran file lagu maksimal adalah 25 MB.',
            'file_path.uploaded' => 'Server menolak file ini. Wajib matikan Terminal (Ctrl+C) lalu "php artisan serve" kembali.',
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
        ];

        try {
            if ($request->hasFile('file_path')) {
                // Laravel akan otomatis membuat file fisiknya di storage/app/public/musics
                $data['file_path'] = $request->file('file_path')->store('musics', 'public');
            }

            Music::create($data);
            return redirect()->route('admin.musics.index')->with('success', 'Musik latar berhasil diunggah.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['file_path' => 'Gagal menyimpan file: ' . $e->getMessage()]);
        }
    }

    public function edit(Music $music)
    {
        return view('admin.musics.edit', compact('music'));
    }

    public function update(Request $request, Music $music)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'file_path' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:25000',
        ], [
            'title.required' => 'Judul lagu wajib diisi.',
            'category.required' => 'Kategori musik wajib dipilih.',
            'file_path.file' => 'Gagal mengunggah file.',
            'file_path.mimes' => 'Format file baru harus berupa: mp3, wav, ogg, atau m4a.',
            'file_path.max' => 'Ukuran file lagu maksimal adalah 25 MB.',
            'file_path.uploaded' => 'Server menolak file ini. Wajib matikan Terminal (Ctrl+C) lalu "php artisan serve" kembali.',
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
        ];

        try {
            if ($request->hasFile('file_path')) {
                // Hapus file lama JIKA ada di storage
                if (!empty($music->file_path) && Storage::disk('public')->exists($music->file_path)) {
                    Storage::disk('public')->delete($music->file_path);
                }
                
                // Upload file baru
                $data['file_path'] = $request->file('file_path')->store('musics', 'public');
            } 

            $music->update($data);
            return redirect()->route('admin.musics.index')->with('success', 'Data musik berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['file_path' => 'Gagal memperbarui file: ' . $e->getMessage()]);
        }
    }

    public function destroy(Music $music)
    {
        try {
            if (!empty($music->file_path) && Storage::disk('public')->exists($music->file_path)) {
                Storage::disk('public')->delete($music->file_path);
            }
            
            $music->delete();
            return redirect()->route('admin.musics.index')->with('success', 'Musik latar berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus musik: ' . $e->getMessage());
        }
    }
}