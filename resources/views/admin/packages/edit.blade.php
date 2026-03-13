@extends('admin.partials.app')
@section('title', 'Edit Paket Harga')

@push('styles')
<style>
    /* Styling khusus area Drag & Drop */
    .sortable-list {
        min-height: 50px;
        padding: 10px;
        background-color: #f8f9fa;
        border: 1px dashed #dee2e6;
        border-radius: 8px;
    }
    .sortable-item {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 8px 12px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: grab;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .sortable-item:active { cursor: grabbing; }
    .sortable-ghost { opacity: 0.4; background-color: #e9ecef; }
    .drag-handle { color: #adb5bd; cursor: grab; }
</style>
@endpush

@section('content')
<div class="pc-container">
    <div class="pc-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Paket Harga</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">Paket Harga</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Paket</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Edit Data: <span class="text-primary">{{ $package->name }}</span></h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-5">
                                    <h6 class="text-primary mb-3">Informasi Dasar</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nama Paket <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Harga Coret (Rp)</label>
                                            <input type="number" name="original_price" class="form-control @error('original_price') is-invalid @enderror" value="{{ old('original_price', $package->original_price) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi Singkat</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $package->description) }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Status Paket</label>
                                        <select name="is_active" class="form-select">
                                            <option value="1" {{ old('is_active', $package->is_active) == '1' ? 'selected' : '' }}>Aktif (Ditampilkan)</option>
                                            <option value="0" {{ old('is_active', $package->is_active) == '0' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-7 border-start">
                                    <h6 class="text-success mb-3">Manajemen Fitur (Drag & Drop)</h6>
                                    <p class="text-muted small mb-4">Seret fitur ke atas/bawah atau pindahkan antara kotak hijau (Dapat) dan kotak abu-abu (Tidak Dapat).</p>
                                    
                                    @php
                                        $features = is_array($package->features) ? $package->features : json_decode($package->features, true);
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-light-success text-success border border-success w-100 py-2 fs-6">
                                                    <i class="feather icon-check-circle"></i> Fitur Didapat (Included)
                                                </span>
                                            </div>
                                            
                                            <div id="list-included" class="sortable-list" data-type="included">
                                                @if(isset($features['included']) && count($features['included']) > 0)
                                                    @foreach($features['included'] as $item)
                                                    <div class="sortable-item">
                                                        <i class="feather icon-menu drag-handle"></i>
                                                        <input type="text" name="features[included][]" class="form-control form-control-sm border-0 px-1 bg-transparent" value="{{ $item }}" required>
                                                        <button class="btn btn-sm btn-link text-danger p-0" type="button" onclick="this.closest('.sortable-item').remove()"><i class="feather icon-trash-2"></i></button>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light-success mt-2 w-100" onclick="addFeature('included')">
                                                <i class="feather icon-plus"></i> Tambah Baris
                                            </button>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-light-secondary text-secondary border border-secondary w-100 py-2 fs-6">
                                                    <i class="feather icon-x-circle"></i> Fitur Ditolak (Excluded)
                                                </span>
                                            </div>
                                            
                                            <div id="list-excluded" class="sortable-list" data-type="excluded">
                                                @if(isset($features['excluded']) && count($features['excluded']) > 0)
                                                    @foreach($features['excluded'] as $item)
                                                    <div class="sortable-item">
                                                        <i class="feather icon-menu drag-handle"></i>
                                                        <input type="text" name="features[excluded][]" class="form-control form-control-sm border-0 px-1 bg-transparent" value="{{ $item }}">
                                                        <button class="btn btn-sm btn-link text-danger p-0" type="button" onclick="this.closest('.sortable-item').remove()"><i class="feather icon-trash-2"></i></button>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light-secondary mt-2 w-100" onclick="addFeature('excluded')">
                                                <i class="feather icon-plus"></i> Tambah Baris
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <hr>
                            <div class="text-end">
                                <a href="{{ route('admin.packages.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-warning"><i class="feather icon-save"></i> Update Paket</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Setup Sortable untuk container Included
        const listIncluded = document.getElementById('list-included');
        new Sortable(listIncluded, {
            group: 'shared-features', // Group yang sama memungkinkan drag antar list
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function (evt) {
                // PERBAIKAN: Ambil tipe dari kotak tujuan tempat item dilepas
                const targetType = evt.to.getAttribute('data-type');
                updateInputName(evt.item, targetType);
            }
        });

        // Setup Sortable untuk container Excluded
        const listExcluded = document.getElementById('list-excluded');
        new Sortable(listExcluded, {
            group: 'shared-features', 
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function (evt) {
                // PERBAIKAN: Ambil tipe dari kotak tujuan tempat item dilepas
                const targetType = evt.to.getAttribute('data-type');
                updateInputName(evt.item, targetType);
            }
        });
    });

    // Fungsi cerdas untuk mengubah 'name' attribute pada input saat item berpindah list
    function updateInputName(itemElement, newType) {
        // Ambil elemen input di dalam item yang di-drag
        const input = itemElement.querySelector('input');
        if (input) {
            // Ubah namenya secara dinamis sesuai letak kotak barunya
            input.name = `features[${newType}][]`;
            
            // Set required jika masuk ke included, hapus jika ke excluded
            if(newType === 'included') {
                input.required = true;
            } else {
                input.required = false;
            }
        }
    }

    // Fungsi untuk menambah baris input baru
    function addFeature(type) {
        const container = document.getElementById('list-' + type);
        const isRequired = type === 'included' ? 'required' : '';
        
        const html = `
            <div class="sortable-item">
                <i class="feather icon-menu drag-handle"></i>
                <input type="text" name="features[${type}][]" class="form-control form-control-sm border-0 px-1 bg-transparent" placeholder="Ketik nama fitur..." ${isRequired} autofocus>
                <button class="btn btn-sm btn-link text-danger p-0" type="button" onclick="this.closest('.sortable-item').remove()"><i class="feather icon-trash-2"></i></button>
            </div>
        `;
        
        // Append HTML
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endpush