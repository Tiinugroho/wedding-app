<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            Galeri Foto & Video
        </h4>
        <label class="inline-flex items-center cursor-pointer shrink-0">
            <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
            <input type="checkbox" name="is_gallery_active" value="1" class="sr-only peer"
                {{ $content['is_gallery_active'] ?? true ? 'checked' : '' }}>
            <div
                class="relative w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-rOrange peer-focus:ring-4 peer-focus:ring-orange-100 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
            </div>
        </label>
    </div>

    <div
        class="transition-opacity duration-300 {{ !($content['is_gallery_active'] ?? true) ? 'opacity-40 pointer-events-none' : '' }}">
        <p class="font-bold text-slate-700 text-sm mb-3">Foto Album
            (<span id="current-gallery-count">{{ $invitation->galleries->where('type', 'photo')->count() }}</span> /
            {{ $packageLogic['gallery_limit'] ?? 5 }})</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach ($invitation->galleries->where('type', 'photo') as $img)
                <div id="gallery-item-{{ $img->id }}"
                    class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-100">
                    <img src="{{ asset('storage/' . $img->file_path) }}" class="w-full h-full object-cover">
                    <div
                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                        <button type="button"
                            onclick="editExistingPhoto('{{ $img->id }}', '{{ asset('storage/' . $img->file_path) }}')"
                            class="bg-white text-amber-500 p-2 rounded-full hover:bg-amber-500 hover:text-white transition shadow-lg"
                            title="Edit/Crop Foto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                        </button>

                        <button type="button" onclick="handleDeletePhoto('{{ $img->id }}')"
                            class="bg-white text-red-500 p-2 rounded-full hover:bg-red-500 hover:text-white transition shadow-lg"
                            title="Hapus Foto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

            <div id="preview-container" class="contents"></div>

            @if ($invitation->galleries->where('type', 'photo')->count() < ($packageLogic['gallery_limit'] ?? 5))
                <div class="relative aspect-square" id="add-photo-btn-container">
                    <input type="file" id="gallery-input" accept="image/*" class="hidden"
                        onchange="openCropper(this)">
                    <button type="button" onclick="document.getElementById('gallery-input').click()"
                        class="w-full h-full border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center hover:border-rOrange hover:bg-orange-50 transition cursor-pointer">
                        <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span class="text-xs font-bold text-slate-500">Pilih Foto</span>
                    </button>
                </div>
            @endif
        </div>

        <div class="border-t border-slate-100 pt-6">
            <div class="flex items-center justify-between mb-4">
                <p class="font-bold text-slate-700 text-sm">Video YouTube (Opsional)</p>
                <button type="button" onclick="addYoutubeRow()"
                    class="text-xs font-bold text-rOrange hover:text-orange-700 bg-orange-50 px-3 py-1.5 rounded-lg transition">+
                    Tambah Video</button>
            </div>
            <div id="youtube-wrapper" class="space-y-3">
                @php $ytLinks = !empty($content['youtube_links']) ? $content['youtube_links'] : ['']; @endphp
                @foreach ($ytLinks as $index => $yt)
                    <div class="flex items-center gap-2 youtube-item">
                        <div
                            class="flex-1 flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:ring-1 focus-within:ring-rOrange focus-within:border-rOrange transition">
                            <span class="pl-4 pr-2 text-slate-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z">
                                    </path>
                                </svg>
                            </span>
                            <input type="url" name="youtube_links[]" value="{{ $yt }}"
                                class="w-full py-3 px-2 bg-transparent border-0 focus:ring-0 text-sm"
                                placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <button type="button" onclick="this.closest('.youtube-item').remove()"
                            class="w-12 h-12 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
