@extends('admin.partials.app')
@section('title', 'Edit Template')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title"><h5 class="m-b-10">Edit Template Tema</h5></div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Template</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Data</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header"><h5>Edit Data: <span class="text-primary">{{ $template->name }}</span></h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.templates.update', $template->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Informasi Utama</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nama Tema <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $template->name) }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ $template->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Path Blade (View Path) <span class="text-danger">*</span></label>
                                        <input type="text" name="view_path" class="form-control" value="{{ old('view_path', $template->view_path) }}" required>
                                    </div>

                                    {{-- <div class="mb-4">
                                        <label class="form-label">Ganti Thumbnail (Opsional)</label>
                                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$template->thumbnail) }}" alt="Preview" class="img-thumbnail" width="150">
                                        </div>
                                    </div> --}}
                                </div>

                                <div class="col-md-6">
                                    @php
                                        // Mencegah error jika data di database masih bentuk JSON string
                                        $req_fields = is_array($template->required_fields) ? $template->required_fields : json_decode($template->required_fields, true);
                                    @endphp
                                    <h6 class="text-success mb-3">Persyaratan Fitur (Required Fields)</h6>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="has_video" id="has_video" value="1" {{ isset($req_fields['has_video']) && $req_fields['has_video'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_video">Mendukung Video Undangan</label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="has_love_story" id="has_love_story" value="1" {{ isset($req_fields['has_love_story']) && $req_fields['has_love_story'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_love_story">Mendukung Cerita Cinta (Love Story)</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Batas Maksimal Galeri Foto</label>
                                        <input type="number" name="gallery_limit" class="form-control" value="{{ $req_fields['gallery_limit'] ?? 0 }}" required>
                                    </div>
                                    
                                    <div class="mb-3 mt-4">
                                        <label class="form-label">Status Tayang</label>
                                        <select name="is_active" class="form-select">
                                            <option value="1" {{ $template->is_active == 1 ? 'selected' : '' }}>Aktif (Tampil di Klien)</option>
                                            <option value="0" {{ $template->is_active == 0 ? 'selected' : '' }}>Draft / Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-end">
                                <a href="{{ route('admin.templates.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-warning">Update Template</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection