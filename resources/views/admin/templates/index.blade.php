@extends('admin.partials.app')
@section('title', 'Kelola Template')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <style>
        /* Penyesuaian Search & Select */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            padding: 0.35rem 0.75rem;
            outline: none;
            background-color: #f8fafc;
        }

        /* Memberikan jarak (margin) pada dropdown Show Entries */
        .dataTables_wrapper .dataTables_length select {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
            padding-right: 2rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #FF8B5A;
            box-shadow: 0 0 0 2px rgba(255, 139, 90, 0.2);
            background-color: #ffffff;
        }

        .dataTables_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* --- STYLING TOMBOL PREV, NEXT & PAGINATION --- */
        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem !important;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b !important;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: #f8fafc;
            border-color: #FF8B5A;
            color: #FF8B5A !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(to right, #FF5A5A, #FF8B5A) !important;
            border-color: transparent;
            color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(255, 90, 90, 0.2);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #94a3b8 !important;
            box-shadow: none;
        }

        /* --- ANIMASI GUNCANGAN UNTUK ICON PERINGATAN --- */
        @keyframes shake-warning {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-10deg);
            }

            75% {
                transform: rotate(10deg);
            }
        }

        /* Animasi Masuk Icon */
        @keyframes zoom-in-bounce {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            70% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-warning {
            animation: zoom-in-bounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                shake-warning 2s infinite ease-in-out 0.5s;
        }

        /* Overlay merah halus saat modal terbuka */
        .modal-active-bg {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }
    </style>
@endpush

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <button type="button" onclick="toggleSidebar()"
                class="lg:hidden bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-rOrange transition group">
                <svg class="w-6 h-6 group-hover:text-rOrange transition" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Master Data Template</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Kelola desain tema undangan Anda.</p>
            </div>
        </div>
        <a href="{{ route('admin.templates.create') }}"
            class="hidden md:flex items-center gap-2 bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-3 rounded-full font-bold text-sm shadow-lg shadow-rRed/20 hover:scale-105 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Template
        </a>
    </header>

    <a href="{{ route('admin.templates.create') }}"
        class="md:hidden flex items-center justify-center gap-2 bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-3 rounded-full font-bold text-sm shadow-lg shadow-rRed/20 hover:scale-105 transition-transform mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Template
    </a>

    <section>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table id="table-template" class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                            <th class="p-6 font-bold w-16">No</th>
                            <th class="p-6 font-bold text-center">Preview</th>
                            <th class="p-6 font-bold">Nama Tema</th>
                            <th class="p-6 font-bold">Kategori & Paket</th>
                            <th class="p-6 font-bold">Status</th>
                            <th class="p-6 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($templates as $template)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition duration-200">
                                <td class="p-6 text-slate-500 font-medium">{{ $loop->iteration }}</td>

                                {{-- Iframe Preview --}}
                                <td class="p-6 text-center">
                                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 shadow-sm mx-auto"
                                        style="width: 100px; height: 100px; background-color: #f8fafc;">
                                        <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
                                            <iframe src="{{ asset('preview/' . $template->view_path . '/index.html') }}"
                                                style="position: absolute; top: 0; left: 0; width: 400%; height: 400%; transform-origin: top left; transform: scale(0.25); border: none;"
                                                scrolling="no" tabindex="-1"></iframe>
                                        </div>
                                        <a href="{{ asset('preview/' . $template->view_path . '/index.html') }}"
                                            target="_blank"
                                            class="absolute inset-0 z-10 flex items-center justify-center bg-slate-900/10 opacity-0 hover:opacity-100 transition-opacity"
                                            title="Lihat Full Preview">
                                            <svg class="w-6 h-6 text-white drop-shadow-md" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>

                                <td class="p-6">
                                    <div class="font-bold text-slate-800">{{ $template->name }}</div>
                                    <div class="text-xs text-slate-400 mt-1">Path: {{ $template->view_path }}</div>
                                </td>
                                <td class="p-6">
                                    <div class="mb-2">
                                        <span
                                            class="px-3 py-1 bg-blue-50 text-blue-500 text-[10px] uppercase tracking-wider font-bold rounded-lg border border-blue-100">
                                            {{ $template->category->name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span
                                            class="px-3 py-1 bg-purple-50 text-purple-600 text-[10px] uppercase tracking-wider font-bold rounded-lg border border-purple-100">
                                            {{ $template->package->name ?? 'Tanpa Paket' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-6">
                                    @if ($template->is_active)
                                        <span
                                            class="px-3 py-1 bg-green-50 text-green-500 text-xs font-bold rounded-lg">Aktif</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-slate-100 text-slate-500 text-xs font-bold rounded-lg">Draft</span>
                                    @endif
                                </td>
                                <td class="p-6 flex justify-center gap-2 items-center h-full">
                                    <a href="{{ route('admin.templates.edit', $template->id) }}"
                                        class="p-2 bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white rounded-xl transition shadow-sm"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form id="delete-form-{{ $template->id }}"
                                        action="{{ route('admin.templates.destroy', $template->id) }}" method="POST"
                                        class="d-inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                            onclick="openCustomModal('delete', 'delete-form-{{ $template->id }}', 'Apakah Anda yakin ingin menghapus template ini secara permanen?')"
                                            class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- CUSTOM MODAL POP-UP (HAPUS) --}}
    <div id="custom-modal"
        class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">

        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeCustomModal()"></div>

        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-all duration-300"
            id="custom-modal-box">

            <div class="relative w-24 h-24 mx-auto mb-6">
                <div class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-25"></div>
                <div
                    class="relative w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center border-4 border-white shadow-inner animate-warning">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
            </div>

            <h3 class="text-2xl font-extrabold text-slate-800 mb-3">Hapus Data?</h3>
            <p id="custom-modal-text" class="text-slate-500 text-sm mb-8 leading-relaxed px-2"></p>

            <div class="flex flex-col gap-3">
                <button type="button" id="confirm-btn"
                    class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-0.5 transition-all active:scale-95">
                    Ya, Hapus Permanen
                </button>
                <button type="button" onclick="closeCustomModal()"
                    class="w-full bg-slate-100 text-slate-600 px-6 py-4 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-template').DataTable({
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                "dom": '<"flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-100 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center p-6 border-t border-slate-100 gap-4"ip>',
                columnDefs: [{
                    orderable: false,
                    targets: [1, 5] // Kolom preview dan aksi tidak bisa disortir
                }]
            });
        });

        let targetFormId = '';

        function openCustomModal(type, formId, message) {
            targetFormId = formId;
            document.getElementById('custom-modal-text').innerText = message;

            const modal = document.getElementById('custom-modal');
            const box = document.getElementById('custom-modal-box');

            modal.classList.remove('opacity-0', 'pointer-events-none');

            // Trigger animasi scale
            setTimeout(() => {
                box.classList.remove('scale-95');
                box.classList.add('scale-100');
            }, 10);
        }

        function closeCustomModal() {
            const modal = document.getElementById('custom-modal');
            const box = document.getElementById('custom-modal-box');

            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        document.getElementById('confirm-btn').addEventListener('click', function() {
            if (targetFormId) document.getElementById(targetFormId).submit();
        });
    </script>
@endpush
