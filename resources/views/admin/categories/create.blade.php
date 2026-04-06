@extends('admin.partials.app')
@section('title', 'Tambah Kategori')

@section('content')
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.categories.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rRed transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Tambah Kategori</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Buat klasifikasi baru untuk tema undangan Anda.</p>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-10 max-w-3xl">
        <form id="create-category-form" action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Nama Kategori</label>
                <input type="text" name="name" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange focus:border-rOrange block p-4 transition outline-none @error('name') border-red-500 @enderror" placeholder="Contoh: Elegan, Minimalis, Floral..." required>
                @error('name')
                    <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-10">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Slug URL</label>
                <input type="text" name="slug" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange focus:border-rOrange block p-4 transition outline-none @error('slug') border-red-500 @enderror" placeholder="contoh-elegan-minimalis" required>
                <p class="text-slate-400 text-xs mt-2 ml-1">*Bisa dikosongkan, sistem akan meng-generate otomatis dari Nama Kategori.</p>
                @error('slug')
                    <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" onclick="openSaveModal()" class="w-full md:w-auto bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-rRed/20 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Kategori
            </button>
        </form>
    </div>

    {{-- CUSTOM MODAL POP-UP (SIMPAN) --}}
    <div id="save-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeSaveModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300" id="save-modal-box">
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Simpan Data?</h3>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Apakah Anda yakin data kategori yang dimasukkan sudah benar?</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" onclick="closeSaveModal()" class="w-full bg-white text-slate-600 border border-slate-200 px-6 py-3 rounded-2xl font-bold hover:bg-slate-50 transition">Periksa Lagi</button>
                <button type="button" onclick="submitForm()" class="w-full bg-gradient-to-r from-green-400 to-green-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-green-500/30 hover:scale-[1.02] transition">Ya, Simpan!</button>
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
            document.getElementById('create-category-form').submit();
        }
    </script>
@endpush