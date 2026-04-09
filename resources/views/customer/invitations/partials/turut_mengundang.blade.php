<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            Turut Mengundang
        </h4>
        <label class="inline-flex items-center cursor-pointer shrink-0 group">
            <span
                class="mr-3 text-sm font-bold text-slate-500 group-hover:text-slate-700 transition-colors">Tampilkan</span>
            <input type="checkbox" name="is_turut_mengundang_active" value="1" class="sr-only peer"
                {{ $content['is_turut_mengundang_active'] ?? true ? 'checked' : '' }}>
            <div
                class="relative w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-rOrange peer-focus:ring-4 peer-focus:ring-orange-100 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
            </div>
        </label>
    </div>

    <div
        class="transition-opacity duration-300 {{ !($content['is_turut_mengundang_active'] ?? true) ? 'opacity-40 pointer-events-none' : '' }}">
        @php
            $tmStr = is_array($content['turut_mengundang'] ?? null)
                ? implode("\n", $content['turut_mengundang'])
                : $content['turut_mengundang'] ?? '';
        @endphp
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Daftar Turut Mengundang</label>
                <textarea name="turut_mengundang" rows="5"
                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                    placeholder="Contoh: Keluarga Besar Montague, Keluarga Besar Capulet, Sahabat Romeo & Juliet">{{ old('turut_mengundang', $tmStr) }}</textarea>
                <p class="text-[10px] text-slate-400 mt-1">Gunakan 'Enter' (baris baru) atau Koma ( , )
                    untuk memisahkan nama/keluarga.</p>
            </div>
        </div>
    </div>
</div>
