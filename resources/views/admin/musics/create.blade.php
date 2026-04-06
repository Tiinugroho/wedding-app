@extends('admin.partials.app')
@section('title', 'Upload Musik')

@section('content')
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.musics.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rRed transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Upload Musik</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Tambahkan lagu latar baru untuk undangan.</p>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-10 max-w-2xl">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
            </span>
            Formulir Upload Musik
        </h3>

        <form id="create-music-form" action="{{ route('admin.musics.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-5">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Judul Lagu & Artis <span class="text-rRed">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: A Thousand Years - Christina Perri" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('title') border-red-500 @enderror" required>
                @error('title') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Kategori Musik <span class="text-rRed">*</span></label>
                <select name="category" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('category') border-red-500 @enderror" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Musik Indonesia" {{ old('category') == 'Musik Indonesia' ? 'selected' : '' }}>Musik Indonesia</option>
                    <option value="Musik Traditional" {{ old('category') == 'Musik Traditional' ? 'selected' : '' }}>Musik Traditional</option>
                    <option value="Musik Jepang" {{ old('category') == 'Musik Jepang' ? 'selected' : '' }}>Musik Jepang</option>
                    <option value="Musik Instrumental" {{ old('category') == 'Musik Instrumental' ? 'selected' : '' }}>Musik Instrumental</option>
                    <option value="Musik Islami" {{ old('category') == 'Musik Islami' ? 'selected' : '' }}>Musik Islami</option>
                    <option value="Musik Barat" {{ old('category') == 'Musik Barat' ? 'selected' : '' }}>Musik Barat</option>
                    <option value="Musik Celebration" {{ old('category') == 'Musik Celebration' ? 'selected' : '' }}>Musik Celebration</option>
                </select>
                @error('category') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-10 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <label class="block text-slate-700 font-bold mb-2 text-sm">File Audio (.mp3, .wav) <span class="text-rRed">*</span></label>
                <input type="file" name="file_path" accept="audio/mp3,audio/wav,audio/*" class="w-full text-slate-600 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition outline-none mb-2 @error('file_path') border-red-500 @enderror" required>
                <p class="text-xs text-slate-400">Ukuran maksimal file: 10 MB.</p>
                @error('file_path') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <button type="button" onclick="openSaveModal()" class="w-full md:w-auto bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-rRed/20 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Upload Musik
            </button>
        </form>
    </div>

    {{-- CUSTOM MODAL SIMPAN --}}
    <div id="save-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeSaveModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300" id="save-modal-box">
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Upload Data?</h3>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Pastikan file audio yang Anda pilih sudah benar dan tidak melebihi 10 MB.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" onclick="closeSaveModal()" class="w-full bg-white text-slate-600 border border-slate-200 px-6 py-3 rounded-2xl font-bold hover:bg-slate-50 transition">Periksa Lagi</button>
                <button type="button" onclick="submitForm()" class="w-full bg-green-500 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-green-500/30 hover:scale-[1.02] transition">Ya, Upload!</button>
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
            document.getElementById('create-music-form').submit();
        }
    </script>
@endpush