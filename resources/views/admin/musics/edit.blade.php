@extends('admin.partials.app')
@section('title', 'Edit Musik')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title"><h5 class="m-b-10">Edit Musik Latar</h5></div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.musics.index') }}">Daftar Musik</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Data</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card mt-2">
                    <div class="card-header"><h5>Edit Data: <span class="text-primary">{{ $music->title }}</span></h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.musics.update', $music->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Judul Lagu & Artis <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $music->title) }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori Musik <span class="text-danger">*</span></label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <option value="Musik Indonesia" {{ old('category', $music->category) == 'Musik Indonesia' ? 'selected' : '' }}>Musik Indonesia</option>
                                    <option value="Musik Traditional" {{ old('category', $music->category) == 'Musik Traditional' ? 'selected' : '' }}>Musik Traditional</option>
                                    <option value="Musik Jepang" {{ old('category', $music->category) == 'Musik Jepang' ? 'selected' : '' }}>Musik Jepang</option>
                                    <option value="Musik Instrumental" {{ old('category', $music->category) == 'Musik Instrumental' ? 'selected' : '' }}>Musik Instrumental</option>
                                    <option value="Musik Islami" {{ old('category', $music->category) == 'Musik Islami' ? 'selected' : '' }}>Musik Islami</option>
                                    <option value="Musik Barat" {{ old('category', $music->category) == 'Musik Barat' ? 'selected' : '' }}>Musik Barat</option>
                                    <option value="Musik Celebration" {{ old('category', $music->category) == 'Musik Celebration' ? 'selected' : '' }}>Musik Celebration</option>
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label d-block">Lagu Saat Ini:</label>
                                <audio controls class="w-100" style="height: 40px;">
                                    <source src="{{ asset('storage/' . $music->file_path) }}" type="audio/mpeg">
                                </audio>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Ganti File Audio (Opsional)</label>
                                <input type="file" name="file_path" class="form-control @error('file_path') is-invalid @enderror" accept="audio/mp3,audio/wav,audio/*">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti lagunya.</small>
                                @error('file_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="text-end">
                                <a href="{{ route('admin.musics.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-warning"><i class="feather icon-save"></i> Update Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection