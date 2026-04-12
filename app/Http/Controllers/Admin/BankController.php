<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    /**
     * Menampilkan halaman daftar Bank.
     */
    public function index()
    {
        $banks = Bank::latest()->get();
        return view('admin.banks.index', compact('banks'));
    }

    /**
     * Menampilkan form tambah Bank.
     */
    public function create()
    {
        return view('admin.banks.create');
    }

    /**
     * Menyimpan data Bank baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048', // Wajib upload logo
            'is_active' => 'nullable|boolean'
        ]);

        $data = $request->only(['name']);
        
        // Cek jika checkbox is_active dicentang
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Proses Upload Logo
        if ($request->hasFile('logo')) {
            // Akan tersimpan di folder: storage/app/public/banks
            $data['logo'] = $request->file('logo')->store('banks', 'public');
        }

        Bank::create($data);

        return redirect()->route('admin.banks.index')
            ->with('success', 'Data Bank / E-Wallet berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit Bank.
     */
    public function edit(Bank $bank)
    {
        return view('admin.banks.edit', compact('bank'));
    }

    /**
     * Memperbarui data Bank di database.
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048', // Opsional saat edit
            'is_active' => 'nullable|boolean'
        ]);

        $data = $request->only(['name']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Jika user mengupload logo baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama dari storage (jika ada dan bukan link eksternal)
            if ($bank->logo && !str_starts_with($bank->logo, 'http')) {
                Storage::disk('public')->delete($bank->logo);
            }
            
            // Simpan logo baru
            $data['logo'] = $request->file('logo')->store('banks', 'public');
        }

        $bank->update($data);

        return redirect()->route('admin.banks.index')
            ->with('success', 'Data Bank / E-Wallet berhasil diperbarui!');
    }

    /**
     * Menghapus data Bank dari database.
     */
    public function destroy(Bank $bank)
    {
        // Hapus file fisik logo dari storage sebelum data dihapus dari database
        if ($bank->logo && !str_starts_with($bank->logo, 'http')) {
            Storage::disk('public')->delete($bank->logo);
        }

        $bank->delete();

        return redirect()->route('admin.banks.index')
            ->with('success', 'Data Bank / E-Wallet berhasil dihapus permanen!');
    }
}