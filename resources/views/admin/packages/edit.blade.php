@extends('admin.partials.app')
@section('title', 'Edit Paket Harga')

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
        <a href="{{ route('admin.packages.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rOrange transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Edit Paket</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Perbarui detail dan fitur <span class="text-rOrange">{{ $package->name }}</span>.</p>
        </div>
    </header>

    <form id="edit-package-form" action="{{ route('admin.packages.update', $package->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- KOLOM KIRI: Info Dasar --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8 h-fit">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    Informasi Dasar
                </h3>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Nama Paket <span class="text-rRed">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Harga Jual (Rp) <span class="text-rRed">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $package->price) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none @error('price') border-red-500 @enderror" required>
                        @error('price') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Harga Coret (Rp)</label>
                        <input type="number" name="original_price" value="{{ old('original_price', $package->original_price) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-slate-700 font-bold mb-2 ml-1 text-sm">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-2xl focus:ring-2 focus:ring-rOrange block p-3.5 transition outline-none">{{ old('description', $package->description) }}</textarea>
                </div>

                <div class="mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="block text-slate-700 font-bold mb-2 text-sm">Status Paket</label>
                    <select name="is_active" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-rOrange block p-3 transition outline-none">
                        <option value="1" {{ old('is_active', $package->is_active) == '1' ? 'selected' : '' }}>Aktif (Tampil di Klien)</option>
                        <option value="0" {{ old('is_active', $package->is_active) == '0' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                    </select>
                </div>

                <button type="button" onclick="openUpdateModal()" class="w-full bg-slate-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:bg-slate-700 hover:scale-[1.02] transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Perbarui Paket
                </button>
            </div>

            {{-- KOLOM KANAN: Drag & Drop Fitur --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-2 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-green-100 text-green-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                    Manajemen Fitur
                </h3>
                <p class="text-slate-400 text-xs mb-6">Seret daftar ke atas/bawah atau pindahkan antara kotak hijau dan abu-abu.</p>

                @php
                    $features = is_array($package->features) ? $package->features : json_decode($package->features, true);
                @endphp

                {{-- Included Features --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-4 py-2 bg-green-50 text-green-600 text-xs font-bold uppercase rounded-xl border border-green-100 flex items-center gap-2 w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Fitur Didapat
                        </span>
                    </div>
                    
                    <div id="list-included" class="sortable-list" data-type="included">
                        @if(isset($features['included']) && count($features['included']) > 0)
                            @foreach($features['included'] as $item)
                            <div class="sortable-item">
                                <svg class="w-5 h-5 drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                <input type="text" name="features[included][]" value="{{ $item }}" class="flex-1 border-0 bg-transparent text-sm text-slate-800 outline-none focus:ring-0" required>
                                <button type="button" class="text-red-400 hover:text-red-600 transition" onclick="this.closest('.sortable-item').remove()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @endforeach
                        @endif
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
                        @if(isset($features['excluded']) && count($features['excluded']) > 0)
                            @foreach($features['excluded'] as $item)
                            <div class="sortable-item">
                                <svg class="w-5 h-5 drag-handle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                <input type="text" name="features[excluded][]" value="{{ $item }}" class="flex-1 border-0 bg-transparent text-sm text-slate-800 outline-none focus:ring-0">
                                <button type="button" class="text-red-400 hover:text-red-600 transition" onclick="this.closest('.sortable-item').remove()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="w-full mt-3 py-2.5 bg-slate-50 text-slate-500 hover:text-slate-700 hover:bg-slate-200 rounded-xl font-bold text-sm transition border border-dashed border-slate-200" onclick="addFeature('excluded')">
                        + Tambah Baris
                    </button>
                </div>
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
            <p class="text-slate-500 text-sm mb-8 leading-relaxed">Anda yakin ingin memperbarui konfigurasi fitur dan harga paket ini?</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button type="button" onclick="closeUpdateModal()" class="w-full bg-white text-slate-600 border border-slate-200 px-6 py-3 rounded-2xl font-bold hover:bg-slate-50 transition">Batal</button>
                <button type="button" onclick="submitUpdateForm()" class="w-full bg-blue-500 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:scale-[1.02] transition">Perbarui!</button>
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

        function openUpdateModal() {
            document.getElementById('update-modal').classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById('update-modal-box').classList.remove('scale-95');
        }
        function closeUpdateModal() {
            document.getElementById('update-modal').classList.add('opacity-0', 'pointer-events-none');
            document.getElementById('update-modal-box').classList.add('scale-95');
        }
        function submitUpdateForm() { document.getElementById('edit-package-form').submit(); }
    </script>
@endpush