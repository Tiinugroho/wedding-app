@extends('admin.partials.app')
@section('title', 'Tambah Paket Harga')

@push('styles')
<style>
    /* Styling area Drag & Drop Tailwind */
    .sortable-list { min-height: 80px; padding: 1rem; background-color: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 1rem; display: flex; flex-direction: column; gap: 0.5rem; }
    .sortable-item { background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.5rem 0.75rem; display: flex; align-items: center; gap: 0.75rem; cursor: grab; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .sortable-item:active { cursor: grabbing; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .sortable-ghost { opacity: 0.4; background-color: #f1f5f9; border: 2px dashed #cbd5e1; }
    .drag-handle { cursor: grab; color: #94a3b8; }
</style>
@endpush

@section('content')
    <header class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.packages.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rRed transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Tambah Paket</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Buat paket harga dan atur fiturnya.</p>
        </div>
    </header>

    <form id="create-package-form" action="{{ route('admin.packages.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- KOLOM KIRI: Info Dasar --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8 h-fit">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    Informasi Dasar
                </h3>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Nama Paket <span class="text-rRed">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Paket Platinum" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Harga Jual (Rp) <span class="text-rRed">*</span></label>
                        <input type="number" name="price" value="{{ old('price') }}" placeholder="Contoh: 76000" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('price') border-red-500 @enderror" required>
                        @error('price') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Harga Coret (Rp)</label>
                        <input type="number" name="original_price" value="{{ old('original_price') }}" placeholder="Contoh: 149000" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none">
                        <p class="text-[10px] text-slate-400 mt-1 ml-1">Kosongkan jika tak ada promo</p>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi pendek untuk ditampilkan..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none">{{ old('description') }}</textarea>
                </div>

                <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Status Paket</label>
                    <select name="is_active" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-rOrange block p-3 transition outline-none">
                        <option value="1">Aktif (Tampil di Klien)</option>
                        <option value="0">Nonaktif (Sembunyikan)</option>
                    </select>
                </div>

                <button type="button" onclick="openSaveModal()" class="w-full bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-rRed/20 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Paket Baru
                </button>
            </div>

            {{-- KOLOM KANAN: Drag & Drop Fitur --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-2 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-green-100 text-green-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                    Manajemen Fitur
                </h3>
                <p class="text-slate-400 text-xs mb-6">Seret daftar ke atas/bawah atau pindahkan antara kotak hijau dan abu-abu.</p>

                {{-- Included Features --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-4 py-2 bg-green-50 text-green-600 text-xs font-bold uppercase rounded-xl border border-green-100 flex items-center gap-2 w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Fitur Didapat
                        </span>
                    </div>
                    
                    <div id="list-included" class="sortable-list" data-type="included">
                        <div class="sortable-item">
                            <svg class="w-5 h-5 drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                            <input type="text" name="features[included][]" class="flex-1 border-0 bg-transparent text-sm text-slate-800 outline-none focus:ring-0" placeholder="Ketik nama fitur..." required>
                            <button type="button" class="text-red-400 hover:text-red-600 transition" onclick="this.closest('.sortable-item').remove()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="w-full mt-3 py-2.5 bg-slate-50 text-slate-500 hover:text-green-500 hover:bg-green-50 rounded-xl font-bold text-sm transition border border-dashed border-slate-200" onclick="addFeature('included')">
                        + Tambah Baris
                    </button>
                </div>

                {{-- Excluded Features --}}
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-4 py-2 bg-slate-50 text-slate-500 text-xs font-bold uppercase rounded-xl border border-slate-200 flex items-center gap-2 w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Fitur Ditolak
                        </span>
                    </div>
                    
                    <div id="list-excluded" class="sortable-list" data-type="excluded">
                        </div>
                    <button type="button" class="w-full mt-3 py-2.5 bg-slate-50 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded-xl font-bold text-sm transition border border-dashed border-slate-200" onclick="addFeature('excluded')">
                        + Tambah Baris
                    </button>
                </div>
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
            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Simpan Paket?</h3>
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Pastikan harga dan list fitur sudah terisi dengan benar.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" onclick="closeSaveModal()" class="w-full bg-white text-slate-600 border border-slate-200 px-6 py-3 rounded-2xl font-bold hover:bg-slate-50 transition">Periksa Lagi</button>
                <button type="button" onclick="submitForm()" class="w-full bg-green-500 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-green-500/30 hover:scale-[1.02] transition">Ya, Simpan!</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const listIncluded = document.getElementById('list-included');
            new Sortable(listIncluded, { group: 'shared-features', animation: 150, handle: '.drag-handle', ghostClass: 'sortable-ghost',
                onEnd: function (evt) { updateInputName(evt.item, evt.to.getAttribute('data-type')); }
            });

            const listExcluded = document.getElementById('list-excluded');
            new Sortable(listExcluded, { group: 'shared-features', animation: 150, handle: '.drag-handle', ghostClass: 'sortable-ghost',
                onEnd: function (evt) { updateInputName(evt.item, evt.to.getAttribute('data-type')); }
            });
        });

        function updateInputName(itemElement, newType) {
            const input = itemElement.querySelector('input');
            if (input) {
                input.name = `features[${newType}][]`;
                input.required = (newType === 'included');
            }
        }

        function addFeature(type) {
            const container = document.getElementById('list-' + type);
            const isRequired = type === 'included' ? 'required' : '';
            const html = `
                <div class="sortable-item">
                    <svg class="w-5 h-5 drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                    <input type="text" name="features[${type}][]" class="flex-1 border-0 bg-transparent text-sm text-slate-800 outline-none focus:ring-0" placeholder="Ketik nama fitur..." ${isRequired} autofocus>
                    <button type="button" class="text-red-400 hover:text-red-600 transition" onclick="this.closest('.sortable-item').remove()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function openSaveModal() {
            document.getElementById('save-modal').classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById('save-modal-box').classList.remove('scale-95');
        }
        function closeSaveModal() {
            document.getElementById('save-modal').classList.add('opacity-0', 'pointer-events-none');
            document.getElementById('save-modal-box').classList.add('scale-95');
        }
        function submitForm() { document.getElementById('create-package-form').submit(); }
    </script>
@endpush