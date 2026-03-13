@extends('admin.partials.app')
@section('title', 'Tambah Paket Harga')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tambah Paket Harga</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">Paket Harga</a></li>
                            <li class="breadcrumb-item" aria-current="page">Tambah Baru</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Formulir Paket Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Informasi Dasar</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nama Paket <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Paket Platinum" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Contoh: 76000" required>
                                            <small class="text-muted">Tanpa titik/koma</small>
                                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Harga Coret (Rp)</label>
                                            <input type="number" name="original_price" class="form-control @error('original_price') is-invalid @enderror" value="{{ old('original_price') }}" placeholder="Contoh: 149000">
                                            <small class="text-muted">Kosongkan jika tidak ada promo</small>
                                            @error('original_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi Singkat</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Deskripsi untuk ditampilkan di bawah harga...">{{ old('description') }}</textarea>
                                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Status Paket</label>
                                        <select name="is_active" class="form-select">
                                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif (Ditampilkan)</option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-success mb-3">Manajemen Fitur</h6>
                                    
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-success mb-0"><i class="feather icon-check-circle"></i> Fitur yang Didapatkan (Included)</label>
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="addFeature('included')"><i class="feather icon-plus"></i> Tambah</button>
                                        </div>
                                        <div id="included-container">
                                            <div class="input-group mb-2">
                                                <input type="text" name="features[included][]" class="form-control" placeholder="Contoh: Masa Aktif 3 Bulan" required>
                                                <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()">Hapus</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-muted mb-0"><i class="feather icon-x-circle"></i> Fitur yang Tidak Didapat (Excluded)</label>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addFeature('excluded')"><i class="feather icon-plus"></i> Tambah</button>
                                        </div>
                                        <div id="excluded-container">
                                            </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="text-end">
                                <a href="{{ route('admin.packages.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Paket</button>
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
<script>
    function addFeature(type) {
        const container = document.getElementById(type + '-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        
        // Membedakan input required untuk included, dan optional untuk excluded
        const isRequired = type === 'included' ? 'required' : '';
        
        div.innerHTML = `
            <input type="text" name="features[${type}][]" class="form-control" placeholder="Ketik nama fitur..." ${isRequired}>
            <button class="btn btn-outline-danger" type="button" onclick="this.parentElement.remove()"><i class="feather icon-trash-2"></i></button>
        `;
        container.appendChild(div);
    }
</script>
@endpush