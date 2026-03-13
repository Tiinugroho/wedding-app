@extends('admin.partials.app')
@section('title', 'Daftar Musik')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        /* Mengecilkan ukuran audio player agar rapi di dalam tabel */
        audio { height: 35px; width: 250px; outline: none; }
    </style>
@endpush

@section('content')
<div class="pc-container">
    <div class="pc-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title"><h5 class="m-b-10">Master Data Musik</h5></div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Daftar Musik</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Daftar Musik Latar</h5>
                        <a href="{{ route('admin.musics.create') }}" class="btn btn-primary btn-sm">
                            <i class="feather icon-plus"></i> Upload Musik
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-music" class="table table-hover table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kategori</th>
                                        <th>Judul Lagu - Artis</th>
                                        <th>Putar Musik (Preview)</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($musics as $music)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-light-info text-info border border-info">{{ $music->category }}</span>
                                        </td>
                                        <td class="fw-bold text-primary">{{ $music->title }}</td>
                                        <td>
                                            <audio controls controlsList="nodownload">
                                                <source src="{{ asset('storage/' . $music->file_path) }}" type="audio/mpeg">
                                                Browser Anda tidak mendukung elemen audio.
                                            </audio>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.musics.edit', $music->id) }}" class="btn btn-icon btn-warning btn-sm me-1" title="Edit">
                                                <i class="feather icon-edit text-white"></i>
                                            </a>
                                            <form action="{{ route('admin.musics.destroy', $music->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus musik ini? Jika dihapus, undangan klien yang menggunakan lagu ini tidak akan bersuara.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-danger btn-sm" title="Hapus">
                                                    <i class="feather icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-music').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                columnDefs: [ { orderable: false, targets: [2, 3] } ] // Matikan sort untuk Audio & Aksi
            });
        });
    </script>
@endpush