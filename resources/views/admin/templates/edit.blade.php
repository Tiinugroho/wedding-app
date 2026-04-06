@extends('admin.partials.app')
@section('title', 'Edit Template')

@section('content')
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.templates.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rOrange transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Edit Template</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Perbarui konfigurasi untuk tema <span class="text-rOrange">{{ $template->name }}</span>.</p>
        </div>
    </header>

    @php
        // Ambil JSON dan decode agar tidak error
        $req_fields = is_array($template->required_fields) ? $template->required_fields : json_decode($template->required_fields, true);
    @endphp

    <form id="edit-template-form" action="{{ route('admin.templates.update', $template->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- KOLOM KIRI --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    Informasi Utama
                </h3>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Nama Tema <span class="text-rRed">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $template->name) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                </div>
                
                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Kategori <span class="text-rRed">*</span></label>
                    <select name="category_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $template->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- FORM PAKET (PENGGANTI HARGA) --}}
                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Paket Tema <span class="text-rRed">*</span></label>
                    <select name="package_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ $template->package_id == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} (Rp {{ number_format($package->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-2 ml-1">Harga akan mengikuti paket yang dipilih.</p>
                </div>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Path Blade (View Path) <span class="text-rRed">*</span></label>
                    <input type="text" name="view_path" value="{{ old('view_path', $template->view_path) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                </div>

                <div class="mb-5 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Ganti Thumbnail (Opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-slate-600 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition outline-none mb-3">
                    @if($template->thumbnail)
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/'.$template->thumbnail) }}" alt="Preview" class="w-20 h-20 object-cover rounded-xl border border-slate-200">
                            <span class="text-xs text-slate-400">Gambar saat ini</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-green-100 text-green-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                    Persyaratan Fitur
                </h3>

                <label class="relative inline-flex items-center cursor-pointer mb-5">
                    <input type="checkbox" name="has_video" value="1" class="sr-only peer" {{ isset($req_fields['has_video']) && $req_fields['has_video'] ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    <span class="ml-3 text-sm font-bold text-slate-700">Mendukung Video</span>
                </label>
                <br>
                <label class="relative inline-flex items-center cursor-pointer mb-6">
                    <input type="checkbox" name="has_love_story" value="1" class="sr-only peer" {{ isset($req_fields['has_love_story']) && $req_fields['has_love_story'] ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    <span class="ml-3 text-sm font-bold text-slate-700">Mendukung Love Story</span>
                </label>

                <div class="mb-6">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Batas Maksimal Galeri <span class="text-rRed">*</span></label>
                    <input type="number" name="gallery_limit" value="{{ $req_fields['gallery_limit'] ?? 0 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                </div>

                <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Status Tayang</label>
                    <select name="is_active" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-rOrange block p-3 transition outline-none">
                        <option value="1" {{ $template->is_active == 1 ? 'selected' : '' }}>Aktif (Tampil di Klien)</option>
                        <option value="0" {{ $template->is_active == 0 ? 'selected' : '' }}>Draft / Nonaktif</option>
                    </select>
                </div>

                <button type="button" onclick="openUpdateModal()" class="w-full bg-slate-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:bg-slate-700 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Perbarui Template
                </button>
            </div>
        </div>
    </form>

    {{-- CUSTOM MODAL UPDATE --}}
    <div id="update-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeUpdateModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300" id="update-modal-box">
            <div class="w-20 h-20 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Simpan Perubahan?</h3>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Anda yakin ingin memperbarui konfigurasi template ini?</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" onclick="closeUpdateModal()" class="w-full bg-white text-slate-600 border border-slate-200 px-6 py-3 rounded-2xl font-bold hover:bg-slate-50 transition">Batal</button>
                <button type="button" onclick="submitUpdateForm()" class="w-full bg-blue-500 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:scale-[1.02] transition">Perbarui!</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openUpdateModal() {
            document.getElementById('update-modal').classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById('update-modal-box').classList.remove('scale-95');
        }
        function closeUpdateModal() {
            document.getElementById('update-modal').classList.add('opacity-0', 'pointer-events-none');
            document.getElementById('update-modal-box').classList.add('scale-95');
        }
        function submitUpdateForm() {
            document.getElementById('edit-template-form').submit();
        }
    </script>
@endpush