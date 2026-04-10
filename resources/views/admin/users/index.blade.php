@extends('admin.partials.app')
@section('title', 'Data Pengguna')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <style>
        /* Kustomisasi DataTables menyatu dengan Tailwind */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem; 
            border: 1px solid #e2e8f0; 
            padding: 0.35rem 0.75rem; 
            outline: none; 
            background-color: #f8fafc;
        }
        .dataTables_wrapper .dataTables_length select { margin-left: 0.5rem; margin-right: 0.5rem; padding-right: 2rem; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #FF8B5A; box-shadow: 0 0 0 2px rgba(255, 139, 90, 0.2); background-color: #ffffff; }
        .dataTables_wrapper .dataTables_filter label { display: flex; align-items: center; gap: 0.5rem; }
        
        /* Kustomisasi Pagination */
        .dataTables_wrapper .dataTables_paginate { display: flex; align-items: center; gap: 0.25rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem !important; 
            border-radius: 0.75rem; 
            border: 1px solid #e2e8f0; 
            background: #ffffff; 
            color: #64748b !important; 
            font-weight: 600; 
            font-size: 0.875rem; 
            cursor: pointer; 
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #f8fafc; border-color: #FF8B5A; color: #FF8B5A !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: linear-gradient(to right, #FF5A5A, #FF8B5A) !important; border-color: transparent; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(255, 90, 90, 0.2); }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; background: #f1f5f9; }

        /* Animasi Custom Modal */
        @keyframes shake-warning { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        @keyframes zoom-in-bounce { 0% { transform: scale(0.5); opacity: 0; } 70% { transform: scale(1.1); opacity: 1; } 100% { transform: scale(1); } }
        .animate-warning { animation: zoom-in-bounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), shake-warning 2s infinite ease-in-out 0.5s; }
    </style>
@endpush

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <button type="button" onclick="toggleSidebar()" class="lg:hidden bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-rOrange transition group">
                <svg class="w-6 h-6 group-hover:text-rOrange transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Manajemen Pengguna</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Pantau dan kelola daftar klien yang terdaftar di sistem.</p>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium">
            <svg class="w-6 h-6 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium">
            <svg class="w-6 h-6 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <section>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table id="table-users" class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                            <th class="p-6 font-bold w-16 text-center">No</th>
                            <th class="p-6 font-bold">Nama Klien</th>
                            <th class="p-6 font-bold">Email</th>
                            <th class="p-6 font-bold">Tanggal Daftar</th>
                            <th class="p-6 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($users as $index => $user)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition duration-200">
                                <td class="p-6 text-slate-500 font-medium text-center">{{ $index + 1 }}</td>
                                <td class="p-6 font-extrabold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center font-bold text-lg shrink-0 border border-indigo-100">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td class="p-6 text-slate-600">{{ $user->email }}</td>
                                <td class="p-6 text-slate-500 font-medium">{{ $user->created_at->translatedFormat('d F Y') }}</td>
                                <td class="p-6 flex justify-center gap-2 items-center h-full">
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="openCustomModal('delete', 'delete-form-{{ $user->id }}', 'Apakah Anda yakin ingin menghapus pengguna ini? Semua data undangan dan riwayat tagihannya akan terhapus secara permanen.')" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition shadow-sm" title="Hapus Pengguna">
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
            $('#table-users').DataTable({
                responsive: true,
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' },
                "dom": '<"flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-100 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center p-6 border-t border-slate-100 gap-4"ip>',
                columnDefs: [
                    { orderable: false, targets: [4] } // Mematikan sorting khusus untuk kolom Aksi (Index ke-4)
                ] 
            });
        });

        // Logika Custom Modal
        let targetFormId = '';
        function openCustomModal(type, formId, message) {
            targetFormId = formId;
            document.getElementById('custom-modal-text').innerText = message;
            const modal = document.getElementById('custom-modal');
            const box = document.getElementById('custom-modal-box');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
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
            if (targetFormId) {
                // Memunculkan loading overlay jika ada sebelum submit
                const loadingOverlay = document.getElementById('loading-overlay');
                if (loadingOverlay) loadingOverlay.classList.replace('hidden', 'flex');
                
                document.getElementById(targetFormId).submit();
            }
        });
    </script>
@endpush