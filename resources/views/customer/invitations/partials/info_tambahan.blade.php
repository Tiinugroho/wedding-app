<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            Info Tambahan, Prokes & Adab
        </h4>
        <label class="inline-flex items-center cursor-pointer shrink-0">
            <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
            <input type="checkbox" name="is_guest_info_active" value="1" class="sr-only peer"
                {{ $content['is_guest_info_active'] ?? true ? 'checked' : '' }}>
            <div
                class="relative w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-rOrange peer-focus:ring-4 peer-focus:ring-orange-100 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
            </div>
        </label>
    </div>

    <div
        class="space-y-6 transition-opacity duration-300 {{ !($content['is_guest_info_active'] ?? true) ? 'opacity-40 pointer-events-none' : '' }}">
        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
            <label class="flex items-center gap-3 cursor-pointer mb-3">
                <input type="checkbox" name="enable_dresscode" value="1"
                    {{ $content['enable_dresscode'] ?? true ? 'checked' : '' }}
                    class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                <span class="font-bold text-slate-700">Aktifkan Informasi Dresscode</span>
            </label>
            <input type="text" name="dresscode" value="{{ old('dresscode', $content['dresscode'] ?? '') }}"
                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                placeholder="Contoh: Formal / Batik Modern (Earth Tone)">
        </div>

        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
            <label class="flex items-center gap-3 cursor-pointer mb-4">
                <input type="checkbox" name="enable_health_protocol" value="1"
                    {{ !empty($content['enable_health_protocol']) ? 'checked' : '' }}
                    class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                <span class="font-bold text-slate-700">Tampilkan Protokol Kesehatan</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="enable_adab_walimah" value="1"
                    {{ !empty($content['enable_adab_walimah']) ? 'checked' : '' }}
                    class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                <div>
                    <span class="font-bold text-slate-700">Tampilkan Adab Menghadiri Walimah</span>
                    <p class="text-xs text-slate-500 mt-1">Anjuran mendoakan pengantin, makan/minum sambil
                        duduk, dan berpakaian sopan.</p>
                </div>
            </label>
        </div>
    </div>
</div>
