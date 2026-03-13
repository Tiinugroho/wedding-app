@extends('admin.partials.app')
@section('title', 'Kelola Kategori Tema')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="pc-container">
    <div class="pc-content">
        
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Master Data Kategori</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Kategori Tema</li>
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
                        <h5>Daftar Kategori Tema</h5>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                            <i class="feather icon-plus"></i> Tambah Kategori
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-category" class="table table-hover table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Kategori</th>
                                        <th>Slug URL</th>
                                        <th>Total Template</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $category->name }}</td>
                                        <td><span class="badge bg-light-secondary text-secondary">{{ $category->slug }}</span></td>
                                        <td>{{ $category->templates->count() }} Tema</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-icon btn-warning btn-sm me-1" title="Edit">
                                                <i class="feather icon-edit text-white"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Menghapus kategori akan menghapus SEMUA Tema yang menggunakan kategori ini. Yakin lanjutkan?');">
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
            $('#table-category').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
                columnDefs: [ { orderable: false, targets: 4 } ]
            });
        });
    </script>
@endpush