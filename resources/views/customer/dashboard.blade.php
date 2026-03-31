@extends('customer.partials.app')
@section('title', 'Dashboard Klien')

@push('styles')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Beranda</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Halo {{ Auth::user()->name }}, selamat datang kembali!</p>
        </div>
    </header>

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
                                <button type="button" onclick="payNow('{{ $invitation->id }}')"
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
                            {{-- TOMBOL TERKUNCI JIKA LEWAT 7 HARI --}}
                            <button type="button" onclick="alert('Waktu uji coba (Trial) 7 hari telah habis. Silakan Aktifkan & Bayar untuk membuka kunci dan mengedit data kembali.')"
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
@endsection

@push('scripts')
    <script>
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
    </script>

    <script>
        function payNow(invitationId) {
            fetch(`/customer/checkout/${invitationId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                alert("Pembayaran Berhasil! Undangan Anda telah aktif.");
                                window.location.reload();
                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran Anda.");
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal!");
                            },
                            onClose: function() {
                                alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                            }
                        });
                    } else {
                        alert('Gagal mendapatkan token pembayaran');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem.');
                });
        }
    </script>
@endpush