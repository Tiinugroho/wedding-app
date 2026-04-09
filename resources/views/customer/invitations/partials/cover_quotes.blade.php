<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
    <h4 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
        </div>
        Pengaturan Halaman Depan (Cover)
    </h4>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="mb-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Siapa yang tampil duluan?</label>
            <div class="flex gap-4 mt-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="couple_order" value="groom_first"
                        class="w-4 h-4 text-rOrange focus:ring-rOrange"
                        {{ ($content['couple_order'] ?? 'groom_first') == 'groom_first' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-slate-700">Pria Dulu</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="couple_order" value="bride_first"
                        class="w-4 h-4 text-rOrange focus:ring-rOrange"
                        {{ ($content['couple_order'] ?? '') == 'bride_first' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-slate-700">Wanita Dulu</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Salam Pembuka Penerima</label>
            <input type="text" name="cover_greeting"
                value="{{ old('cover_greeting', $content['cover_greeting'] ?? 'Kepada Yth.') }}"
                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                placeholder="Misal: Kepada Yth. / Dear / To:">
        </div>
    </div>

    <div class="mt-6 border-t border-slate-100 pt-6">
        <label class="block text-sm font-bold text-slate-700 mb-2">Quotes / Kutipan Pembuka
            (Opsional)</label>
        <textarea name="quotes" rows="3"
            class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
            placeholder="Contoh: Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri...">{{ old('quotes', $content['quotes'] ?? '') }}</textarea>
    </div>

    <div class="mt-6 border-t border-slate-100 pt-6">
        <label class="block text-sm font-bold text-slate-700 mb-2">Foto Cover Undangan (Opsional)</label>
        <p class="text-xs text-slate-500 mb-3">Jika dikosongkan, sistem otomatis mengambil foto pertama dari
            Galeri.</p>
        <div class="flex items-center gap-4">
            @if (!empty($content['cover_image']))
                <img src="{{ asset('storage/' . $content['cover_image']) }}"
                    class="w-16 h-16 rounded-xl object-cover shadow-sm border border-slate-200">
            @endif
            <input type="file" name="cover_image" accept="image/*"
                class="w-full py-2 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm">
        </div>
    </div>
</div>
