<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002-2h10a2 2 0 002-2v-7">
                    </path>
                </svg>
            </div>
            Kirim Kado & Amplop Digital
        </h4>
        <div class="flex items-center gap-4">
            <label class="inline-flex items-center cursor-pointer shrink-0">
                <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                <input type="checkbox" name="is_gift_active" value="1" class="sr-only peer"
                    {{ $content['is_gift_active'] ?? true ? 'checked' : '' }}>
                <div
                    class="relative w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-rOrange peer-focus:ring-4 peer-focus:ring-orange-100 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
                </div>
            </label>
        </div>
    </div>

    @if (empty($packageLogic['has_digital_gift']) || $packageLogic['has_digital_gift'] == false)
        <div
            class="absolute inset-0 z-10 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 mt-16">
            <h5 class="font-bold text-slate-800 mb-1">Fitur Eksklusif</h5>
            <button type="button"
                class="btn-open-upgrade bg-rOrange text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg">Upgrade
                Paket</button>
        </div>
    @endif

    <div
        class="transition-opacity duration-300 {{ empty($packageLogic['has_digital_gift']) || !$packageLogic['has_digital_gift'] || !($content['is_gift_active'] ?? true) ? 'opacity-30 pointer-events-none' : '' }}">

        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-sm font-bold text-slate-800">Rekening / E-Wallet (Amplop Digital)</h5>
                <button type="button" onclick="addBankRow()"
                    class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition shadow-sm">+
                    Rekening</button>
            </div>

            <div id="bank-wrapper" class="space-y-4">
                @if (!empty($content['banks']) && is_array($content['banks']))
                    @foreach ($content['banks'] as $key => $bank)
                        <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 bank-item">
                            <button type="button" onclick="this.closest('.bank-item').remove()"
                                class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Pilih Bank /
                                        E-Wallet</label>
                                    <select name="banks[{{ $key }}][name]"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer">
                                        <option value="">-- Pilih Pembayaran --</option>
                                        @foreach ($masterBanks as $mb)
                                            <option value="{{ $mb->name }}"
                                                {{ ($bank['name'] ?? '') == $mb->name ? 'selected' : '' }}>
                                                {{ $mb->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Atas Nama</label>
                                    <input type="text" name="banks[{{ $key }}][account_name]"
                                        value="{{ $bank['account_name'] ?? '' }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Nama Pemilik Rekening">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Nomor Rekening / No
                                        HP</label>
                                    <input type="text" name="banks[{{ $key }}][account_number]"
                                        value="{{ $bank['account_number'] ?? '' }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: 1234567890">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 bank-item">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Pilih Bank / E-Wallet</label>
                                <select name="banks[0][name]"
                                    class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer">
                                    <option value="">-- Pilih Pembayaran --</option>
                                    @foreach ($masterBanks as $mb)
                                        <option value="{{ $mb->name }}">{{ $mb->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Atas Nama</label>
                                <input type="text" name="banks[0][account_name]"
                                    class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                    placeholder="Nama Pemilik Rekening">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-600 mb-1">Nomor Rekening / No
                                    HP</label>
                                <input type="text" name="banks[0][account_number]"
                                    class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                    placeholder="Contoh: 1234567890">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t border-slate-100 pt-8">
            <h5 class="text-sm font-bold text-slate-800 mb-4">Pengiriman Kado Fisik</h5>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-600 mb-1">Alamat Pengiriman Lengkap</label>
                <textarea name="alamat_kado" rows="3"
                    class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                    placeholder="Contoh: Jl. Merdeka No. 123...">{{ $content['alamat_kado'] ?? '' }}</textarea>
                <p class="text-[10px] text-slate-400 mt-1">Alamat ini akan ditampilkan di undangan agar tamu tahu ke
                    mana kado harus dikirim.</p>
            </div>

            <div class="flex items-center justify-between mb-4 mt-6">
                <h5 class="text-sm font-bold text-slate-800">Daftar Keinginan Kado (Gift Registry)</h5>
                <button type="button" onclick="addGiftRow()"
                    class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition shadow-sm">+
                    Kado</button>
            </div>

            <div id="gift-wrapper" class="space-y-4">
                @if (!empty($content['gifts']) && is_array($content['gifts']))
                    @foreach ($content['gifts'] as $key => $gift)
                        <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 gift-item">
                            <button type="button" onclick="this.closest('.gift-item').remove()"
                                class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Barang /
                                        Kado</label>
                                    <input type="text" name="gifts[{{ $key }}][item_name]"
                                        value="{{ $gift['item_name'] ?? '' }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: Air Fryer Digital">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Tambahan
                                        (Opsional)</label>
                                    <input type="text" name="gifts[{{ $key }}][description]"
                                        value="{{ $gift['description'] ?? '' }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: Warna Hitam / Kapasitas 4L">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-xs text-slate-400 italic text-center py-4 empty-gift-text">Belum ada daftar kado
                        yang ditambahkan.</p>
                @endif
            </div>
        </div>

    </div>
</div>
