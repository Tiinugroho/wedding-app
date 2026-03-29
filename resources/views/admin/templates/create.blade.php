@extends('admin.partials.app')
@section('title', 'Tambah Template')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title"><h5 class="m-b-10">Tambah Template Tema</h5></div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Template</a></li>
                            <li class="breadcrumb-item" aria-current="page">Tambah Baru</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"><h5>Formulir Template Baru</h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Informasi Utama</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nama Tema <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Path Blade (View Path) <span class="text-danger">*</span></label>
                                        <input type="text" name="view_path" class="form-control" placeholder="Contoh: wed-1" required>
                                        <small class="text-muted">Ini adalah nama file blade yang akan dirender (resources/views/themes/wed-1/index.blade.php)</small>
                                    </div>

                                    {{-- <div class="mb-4">
                                        <label class="form-label">Upload Thumbnail <span class="text-danger">*</span></label>
                                        <input type="file" name="thumbnail" class="form-control" accept="image/*" required>
                                    </div> --}}
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-success mb-3">Persyaratan Fitur (Required Fields)</h6>
                                    <p class="text-muted small">Fitur apa saja yang didukung oleh tema/desain ini?</p>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="has_video" id="has_video" value="1" checked>
                                        <label class="form-check-label" for="has_video">Mendukung Video Undangan</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="has_love_story" id="has_love_story" value="1" checked>
                                        <label class="form-check-label" for="has_love_story">Mendukung Cerita Cinta (Love Story)</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Batas Maksimal Galeri Foto</label>
                                        <input type="number" name="gallery_limit" class="form-control" value="10" min="0" required>
                                        <small class="text-muted">Isi dengan batasan desain HTML-nya (misal: grid foto di desain ini hanya muat 10)</small>
                                    </div>
                                    
                                    <div class="mb-3 mt-4">
                                        <label class="form-label">Status Tayang</label>
                                        <select name="is_active" class="form-select">
                                            <option value="1">Aktif (Tampil di Klien)</option>
                                            <option value="0">Draft / Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-end">
                                <a href="{{ route('admin.templates.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Template</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection