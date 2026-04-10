@extends('customer.partials.app')
@section('title', 'Riwayat Pembayaran')

@push('styles')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    
    <style>
        /* Kustomisasi DataTables menyatu dengan Tailwind */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.75rem; border: 1px solid #e2e8f0; padding: 0.35rem 0.75rem; outline: none; background-color: #f8fafc;
        }
        .dataTables_wrapper .dataTables_length select { margin-left: 0.5rem; margin-right: 0.5rem; padding-right: 2rem; }
        .dataTables_wrapper .dataTables_filter input:focus { border-color: #f97316; box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2); background-color: #ffffff; }
        .dataTables_wrapper .dataTables_filter label { display: flex; align-items: center; gap: 0.5rem; }
        .dataTables_wrapper .dataTables_paginate { display: flex; align-items: center; gap: 0.25rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem !important; border-radius: 0.75rem; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b !important; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) { background: #f8fafc; border-color: #f97316; color: #f97316 !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: linear-gradient(to right, #f43f5e, #f97316) !important; border-color: transparent; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.2); }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; background: #f1f5f9; }

        /* Animasi Modal */
        @keyframes shake-warning { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        @keyframes zoom-in-bounce { 0% { transform: scale(0.5); opacity: 0; } 70% { transform: scale(1.1); opacity: 1; } 100% { transform: scale(1); } }
        .animate-warning { animation: zoom-in-bounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), shake-warning 2s infinite ease-in-out 0.5s; }
    </style>
@endpush

@section('content')
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.dashboard') }}" class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 transition shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Riwayat Pembayaran</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Daftar semua transaksi dan tagihan undangan Anda.</p>
            </div>
        </div>
    </header>

    <section>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-2 md:p-4">
            <div class="overflow-x-auto p-4">
                <table id="table-orders" class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100 bg-slate-50/50">
                            <th class="p-6 font-bold w-16 text-center">No</th>
                            <th class="p-6 font-bold">No. Tagihan</th>
                            <th class="p-6 font-bold">Paket Undangan</th>
                            <th class="p-6 font-bold">Total Bayar</th>
                            <th class="p-6 font-bold text-center">Status</th>
                            <th class="p-6 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($orders as $index => $order)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition duration-200">
                                <td class="p-6 text-slate-500 font-medium text-center">{{ $index + 1 }}</td>
                                <td class="p-6 font-extrabold text-slate-800">
                                    {{ $order->order_number }}
                                    <div class="text-[10px] text-slate-400 font-medium mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="p-6 font-bold text-slate-700">{{ $order->package->name ?? 'Paket Kustom' }}</td>
                                <td class="p-6">
                                    <span class="font-extrabold text-rOrange">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="p-6 text-center">
                                    @if ($order->status == 'success' || $order->status == 'paid' || $order->status == 'settlement')
                                        <span class="px-3 py-1.5 bg-green-50 text-green-600 text-[10px] font-bold uppercase rounded-lg border border-green-100 tracking-wider">Lunas</span>
                                    @elseif ($order->status == 'pending')
                                        <span class="px-3 py-1.5 bg-amber-50 text-amber-600 text-[10px] font-bold uppercase rounded-lg border border-amber-100 tracking-wider animate-pulse">Menunggu</span>
                                    @else
                                        <span class="px-3 py-1.5 bg-red-50 text-red-500 text-[10px] font-bold uppercase rounded-lg border border-red-100 tracking-wider">Gagal/Expired</span>
                                    @endif
                                </td>
                                <td class="p-6 flex justify-center items-center h-full">
                                    @if ($order->status == 'pending')
                                        {{-- Tombol Lanjutkan Pembayaran jika status masih Pending --}}
                                        <button type="button" onclick="payNow(this, '{{ $order->invitation_id }}')" 
                                            class="px-5 py-2.5 bg-gradient-to-r from-rRed to-rOrange text-white rounded-xl text-xs font-bold shadow-lg shadow-rOrange/20 hover:scale-105 transition-transform flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                            Bayar Sekarang
                                        </button>
                                    @else
                                        {{-- Jika sudah lunas atau gagal, arahkan kembali ke kelola undangan --}}
                                        <a href="{{ route('customer.invitations.edit', $order->invitation_id) }}" 
                                            class="px-5 py-2.5 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Lihat Undangan
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- UNIVERSAL ALERT MODAL --}}
    <div id="universal-modal" class="fixed inset-0 z-[10000] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeUniversalModal()"></div>
        <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-all duration-300" id="universal-modal-box">
            <div class="relative w-24 h-24 mx-auto mb-6">
                <div id="um-ping" class="absolute inset-0 rounded-full animate-ping opacity-25"></div>
                <div id="um-icon-bg" class="relative w-24 h-24 rounded-full flex items-center justify-center border-4 border-white shadow-inner animate-warning">
                    <svg id="um-icon" class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                </div>
            </div>
            <h3 id="um-title" class="text-2xl font-extrabold text-slate-800 mb-3"></h3>
            <p id="um-message" class="text-slate-500 text-sm mb-8 leading-relaxed px-2 whitespace-pre-line"></p>
            <div class="flex flex-col gap-3">
                <button type="button" id="um-btn" onclick="closeUniversalModal()" class="w-full text-white px-6 py-4 rounded-2xl font-bold shadow-lg hover:-translate-y-0.5 transition-all active:scale-95">Tutup Layar</button>
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
            $('#table-orders').DataTable({
                responsive: true,
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json' },
                "dom": '<"flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-100 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center p-6 border-t border-slate-100 gap-4"ip>',
                order: [[1, 'desc']], // Urutkan berdasarkan No Tagihan (terbaru di atas)
                columnDefs: [{ orderable: false, targets: [5] }] // Matikan sorting untuk kolom Aksi
            });
        });

        // =========================================================
        // 🔥 LOGIKA FUNGSI UNIVERSAL ALERT MODAL 🔥
        // =========================================================
        let universalModalCallback = null;

        function showUniversalAlert(type, title, message, callback = null) {
            const modal = document.getElementById('universal-modal');
            const box = document.getElementById('universal-modal-box');
            const ping = document.getElementById('um-ping');
            const iconBg = document.getElementById('um-icon-bg');
            const icon = document.getElementById('um-icon');
            const titleEl = document.getElementById('um-title');
            const msgEl = document.getElementById('um-message');
            const btn = document.getElementById('um-btn');

            universalModalCallback = callback;

            ping.className = "absolute inset-0 rounded-full animate-ping opacity-25";
            iconBg.className = "relative w-24 h-24 rounded-full flex items-center justify-center border-4 border-white shadow-inner animate-warning";
            btn.className = "w-full text-white px-6 py-4 rounded-2xl font-bold shadow-lg hover:-translate-y-0.5 transition-all active:scale-95";

            if (type === 'success') {
                ping.classList.add('bg-emerald-100');
                iconBg.classList.add('bg-emerald-50', 'text-emerald-500');
                btn.classList.add('bg-gradient-to-r', 'from-emerald-500', 'to-teal-600', 'shadow-emerald-500/30');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>';
            } else if (type === 'error') {
                ping.classList.add('bg-red-100');
                iconBg.classList.add('bg-red-50', 'text-red-500');
                btn.classList.add('bg-gradient-to-r', 'from-red-500', 'to-rose-600', 'shadow-red-500/30');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>';
            } else { 
                ping.classList.add('bg-amber-100');
                iconBg.classList.add('bg-amber-50', 'text-amber-500');
                btn.classList.add('bg-gradient-to-r', 'from-amber-500', 'to-orange-600', 'shadow-amber-500/30');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>';
            }

            titleEl.innerText = title;
            msgEl.innerText = message;

            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => { box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
        }

        function closeUniversalModal() {
            const modal = document.getElementById('universal-modal');
            const box = document.getElementById('universal-modal-box');
            box.classList.remove('scale-100'); box.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');

            if (universalModalCallback) {
                setTimeout(universalModalCallback, 300);
                universalModalCallback = null;
            }
        }

        // =========================================================
        // 🔥 LOGIKA MIDTRANS PEMBAYARAN AJAX 🔥
        // =========================================================
        function payNow(btnElement, invitationId) {
            const originalText = btnElement.innerHTML;
            btnElement.innerText = 'Memproses...';
            btnElement.disabled = true;

            fetch('/customer/checkout/get-snap-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ type: 'package_premium', invitation_id: invitationId })
                })
                .then(response => response.json())
                .then(data => {
                    btnElement.innerHTML = originalText;
                    btnElement.disabled = false;

                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                if(typeof btnElement !== 'undefined') btnElement.innerText = 'Menyimpan...';

                                fetch('/customer/checkout/success', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify(result)
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if(data.success) {
                                        let metode = result.payment_type.replace(/_/g, ' ').toUpperCase();
                                        showUniversalAlert('success', 'Pembayaran Berhasil!', 'Transaksi sukses menggunakan metode:\n' + metode, () => window.location.reload());
                                    } else {
                                        showUniversalAlert('warning', 'Menunggu', 'Pembayaran sedang diproses oleh bank.');
                                    }
                                })
                                .catch(err => {
                                    showUniversalAlert('error', 'Terjadi Kesalahan', 'Gagal menyimpan status ke database lokal.');
                                });
                            },
                            onPending: function(result) {
                                showUniversalAlert('warning', 'Menunggu Pembayaran', 'Silakan selesaikan pembayaran Anda di panduan Midtrans yang diberikan.');
                            },
                            onError: function(result) {
                                showUniversalAlert('error', 'Pembayaran Gagal!', 'Proses pembayaran Anda mengalami kegagalan.');
                            },
                            onClose: function() {
                                console.log('Pop-up Midtrans ditutup');
                            }
                        });
                    } else if (data.error) {
                        showUniversalAlert('error', 'Terjadi Kesalahan', data.error);
                    } else {
                        showUniversalAlert('error', 'Gagal', 'Gagal mendapatkan token pembayaran dari server.');
                    }
                })
                .catch(error => {
                    showUniversalAlert('error', 'Koneksi Terputus', 'Gagal menghubungi server sistem pembayaran.');
                    btnElement.innerHTML = originalText;
                    btnElement.disabled = false;
                });
        }
    </script>
@endpush