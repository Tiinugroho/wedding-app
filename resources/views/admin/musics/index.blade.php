@extends('admin.partials.app')
@section('title', 'Daftar Musik')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <style>
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem; border: 1px solid #e2e8f0; padding: 0.35rem 0.75rem; outline: none; background-color: #f8fafc;
        }
        .dataTables_wrapper .dataTables_length select { margin-left: 0.5rem; margin-right: 0.5rem; padding-right: 2rem; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #FF8B5A; box-shadow: 0 0 0 2px rgba(255, 139, 90, 0.2); background-color: #ffffff; }
        .dataTables_wrapper .dataTables_filter label { display: flex; align-items: center; gap: 0.5rem; }
        .dataTables_wrapper .dataTables_paginate { display: flex; align-items: center; gap: 0.25rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem !important; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b !important; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #f8fafc; border-color: #FF8B5A; color: #FF8B5A !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: linear-gradient(to right, #FF5A5A, #FF8B5A) !important; border-color: transparent; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(255, 90, 90, 0.2); }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; background: #f1f5f9; }

        @keyframes shake-warning { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        @keyframes zoom-in-bounce { 0% { transform: scale(0.5); opacity: 0; } 70% { transform: scale(1.1); opacity: 1; } 100% { transform: scale(1); } }
        .animate-warning { animation: zoom-in-bounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), shake-warning 2s infinite ease-in-out 0.5s; }

        /* Kustomisasi Audio Player HTML5 */
        audio { height: 40px; outline: none; border-radius: 2rem; width: 100%; max-width: 250px; }
        audio::-webkit-media-controls-panel { background-color: #f8fafc; }
        audio::-webkit-media-controls-play-button { background-color: #e2e8f0; border-radius: 50%; }
        audio::-webkit-media-controls-current-time-display,
        audio::-webkit-media-controls-time-remaining-display { color: #64748b; font-family: inherit; font-size: 12px; }
    </style>
@endpush

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <button type="button" onclick="toggleSidebar()" class="lg:hidden bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-rOrange transition group">
                <svg class="w-6 h-6 group-hover:text-rOrange transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Master Data Musik</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Kelola daftar lagu latar untuk undangan klien.</p>
            </div>
        </div>
        <a href="{{ route('admin.musics.create') }}" class="hidden md:flex items-center gap-2 bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-3 rounded-full font-bold text-sm shadow-lg shadow-rRed/20 hover:scale-105 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Upload Musik
        </a>
    </header>

    <a href="{{ route('admin.musics.create') }}" class="md:hidden flex items-center justify-center gap-2 bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-3 rounded-full font-bold text-sm shadow-lg shadow-rRed/20 hover:scale-105 transition-transform mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Upload Musik
    </a>

    <section>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table id="table-music" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                            <th class="p-6 font-bold w-16">No</th>
                            <th class="p-6 font-bold">Kategori</th>
                            <th class="p-6 font-bold">Judul Lagu - Artis</th>
                            <th class="p-6 font-bold text-center">Preview (Putar)</th>
                            <th class="p-6 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($musics as $music)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition duration-200">
                                <td class="p-6 text-slate-500 font-medium">{{ $loop->iteration }}</td>
                                <td class="p-6">
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-500 text-[10px] font-bold uppercase rounded-lg tracking-wider border border-indigo-100">
                                        {{ $music->category }}
                                    </span>
                                </td>
                                <td class="p-6 font-extrabold text-slate-800">{{ $music->title }}</td>
                                <td class="p-6 text-center">
                                    <div class="flex justify-center">
                                        <audio controls controlsList="nodownload">
                                            <source src="{{ asset('storage/' . $music->file_path) }}" type="audio/mpeg">
                                            Browser tidak support.
                                        </audio>
                                    </div>
                                </td>
                                <td class="p-6 flex justify-center gap-2 items-center h-full mt-2">
                                    <a href="{{ route('admin.musics.edit', $music->id) }}" class="p-2 bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white rounded-xl transition shadow-sm" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form id="delete-form-{{ $music->id }}" action="{{ route('admin.musics.destroy', $music->id) }}" method="POST" class="d-inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="openCustomModal('delete', 'delete-form-{{ $music->id }}', 'Apakah Anda yakin ingin menghapus musik ini? Jika dihapus, undangan klien yang menggunakan lagu ini tidak akan bersuara.')" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
    <div id="custom-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeCustomModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-all duration-300" id="custom-modal-box">
            <div class="relative w-24 h-24 mx-auto mb-6">
                <div class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-25"></div>
                <div class="relative w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center border-4 border-white shadow-inner animate-warning">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-3">Hapus Data?</h3>
            <p id="custom-modal-text" class="text-slate-500 text-sm mb-8 leading-relaxed px-2"></p>
            <div class="flex flex-col gap-3">
                <button type="button" id="confirm-btn" class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-red-500/30 hover:-translate-y-0.5 transition-all active:scale-95">Ya, Hapus Permanen</button>
                <button type="button" onclick="closeCustomModal()" class="w-full bg-slate-100 text-slate-600 px-6 py-4 rounded-2xl font-bold hover:bg-slate-200 transition-all">Batal</button>
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
            $('#table-music').DataTable({
                responsive: true,
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' },
                "dom": '<"flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-100 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center p-6 border-t border-slate-100 gap-4"ip>',
                columnDefs: [{ orderable: false, targets: [3, 4] }] // Mematikan sorting untuk kolom Preview Audio & Aksi
            });
        });

        let targetFormId = '';
        function openCustomModal(type, formId, message) {
            targetFormId = formId;
            document.getElementById('custom-modal-text').innerText = message;
            const modal = document.getElementById('custom-modal');
            const box = document.getElementById('custom-modal-box');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => { box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
        }
        function closeCustomModal() {
            const modal = document.getElementById('custom-modal');
            const box = document.getElementById('custom-modal-box');
            box.classList.remove('scale-100'); box.classList.add('scale-95'); modal.classList.add('opacity-0', 'pointer-events-none');
        }
        document.getElementById('confirm-btn').addEventListener('click', function() {
            if (targetFormId) document.getElementById(targetFormId).submit();
        });
    </script>
@endpush