<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Menampilkan daftar paket harga.
     */
    public function index()
    {
        // Mengambil semua paket dari database, urutkan berdasarkan harga termurah
        $packages = Package::orderBy('price', 'asc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Menampilkan form tambah paket baru.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Menyimpan data paket baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
            'is_active'      => 'required|boolean',
            'features'       => 'nullable|array',
        ]);

        $featuresInput = $request->input('features', []);

        // array_filter: menghapus form kosong
        // array_values: me-reset angka urutan (0,1,2..) agar TEPAT menjadi JSON Array, bukan Object
        $validated['features'] = [
            'included' => isset($featuresInput['included']) ? array_values(array_filter($featuresInput['included'])) : [],
            'excluded' => isset($featuresInput['excluded']) ? array_values(array_filter($featuresInput['excluded'])) : [],
        ];

        Package::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket harga berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit paket.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Memperbarui data paket di database.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
            'is_active'      => 'required|boolean',
            'features'       => 'nullable|array',
        ]);

        $featuresInput = $request->input('features', []);

        // Sama seperti store, array_values sangat wajib ada di sini
        $validated['features'] = [
            'included' => isset($featuresInput['included']) ? array_values(array_filter($featuresInput['included'])) : [],
            'excluded' => isset($featuresInput['excluded']) ? array_values(array_filter($featuresInput['excluded'])) : [],
        ];

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket harga berhasil diperbarui.');
    }

    /**
     * Menghapus data paket dari database.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Paket harga berhasil dihapus.');
    }
}
