@extends('admin.partials.app')
@section('title', 'Edit Kategori')

@section('content')
<div class="pc-container">
    <div class="pc-content">

        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Kategori Tema</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori Tema</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Data</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card mt-5">
                    <div class="card-header">
                        <h5>Edit Kategori: <span class="text-primary">{{ $category->name }}</span></h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Slug URL <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}" required>
                                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="text-end">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-warning">Update Kategori</button>
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
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');
    name.addEventListener('keyup', function() {
        let preslug = name.value;
        preslug = preslug.replace(/ /g,"-");
        slug.value = preslug.toLowerCase().replace(/[^\w-]+/g, "");
    });
</script>
@endpush