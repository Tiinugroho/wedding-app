@extends('admin.partials.app')
@section('title', 'Kelola Paket Harga')

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
                            <h5 class="m-b-10">Master Data Paket</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Paket Harga</li>
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
                        <h5>Daftar Paket Harga</h5>
                        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm">
                            <i class="feather icon-plus"></i> Tambah Paket Baru
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-paket" class="table table-hover table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Paket</th>
                                        <th>Harga Jual (Rp)</th>
                                        <th>Harga Coret (Rp)</th>
                                        <th class="text-center">Status</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold text-primary">{{ $package->name }}</td>
                                        <td>Rp. {{ number_format($package->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($package->original_price)
                                                Rp. <span class="text-muted text-decoration-line-through">{{ number_format($package->original_price, 0, ',', '.') }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($package->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-icon btn-warning btn-sm me-1" title="Edit">
                                                <i class="feather icon-edit text-white"></i>
                                            </a>
                                            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?');">
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
            // Inisialisasi DataTables dengan terjemahan Bahasa Indonesia
            $('#table-paket').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                },
                // Menonaktifkan sorting otomatis pada kolom "Aksi"
                columnDefs: [
                    { orderable: false, targets: 5 } 
                ]
            });
        });
    </script>
@endpush