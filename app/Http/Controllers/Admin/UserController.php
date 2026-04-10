<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan halaman daftar pengguna / klien.
     */
    public function index()
    {
        // Mengambil semua data user yang bertipe 'client'
        // Diurutkan berdasarkan yang paling baru mendaftar (latest)
        $users = User::where('role', 'client')->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Opsional: Fungsi untuk menghapus pengguna jika diperlukan suatu saat nanti
     * (Sesuai dengan routes web.php yang ada fitur destroy-nya)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi keamanan: Admin tidak boleh menghapus akun admin lainnya
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Akun Admin tidak boleh dihapus!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Data pengguna berhasil dihapus.');
    }
}