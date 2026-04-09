<div id="upgradeModal"
    class="fixed inset-0 z-[60] hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300"
        id="upgradeModalContent">
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div>
                <h3 class="text-2xl font-extrabold text-slate-800">Upgrade Paket</h3>
                <p class="text-sm text-slate-500">Paket saat ini: <strong
                        class="text-slate-700">{{ $currentPackageName }}</strong></p>
            </div>
            <button type="button" id="closeUpgradeBtn"
                class="w-10 h-10 rounded-full bg-white border text-slate-500 flex items-center justify-center hover:bg-red-50 hover:text-red-500"><svg
                    class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg></button>
        </div>
        <div class="p-8">
            @if (isset($upgradePackages) && $upgradePackages->isEmpty())
                <p class="text-center text-slate-500">Anda sudah menggunakan paket tertinggi.</p>
            @elseif(isset($upgradePackages))
                <div class="space-y-4">
                    @foreach ($upgradePackages as $pkg)
                        @php $selisihHarga = $pkg->price - $currentPackagePrice; @endphp
                        <div class="border-2 border-slate-100 rounded-3xl p-5 flex items-center justify-between">
                            <div>
                                <h5 class="font-extrabold text-lg">{{ $pkg->name }}</h5>
                                <p class="text-2xl font-extrabold text-rOrange">Rp
                                    {{ number_format($selisihHarga, 0, ',', '.') }}</p>
                            </div>
                            <button type="button" class="px-6 py-2 bg-slate-900 text-white rounded-xl">Pilih</button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
