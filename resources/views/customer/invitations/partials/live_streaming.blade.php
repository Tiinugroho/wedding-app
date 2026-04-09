<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            Live Streaming Acara
        </h4>
    </div>

    @if (isset($packageLogic['has_live_stream']) && $packageLogic['has_live_stream'] == true)
        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
            <label class="flex items-center gap-3 cursor-pointer mb-5">
                <input type="checkbox" name="is_livestream_active" value="1"
                    {{ !empty($content['is_livestream_active']) ? 'checked' : '' }}
                    class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                <div>
                    <span class="font-bold text-slate-700">Aktifkan Tombol Live Stream</span>
                    <p class="text-xs text-slate-500 mt-1">Tamu dapat mengklik tombol pada undangan untuk
                        bergabung ke siaran langsung acara Anda.</p>
                </div>
            </label>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Platform</label>
                    <select name="live_stream_platform"
                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer">
                        <option value="youtube"
                            {{ ($content['live_stream_platform'] ?? '') == 'youtube' ? 'selected' : '' }}>
                            YouTube</option>
                        <option value="zoom"
                            {{ ($content['live_stream_platform'] ?? '') == 'zoom' ? 'selected' : '' }}>Zoom
                        </option>
                        <option value="tiktok"
                            {{ ($content['live_stream_platform'] ?? '') == 'tiktok' ? 'selected' : '' }}>
                            TikTok</option>
                        <option value="instagram"
                            {{ ($content['live_stream_platform'] ?? '') == 'instagram' ? 'selected' : '' }}>
                            Instagram</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Link / URL Streaming</label>
                    <input type="url" name="live_stream_link" value="{{ $content['live_stream_link'] ?? '' }}"
                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                        placeholder="https://...">
                </div>
            </div>
        </div>
    @else
        <div
            class="absolute inset-0 z-10 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 mt-16">
            <h5 class="font-bold text-slate-800 mb-1">Fitur Terkunci di Paket {{ $currentPackageName }}
            </h5>
            <p class="text-sm text-slate-500 mb-4">Fitur Live Streaming hanya tersedia untuk Paket Premium.
            </p>
            <button type="button"
                class="btn-open-upgrade bg-rOrange text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg">Upgrade
                Paket</button>
        </div>
        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50 opacity-30 pointer-events-none">
            <div class="h-6 w-48 bg-slate-200 rounded mb-4"></div>
            <div class="h-10 w-full bg-slate-200 rounded mb-2"></div>
        </div>
    @endif
</div>
