@extends('admin.partials.app')
@section('title', 'Upload Musik')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title"><h5 class="m-b-10">Upload Musik Latar</h5></div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.musics.index') }}">Daftar Musik</a></li>
                            <li class="breadcrumb-item" aria-current="page">Upload Baru</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card mt-2">
                    <div class="card-header"><h5>Formulir Upload Musik</h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.musics.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Judul Lagu & Artis <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: A Thousand Years - Christina Perri" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori Musik <span class="text-danger">*</span></label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="Musik Indonesia" {{ old('category') == 'Musik Indonesia' ? 'selected' : '' }}>Musik Indonesia</option>
                                    <option value="Musik Traditional" {{ old('category') == 'Musik Traditional' ? 'selected' : '' }}>Musik Traditional</option>
                                    <option value="Musik Jepang" {{ old('category') == 'Musik Jepang' ? 'selected' : '' }}>Musik Jepang</option>
                                    <option value="Musik Instrumental" {{ old('category') == 'Musik Instrumental' ? 'selected' : '' }}>Musik Instrumental</option>
                                    <option value="Musik Islami" {{ old('category') == 'Musik Islami' ? 'selected' : '' }}>Musik Islami</option>
                                    <option value="Musik Barat" {{ old('category') == 'Musik Barat' ? 'selected' : '' }}>Musik Barat</option>
                                    <option value="Musik Celebration" {{ old('category') == 'Musik Celebration' ? 'selected' : '' }}>Musik Celebration</option>
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">File Audio (.mp3, .wav) <span class="text-danger">*</span></label>
                                <input type="file" name="file_path" class="form-control @error('file_path') is-invalid @enderror" accept="audio/mp3,audio/wav,audio/*" required>
                                <small class="text-muted">Ukuran maksimal file: 10 MB.</small>
                                @error('file_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="text-end">
                                <a href="{{ route('admin.musics.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-primary"><i class="feather icon-upload"></i> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection