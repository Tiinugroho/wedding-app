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
            'category' => 'required|string|max:255', // Tambahkan validasi kategori
            'file_path' => 'required|file|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category, // Masukkan kategori ke array data
        ];

        // Proses Upload File Audio
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('musics', 'public');
        }

        Music::create($data);

        return redirect()->route('admin.musics.index')->with('success', 'Musik latar berhasil diunggah.');
    }

    public function edit(Music $music)
    {
        return view('admin.musics.edit', compact('music'));
    }

    public function update(Request $request, Music $music)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255', // Tambahkan validasi kategori
            'file_path' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category, // Masukkan kategori ke array data
        ];

        if ($request->hasFile('file_path')) {
            // Hapus file lama
            if ($music->file_path && Storage::disk('public')->exists($music->file_path)) {
                Storage::disk('public')->delete($music->file_path);
            }
            // Upload file baru
            $data['file_path'] = $request->file('file_path')->store('musics', 'public');
        }

        $music->update($data);

        return redirect()->route('admin.musics.index')->with('success', 'Data musik berhasil diperbarui.');
    }

    public function destroy(Music $music)
    {
        // Cek apakah ada file fisik, lalu hapus
        if ($music->file_path && Storage::disk('public')->exists($music->file_path)) {
            Storage::disk('public')->delete($music->file_path);
        }
        
        $music->delete();

        return redirect()->route('admin.musics.index')->with('success', 'Musik latar berhasil dihapus.');
    }
}