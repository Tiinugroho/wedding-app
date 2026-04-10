@extends('customer.partials.app')
@section('title', 'Scanner Buku Tamu')

@push('styles')
    <style>
        /* =======================================================
           🔥 HACK CSS UNTUK HTML5-QRCODE AGAR TAMPIL MODERN 🔥
           ======================================================= */
        
        /* Container Utama */
        #reader { 
            border: none !important; 
            border-radius: 2rem !important; 
            overflow: hidden !important; 
            background: #f8fafc !important; /* slate-50 */
            padding: 0 !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        /* Container Pembungkus Video */
        #reader__scan_region {
            width: 100% !important;
            display: flex;
            justify-content: center;
            background-color: #0f172a; /* Warna gelap agar rapi saat loading */
        }

        /* Video Feed (Ini yang membuat kamera pas dan proporsional) */
        #reader video { 
            object-fit: cover !important; 
            border-radius: 2rem !important;
            width: 100% !important;
            height: auto !important;
            max-height: 60vh !important; /* Mencegah video terlalu tinggi di HP */
        }

        /* Container Tombol & Teks Dashboard (Bagian bawah video) */
        #reader__dashboard_section_csr {
            padding: 1.5rem !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 0.5rem !important;
            width: 100%;
        }

        /* Tombol Start/Stop Camera */
        #reader__dashboard_section_csr button { 
            background: linear-gradient(to right, #f97316, #ea580c) !important; /* from-rLightOrange to-rOrange */
            color: white !important; 
            border: none !important; 
            padding: 0.75rem 2rem !important; 
            border-radius: 1rem !important; 
            font-weight: 800 !important; 
            font-size: 0.875rem !important;
            cursor: pointer !important;
            box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.3) !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-top: 0.5rem !important;
        }

        #reader__dashboard_section_csr button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 20px -3px rgba(234, 88, 12, 0.4) !important;
        }

        /* Teks informasi bawaan ("Request Camera Permissions", dll) */
        #reader__dashboard_section_csr span { 
            color: #64748b !important; /* slate-500 */
            font-size: 0.875rem !important;
            font-family: inherit !important;
            font-weight: 500 !important;
            text-align: center !important;
        }

        /* Link Ganti Kamera (Swap Camera) */
        #reader__dashboard_section_swaplink {
            text-decoration: none !important;
            color: #f97316 !important; /* text-rOrange */
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            margin-top: 1rem !important;
            display: inline-block !important;
        }
        #reader__dashboard_section_swaplink:hover {
            color: #c2410c !important;
            text-decoration: underline !important;
        }

        /* Sembunyikan elemen sampah bawaan (Teks "Powered by", Header, dll) */
        #reader__header_message,
        #reader a[href="https://scanapp.org"] { 
            display: none !important; 
        }

        /* Garis Scan Animasi Kustom (opsional) */
        #reader__scan_region img {
            display: none !important; /* Sembunyikan logo QR bawaan yang kaku */
        }
    </style>
@endpush

@section('content')
    <header class="mb-10 flex items-center gap-4">
        <a href="{{ route('customer.dashboard') }}" class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 transition shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800">Scanner Buku Tamu</h2>
            <p class="text-slate-400 mt-1 font-medium">Arahkan kamera ke QR Code milik tamu undangan.</p>
        </div>
    </header>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
        
        {{-- KOTAK SCANNER KAMERA --}}
        <div class="xl:col-span-7 flex flex-col h-full">
            <div class="bg-white p-6 rounded-[3rem] border border-slate-100 shadow-xl relative flex-1 flex flex-col items-center justify-center">
                
                <div id="scan-status" class="absolute top-10 left-1/2 -translate-x-1/2 z-50 px-8 py-4 rounded-full font-extrabold text-sm shadow-2xl transition-all duration-300 opacity-0 -translate-y-4 tracking-wide uppercase">
                    Menunggu QR Code...
                </div>

                <div id="reader" class="w-full relative ring-4 ring-slate-50 overflow-hidden shadow-inner"></div>
                
                <div class="mt-8 text-center bg-orange-50/50 p-4 rounded-2xl border border-orange-100 w-full">
                    <div class="flex items-center justify-center gap-2 text-rOrange font-bold text-sm mb-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Instruksi
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Klik "Request Camera Permissions" jika diminta, lalu arahkan QR Code tamu tepat di tengah layar.</p>
                </div>
            </div>
        </div>

        {{-- RIWAYAT SCAN TERBARU --}}
        <div class="xl:col-span-5 flex flex-col h-full">
            <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-xl flex-1 flex flex-col max-h-[750px]">
                <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
                    <div>
                        <h3 class="text-2xl font-extrabold text-slate-800 mb-1">Tamu Hadir</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Riwayat Check-In</p>
                    </div>
                    <div class="bg-gradient-to-r from-rRed to-rOrange text-white px-5 py-2.5 rounded-xl shadow-lg shadow-rOrange/20 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span id="total-count" class="text-lg font-black">{{ count($attendances) }}</span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto pr-2 space-y-4 custom-scrollbar" id="attendance-list">
                    @forelse($attendances as $att)
                        <div class="p-5 rounded-[1.5rem] border border-slate-100 bg-slate-50 flex items-center justify-between hover:bg-white hover:shadow-md transition duration-300 animate-fade-in">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center shadow-sm shrink-0 border border-emerald-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-base mb-0.5">{{ $att->guest_name }}</p>
                                    <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Check-In Berhasil</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-slate-400">{{ \Carbon\Carbon::parse($att->check_in_time)->format('H:i') }}</p>
                                <p class="text-[10px] font-bold text-slate-300 uppercase">WIB</p>
                            </div>
                        </div>
                    @empty
                        <div id="empty-state" class="h-full flex flex-col items-center justify-center text-center py-10 opacity-60">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            </div>
                            <p class="text-slate-500 font-bold">Belum Ada Tamu</p>
                            <p class="text-xs text-slate-400 mt-1">Hasil scan QR Code akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Kustomisasi Scrollbar untuk riwayat tamu --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let isProcessing = false;
        let totalCount = {{ count($attendances) }};

        // Fungsi Tampilkan Notifikasi Melayang
        function showStatus(message, type) {
            const statusEl = document.getElementById('scan-status');
            statusEl.innerText = message;
            
            // Pewarnaan dinamis
            statusEl.className = `absolute top-10 left-1/2 -translate-x-1/2 z-50 px-8 py-4 rounded-full font-extrabold text-sm shadow-2xl transition-all duration-300 transform translate-y-0 opacity-100 tracking-wide uppercase ${type === 'success' ? 'bg-emerald-500 text-white shadow-emerald-500/30 border border-emerald-400' : (type === 'error' ? 'bg-rose-500 text-white shadow-rose-500/30 border border-rose-400' : 'bg-amber-500 text-white')}`;

            setTimeout(() => {
                statusEl.classList.remove('translate-y-0', 'opacity-100');
                statusEl.classList.add('-translate-y-4', 'opacity-0');
            }, 4000);
        }

        // Fungsi Tambah List ke HTML tanpa refresh
        function appendToList(name, time) {
            const list = document.getElementById('attendance-list');
            const emptyState = document.getElementById('empty-state');
            if (emptyState) emptyState.remove();

            const html = `
                <div class="p-5 rounded-[1.5rem] border border-emerald-100 bg-emerald-50/30 flex items-center justify-between hover:bg-white hover:shadow-md transition duration-300 animate-fade-in">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/30 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 text-base mb-0.5">${name}</p>
                            <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Check-In Berhasil</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-slate-400">${time}</p>
                        <p class="text-[10px] font-bold text-slate-300 uppercase">WIB</p>
                    </div>
                </div>`;
            
            list.insertAdjacentHTML('afterbegin', html);
            totalCount++;
            document.getElementById('total-count').innerText = `${totalCount}`;
        }

        // FUNGSI UTAMA: Saat QR Berhasil Terbaca
        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return; 
            isProcessing = true;

            // Bunyi Beep sukses (Volume pelan)
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
            audio.volume = 0.5;
            audio.play().catch(e => console.log('Audio autoplay prevented'));

            // Memanggil API Laravel
            fetch(decodedText, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatus(data.message, 'success');
                    appendToList(data.guest_name, data.time);
                } else {
                    // Bunyi Beep Error (Jika double scan / tamu tidak valid)
                    const errAudio = new Audio('https://assets.mixkit.co/active_storage/sfx/2572/2572-preview.mp3');
                    errAudio.volume = 0.4;
                    errAudio.play().catch(e => {});
                    showStatus(data.message, 'error');
                }
            })
            .catch(error => {
                showStatus('Gagal membaca QR Code. Pastikan QR valid.', 'error');
            })
            .finally(() => {
                // Jeda 3 detik sebelum mengizinkan scan tamu berikutnya
                setTimeout(() => {
                    isProcessing = false;
                }, 3000);
            });
        }

        function onScanFailure(error) {
            // Biarkan kosong, function ini akan dipanggil tiap detik selama kamera tidak melihat QR.
        }

        // Konfigurasi Kamera agar lebih pas
        // Saya hapus parameter aspectRatio dan membiarkannya menyesuaikan ukuran container
        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
            fps: 15, 
            qrbox: { width: 250, height: 250 },
            showTorchButtonIfSupported: true,
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        }, false);
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
@endpush