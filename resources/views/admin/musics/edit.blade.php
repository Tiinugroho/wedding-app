@extends('admin.partials.app')
@section('title', 'Edit Musik')

@push('styles')
    <style>
        /* Kustomisasi Audio Player untuk form Edit */
        audio { height: 45px; outline: none; border-radius: 2rem; width: 100%; max-width: 100%; }
        audio::-webkit-media-controls-panel { background-color: #f8fafc; border: 1px solid #e2e8f0; }
    </style>
@endpush

@section('content')
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.musics.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rOrange transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Edit Musik</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Perbarui detail atau file untuk lagu <span class="text-rOrange">{{ $music->title }}</span>.</p>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-10 max-w-2xl relative overflow-hidden">
        {{-- Background dekoratif ringan --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-bl-[10rem] -z-0 pointer-events-none"></div>

        <form id="edit-music-form" action="{{ route('admin.musics.update', $music->id) }}" method="POST" enctype="multipart/form-data" class="relative z-10">
            @csrf @method('PUT')

            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                </span>
                Detail Lagu
            </h3>

            <div class="mb-5">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Judul Lagu & Artis <span class="text-rRed">*</span></label>
                <input type="text" name="title" value="{{ old('title', $music->title) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('title') border-red-500 @enderror" required>
                @error('title') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Kategori Musik <span class="text-rRed">*</span></label>
                <select name="category" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('category') border-red-500 @enderror" required>
                    <option value="" disabled>-- Pilih Kategori --</option>
                    <option value="Musik Indonesia" {{ old('category', $music->category) == 'Musik Indonesia' ? 'selected' : '' }}>Musik Indonesia</option>
                    <option value="Musik Traditional" {{ old('category', $music->category) == 'Musik Traditional' ? 'selected' : '' }}>Musik Traditional</option>
                    <option value="Musik Jepang" {{ old('category', $music->category) == 'Musik Jepang' ? 'selected' : '' }}>Musik Jepang</option>
                    <option value="Musik Instrumental" {{ old('category', $music->category) == 'Musik Instrumental' ? 'selected' : '' }}>Musik Instrumental</option>
                    <option value="Musik Islami" {{ old('category', $music->category) == 'Musik Islami' ? 'selected' : '' }}>Musik Islami</option>
                    <option value="Musik Barat" {{ old('category', $music->category) == 'Musik Barat' ? 'selected' : '' }}>Musik Barat</option>
                    <option value="Musik Celebration" {{ old('category', $music->category) == 'Musik Celebration' ? 'selected' : '' }}>Musik Celebration</option>
                </select>
                @error('category') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-10 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                <label class="block text-slate-700 font-bold mb-3 text-sm">Lagu Saat Ini:</label>
                <div class="mb-5">
                    <audio controls controlsList="nodownload">
                        <source src="{{ asset('storage/' . $music->file_path) }}" type="audio/mpeg">
                        Browser tidak support.
                    </audio>
                </div>
                
                <hr class="border-slate-200 mb-4">
                
                <label class="block text-slate-700 font-bold mb-2 text-sm">Ganti File Audio (Opsional)</label>
                <input type="file" name="file_path" accept="audio/mp3,audio/wav,audio/*" class="w-full text-slate-600 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition outline-none mb-2">
                <p class="text-xs text-slate-400">Biarkan kosong jika tidak ingin mengganti lagunya.</p>
                @error('file_path') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
            </div>

            <button type="button" onclick="openUpdateModal()" class="w-full md:w-auto bg-slate-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:bg-slate-700 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui Data Musik
            </button>
        </form>
    </div>

    {{-- CUSTOM MODAL UPDATE --}}
    <div id="update-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeUpdateModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300" id="update-modal-box">
            <div class="w-20 h-20 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Simpan Perubahan?</h3>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Anda yakin ingin memperbarui data lagu ini?</p>
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
            document.getElementById('edit-music-form').submit();
        }
    </script>
@endpush