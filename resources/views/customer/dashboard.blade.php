@extends('customer.partials.app')
@section('title', 'Dashboard Klien')

@push('styles')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        /* Animasi untuk Modal Pop-up */
        @keyframes shake-warning {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }
        @keyframes zoom-in-bounce {
            0% { transform: scale(0.5); opacity: 0; }
            70% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }
        .animate-warning {
            animation: zoom-in-bounce 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), shake-warning 2s infinite ease-in-out 0.5s;
        }
    </style>
@endpush

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Beranda</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Halo {{ Auth::user()->name }}, selamat datang kembali!</p>
        </div>
    </header>

    {{-- 🔥 TRIGGER POP-UP UNTUK SESSION LARAVEL 🔥 --}}
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showUniversalAlert('success', 'Berhasil!', "{{ session('success') }}");
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showUniversalAlert('error', 'Gagal!', "{{ session('error') }}");
            });
        </script>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 md:gap-8 mb-12">
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Total Pengunjung</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalViews) }}</span>
                    <span class="text-slate-400 text-xs font-bold mb-1">Orang</span>
                </div>
            </div>
        </div>
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Konfirmasi RSVP</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalRsvp) }}</span>
                    <span class="text-slate-400 text-xs font-bold mb-1">Tamu</span>
                </div>
            </div>
        </div>
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rRed/5 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Ucapan Doa</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalWishes) }}</span>
                    <span class="text-rRed text-xs font-bold mb-1">Pesan</span>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl md:text-2xl font-extrabold text-slate-800">Undangan Saya</h3>
            <a href="{{ route('customer.invitations.create') }}"
                class="group flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-rRed transition shadow-lg shadow-slate-200">
                <span>Buat Baru</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

            @foreach ($invitations as $invitation)
                @php
                    // HITUNG UMUR UNDANGAN
                    $umurHari = $invitation->created_at->diffInDays(now());
                    $isLocked = ($invitation->status != 'active' && $umurHari >= 7);
                @endphp

                <div class="bg-white p-6 rounded-[3rem] border border-slate-100 shadow-sm flex flex-col justify-between h-full min-h-[400px]">
                    <div>
                        {{-- LIVE URL THUMBNAIL MENGGUNAKAN IFRAME --}}
                        <a href="{{ url('/' . $invitation->slug) }}" target="_blank"
                            class="block relative rounded-[2rem] overflow-hidden mb-6 aspect-video bg-slate-100 group">

                            {{-- Pembungkus Iframe --}}
                            <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden bg-stone-900">
                                <iframe src="{{ url('/' . $invitation->slug) }}?thumbnail=1"
                                    class="absolute top-0 left-0 w-[400%] h-[400%] origin-top-left scale-[0.25] border-0"
                                    scrolling="no" tabindex="-1">
                                </iframe>
                            </div>

                            {{-- Overlay Transparan saat di-hover --}}
                            <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition flex items-center justify-center z-10">
                                <span class="bg-white/90 backdrop-blur-sm text-slate-800 text-[10px] font-bold px-4 py-2 rounded-full shadow-lg uppercase tracking-widest translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    Buka Undangan
                                </span>
                            </div>

                            <div class="absolute top-4 left-4 z-10">
                                @if ($invitation->status != 'active')
                                    @if ($isLocked)
                                        <span class="px-4 py-2 mx-2 bg-red-100 text-red-600 text-xs font-bold uppercase rounded-full shadow-sm">
                                            Terkunci (Expired)
                                        </span>
                                    @else
                                        <span class="px-4 py-2 mx-2 bg-amber-100 text-amber-600 text-xs font-bold uppercase rounded-full shadow-sm">
                                            Draft / Belum Lunas
                                        </span>
                                    @endif
                                    <span class="px-4 py-2 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold rounded-full shadow-sm">
                                        {{ $invitation->template->name }}
                                    </span>
                                @else
                                    <span class="px-4 py-2 mx-2 bg-green-100 text-green-600 text-xs font-bold uppercase rounded-full shadow-sm">
                                        Lunas
                                    </span>
                                    <span class="px-4 py-2 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold rounded-full shadow-sm">
                                        {{ $invitation->template->name }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <h4 class="text-xl font-bold text-slate-800 mb-1">ruangrestu.com/{{ $invitation->slug }}</h4>
                        <p class="text-slate-400 text-sm mb-4 italic">Dibuat pada {{ $invitation->created_at->format('d M Y') }}</p>

                        <div class="flex items-center gap-2 mb-6">
                            @if ($invitation->status != 'active')
                                <button type="button" onclick="payNow(this, '{{ $invitation->id }}')"
                                    class="w-full flex items-center justify-center py-3 bg-gradient-to-r from-rRed to-rOrange text-white rounded-2xl font-bold text-sm hover:scale-[1.02] transition shadow-lg shadow-rOrange/30">
                                    Aktifkan & Bayar
                                </button>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-600 text-[10px] font-bold uppercase rounded-lg">Lunas</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        @if($isLocked)
                            {{-- TOMBOL TERKUNCI MENGGUNAKAN UNIVERSAL MODAL --}}
                            <button type="button" onclick="showUniversalAlert('warning', 'Terkunci', 'Waktu uji coba (Trial) 7 hari telah habis.\n\nSilakan klik Aktifkan & Bayar untuk membuka kunci dan mengedit data kembali.')"
                                class="flex items-center justify-center py-3 bg-slate-200 text-slate-400 rounded-2xl font-bold text-sm cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Terkunci
                            </button>
                        @else
                            {{-- TOMBOL NORMAL JIKA MASIH TRIAL / SUDAH LUNAS --}}
                            <a href="{{ route('customer.invitations.edit', $invitation->id) }}"
                                class="flex items-center justify-center py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                                Kelola Data
                            </a>
                        @endif

                        <a href="{{ url('/' . $invitation->slug) }}" target="_blank"
                            class="flex items-center justify-center py-3 bg-rRed text-white rounded-2xl font-bold text-sm hover:bg-rRed/90 transition shadow-lg shadow-rRed/20">
                            Live Preview
                        </a>
                        <a href="{{ route('customer.blast.index', $invitation->id) }}"
                            class="flex items-center justify-center py-3 bg-rOrange text-white rounded-2xl font-bold text-sm hover:bg-rOrange/90 transition shadow-lg shadow-rOrange/20">
                            Buka Halaman WA Blast
                        </a>
                    </div>
                </div>
            @endforeach

            <a href="{{ route('customer.invitations.create') }}"
                class="group border-2 border-dashed border-slate-200 rounded-[3rem] flex flex-col items-center justify-center p-12 text-center hover:border-rRed hover:bg-rRed/5 transition-all h-full min-h-[400px]">
                <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-6 group-hover:bg-rRed group-hover:text-white transition-all">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h5 class="text-xl font-bold text-slate-800 mb-2">Buat Undangan Baru</h5>
                <p class="text-slate-400 text-sm">Pilih tema dan mulai buat undangan digital impian Anda.</p>
            </a>

        </div>
    </section>

    {{-- ========================================================= --}}
    {{-- 🔥 UNIVERSAL ALERT MODAL (UNTUK SUCCESS, ERROR, WARNING) 🔥 --}}
    {{-- ========================================================= --}}
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
    <script>
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

            // Bersihkan sisa class sebelumnya
            ping.className = "absolute inset-0 rounded-full animate-ping opacity-25";
            iconBg.className = "relative w-24 h-24 rounded-full flex items-center justify-center border-4 border-white shadow-inner animate-warning";
            btn.className = "w-full text-white px-6 py-4 rounded-2xl font-bold shadow-lg hover:-translate-y-0.5 transition-all active:scale-95";

            // Atur Warna & Ikon berdasarkan 'type'
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
            } else { // warning
                ping.classList.add('bg-amber-100');
                iconBg.classList.add('bg-amber-50', 'text-amber-500');
                btn.classList.add('bg-gradient-to-r', 'from-amber-500', 'to-orange-600', 'shadow-amber-500/30');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>';
            }

            titleEl.innerText = title;
            msgEl.innerText = message;

            // Munculkan Modal
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => { box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
        }

        function closeUniversalModal() {
            const modal = document.getElementById('universal-modal');
            const box = document.getElementById('universal-modal-box');
            box.classList.remove('scale-100'); box.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');

            // Jalankan callback (misal: refresh halaman) jika ada, setelah animasi tutup selesai
            if (universalModalCallback) {
                setTimeout(universalModalCallback, 300);
                universalModalCallback = null;
            }
        }

        // =========================================================
        // 🔥 LOGIKA LIVE PREVIEW URL (Dari form ke tab baru) 🔥
        // =========================================================
        document.addEventListener('DOMContentLoaded', function() {
            const previewBtn = document.getElementById('btn-live-preview');
            const mainForm = document.querySelector('form[action*="invitations"]'); 

            if (previewBtn && mainForm) {
                previewBtn.addEventListener('click', function(e) {
                    e.preventDefault(); 

                    const formData = new FormData(mainForm);
                    const params = new URLSearchParams();

                    for (const [key, value] of formData.entries()) {
                        if (typeof value === 'string' && value.trim() !== '') {
                            params.append(key, value);
                        }
                    }

                    const baseUrl = this.getAttribute('href').split('?')[0];
                    const previewUrl = baseUrl + '?' + params.toString();

                    window.open(previewUrl, '_blank');
                });
            }
        });

        // =========================================================
        // 🔥 LOGIKA MIDTRANS PEMBAYARAN AJAX 🔥
        // =========================================================
        function payNow(btnElement, invitationId) {
            const originalText = btnElement.innerText;
            btnElement.innerText = 'Memproses...';
            btnElement.disabled = true;

            // Memanggil endpoint baru yang sudah kita buat sebelumnya di CheckoutController
            fetch('/customer/checkout/get-snap-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    // Karena ini aktivasi dari dashboard, kita kirimkan 'package_premium'
                    body: JSON.stringify({ type: 'package_premium', invitation_id: invitationId })
                })
                .then(response => response.json())
                .then(data => {
                    btnElement.innerText = originalText;
                    btnElement.disabled = false;

                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                            // Ubah teks tombol jadi Loading
                            if(typeof btnElement !== 'undefined') btnElement.innerText = 'Menyimpan...';

                            // Tembak data sukses ke server lokal (beserta jenis pembayarannya)
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
                                    // Rapikan nama metode (contoh: "bank_transfer" jadi "BANK TRANSFER")
                                    let metode = result.payment_type.replace(/_/g, ' ').toUpperCase();
                                    
                                    showUniversalAlert(
                                        'success', 
                                        'Pembayaran Berhasil!', 
                                        'Transaksi sukses menggunakan metode:\n' + metode, 
                                        () => window.location.reload()
                                    );
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
                                console.log('Pop-up Midtrans ditutup sebelum dibayar');
                            }
                        });
                    } else if (data.error) {
                        showUniversalAlert('error', 'Terjadi Kesalahan', data.error);
                    } else {
                        showUniversalAlert('error', 'Gagal', 'Gagal mendapatkan token pembayaran dari server.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showUniversalAlert('error', 'Koneksi Terputus', 'Gagal menghubungi server sistem pembayaran.');
                    btnElement.innerText = originalText;
                    btnElement.disabled = false;
                });
        }
    </script>
@endpush