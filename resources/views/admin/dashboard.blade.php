@extends('admin.partials.app')
@section('title', 'Dashboard Admin')

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
        
        .dataTables_wrapper .dataTables_length select { margin-left: 0.5rem; margin-right: 0.5rem; padding-right: 2rem; }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #FF8B5A;
            box-shadow: 0 0 0 2px rgba(255, 139, 90, 0.2);
            background-color: #ffffff;
        }

        .dataTables_wrapper .dataTables_filter label { display: flex; align-items: center; gap: 0.5rem; }

        /* --- STYLING TOMBOL PREV, NEXT & PAGINATION --- */
        .dataTables_wrapper .dataTables_paginate { display: flex; align-items: center; gap: 0.25rem; }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem !important; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b !important; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease-in-out;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #f8fafc; border-color: #FF8B5A; color: #FF8B5A !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: linear-gradient(to right, #FF5A5A, #FF8B5A) !important; border-color: transparent; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(255, 90, 90, 0.2); }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; background: #f1f5f9; }

        /* PERBAIKAN: Memastikan teks data kosong di tengah */
        .dataTables_empty {
            text-align: center !important;
            padding: 2.5rem !important;
            color: #94a3b8;
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <button type="button" onclick="toggleSidebar()" class="lg:hidden bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-rOrange transition group">
                <svg class="w-6 h-6 group-hover:text-rOrange transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Beranda Admin</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Halo {{ Auth::user()->name }}, selamat datang di panel kontrol!</p>
            </div>
        </div>
    </header>

    {{-- KARTU STATISTIK ADMIN --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 md:gap-8 mb-12">
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Total Klien</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalClients) }}</span>
                    <span class="text-slate-400 text-xs font-bold mb-1">Pengguna</span>
                </div>
            </div>
        </div>

        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Total Pendapatan</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Undangan Aktif</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($activeInvitations) }}</span>
                    <span class="text-orange-500 text-xs font-bold mb-1">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION PESANAN TERBARU --}}
    <section>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl md:text-2xl font-extrabold text-slate-800">Pesanan Terbaru</h3>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table id="recentOrdersTable" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                            <th class="p-6 font-bold whitespace-nowrap">Pelanggan</th>
                            <th class="p-6 font-bold whitespace-nowrap">Tema Undangan</th>
                            <th class="p-6 font-bold whitespace-nowrap">Nominal</th>
                            <th class="p-6 font-bold whitespace-nowrap">Status</th>
                            <th class="p-6 font-bold whitespace-nowrap">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        {{-- PERBAIKAN: Gunakan foreach biasa tanpa empty --}}
                        @foreach ($recentOrders as $order)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition duration-200">
                                <td class="p-6">
                                    <div class="font-bold text-slate-800">{{ $order->user->name ?? 'Pengguna Dihapus' }}</div>
                                    <div class="text-slate-400 text-xs mt-1">{{ $order->user->email ?? '-' }}</div>
                                </td>
                                <td class="p-6">
                                    <div class="font-bold text-slate-700">{{ $order->invitation->template->name ?? 'Template Dihapus' }}</div>
                                    @if (isset($order->invitation->slug))
                                        <a href="{{ url('/' . $order->invitation->slug) }}" target="_blank"
                                            class="text-xs text-blue-500 hover:text-blue-700 hover:underline mt-1 block">
                                            /{{ $order->invitation->slug }}
                                        </a>
                                    @endif
                                </td>
                                <td class="p-6 font-extrabold text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </td>
                                <td class="p-6">
                                    @if ($order->status == 'success')
                                        <span class="px-3 py-1.5 bg-green-100 text-green-600 text-[10px] font-bold uppercase rounded-lg tracking-wider">Berhasil</span>
                                    @elseif($order->status == 'pending')
                                        <span class="px-3 py-1.5 bg-amber-100 text-amber-600 text-[10px] font-bold uppercase rounded-lg tracking-wider">Menunggu</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-red-100 text-red-600 text-[10px] font-bold uppercase rounded-lg tracking-wider">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td class="p-6 text-slate-500 text-sm whitespace-nowrap">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#recentOrdersTable').DataTable({
                responsive: true,
                language: {
                    // Gunakan HTTPS agar tidak kena blokir CORS
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                    // Ubah teks kosong bawaan DataTables
                    emptyTable: "Belum ada pesanan terbaru saat ini."
                },
                "dom": '<"flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-100 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center p-6 border-t border-slate-100 gap-4"ip>',
                "pageLength": 5, 
                "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]]
            });
        });
    </script>
@endpush