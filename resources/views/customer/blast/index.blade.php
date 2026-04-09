@extends('customer.partials.app')
@section('title', 'WhatsApp Blast')

@push('styles')
    <style>
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

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endpush

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">WhatsApp Blast</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Kelola kontak tamu dan kirim undangan secara massal.</p>
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

    {{-- 🔥 NOTIFIKASI ERROR VALIDASI FORM (TETAP INLINE AGAR TERBACA JELAS) 🔥 --}}
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showUniversalAlert('error', 'Validasi Gagal', 'Terdapat kesalahan pada isian form. Silakan periksa kembali.');
            });
        </script>
        <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-[2rem] mb-8 font-bold flex flex-col gap-2 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Terdapat kesalahan pengisian:
            </div>
            <ul class="list-disc list-inside text-sm font-medium ml-9">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!$invitation)
        <div class="bg-white p-12 rounded-[3rem] border border-slate-100 shadow-sm text-center flex flex-col items-center justify-center min-h-[50vh]">
            <div class="w-24 h-24 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 mb-3">Belum Ada Undangan</h3>
            <p class="text-slate-500 max-w-md mx-auto mb-8">Anda harus membuat undangan digital terlebih dahulu sebelum bisa menggunakan fitur import kontak dan WhatsApp Blast.</p>
            <a href="{{ route('customer.invitations.create') }}" class="inline-flex items-center gap-3 bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-4 rounded-full font-bold shadow-lg shadow-rOrange/20 hover:scale-105 transition">
                Buat Undangan Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3"></path></svg>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM KIRI: STATUS, IMPORT & MANUAL --}}
            <div class="xl:col-span-4 space-y-8">

                {{-- CARD KONEKSI WA --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-slate-50 rounded-bl-[4rem]"></div>
                    <h3 class="text-xl font-bold text-slate-800 mb-6 relative z-10">Status Koneksi</h3>
                    <div id="wa-user" class="text-xs text-slate-500 mt-2 hidden mb-4"></div>

                    <div id="qr-container" class="w-48 h-48 mx-auto bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200 flex items-center justify-center mb-6 overflow-hidden relative">
                        <span id="qr-loading" class="text-xs font-bold text-slate-400 px-4">Klik tombol di bawah untuk memunculkan QR</span>
                        <img id="qr-image" src="" class="hidden w-full h-full object-contain p-4 absolute inset-0">
                        
                        {{-- 🔥 GAMBAR WA SAAT TERHUBUNG (RAPI) 🔥 --}}
                        <div id="wa-connected-icon" class="hidden w-full h-full flex-col items-center justify-center bg-white">
                            <svg class="w-16 h-16 text-[#25D366] mb-3 drop-shadow-md" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.12.553 4.142 1.543 5.922L.106 24l6.23-1.637c1.724.908 3.655 1.386 5.695 1.386 6.646 0 12.031-5.385 12.031-12.031S18.677 0 12.031 0zm.014 21.688c-1.782 0-3.52-.479-5.045-1.386l-.36-.214-3.75.986.998-3.657-.235-.374a10.024 10.024 0 01-1.54-5.362c0-5.524 4.496-10.02 10.02-10.02 5.524 0 10.02 4.496 10.02 10.02 0 5.524-4.496 10.02-10.02 10.02zm5.495-7.464c-.302-.15-1.783-.88-2.06-.98-.276-.1-.478-.15-.68.15-.202.3-.78 1-.956 1.2-.176.2-.352.226-.654.076-.302-.15-1.273-.468-2.424-1.492-.895-.797-1.5-1.78-1.676-2.08-.176-.3-.018-.462.133-.612.136-.135.302-.35.453-.526.15-.175.202-.3.302-.5.1-.2.05-.376-.026-.526-.076-.15-.68-1.64-93-2.247-.243-.593-.49-.512-.68-.522-.176-.01-.376-.01-.578-.01-.202 0-.528.075-.805.375-.276.3-1.054 1.03-1.054 2.513 0 1.483 1.08 2.915 1.23 3.115.15.2 2.127 3.242 5.15 4.542.72.31 1.28.495 1.717.634.72.23 1.376.197 1.895.12.58-.086 1.783-.73 2.034-1.435.252-.705.252-1.31.176-1.436-.076-.126-.276-.2-.578-.35z"></path>
                            </svg>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Terkoneksi</span>
                        </div>
                    </div>

                    <div id="wa-status" class="inline-block px-6 py-2 bg-slate-100 text-slate-500 rounded-full font-extrabold text-[10px] uppercase tracking-widest mb-6">Terputus</div>

                    <button id="btn-start-wa" onclick="startWaSession()" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-rRed transition shadow-lg shadow-slate-200">
                        Sambungkan WhatsApp
                    </button>

                    <button id="btn-logout-wa" onclick="openLogoutModal()" class="w-full py-3 bg-red-50 text-red-500 rounded-xl font-bold mt-4 hover:bg-red-500 hover:text-white transition hidden">
                        Putuskan Koneksi WA
                    </button>
                </div>

                {{-- CARD IMPORT EXCEL --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-800 mb-2 text-center">Import Tamu Excel</h3>
                    <p class="text-slate-400 text-xs text-center mb-6">Unggah daftar tamu sekaligus via Excel</p>
                    <div class="space-y-4">
                        <a href="{{ route('customer.blast.template') }}" class="flex items-center justify-center gap-2 w-full py-3 border border-slate-200 text-slate-600 rounded-xl font-bold text-xs hover:bg-slate-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Template
                        </a>
                        <form action="{{ route('customer.blast.import', $invitation->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file_excel" accept=".xlsx" required class="w-full text-xs text-slate-500 mb-4 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-200">
                                Upload & Import
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CARD TAMBAH MANUAL --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-800 mb-2 text-center">Tambah Manual</h3>
                    <p class="text-slate-400 text-xs text-center mb-6">Ketik nama dan nomor WA tamu</p>
                    <form action="{{ route('customer.blast.manual', $invitation->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="name" required placeholder="Nama Tamu (Cth: Budi)" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition">
                        </div>
                        <div class="mb-4">
                            <input type="text" name="phone_number" placeholder="Nomor WA (Opsional)" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition">
                        </div>
                        <button type="submit" class="w-full py-4 bg-slate-800 text-white rounded-2xl font-bold shadow-lg shadow-slate-200 hover:bg-slate-900 transition">
                            Simpan Tamu
                        </button>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM BLAST --}}
            <div class="xl:col-span-8">
                <div class="bg-white p-8 md:p-10 rounded-[3rem] border border-slate-100 shadow-sm">

                    {{-- 🔥 INFO KUOTA GRATIS ATAU TOMBOL TOP UP JIKA LUNAS 🔥 --}}
                    @if ($invitation->status != 'active')
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 text-orange-700 p-6 rounded-[2rem] mb-8 shadow-sm">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                                <div>
                                    <div class="flex items-center gap-3 font-extrabold text-lg mb-1">
                                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Mode Trial (Versi Gratis)
                                    </div>
                                    <p class="text-sm font-medium text-orange-600/80">Anda hanya dapat melakukan Blast maksimal 30 Tamu, dan web undangan belum bisa diakses publik.</p>
                                    <div class="mt-3">
                                        <span class="bg-white/80 border border-orange-200 px-4 py-2 rounded-full text-xs font-extrabold text-orange-600 shadow-sm inline-block">
                                            Sisa Kuota Blast: <span class="text-lg">{{ max(0, 30 - $sudahDikirim) }}</span> Tamu
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full md:w-auto shrink-0">
                                    <button type="button" onclick="openUpgradeModal()" class="block w-full text-center bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white px-8 py-4 rounded-2xl font-extrabold shadow-xl shadow-orange-500/30 transition-all hover:scale-105">
                                        Lihat Pilihan Layanan
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- STATUS ACTIVE (Sudah Lunas) --}}
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-6 rounded-[2rem] mb-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 font-extrabold text-lg mb-1">
                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Paket Undangan Aktif
                                </div>
                                <p class="text-sm text-emerald-600/80 font-medium">Link undangan Anda dapat diakses publik dengan aman.</p>
                            </div>
                            <div class="w-full md:w-auto shrink-0">
                                <button type="button" onclick="openUpgradeModal()" class="block w-full text-center bg-white border border-emerald-200 text-emerald-600 hover:bg-emerald-600 hover:text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-sm">
                                    + Top Up Kuota Blast
                                </button>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('customer.blast.send', $invitation->id) }}" method="POST">
                        @csrf
                        <div class="mb-8">
                            <label class="block text-sm font-extrabold text-slate-700 mb-3">Template Pesan</label>
                            <div class="mb-4">
                                <select id="template-selector" onchange="changeTemplate()" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 transition font-medium cursor-pointer text-sm">
                                    <option value="formal" selected>Template 1 (Formal & Sopan)</option>
                                    <option value="casual">Template 2 (Santai & Akrab)</option>
                                    <option value="islami">Template 3 (Nuansa Islami)</option>
                                    <option value="">-- Ketik Manual Sendiri --</option>
                                </select>
                            </div>

                            <textarea id="message-area" name="message" rows="6" required class="w-full p-6 bg-slate-50 border border-slate-200 rounded-[2rem] focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 transition text-sm" placeholder="Halo {nama}, kami mengundang Anda..."></textarea>
                            <div class="flex gap-4 mt-3">
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-lg">Gunakan Tag: {nama}</span>
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-lg">Gunakan Tag: {link}</span>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                                <h4 class="text-sm font-extrabold text-slate-700">Pilih Tamu ({{ count($guests) }})</h4>
                                @if (count($guests) > 0)
                                    <label class="flex items-center gap-2 cursor-pointer text-sm font-bold text-rOrange hover:text-rRed transition bg-orange-50 px-4 py-2 rounded-xl">
                                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                                        Pilih Semua Tamu
                                    </label>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                @forelse ($guests as $guest)
                                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 bg-slate-50/50 hover:bg-white hover:border-rOrange/20 transition group">
                                        <label class="flex items-center gap-4 cursor-pointer flex-1">
                                            <input type="checkbox" name="guest_ids[]" value="{{ $guest->id }}" class="w-5 h-5 text-rOrange rounded-lg border-slate-300 focus:ring-rOrange guest-checkbox" onchange="checkQuota(this)" {{ $guest->phone_number ? '' : 'disabled' }}>
                                            <div class="flex-1">
                                                <span class="block font-bold text-slate-800">{{ $guest->name }}</span>
                                                <span class="text-xs text-slate-400">{{ $guest->phone_number ?? 'Nomor WhatsApp tidak tersedia' }}</span>
                                            </div>
                                            @if ($guest->is_blasted)
                                                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full mr-2">Pernah Terkirim</span>
                                            @endif
                                        </label>

                                        <button type="button" onclick="openCustomModal('delete', 'delete-form-{{ $guest->id }}', 'Apakah Anda yakin ingin menghapus tamu {{ $guest->name }}?')" class="p-2.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition" title="Hapus Tamu">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-400 text-sm font-medium border-2 border-dashed border-slate-200 rounded-2xl">
                                        Belum ada tamu. Silakan import Excel atau ketik manual.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <button type="submit" class="w-full py-5 bg-gradient-to-r from-rRed to-rOrange text-white rounded-[2rem] font-extrabold text-lg shadow-xl shadow-rOrange/20 hover:scale-[1.01] transition flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Kirim Blast Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 🔥 FORM HAPUS TAMU (DISEMBUNYIKAN) 🔥 --}}
        @foreach ($guests as $guest)
            <form id="delete-form-{{ $guest->id }}" action="{{ route('customer.blast.deleteGuest', $guest->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        {{-- 🔥 FLOATING WA LOGO LAYOUT (ANTI BERANTAKAN) 🔥 --}}
        <a href="https://wa.me/6281234567890" target="_blank" class="fixed bottom-6 right-6 z-[9998] bg-[#25D366] text-white p-3 md:p-4 rounded-full shadow-lg hover:scale-110 transition-transform flex items-center justify-center group" title="Bantuan WhatsApp">
            <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.12.553 4.142 1.543 5.922L.106 24l6.23-1.637c1.724.908 3.655 1.386 5.695 1.386 6.646 0 12.031-5.385 12.031-12.031S18.677 0 12.031 0zm.014 21.688c-1.782 0-3.52-.479-5.045-1.386l-.36-.214-3.75.986.998-3.657-.235-.374a10.024 10.024 0 01-1.54-5.362c0-5.524 4.496-10.02 10.02-10.02 5.524 0 10.02 4.496 10.02 10.02 0 5.524-4.496 10.02-10.02 10.02zm5.495-7.464c-.302-.15-1.783-.88-2.06-.98-.276-.1-.478-.15-.68.15-.202.3-.78 1-.956 1.2-.176.2-.352.226-.654.076-.302-.15-1.273-.468-2.424-1.492-.895-.797-1.5-1.78-1.676-2.08-.176-.3-.018-.462.133-.612.136-.135.302-.35.453-.526.15-.175.202-.3.302-.5.1-.2.05-.376-.026-.526-.076-.15-.68-1.64-93-2.247-.243-.593-.49-.512-.68-.522-.176-.01-.376-.01-.578-.01-.202 0-.528.075-.805.375-.276.3-1.054 1.03-1.054 2.513 0 1.483 1.08 2.915 1.23 3.115.15.2 2.127 3.242 5.15 4.542.72.31 1.28.495 1.717.634.72.23 1.376.197 1.895.12.58-.086 1.783-.73 2.034-1.435.252-.705.252-1.31.176-1.436-.076-.126-.276-.2-.578-.35z"></path>
            </svg>
            <span class="absolute right-full mr-4 bg-white text-slate-700 text-xs font-bold px-3 py-2 rounded-xl shadow-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                Butuh Bantuan?
            </span>
        </a>

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

        {{-- 🔥 CUSTOM MODAL POP-UP (HAPUS TAMU) - Z-INDEX 9999 🔥 --}}
        <div id="custom-modal" class="fixed inset-0 z-[9999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeCustomModal()"></div>
            <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-all duration-300" id="custom-modal-box">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-25"></div>
                    <div class="relative w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center border-4 border-white shadow-inner animate-warning">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-3">Konfirmasi Hapus?</h3>
                <p id="custom-modal-text" class="text-slate-500 text-sm mb-8 leading-relaxed px-2"></p>
                <div class="flex flex-col gap-3">
                    <button type="button" id="confirm-btn" class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-red-500/30 hover:-translate-y-0.5 transition-all active:scale-95">Ya, Hapus Data</button>
                    <button type="button" onclick="closeCustomModal()" class="w-full bg-slate-100 text-slate-600 px-6 py-4 rounded-2xl font-bold hover:bg-slate-200 transition-all">Batal</button>
                </div>
            </div>
        </div>

        {{-- 🔥 CUSTOM MODAL POP-UP (LOGOUT WA) - Z-INDEX 9999 🔥 --}}
        <div id="logout-modal" class="fixed inset-0 z-[9999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeLogoutModal()"></div>
            <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-all duration-300" id="logout-modal-box">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="absolute inset-0 bg-red-100 rounded-full animate-ping opacity-25"></div>
                    <div class="relative w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center border-4 border-white shadow-inner animate-warning">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-3">Putuskan Koneksi?</h3>
                <p class="text-slate-500 text-sm mb-8 leading-relaxed px-2">Koneksi WhatsApp Anda akan dihapus dari server. Anda harus melakukan Scan QR ulang nanti.</p>
                <div class="flex flex-col gap-3">
                    <button type="button" onclick="confirmLogout()" class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-red-500/30 hover:-translate-y-0.5 transition-all active:scale-95">Ya, Putuskan</button>
                    <button type="button" onclick="closeLogoutModal()" class="w-full bg-slate-100 text-slate-600 px-6 py-4 rounded-2xl font-bold hover:bg-slate-200 transition-all">Batal</button>
                </div>
            </div>
        </div>

        {{-- 🔥 CUSTOM MODAL POP-UP (PILIH PAKET / TOP UP) - Z-INDEX 9999 🔥 --}}
        <div id="upgrade-modal" class="fixed inset-0 z-[9999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeUpgradeModal()"></div>
            <div class="relative bg-white/95 backdrop-blur-xl p-6 md:p-10 rounded-[3rem] shadow-2xl border-2 border-white max-w-4xl w-full mx-4 transform scale-95 transition-all duration-300 overflow-y-auto max-h-[90vh] custom-scrollbar" id="upgrade-modal-box">

                <div class="text-center mb-8">
                    <h3 class="text-3xl font-black text-slate-800 mb-2">Layanan RuangRestu</h3>
                    <p class="text-slate-500 font-medium">Pilih layanan tambahan yang Anda butuhkan.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- SEKSI 1: UPGRADE PAKET --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 px-2">
                            <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center font-bold">1</div>
                            <h4 class="font-black text-slate-800 uppercase tracking-wider text-sm">Aktivasi Web</h4>
                        </div>
                        <div class="border-2 {{ $invitation->status == 'active' ? 'border-slate-100 bg-slate-50 opacity-60' : 'border-orange-200 bg-orange-50/30 hover:border-orange-400' }} rounded-[2rem] p-6 relative transition-all flex flex-col h-full">
                            @if($invitation->status != 'active')
                                <div class="absolute top-4 right-4 bg-orange-500 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase">Penting</div>
                            @endif
                            
                            <h5 class="text-xl font-extrabold text-slate-800 mb-1">Paket Premium</h5>
                            <div class="text-2xl font-black text-orange-500 mb-4">Rp 89.000 <span class="text-xs text-slate-400 font-bold">/Acara</span></div>
                            <ul class="text-xs text-slate-600 space-y-2 mb-6 flex-grow">
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Akses sebar publik dibuka</li>
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Kuota Dasar: 30 Pesan Blast</li>
                            </ul>
                            
                            @if($invitation->status == 'active')
                                <div class="block w-full text-center bg-slate-200 text-slate-500 py-3 rounded-2xl font-bold text-sm cursor-not-allowed">
                                    Sudah Aktif
                                </div>
                            @else
                                <button type="button" onclick="payWithMidtrans('package_premium', {{ $invitation->id }})" class="block w-full text-center bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-2xl font-bold transition-all shadow-lg shadow-orange-500/30 text-sm">
                                    Aktifkan Sekarang
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- SEKSI 2: TOP UP KUOTA WA --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 px-2">
                            <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold">2</div>
                            <h4 class="font-black text-slate-800 uppercase tracking-wider text-sm">Tambah Kuota Blast</h4>
                        </div>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="bg-white border-2 border-slate-100 p-4 rounded-2xl flex items-center justify-between hover:border-indigo-300 transition-all group">
                                <div>
                                    <div class="font-extrabold text-slate-800 text-sm">+100 Pesan</div>
                                    <div class="text-indigo-600 font-black">Rp 25.000</div>
                                </div>
                                <button type="button" onclick="payWithMidtrans('addon_blast_100', {{ $invitation->id }})" class="bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">Pilih</button>
                            </div>
                            <div class="bg-white border-2 border-slate-100 p-4 rounded-2xl flex items-center justify-between hover:border-indigo-300 transition-all group">
                                <div>
                                    <div class="font-extrabold text-slate-800 text-sm">+500 Pesan</div>
                                    <div class="text-indigo-600 font-black">Rp 100.000</div>
                                </div>
                                <button type="button" onclick="payWithMidtrans('addon_blast_500', {{ $invitation->id }})" class="bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">Pilih</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 text-center border-t border-slate-100 pt-6">
                    <button type="button" onclick="closeUpgradeModal()" class="text-slate-400 font-extrabold px-8 py-2 rounded-full hover:bg-slate-100 hover:text-slate-600 transition-colors text-sm">Tutup Layar</button>
                </div>
            </div>
        </div>

    @endif
@endsection

@push('scripts')
    {{-- SCRIPT MIDTRANS SNAP --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') ?? 'SB-Mid-client-XXXXX' }}"></script>

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
        // 🔥 LOGIKA PEMBAYARAN MIDTRANS POPUP (AJAX) 🔥
        // =========================================================
        function payWithMidtrans(type, invitationId) {
            const btn = event.target;
            const originalText = btn.innerText;
            btn.innerText = 'Memproses...';
            btn.disabled = true;

            fetch('/customer/checkout/get-snap-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type: type, invitation_id: invitationId })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerText = originalText;
                btn.disabled = false;

                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
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
                        onPending: function(result){
                            showUniversalAlert('warning', 'Menunggu Pembayaran', 'Silakan selesaikan pembayaran Anda di panduan Midtrans yang diberikan.');
                        },
                        onError: function(result){
                            showUniversalAlert('error', 'Gagal', 'Proses pembayaran Anda mengalami kegagalan.');
                        },
                        onClose: function(){
                            console.log('Popup Midtrans ditutup sebelum dibayar');
                        }
                    });
                } else if (data.error) {
                    showUniversalAlert('error', 'Terjadi Kesalahan', data.error);
                } else {
                    showUniversalAlert('error', 'Token Gagal', 'Gagal mendapatkan token pembayaran dari server.');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showUniversalAlert('error', 'Koneksi Terputus', 'Gagal menghubungi server sistem pembayaran.');
                btn.innerText = originalText;
                btn.disabled = false;
            });
        }

        // 🔥 LOGIKA MODAL LAYANAN (PILIH PAKET / TOP UP) 🔥
        function openUpgradeModal() {
            const modal = document.getElementById('upgrade-modal');
            const box = document.getElementById('upgrade-modal-box');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => { box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
        }
        function closeUpgradeModal() {
            const modal = document.getElementById('upgrade-modal');
            const box = document.getElementById('upgrade-modal-box');
            box.classList.remove('scale-100'); box.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        // 🔥 LOGIKA MODAL HAPUS TAMU 🔥
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
            box.classList.remove('scale-100'); box.classList.add('scale-95'); 
            modal.classList.add('opacity-0', 'pointer-events-none');
        }
        document.getElementById('confirm-btn').addEventListener('click', function() {
            if (targetFormId) document.getElementById(targetFormId).submit();
        });

        // 🔥 LOGIKA KUOTA GRATIS (MENGGUNAKAN UNIVERSAL MODAL) 🔥
        const isFreePlan = {{ isset($invitation) && $invitation->status != 'active' ? 'true' : 'false' }};
        const maxFree = 30;
        const sudahDikirim = {{ $sudahDikirim ?? 0 }};
        let sisaKuota = Math.max(0, maxFree - sudahDikirim);

        function checkQuota(source) {
            if (!isFreePlan) return; 

            const checkedBoxes = document.querySelectorAll('.guest-checkbox:checked').length;
            if (checkedBoxes > sisaKuota) {
                source.checked = false; 
                showUniversalAlert('warning', 'Peringatan Kuota!', `Versi Gratis dibatasi maksimal 30 pesan.\nSisa kuota Anda: ${sisaKuota} tamu.\n\nSilakan "Lihat Pilihan Layanan" untuk mengaktifkan web dan mengirim tanpa batas.`);
            }
        }

        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.guest-checkbox:not(:disabled)');

            if (isFreePlan && source.checked) {
                let checkedCount = document.querySelectorAll('.guest-checkbox:checked').length;
                checkboxes.forEach(cb => {
                    if (!cb.checked && checkedCount < sisaKuota) {
                        cb.checked = true;
                        checkedCount++;
                    } else if (!cb.checked) {
                        cb.checked = false;
                    }
                });
                if (checkboxes.length > sisaKuota) {
                    showUniversalAlert('warning', 'Perhatian!', `Hanya ${sisaKuota} tamu yang dipilih karena Anda masih menggunakan Versi Gratis.`);
                }
            } else {
                checkboxes.forEach(cb => cb.checked = source.checked);
            }
        }

        // ============================================

        const waBaseUrl = "{{ config('services.wa_engine.url', 'http://127.0.0.1:3000') }}";
        const sessionId = "{{ $sessionId ?? 'default' }}";
        let pollInterval = null;
        let isStarting = false;

        const qrImage = document.getElementById('qr-image');
        const qrLoading = document.getElementById('qr-loading');
        const status = document.getElementById('wa-status');
        const userDiv = document.getElementById('wa-user');
        const btnStart = document.getElementById('btn-start-wa');
        const btnLogout = document.getElementById('btn-logout-wa');
        const connectedIcon = document.getElementById('wa-connected-icon');

        const templates = {
            formal: "Kepada Yth. Bapak/Ibu/Saudara/i {nama},\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Anda untuk hadir dan memberikan doa restu pada acara pernikahan kami.\n\nDetail lengkap acara beserta lokasi dapat diakses melalui tautan berikut:\n{link}\n\nKehadiran dan doa restu Anda merupakan suatu kehormatan dan kebahagiaan bagi kami.\n\nTerima kasih,\nSalam Hangat.",
            casual: "Halo {nama}! 👋\n\nKabar bahagia nih! Kami akan melangsungkan acara pernikahan dan kami sangat berharap kamu bisa hadir untuk merayakan momen spesial ini bersama kami.\n\nSilakan buka undangan digital kami untuk melihat detail waktu dan lokasinya ya:\n{link}\n\nJangan lupa datang ya, kehadiranmu sangat berarti buat kami! Ditunggu lho. 😉",
            islami: "Assalamualaikum Wr. Wb. {nama},\n\nDengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan acara pernikahan kami.\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.\n\nInformasi detail acara dapat dilihat pada tautan berikut:\n{link}\n\nWassalamualaikum Wr. Wb."
        };

        function changeTemplate() {
            const selector = document.getElementById('template-selector');
            const textArea = document.getElementById('message-area');
            if (templates[selector.value]) textArea.value = templates[selector.value];
            else textArea.value = "";
        }

        window.addEventListener('load', () => {
            const selector = document.getElementById('template-selector');
            const textArea = document.getElementById('message-area');
            if (selector && selector.value === 'formal' && textArea.value === '') {
                textArea.value = templates['formal'];
            }

            if (!document.getElementById('wa-status')) return; 

            fetch(`${waBaseUrl}/api/wa/status/${sessionId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'connected') checkStatus();
                    else if (data.status === 'qr_ready' || data.status === 'loading') startWaSession();
                })
                .catch(err => console.log("Menunggu server Node..."));
        });

        function openLogoutModal() {
            const modal = document.getElementById('logout-modal');
            const box = document.getElementById('logout-modal-box');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => { box.classList.remove('scale-95'); box.classList.add('scale-100'); }, 10);
        }
        function closeLogoutModal() {
            const modal = document.getElementById('logout-modal');
            const box = document.getElementById('logout-modal-box');
            box.classList.remove('scale-100'); box.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }
        function confirmLogout() {
            closeLogoutModal();
            logoutWa();
        }

        function resetUI() {
            qrImage.classList.add('hidden');
            connectedIcon.classList.add('hidden');
            qrLoading.classList.remove('hidden');
            qrLoading.innerText = 'Klik tombol untuk scan QR';
            status.innerText = 'Terputus';
            status.className = "inline-block px-6 py-2 bg-slate-100 text-slate-500 rounded-full font-extrabold text-[10px] uppercase tracking-widest";
            userDiv.classList.add('hidden');
            btnStart.style.display = 'block';
            btnStart.disabled = false;
            btnLogout.classList.add('hidden');
        }

        function startWaSession() {
            if (isStarting) return;
            isStarting = true;
            qrLoading.innerText = 'Menyiapkan server...';
            status.innerText = 'Loading...';
            btnStart.disabled = true;

            fetch("{{ route('customer.blast.start') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                })
                .then(() => {
                    if (pollInterval) clearInterval(pollInterval);
                    pollInterval = setInterval(checkStatus, 2500);
                })
                .catch(err => {
                    resetUI();
                    isStarting = false;
                });
        }

        function checkStatus() {
            fetch(`${waBaseUrl}/api/wa/status/${sessionId}`)
                .then(res => res.json())
                .then(data => {
                    switch (data.status) {
                        case 'qr_ready':
                            qrLoading.classList.add('hidden');
                            connectedIcon.classList.add('hidden');
                            qrImage.classList.remove('hidden');
                            qrImage.src = data.qr;
                            status.innerText = 'Scan QR';
                            status.className = "inline-block px-6 py-2 bg-yellow-100 text-yellow-600 rounded-full font-extrabold text-[10px] uppercase tracking-widest";
                            userDiv.classList.add('hidden');
                            break;
                        case 'connected':
                            qrImage.classList.add('hidden');
                            qrLoading.classList.add('hidden');
                            
                            connectedIcon.classList.remove('hidden');
                            connectedIcon.classList.add('flex');
                            
                            status.innerText = 'Terhubung';
                            status.className = "inline-block px-6 py-2 bg-emerald-100 text-emerald-600 rounded-full font-extrabold text-[10px] uppercase tracking-widest";
                            btnStart.style.display = 'none';
                            btnLogout.classList.remove('hidden');
                            if (data.user) {
                                userDiv.innerText = "Login sebagai: " + data.user.name;
                                userDiv.classList.remove('hidden');
                            }
                            break;
                        case 'restarting':
                            qrImage.classList.add('hidden');
                            connectedIcon.classList.add('hidden');
                            qrLoading.classList.remove('hidden');
                            qrLoading.innerText = 'Menyiapkan QR baru...';
                            status.innerText = 'Reconnect...';
                            status.className = "inline-block px-6 py-2 bg-blue-100 text-blue-600 rounded-full font-extrabold text-[10px] uppercase tracking-widest";
                            userDiv.classList.add('hidden');
                            btnLogout.classList.add('hidden');
                            break;
                        case 'disconnected':
                            resetUI();
                            isStarting = false;
                            break;
                    }
                })
                .catch(err => console.error("Polling error:", err));
        }

        function logoutWa() {
            btnLogout.innerText = "Memproses...";
            btnLogout.disabled = true;

            fetch("{{ route('customer.blast.logout') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                })
                .then(() => {
                    clearInterval(pollInterval);
                    isStarting = false;
                    resetUI();
                    btnLogout.innerText = "Putuskan Koneksi WA";
                    setTimeout(() => { startWaSession(); }, 1500);
                })
                .catch(err => console.error("Logout error:", err));
        }
    </script>
@endpush