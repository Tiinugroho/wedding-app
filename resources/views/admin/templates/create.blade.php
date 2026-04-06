@extends('admin.partials.app')
@section('title', 'Tambah Template')

@section('content')
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.templates.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rRed transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Tambah Template</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Tambahkan desain tema baru ke dalam sistem.</p>
        </div>
    </header>

    <form id="create-template-form" action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- KOLOM KIRI: Informasi Utama --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    Informasi Utama
                </h3>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Nama Tema <span class="text-rRed">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('name') border-red-500 @enderror" required>
                </div>
                
                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Kategori <span class="text-rRed">*</span></label>
                    <select name="category_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- FORM PAKET (PENGGANTI HARGA) --}}
                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Paket Tema <span class="text-rRed">*</span></label>
                    <select name="package_id" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }} (Rp {{ number_format($package->price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-2 ml-1">Harga akan mengikuti paket yang dipilih.</p>
                </div>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Path Blade (View Path) <span class="text-rRed">*</span></label>
                    <input type="text" name="view_path" value="{{ old('view_path') }}" placeholder="Contoh: wed-1" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                    <p class="text-xs text-slate-400 mt-2 ml-1">Nama file folder di resources/views/themes/...</p>
                </div>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Upload Thumbnail <span class="text-rRed">*</span></label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-rOrange file:text-white hover:file:bg-rRed" required>
                    @error('thumbnail') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- KOLOM KANAN: Fitur & Status --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-green-100 text-green-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                    Persyaratan Fitur
                </h3>

                <label class="relative inline-flex items-center cursor-pointer mb-5">
                    <input type="checkbox" name="has_video" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    <span class="ml-3 text-sm font-bold text-slate-700">Mendukung Video</span>
                </label>
                <br>
                <label class="relative inline-flex items-center cursor-pointer mb-6">
                    <input type="checkbox" name="has_love_story" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    <span class="ml-3 text-sm font-bold text-slate-700">Mendukung Love Story</span>
                </label>

                <div class="mb-6">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Batas Maksimal Galeri <span class="text-rRed">*</span></label>
                    <input type="number" name="gallery_limit" value="10" min="0" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none" required>
                </div>

                <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Status Tayang</label>
                    <select name="is_active" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-rOrange block p-3 transition outline-none">
                        <option value="1">Aktif (Tampil di Klien)</option>
                        <option value="0">Draft / Nonaktif</option>
                    </select>
                </div>

                <button type="button" onclick="openSaveModal()" class="w-full bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-rRed/20 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Template
                </button>
            </div>
        </div>
    </form>

    {{-- CUSTOM MODAL SIMPAN --}}
    <div id="save-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeSaveModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300" id="save-modal-box">
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Simpan Data?</h3>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Pastikan semua kolom mandatory (*) dan file thumbnail sudah terisi.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" onclick="closeSaveModal()" class="w-full bg-white text-slate-600 border border-slate-200 px-6 py-3 rounded-2xl font-bold hover:bg-slate-50 transition">Periksa Lagi</button>
                <button type="button" onclick="submitForm()" class="w-full bg-green-500 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-green-500/30 hover:scale-[1.02] transition">Ya, Simpan!</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openSaveModal() {
            document.getElementById('save-modal').classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById('save-modal-box').classList.remove('scale-95');
        }
        function closeSaveModal() {
            document.getElementById('save-modal').classList.add('opacity-0', 'pointer-events-none');
            document.getElementById('save-modal-box').classList.add('scale-95');
        }
        function submitForm() {
            document.getElementById('create-template-form').submit();
        }
    </script>
@endpush