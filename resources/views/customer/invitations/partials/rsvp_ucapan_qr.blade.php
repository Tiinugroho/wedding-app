<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <h4 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                </path>
            </svg>
        </div>
        Buku Tamu, QR Absensi & Ucapan
    </h4>

    <div class="space-y-4">
        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
            <p class="text-sm text-slate-600 font-medium mb-4">Formulir kehadiran (RSVP) akan selalu
                terbuka agar tamu bisa mengonfirmasi kehadirannya. Namun Anda dapat memilih apakah ingin
                menampilkan hasil ucapan doa dari tamu di halaman undangan publik.</p>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_wishes_active" value="1"
                    {{ $content['is_wishes_active'] ?? true ? 'checked' : '' }}
                    class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                <span class="font-bold text-slate-700">Tampilkan Hasil Ucapan Tamu di Undangan
                    Publik</span>
            </label>
        </div>

        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50 relative overflow-hidden">
            @if (isset($packageLogic['has_qr_attendance']) && $packageLogic['has_qr_attendance'] == true)
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="enable_qr_attendance" value="1"
                        {{ !empty($content['enable_qr_attendance']) ? 'checked' : '' }}
                        class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                    <div>
                        <span class="font-bold text-slate-700">Wajibkan Tamu Scan QR Code Absensi</span>
                        <p class="text-xs text-slate-500 mt-1">Setiap tamu akan mendapatkan QR Code unik.
                            Saat acara, Anda dapat menggunakan fitur scanner untuk mencatat kehadiran secara
                            otomatis di buku tamu digital.</p>
                    </div>
                </label>
            @else
                <div
                    class="absolute inset-0 z-10 bg-white/90 backdrop-blur-sm flex flex-col md:flex-row items-center justify-between text-left p-5">
                    <div>
                        <h6 class="font-bold text-slate-800 text-sm">Fitur Buku Tamu Digital & QR Absensi
                            Terkunci</h6>
                        <p class="text-xs text-slate-500 mt-1">Hanya tersedia di Paket Premium</p>
                    </div>
                    <button type="button"
                        class="btn-open-upgrade mt-3 md:mt-0 bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md">Upgrade
                        Paket</button>
                </div>
                <label class="flex items-center gap-3 cursor-pointer opacity-30">
                    <input type="checkbox" disabled class="w-5 h-5 rounded border-slate-300">
                    <div>
                        <span class="font-bold text-slate-700">Wajibkan Tamu Scan QR Code Absensi</span>
                        <p class="text-xs text-slate-500 mt-1">Setiap tamu akan mendapatkan QR Code unik.
                        </p>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
