@extends('customer.partials.app')
@section('title', 'Kelola Isi Undangan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <style>
        /* =======================================
           CUSTOM CSS TOM SELECT (DROPDOWN)
           ======================================= */
        .ts-control {
            padding: 0.75rem 1rem !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            font-size: 0.875rem !important;
            color: #334155 !important;
            box-shadow: none !important;
        }
        .ts-control.focus {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden;
            z-index: 9999 !important; 
        }
        .ts-dropdown .option {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .ts-dropdown .option:hover,
        .ts-dropdown .option.active {
            background-color: #fff7ed !important;
            color: #ea580c !important;
        }

        /* =======================================
           CUSTOM CSS FLATPICKR (KALENDER & JAM)
           ======================================= */
        .flatpickr-calendar {
            border-radius: 1rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            padding: 0.5rem !important;
            font-family: inherit !important;
        }
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, 
        .flatpickr-day.startRange.inRange, 
        .flatpickr-day.endRange.inRange, 
        .flatpickr-day.selected:focus, 
        .flatpickr-day.startRange:focus, 
        .flatpickr-day.endRange:focus, 
        .flatpickr-day.selected:hover, 
        .flatpickr-day.startRange:hover, 
        .flatpickr-day.endRange:hover, 
        .flatpickr-day.selected.prevMonthDay, 
        .flatpickr-day.startRange.prevMonthDay, 
        .flatpickr-day.endRange.prevMonthDay, 
        .flatpickr-day.selected.nextMonthDay, 
        .flatpickr-day.startRange.nextMonthDay, 
        .flatpickr-day.endRange.nextMonthDay {
            background: #f97316 !important;
            border-color: #f97316 !important;
        }

        /* Custom File Upload Button */
        input[type="file"]::file-selector-button {
            margin-right: 1rem;
            padding: 0.5rem 1.25rem;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        input[type="file"]::file-selector-button:hover {
            background-color: #fff7ed;
            color: #ea580c;
            border-color: #f97316;
        }
    </style>
@endpush

@section('content')
    <header class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.invitations.index') }}"
                class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 transition shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800">Kelola Isi Undangan</h2>
                <p class="text-slate-400 mt-1">Langkah 2: Lengkapi data untuk <span
                        class="text-rOrange font-semibold">ruangrestu.com/{{ $invitation->slug }}</span></p>
            </div>
        </div>

        <a href="{{ url('/' . $invitation->slug) }}" target="_blank" id="btn-live-preview"
            class="flex items-center justify-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                </path>
            </svg>
            Lihat Live Preview
        </a>
    </header>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 font-medium shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <strong>Gagal Menyimpan! Periksa isian Anda:</strong>
            </div>
            <ul class="list-disc list-inside ml-9 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.invitations.update', $invitation->id) }}" method="POST" enctype="multipart/form-data"
        id="invitationForm" onsubmit="document.getElementById('loading-overlay').classList.replace('hidden', 'flex');"
        class="pb-32 xl:pb-0">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            <div class="xl:col-span-8 space-y-8">

                {{-- 1. PENGATURAN COVER & QUOTES --}}
                @include('customer.invitations.partials.cover_quotes')

                {{-- 2. DATA MEMPELAI PRIA --}}
                @include('customer.invitations.partials.mempelai_pria')

                {{-- 3. DATA MEMPELAI WANITA --}}
                @include('customer.invitations.partials.mempelai_wanita')

                {{-- 4. TURUT MENGUNDANG + TOGGLE --}}
                @include('customer.invitations.partials.turut_mengundang')

                {{-- 5. WAKTU & LOKASI ACARA + TOGGLE --}}
                @include('customer.invitations.partials.waktu_lokasi')

                {{-- 6. INFO TAMBAHAN (DRESSCODE, PROKES & ADAB) + TOGGLE --}}
                @include('customer.invitations.partials.info_tambahan')

                {{-- 7. MUSIC SELECTOR --}}
                @include('customer.invitations.partials.music_selector')

                {{-- 8. CERITA CINTA + TOGGLE --}}
                @include('customer.invitations.partials.cerita_cinta')

                {{-- 9. GALERI & YOUTUBE + TOGGLE --}}
                @include('customer.invitations.partials.galeri_youtube')

                {{-- 10. LIVE STREAMING ACARA --}}
                @include('customer.invitations.partials.live_streaming')

                {{-- 11. KADO DIGITAL + TOGGLE --}}
                @include('customer.invitations.partials.kado_digital')

                {{-- 12. RSVP, UCAPAN, & QR ABSENSI --}}
                @include('customer.invitations.partials.rsvp_ucapan_qr')

                {{-- 13. GANTI TEMA / TEMPLATE DENGAN FILTER & CARD KECIL --}}
                @include('customer.invitations.partials.ganti_tema')

            </div>

            {{-- DESKTOP SIDEBAR SAVE BUTTON --}}
            <div class="hidden xl:block xl:col-span-4">
                <div class="sticky top-10 bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-xl text-center">
                    <h4 class="font-extrabold text-2xl mb-2">Simpan Perubahan</h4>
                    <p class="text-sm text-slate-400 mb-8 leading-relaxed">Pastikan semua data sudah benar sebelum disimpan.</p>
                    <button type="button" onclick="openSaveDrawer()"
                        class="w-full py-4 bg-gradient-to-r from-rRed to-rOrange rounded-2xl font-bold text-white hover:scale-105 transition shadow-lg flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <span>Simpan Data Undangan</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- 🔥 FLOATING BUTTON (Mengambang aman di atas Navbar) 🔥 --}}
        <div class="xl:hidden fixed bottom-24 left-1/2 -translate-x-1/2 z-[60]">
            <button type="button" onclick="openSaveDrawer()"
                class="bg-slate-900 text-white px-6 py-3.5 rounded-full font-bold flex items-center gap-3 shadow-[0_10px_30px_rgba(0,0,0,0.3)] hover:scale-105 transition-transform w-max border border-slate-700 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                <span class="whitespace-nowrap">Simpan Data</span>
            </button>
        </div>

        {{-- BOTTOM CANVAS / DRAWER --}}
        <div id="save-drawer-overlay" class="fixed inset-0 bg-slate-900/50 z-[100] hidden opacity-0 transition-opacity duration-300 backdrop-blur-sm" onclick="closeSaveDrawer()"></div>

        <div id="save-drawer" class="fixed bottom-0 inset-x-0 xl:inset-x-auto xl:right-0 xl:top-0 xl:w-[400px] h-auto xl:h-full max-h-[85vh] xl:max-h-none bg-white z-[110] shadow-[0_-20px_40px_rgba(0,0,0,0.2)] transform translate-y-full xl:translate-y-0 xl:translate-x-full transition-transform duration-300 ease-in-out flex flex-col rounded-t-[2.5rem] xl:rounded-none">
            <div class="w-full flex justify-center pt-4 pb-2 xl:hidden" onclick="closeSaveDrawer()">
                <div class="w-12 h-1.5 bg-slate-200 rounded-full cursor-pointer"></div>
            </div>
            <div class="p-6 pt-2 xl:pt-6 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
                <h3 class="font-extrabold text-lg text-slate-800">Konfirmasi</h3>
                <button type="button" onclick="closeSaveDrawer()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:text-red-500 hover:bg-red-50 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 pb-12 overflow-y-auto flex flex-col justify-center xl:flex-1">
                <div class="w-20 h-20 bg-orange-50 text-rOrange rounded-full flex items-center justify-center mb-6 mx-auto border-[6px] border-orange-100/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                </div>
                <h4 class="text-center font-extrabold text-2xl mb-3 text-slate-800">Simpan Data?</h4>
                <p class="text-center text-sm text-slate-500 mb-8 leading-relaxed">Data yang disimpan akan langsung di-update pada halaman undangan publik Anda.</p>
                <button type="button" onclick="document.getElementById('invitationForm').submit()" class="w-full py-4 bg-gradient-to-r from-rRed to-rOrange rounded-xl font-bold text-white hover:scale-[1.02] transition shadow-lg shadow-rOrange/30">Ya, Simpan Sekarang</button>
                <button type="button" onclick="closeSaveDrawer()" class="w-full py-4 mt-3 bg-slate-100 rounded-xl font-bold text-slate-600 hover:bg-slate-200 transition cursor-pointer">Batal, Periksa Lagi</button>
            </div>
        </div>
    </form>

    <form id="global-delete-photo-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- MODAL UPGRADE --}}
    @include('customer.invitations.partials.upgrade_modal')

    {{-- MODAL PREVIEW TEMA IFRAME --}}
    @include('customer.invitations.partials.preview_modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <script>
        // ==========================================
        // 🌟 INIT KUSTOMISASI DATE, TIME, & SELECT
        // ==========================================
        function initCustomInputs() {
            // Trik: Suntikkan placeholder dd/mm/yyyy ke input Date
            document.querySelectorAll('input[type="date"]').forEach(el => {
                if (!el.getAttribute('placeholder')) {
                    el.setAttribute('placeholder', 'dd/mm/yyyy');
                }
            });

            // Trik: Suntikkan placeholder --:-- ke input Time
            document.querySelectorAll('input[type="time"]').forEach(el => {
                if (!el.getAttribute('placeholder')) {
                    el.setAttribute('placeholder', '--:--');
                }
            });

            // 1. Ubah Input Date jadi Kalender Mewah
            flatpickr('input[type="date"]', {
                dateFormat: "Y-m-d", // Format untuk dikirim ke database
                altInput: true,
                altFormat: "d/m/Y",  // Format Indonesia yang dilihat Klien (contoh: 24/12/2026)
                disableMobile: true  
            });

            // 2. Ubah Input Time jadi Jam Mewah
            flatpickr('input[type="time"]', {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                disableMobile: true,
                altInput: true,     
                altFormat: "H:i"
            });

            // 3. Ubah semua Dropdown (<select>) jadi Mewah
            document.querySelectorAll('select:not(.tomselected)').forEach((el) => {
                new TomSelect(el, {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    dropdownParent: 'body'
                });
            });
        }

        // Panggil saat halaman pertama kali dimuat
        initCustomInputs();

        // ==========================================
        // LACI/DRAWER KONFIRMASI SIMPAN
        // ==========================================
        function openSaveDrawer() {
            const overlay = document.getElementById('save-drawer-overlay');
            const drawer = document.getElementById('save-drawer');
            overlay.classList.remove('hidden');
            void overlay.offsetWidth;
            overlay.classList.remove('opacity-0');
            drawer.classList.remove('translate-y-full', 'xl:translate-x-full');
            document.body.style.overflow = 'hidden';
        }

        function closeSaveDrawer() {
            const overlay = document.getElementById('save-drawer-overlay');
            const drawer = document.getElementById('save-drawer');
            drawer.classList.add('translate-y-full', 'xl:translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
            // Pakai string kosong untuk menghindari bug di Tailwind saat ditutup
            document.body.style.overflow = '';
        }

        // ==========================================
        // FUNGSI INPUT BARIS DINAMIS
        // ==========================================
        function addEventRow() {
            const id = Date.now();
            const html = `
                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 relative event-item mt-4 animate-fade-in">
                    <button type="button" onclick="this.closest('.event-item').remove()" class="absolute top-4 right-4 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg shadow-sm transition-colors">Hapus</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nama Acara</label><input type="text" name="events[${id}][title]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Resepsi Tambahan"></div>
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label><input type="date" name="events[${id}][date]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"></div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Waktu Acara (Mulai - Selesai)</label>
                            <div class="flex items-center gap-2">
                                <input type="time" name="events[${id}][time]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange">
                                <span class="text-slate-400 font-bold">-</span>
                                <input type="time" name="events[${id}][time_end]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange">
                            </div>
                        </div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nama Tempat/Gedung</label><input type="text" name="events[${id}][location]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Gedung Serbaguna"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Alamat Lengkap</label><textarea name="events[${id}][address]" rows="2" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"></textarea></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Link Google Maps (Opsional)</label><input type="url" name="events[${id}][map]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="https://maps.google.com/..."></div>
                    </div>
                </div>`;
            document.getElementById('event-wrapper').insertAdjacentHTML('beforeend', html);
            initCustomInputs();
        }

        function addLoveStoryRow() {
            const id = Date.now();
            const html = `
                <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item mt-4 animate-fade-in">
                    <button type="button" onclick="this.closest('.love-story-item').remove()" class="absolute top-5 right-5 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    <input type="hidden" name="love_stories[${id}][old_image]" value="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label><input type="text" name="love_stories[${id}][year]" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Januari 2021"></div>
                        <div class="pr-12 md:pr-0"><label class="block text-xs font-bold text-slate-600 mb-1">Judul Momen</label><input type="text" name="love_stories[${id}][title]" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Awal Bertemu"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Cerita</label><textarea name="love_stories[${id}][description]" rows="3" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Ceritakan momen tersebut..."></textarea></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-2">Foto Momen (Opsional)</label><input type="file" name="love_stories[${id}][image]" accept="image/*" class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer"></div>
                    </div>
                </div>`;
            document.getElementById('love-story-wrapper').insertAdjacentHTML('beforeend', html);
            initCustomInputs();
        }

        const bankOptions = `{!! isset($masterBanks) ? $masterBanks->map(fn($b) => '<option value="' . $b->name . '">' . $b->name . '</option>')->implode('') : '' !!}`;

        function addBankRow() {
            const id = Date.now();
            const html = `
                <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 bank-item mt-4 animate-fade-in">
                    <button type="button" onclick="this.closest('.bank-item').remove()" class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Pilih Bank / E-Wallet</label>
                            <select name="banks[${id}][name]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer">
                                <option value="">-- Pilih Pembayaran --</option>
                                ${bankOptions}
                            </select>
                        </div>
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Atas Nama</label><input type="text" name="banks[${id}][account_name]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Nama Pemilik Rekening"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nomor Rekening / No HP</label><input type="text" name="banks[${id}][account_number]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: 1234567890"></div>
                    </div>
                </div>`;
            document.getElementById('bank-wrapper').insertAdjacentHTML('beforeend', html);
            initCustomInputs();
        }

        function addYoutubeRow() {
            const html = `
                <div class="flex items-center gap-2 youtube-item mt-3">
                    <div class="flex-1 flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:ring-1 focus-within:ring-rOrange focus-within:border-rOrange transition">
                        <span class="pl-4 pr-2 text-slate-400"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg></span>
                        <input type="url" name="youtube_links[]" class="w-full py-3 px-2 bg-transparent border-0 focus:ring-0 text-sm" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <button type="button" onclick="this.closest('.youtube-item').remove()" class="w-12 h-12 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>`;
            document.getElementById('youtube-wrapper').insertAdjacentHTML('beforeend', html);
        }

        // ==========================================
        // FUNGSI PREVIEW FOTO
        // ==========================================
        function previewImages(input) {
            const container = document.getElementById('preview-container');
            container.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-square rounded-2xl overflow-hidden border-2 border-rOrange';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover opacity-60"><div class="absolute inset-0 flex items-center justify-center"><span class="bg-rOrange text-white text-[10px] px-2 py-1 rounded-lg font-bold uppercase">Ready</span></div>`;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function handleDeletePhoto(photoId) {
            if (confirm('Hapus foto ini secara permanen?')) {
                const deleteForm = document.getElementById('global-delete-photo-form');
                deleteForm.action = `/customer/gallery/${photoId}`;
                deleteForm.submit();
            }
        }

        // ==========================================
        // EVENT LISTENER SAAT HALAMAN DIMUAT
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('input[type="checkbox"][name^="is_"]');
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const wrapper = this.closest('.bg-white').querySelector('.transition-opacity');
                    if (wrapper) {
                        if (this.checked) wrapper.classList.remove('opacity-40', 'pointer-events-none');
                        else wrapper.classList.add('opacity-40', 'pointer-events-none');
                    }
                });
            });

            const filterBtns = document.querySelectorAll('.filter-btn');
            const templateCards = document.querySelectorAll('.template-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-slate-900', 'text-white', 'shadow-md');
                        b.classList.add('bg-slate-100', 'text-slate-600');
                    });
                    btn.classList.remove('bg-slate-100', 'text-slate-600');
                    btn.classList.add('bg-slate-900', 'text-white', 'shadow-md');

                    const filterCat = btn.getAttribute('data-filter');

                    templateCards.forEach(card => {
                        if (filterCat === 'all' || card.getAttribute('data-category') === filterCat) {
                            card.style.display = 'block';
                            card.style.animation = 'none';
                            card.offsetHeight;
                            card.style.animation = 'slide-up-soft 0.4s ease forwards';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            const upgradeModal = document.getElementById('upgradeModal');
            const closeUpgradeBtn = document.getElementById('closeUpgradeBtn');

            document.body.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('btn-open-upgrade')) {
                    e.preventDefault();
                    upgradeModal.classList.remove('hidden');
                    setTimeout(() => upgradeModal.classList.remove('opacity-0'), 20);
                }
            });

            if (closeUpgradeBtn) {
                closeUpgradeBtn.addEventListener('click', () => {
                    upgradeModal.classList.add('opacity-0');
                    setTimeout(() => upgradeModal.classList.add('hidden'), 300);
                });
            }

            const modal = document.getElementById('themeModal');
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalTitle');
            const modalCategory = document.getElementById('modalCategory');
            const modalIframe = document.getElementById('modalIframe');
            const closeBtn = document.getElementById('closeModalBtn');
            const footerCloseBtn = document.getElementById('footerCloseBtn');
            const previewBtns = document.querySelectorAll('.preview-btn');

            function openModal(title, category, pathUrl) {
                modalTitle.textContent = title;
                modalCategory.textContent = category;
                modalIframe.src = pathUrl;

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 20);
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('opacity-0');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modalIframe.src = '';
                }, 300);
                document.body.style.overflow = '';
            }

            previewBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openModal(this.getAttribute('data-title'), this.getAttribute('data-category'), this.getAttribute('data-path'));
                });
            });

            closeBtn.addEventListener('click', closeModal);
            footerCloseBtn.addEventListener('click', closeModal);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        });
    </script>
@endpush