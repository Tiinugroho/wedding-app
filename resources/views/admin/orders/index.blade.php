@extends('admin.partials.app')
@section('title', 'Riwayat Pembayaran')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    
    <style>
        /* Kustomisasi Form Input Filter (TomSelect & Custom Input) */
        .ts-control, .custom-input {
            padding: 0.75rem 1rem !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            font-size: 0.875rem !important;
            color: #334155 !important;
            box-shadow: none !important;
            width: 100%;
        }
        .ts-control.focus, .custom-input:focus {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
            outline: none;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important; border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important; overflow: hidden; z-index: 9999 !important; 
        }
        .ts-dropdown .option:hover, .ts-dropdown .option.active { background-color: #fff7ed !important; color: #ea580c !important; }
        
        .flatpickr-calendar {
            border-radius: 1rem !important; border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important; padding: 0.5rem !important; font-family: inherit !important;
        }
        .flatpickr-day.selected { background: #f97316 !important; border-color: #f97316 !important; }

        /* Kustomisasi DataTables menyatu dengan Tailwind */
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
        
        /* Tombol Export Excel di dalam Tabel */
        .dt-button.buttons-excel {
            background: linear-gradient(to right, #10b981, #059669) !important;
            color: white !important;
            border: none !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.75rem !important;
            font-weight: bold !important;
            font-size: 0.875rem !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2) !important;
            transition: all 0.3s ease !important;
        }
        .dt-button.buttons-excel:hover { transform: translateY(-2px) !important; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3) !important; }
    </style>
@endpush

@section('content')
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div class="flex items-center gap-4">
            <button type="button" onclick="toggleSidebar()" class="lg:hidden bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-rOrange transition group">
                <svg class="w-6 h-6 group-hover:text-rOrange transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Riwayat Pembayaran</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Pantau seluruh transaksi dan pembelian paket undangan.</p>
            </div>
        </div>
    </header>

    {{-- KOTAK FILTER --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm mb-8">
        <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-rOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            Filter Transaksi
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-2">Pilih Waktu Cepat</label>
                <select name="filter" class="custom-select">
                    <option value="">Semua Waktu</option>
                    <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-2">Atau Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="custom-input flatpickr-date" placeholder="dd/mm/yyyy">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="custom-input flatpickr-date" placeholder="dd/mm/yyyy">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full bg-slate-900 text-white px-4 py-3 rounded-xl font-bold text-sm shadow-md hover:bg-slate-800 transition">Terapkan</button>
                @if(request()->hasAny(['filter', 'start_date', 'end_date']))
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-center px-4 py-3 bg-red-50 text-red-500 rounded-xl font-bold text-sm hover:bg-red-500 hover:text-white transition" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <section>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table id="table-orders" class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                            <th class="p-6 font-bold w-16 text-center">No</th>
                            <th class="p-6 font-bold">No. Tagihan (INV)</th>
                            <th class="p-6 font-bold">Nama Klien</th>
                            <th class="p-6 font-bold">Paket & Harga</th>
                            <th class="p-6 font-bold text-center">Status</th>
                            <th class="p-6 font-bold">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($orders as $index => $order)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition duration-200">
                                <td class="p-6 text-slate-500 font-medium text-center">{{ $index + 1 }}</td>
                                <td class="p-6 font-extrabold text-slate-800">{{ $order->order_number }}</td>
                                <td class="p-6 text-slate-600 font-medium">{{ $order->user->name ?? 'User Dihapus' }}</td>
                                <td class="p-6">
                                    <span class="block font-extrabold text-slate-800">{{ $order->package->name ?? 'Paket Kustom' }}</span>
                                    <span class="text-xs font-bold text-rOrange">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="p-6 text-center">
                                    @if ($order->status == 'success' || $order->status == 'paid' || $order->status == 'settlement')
                                        <span class="px-3 py-1 bg-green-50 text-green-500 text-[10px] font-bold uppercase rounded-lg border border-green-100 tracking-wider">Berhasil</span>
                                    @elseif ($order->status == 'pending')
                                        <span class="px-3 py-1 bg-amber-50 text-amber-500 text-[10px] font-bold uppercase rounded-lg border border-amber-100 tracking-wider">Menunggu</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-50 text-red-500 text-[10px] font-bold uppercase rounded-lg border border-red-100 tracking-wider">Gagal/Expired</span>
                                    @endif
                                </td>
                                <td class="p-6 text-slate-500 font-medium">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
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
    
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables dengan tombol Export Excel
            $('#table-orders').DataTable({
                responsive: true,
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' },
                // Tambahkan 'B' di DOM untuk memunculkan Buttons
                "dom": '<"flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-100 gap-4"Bf>rt<"flex flex-col md:flex-row justify-between items-center p-6 border-t border-slate-100 gap-4"ip>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Export Excel',
                        title: 'Riwayat_Pembayaran_RuangRestu_' + new Date().toISOString().split('T')[0],
                        className: 'dt-button buttons-excel'
                    }
                ],
                order: [[5, 'desc']] // Urutkan berdasarkan kolom tanggal (index 5) paling baru
            });

            // Inisialisasi Flatpickr
            flatpickr('.flatpickr-date', {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y",
                disableMobile: true 
            });

            // Inisialisasi TomSelect
            document.querySelectorAll('.custom-select').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    sortField: { field: "text", direction: "asc" }
                });
            });
        });
    </script>
@endpush