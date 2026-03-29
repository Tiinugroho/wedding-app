@extends('admin.partials.app')
@section('title', 'Kelola Template')

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
                        <div class="page-header-title"><h5 class="m-b-10">Master Data Template</h5></div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Template Tema</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Daftar Template Tema</h5>
                        <a href="{{ route('admin.templates.create') }}" class="btn btn-primary btn-sm"><i class="feather icon-plus"></i> Tambah Template</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-template" class="table table-hover table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 15%" class="text-center">Preview</th>
                                        <th>Nama Tema</th>
                                        <th>Kategori</th>
                                        <th>View Path</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($templates as $template)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        
                                        {{-- KOLOM LIVE PREVIEW IFRAME --}}
                                        <td class="text-center">
                                            <div class="position-relative overflow-hidden rounded border border-light mx-auto" style="width: 100px; height: 100px; background-color: #f8f9fa;">
                                                {{-- Iframe pembungkus agar tidak bisa di-scroll manual --}}
                                                <div class="position-absolute top-0 start-0 w-100 h-100" style="pointer-events: none;">
                                                    <iframe src="{{ asset('preview/' . $template->view_path . '/index.html') }}" 
                                                            style="position: absolute; top: 0; left: 0; width: 400%; height: 400%; transform-origin: top left; transform: scale(0.25); border: none;" 
                                                            scrolling="no" 
                                                            tabindex="-1">
                                                    </iframe>
                                                </div>
                                                {{-- Tombol klik untuk membuka preview besar di tab baru --}}
                                                <a href="{{ asset('preview/' . $template->view_path . '/index.html') }}" target="_blank" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 10;" title="Lihat Full Preview"></a>
                                            </div>
                                        </td>
                                        
                                        <td class="fw-bold">{{ $template->name }}</td>
                                        <td><span class="badge bg-light-primary">{{ $template->category->name ?? 'Tanpa Kategori' }}</span></td>
                                        <td><code class="text-dark bg-light px-2 py-1 rounded">public/preview/{{ $template->view_path }}/index.hmtl</code></td>
                                        <td>{!! $template->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>' !!}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.templates.edit', $template->id) }}" class="btn btn-icon btn-warning btn-sm me-1" title="Edit Template"><i class="feather icon-edit text-white"></i></a>
                                            <form action="{{ route('admin.templates.destroy', $template->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus template ini secara permanen?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-danger btn-sm" title="Hapus Template"><i class="feather icon-trash-2"></i></button>
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
    <script>$(document).ready(function() { $('#table-template').DataTable({ language:{url:'//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'}, columnDefs:[{orderable:false,targets:[0,5]}] }); });</script>
@endpush