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

    {{-- 🔥 KARTU STATISTIK GLOBAL (Hanya Total Undangan) 🔥 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8 mb-12">
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Total Undangan</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalInvitations) }}</span>
                    <span class="text-blue-500 text-xs font-bold mb-1">Acara</span>
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
                    $isLocked = false;
                    $isExpired = false;
                    $sisaHari = 0;

                    if ($invitation->status != 'active') {
                        // 1. LOGIKA UNTUK BELUM LUNAS (Masa Uji Coba 7 Hari)
                        $batasWaktuTrial = $invitation->created_at->copy()->addDays(7);
                        if (now()->greaterThanOrEqualTo($batasWaktuTrial)) {
                            $isLocked = true;
                        } else {
                            $sisaHari = now()->diffInDays($batasWaktuTrial);
                            // Jika kurang dari 24 jam, set minimal 1 hari
                            $sisaHari = $sisaHari == 0 ? 1 : $sisaHari; 
                        }
                    } else {
                        // 2. LOGIKA UNTUK SUDAH LUNAS (Masa Aktif Premium)
                        if ($invitation->expires_at) {
                            $batasWaktuAktif = \Carbon\Carbon::parse($invitation->expires_at);
                            if (now()->greaterThanOrEqualTo($batasWaktuAktif)) {
                                $isExpired = true;
                            } else {
                                $sisaHari = now()->diffInDays($batasWaktuAktif);
                                // Jika kurang dari 24 jam, set minimal 1 hari
                                $sisaHari = $sisaHari == 0 ? 1 : $sisaHari;
                            }
                        }
                    }
                @endphp

                <div class="bg-white p-6 rounded-[3rem] border border-slate-100 shadow-sm flex flex-col justify-between h-full min-h-[400px]">
                    <div>
                        <a href="{{ url('/' . $invitation->slug) }}" target="_blank"
                            class="block relative rounded-[2rem] overflow-hidden mb-6 aspect-video bg-slate-100 group">

                            <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden bg-stone-900">
                                <iframe src="{{ url('/' . $invitation->slug) }}?thumbnail=1"
                                    class="absolute top-0 left-0 w-[400%] h-[400%] origin-top-left scale-[0.25] border-0"
                                    scrolling="no" tabindex="-1">
                                </iframe>
                            </div>

                            <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition flex items-center justify-center z-10">
                                <span class="bg-white/90 backdrop-blur-sm text-slate-800 text-[10px] font-bold px-4 py-2 rounded-full shadow-lg uppercase tracking-widest translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    Buka Undangan
                                </span>
                            </div>

                            <div class="absolute top-4 left-4 z-10 flex flex-col items-start gap-2">
                                <div class="flex items-center">
                                    @if ($invitation->status != 'active')
                                        @if ($isLocked)
                                            <span class="px-4 py-2 mr-2 bg-red-100 text-red-600 text-xs font-bold uppercase rounded-full shadow-sm">Terkunci (Expired)</span>
                                        @else
                                            <span class="px-4 py-2 mr-2 bg-amber-100 text-amber-600 text-xs font-bold uppercase rounded-full shadow-sm">Draft / Belum Lunas</span>
                                        @endif
                                    @else
                                        <span class="px-4 py-2 mr-2 bg-green-100 text-green-600 text-xs font-bold uppercase rounded-full shadow-sm">Lunas</span>
                                    @endif
                                    <span class="px-4 py-2 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold rounded-full shadow-sm">
                                        {{ $invitation->template->name }}
                                    </span>
                                </div>
                                
                                {{-- 🔥 LABEL SISA WAKTU MUNCUL DI SINI 🔥 --}}
                                @if ($invitation->status != 'active' && !$isLocked)
                                    <span class="px-3 py-1.5 bg-amber-500/90 backdrop-blur text-white text-[10px] font-bold rounded-full shadow-sm flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Sisa Trial: {{ $sisaHari }} Hari
                                    </span>
                                @elseif ($invitation->status == 'active')
                                    @if ($isExpired)
                                        <span class="px-3 py-1.5 bg-red-600/90 backdrop-blur text-white text-[10px] font-bold rounded-full shadow-sm flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Masa Aktif Habis
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 bg-slate-900/80 backdrop-blur text-white text-[10px] font-bold rounded-full shadow-sm flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Sisa Aktif: {{ $sisaHari }} Hari
                                        </span>
                                    @endif
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
                            @endif
                        </div>
                    </div>

                    <div class="mt-auto">
                        @if($isLocked)
                            <button type="button" onclick="showUniversalAlert('warning', 'Terkunci', 'Waktu uji coba (Trial) 7 hari telah habis.\n\nSilakan klik Aktifkan & Bayar untuk membuka kunci dan mengedit data kembali.')"
                                class="w-full flex items-center justify-center py-3 bg-slate-200 text-slate-400 rounded-2xl font-bold text-sm cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Data Terkunci
                            </button>
                        @elseif($isExpired && $invitation->status == 'active')
                            <button type="button" onclick="showUniversalAlert('warning', 'Masa Aktif Habis', 'Masa aktif undangan ini telah berakhir. Hubungi Admin untuk perpanjangan masa aktif.')"
                                class="w-full flex items-center justify-center py-3 bg-slate-200 text-slate-400 rounded-2xl font-bold text-sm cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Kadaluarsa
                            </button>
                        @else
                            <button type="button" 
                                onclick="openActionModal('{{ route('customer.invitations.edit', $invitation->id) }}', '{{ url('/' . $invitation->slug) }}', '{{ route('customer.blast.index', $invitation->id) }}', '{{ route('customer.invitations.scanner', $invitation->id) }}')"
                                class="w-full flex items-center justify-center py-3 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition shadow-lg">
                                Kelola Data
                            </button>
                        @endif
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
    {{-- 🔥 MODAL AKSI (KELOLA DATA) 🔥 --}}
    {{-- ========================================================= --}}
    <div id="action-modal" class="fixed inset-0 z-[10000] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeActionModal()"></div>
        <div class="relative bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl border border-slate-100 max-w-md w-full mx-4 transform scale-95 transition-all duration-300" id="action-modal-box">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-extrabold text-slate-800">Pilih Aksi</h3>
                <button onclick="closeActionModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <a href="#" id="btn-kelola-konten" class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-slate-300 hover:bg-slate-100 transition group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Kelola Konten</span>
                </a>
                
                <a href="#" id="btn-live-preview-modal" target="_blank" class="flex flex-col items-center justify-center p-4 bg-red-50 rounded-2xl border border-red-100 hover:border-red-300 hover:bg-red-100 transition group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-red-600">Live Preview</span>
                </a>

                <a href="#" id="btn-wa-blast" class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-2xl border border-green-100 hover:border-green-300 hover:bg-green-100 transition group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-green-600">WA Blast</span>
                </a>

                <a href="#" id="btn-scanner" class="flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-2xl border border-indigo-100 hover:border-indigo-300 hover:bg-indigo-100 transition group">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-indigo-600">Scanner</span>
                </a>
            </div>
        </div>
    </div>

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
        // ==========================================
        // SCRIPT KELOLA DATA MODAL
        // ==========================================
        function openActionModal(editUrl, previewUrl, blastUrl, scannerUrl) {
            document.getElementById('btn-kelola-konten').href = editUrl;
            document.getElementById('btn-live-preview-modal').href = previewUrl;
            document.getElementById('btn-wa-blast').href = blastUrl;
            document.getElementById('btn-scanner').href = scannerUrl;

            const modal = document.getElementById('action-modal');
            const box = document.getElementById('action-modal-box');
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => { box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
        }

        function closeActionModal() {
            const modal = document.getElementById('action-modal');
            const box = document.getElementById('action-modal-box');
            
            box.classList.remove('scale-100'); box.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        // ==========================================
        // SCRIPT UNIVERSAL ALERT MODAL
        // ==========================================
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

        // ==========================================
        // MIDTRANS PAYMENT LOGIC
        // ==========================================
        function payNow(btnElement, invitationId) {
            const originalText = btnElement.innerText;
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
                    btnElement.innerText = originalText;
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