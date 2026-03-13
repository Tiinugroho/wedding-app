@extends('admin.partials.app')
@section('title', 'Tambah Kategori')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Tambah Kategori Tema</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori Tema</a></li>
                            <li class="breadcrumb-item" aria-current="page">Tambah Baru</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card mt-5">
                    <div class="card-header">
                        <h5>Tambah Kategori Tema</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Modern Elegant" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Slug URL <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="modern-elegant" required>
                                <small class="text-muted">Akan terisi otomatis berdasarkan nama kategori.</small>
                                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="text-end">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
    // Fitur cerdas: Membuat Slug otomatis saat mengetik Nama Kategori
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');

    name.addEventListener('keyup', function() {
        let preslug = name.value;
        preslug = preslug.replace(/ /g,"-");
        slug.value = preslug.toLowerCase().replace(/[^\w-]+/g, "");
    });
</script>
@endpush