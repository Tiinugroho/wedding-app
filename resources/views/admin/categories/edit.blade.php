@extends('admin.partials.app')
@section('title', 'Edit Kategori')

@section('content')
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.categories.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rOrange transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Edit Kategori</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Perbarui informasi kategori tema.</p>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-10 max-w-3xl relative overflow-hidden">
        {{-- Background aksen dekoratif --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-rYellow/20 rounded-bl-[10rem] -z-0 pointer-events-none"></div>

        <form id="edit-category-form" action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="relative z-10">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange focus:border-rOrange block p-4 transition outline-none @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-10">
                <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Slug URL</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange focus:border-rOrange block p-4 transition outline-none @error('slug') border-red-500 @enderror" required>
                @error('slug')
                    <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" onclick="openUpdateModal()" class="w-full md:w-auto bg-slate-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:bg-slate-700 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Perbarui Kategori
            </button>
        </form>
    </div>

    {{-- CUSTOM MODAL POP-UP (UPDATE) --}}
    <div id="update-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeUpdateModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300" id="update-modal-box">
            <div class="w-20 h-20 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Simpan Perubahan?</h3>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Anda yakin ingin memperbarui data kategori ini?</p>
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
            document.getElementById('edit-category-form').submit();
        }
    </script>
@endpush