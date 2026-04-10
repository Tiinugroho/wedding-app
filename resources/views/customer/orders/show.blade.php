@extends('customer.partials.app')
@section('title', 'Detail Tagihan - ' . $order->order_number)

@push('styles')
    @if($order->status == 'pending')
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
    <style>
        /* Sembunyikan elemen yang tidak perlu saat di-print */
        @media print {
            body { background-color: white !important; }
            #sidebar-backdrop, #admin-sidebar, header, .no-print { display: none !important; }
            .print-area { box-shadow: none !important; border: 1px solid #e2e8f0 !important; border-radius: 0 !important; width: 100% !important; margin: 0 !important; padding: 2rem !important; }
            .bg-slate-50 { background-color: transparent !important; }
        }
    </style>
@endpush

@section('content')
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 no-print">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.orders.index') }}" class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 hover:bg-slate-50 transition shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Detail Tagihan</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Invoice untuk transaksi {{ $order->order_number }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Invoice
            </button>
            @if($order->status == 'pending')
                <button type="button" onclick="payNow(this, '{{ $order->invitation_id }}')" class="px-5 py-2.5 bg-gradient-to-r from-rRed to-rOrange text-white rounded-xl text-sm font-bold shadow-lg shadow-rOrange/20 hover:scale-105 transition-transform flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Bayar Sekarang
                </button>
            @endif
        </div>
    </header>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden relative print-area">
            
            {{-- Watermark Background --}}
            <div class="absolute inset-0 opacity-[0.02] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/floral-flourish.png')]"></div>
            
            {{-- Header Invoice --}}
            <div class="p-8 md:p-12 border-b border-dashed border-slate-200 bg-slate-50/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange mb-1">
                            RuangRestu
                        </h1>
                        <p class="text-slate-400 text-xs font-bold tracking-widest uppercase">Platform Undangan Digital</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h2 class="text-2xl font-black text-slate-800 mb-1">INVOICE</h2>
                        <p class="text-slate-500 font-medium">#{{ $order->order_number }}</p>
                    </div>
                </div>
            </div>

            {{-- Info Klien & Tanggal --}}
            <div class="p-8 md:p-12 border-b border-dashed border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-2">DITAGIHKAN KEPADA:</p>
                        <h4 class="text-lg font-bold text-slate-800">{{ Auth::user()->name }}</h4>
                        <p class="text-slate-500 text-sm mt-1">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Tanggal Invoice</p>
                            <p class="text-slate-800 font-bold">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-slate-500 text-xs">{{ $order->created_at->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Status Pembayaran</p>
                            @if ($order->status == 'success' || $order->status == 'paid' || $order->status == 'settlement')
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-600 text-xs font-bold uppercase rounded-lg border border-green-200 tracking-wider">Lunas</span>
                            @elseif ($order->status == 'pending')
                                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-600 text-xs font-bold uppercase rounded-lg border border-amber-200 tracking-wider">Menunggu</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-500 text-xs font-bold uppercase rounded-lg border border-red-200 tracking-wider">Batal/Gagal</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Item Tagihan --}}
            <div class="p-8 md:p-12">
                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[10px] uppercase tracking-widest">
                                <th class="p-4 font-bold">Deskripsi Layanan</th>
                                <th class="p-4 font-bold text-center">Harga Satuan</th>
                                <th class="p-4 font-bold text-center">Diskon</th>
                                <th class="p-4 font-bold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-slate-50">
                                <td class="p-4">
                                    <p class="font-extrabold text-slate-800 text-base mb-1">Aktivasi Paket {{ $order->package->name ?? 'Kustom' }}</p>
                                    <p class="text-slate-500 text-xs line-clamp-2">{{ $order->package->description ?? 'Layanan pembuatan undangan digital premium.' }}</p>
                                    <p class="text-slate-400 text-[10px] mt-2">Untuk Link: <a href="{{ url('/' . $order->invitation->slug) }}" class="text-blue-500 underline" target="_blank">ruangrestu.com/{{ $order->invitation->slug }}</a></p>
                                </td>
                                <td class="p-4 text-center font-bold text-slate-700">
                                    @if($order->package && $order->package->original_price)
                                        Rp {{ number_format($order->package->original_price, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($order->amount, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="p-4 text-center font-bold text-green-500">
                                    @if($order->package && $order->package->original_price && $order->package->original_price > $order->amount)
                                        - Rp {{ number_format($order->package->original_price - $order->amount, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-4 text-right font-black text-slate-800 text-lg">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Total --}}
                <div class="flex justify-end mt-8">
                    <div class="w-full md:w-1/2 lg:w-1/3 space-y-3">
                        <div class="flex justify-between items-center text-slate-500 text-sm">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-700">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500 text-sm">
                            <span>Biaya Admin / Pajak</span>
                            <span class="font-bold text-slate-700">Rp 0</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                            <span class="font-extrabold text-slate-800">Total Pembayaran</span>
                            <span class="text-2xl font-black text-rOrange">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Pesan --}}
            <div class="p-6 bg-slate-50/80 border-t border-slate-100 text-center">
                <p class="text-slate-500 text-sm">Terima kasih telah mempercayakan momen bahagia Anda kepada <span class="font-bold text-slate-700">RuangRestu</span>.</p>
                <p class="text-slate-400 text-xs mt-1">Jika ada pertanyaan mengenai tagihan ini, silakan hubungi tim dukungan kami.</p>
            </div>
        </div>
    </div>

    {{-- UNIVERSAL ALERT MODAL --}}
    <div id="universal-modal" class="fixed inset-0 z-[10000] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 no-print">
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
    <script>
        // Logika Universal Alert
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