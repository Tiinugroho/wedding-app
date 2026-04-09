<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                </svg>
            </div>
            Cerita Cinta
        </h4>
        <div class="flex items-center gap-4">
            <button type="button" onclick="addLoveStoryRow()"
                class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition shadow-sm">+
                Tambah</button>
            <label class="inline-flex items-center cursor-pointer shrink-0">
                <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                <input type="checkbox" name="is_story_active" value="1" class="sr-only peer"
                    {{ $content['is_story_active'] ?? true ? 'checked' : '' }}>
                <div
                    class="relative w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-rOrange peer-focus:ring-4 peer-focus:ring-orange-100 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
                </div>
            </label>
        </div>
    </div>

    @if (empty($packageLogic['has_love_story']) || $packageLogic['has_love_story'] == false)
        <div
            class="absolute inset-0 z-10 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 mt-16">
            <h5 class="font-bold text-slate-800 mb-1">Fitur Terkunci di Paket {{ $currentPackageName }}
            </h5>
            <button type="button"
                class="btn-open-upgrade bg-rOrange text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg">Upgrade
                Paket</button>
        </div>
    @endif

    <div id="love-story-wrapper"
        class="space-y-6 transition-opacity duration-300 {{ empty($packageLogic['has_love_story']) || !$packageLogic['has_love_story'] || !($content['is_story_active'] ?? true) ? 'opacity-30 pointer-events-none' : '' }}">
        @if (!empty($content['love_stories']) && is_array($content['love_stories']))
            @foreach ($content['love_stories'] as $key => $story)
                <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item">
                    <button type="button" onclick="this.closest('.love-story-item').remove()"
                        class="absolute top-5 right-5 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    <input type="hidden" name="love_stories[{{ $key }}][old_image]"
                        value="{{ $story['image'] ?? '' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label><input
                                type="text" name="love_stories[{{ $key }}][year]"
                                value="{{ $story['year'] ?? '' }}"
                                class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Contoh: Januari 2021"></div>
                        <div class="pr-12 md:pr-0"><label class="block text-xs font-bold text-slate-600 mb-1">Judul
                                Momen</label><input type="text" name="love_stories[{{ $key }}][title]"
                                value="{{ $story['title'] ?? '' }}"
                                class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Contoh: Awal Bertemu"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi
                                Cerita</label>
                            <textarea name="love_stories[{{ $key }}][description]" rows="3"
                                class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Ceritakan momen tersebut...">{{ $story['description'] ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-2">Foto Momen
                                (Opsional)
                            </label>
                            <div class="flex items-center gap-4">
                                @if (!empty($story['image']))
                                    <img src="{{ asset('storage/' . $story['image']) }}"
                                        class="w-14 h-14 rounded-xl object-cover shadow-sm border border-slate-200">
                                @else
                                    <div
                                        class="w-14 h-14 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="love_stories[{{ $key }}][image]" accept="image/*"
                                    class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label><input
                            type="text" name="love_stories[0][year]"
                            class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                            placeholder="Contoh: Januari 2021"></div>
                    <div class="pr-12 md:pr-0"><label class="block text-xs font-bold text-slate-600 mb-1">Judul
                            Momen</label><input type="text" name="love_stories[0][title]"
                            class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                            placeholder="Contoh: Awal Bertemu"></div>
                    <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi
                            Cerita</label>
                        <textarea name="love_stories[0][description]" rows="3"
                            class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                            placeholder="Ceritakan momen tersebut..."></textarea>
                    </div>
                    <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-2">Foto Momen
                            (Opsional)</label><input type="file" name="love_stories[0][image]" accept="image/*"
                            class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer">
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
