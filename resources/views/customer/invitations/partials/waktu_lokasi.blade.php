<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            Waktu & Lokasi Acara
        </h4>
        <div class="flex items-center gap-4">
            <button type="button" onclick="addEventRow()"
                class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition shadow-sm">+
                Tambah Resepsi</button>
            <label class="inline-flex items-center cursor-pointer shrink-0">
                <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                <input type="checkbox" name="is_event_active" value="1" class="sr-only peer"
                    {{ $content['is_event_active'] ?? true ? 'checked' : '' }}>
                <div
                    class="relative w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-rOrange peer-focus:ring-4 peer-focus:ring-orange-100 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
                </div>
            </label>
        </div>
    </div>

    <div class="space-y-6 transition-opacity duration-300 {{ !($content['is_event_active'] ?? true) ? 'opacity-40 pointer-events-none' : '' }}">
        {{-- AKAD NIKAH (STATIS) --}}
        <div class="p-5 bg-orange-50/50 rounded-2xl border border-orange-100 relative">
            <h5 class="font-bold text-orange-600 mb-4">Akad Nikah / Pemberkatan</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label>
                    <input type="text" name="akad_date" value="{{ old('akad_date', $content['akad_date'] ?? '') }}"
                        class="w-full py-2 px-3 sm:py-2.5 sm:px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer text-sm sm:text-base init-date"
                        placeholder="Pilih Tanggal...">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Waktu Acara (Mulai - Selesai)</label>
                    <div class="flex items-center gap-3">
                        <div class="w-24 sm:w-32 relative">
                            @php $safeAkadTime = !empty($content['akad_time']) ? substr(trim($content['akad_time']), 0, 5) : ''; @endphp
                            <input type="text" name="akad_time" value="{{ old('akad_time', $safeAkadTime) }}"
                                class="w-full py-2 px-3 sm:py-2.5 sm:px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange text-sm sm:text-base text-center cursor-pointer init-time"
                                placeholder="Waktu Mulai">
                        </div>
                        <span class="text-slate-400 font-bold shrink-0">-</span>
                        <div class="w-24 sm:w-32 relative">
                            @php $safeAkadTimeEnd = !empty($content['akad_time_end']) ? substr(trim($content['akad_time_end']), 0, 5) : ''; @endphp
                            <input type="text" name="akad_time_end" value="{{ old('akad_time_end', $safeAkadTimeEnd) }}"
                                class="w-full py-2 px-3 sm:py-2.5 sm:px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange text-sm sm:text-base text-center cursor-pointer init-time"
                                placeholder="Waktu Selesai">
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Nama Tempat/Gedung</label>
                    <input type="text" name="akad_location"
                        value="{{ old('akad_location', $content['akad_location'] ?? '') }}"
                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                        placeholder="Contoh: Masjid Raya Pekanbaru">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Alamat Lengkap</label>
                    <textarea name="akad_address" rows="2"
                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                        placeholder="Contoh: Jl. Senapelan No. 128, Riau">{{ old('akad_address', $content['akad_address'] ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Link Google Maps</label>
                    <input type="url" name="akad_map" value="{{ old('akad_map', $content['akad_map'] ?? '') }}"
                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                        placeholder="https://maps.google.com/...">
                </div>
            </div>
        </div>

        {{-- RESEPSI DINAMIS --}}
        <div id="event-wrapper" class="space-y-6">
            @php
                $eventsData = !empty($content['events']) && is_array($content['events']) ? $content['events'] : [];
                if (empty($eventsData)) {
                    $eventsData[] = [
                        'title' => '', // Dikosongkan agar placeholder HTML berfungsi
                        'date' => '',
                        'time' => '',
                        'time_end' => '',
                        'location' => '',
                        'address' => '',
                        'map' => '',
                    ];
                }
            @endphp

            @foreach ($eventsData as $key => $event)
                @php
                    $safeEventTime = !empty($event['time']) ? substr(trim($event['time']), 0, 5) : '';
                    $safeEventTimeEnd = !empty($event['time_end']) ? substr(trim($event['time_end']), 0, 5) : '';
                @endphp
                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 relative event-item">
                    @if ($key > 0)
                        <button type="button" onclick="this.closest('.event-item').remove()"
                            class="absolute top-4 right-4 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg shadow-sm transition-colors">Hapus</button>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nama Acara</label>
                            <input type="text" name="events[{{ $key }}][title]"
                                value="{{ $event['title'] ?? '' }}"
                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Contoh: Resepsi Pernikahan / Unduh Mantu">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label>
                            <input type="text" name="events[{{ $key }}][date]"
                                value="{{ $event['date'] ?? '' }}"
                                class="w-full py-2 px-3 sm:py-2.5 sm:px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer text-sm sm:text-base init-date"
                                placeholder="Pilih Tanggal...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Waktu Acara</label>
                            <div class="flex items-center gap-3">
                                <div class="w-24 sm:w-32 relative">
                                    <input type="text" name="events[{{ $key }}][time]"
                                        value="{{ $safeEventTime }}"
                                        class="w-full py-2 px-3 sm:py-2.5 sm:px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange text-sm sm:text-base text-center cursor-pointer init-time"
                                        placeholder="Waktu Mulai">
                                </div>
                                <span class="text-slate-400 font-bold shrink-0">-</span>
                                <div class="w-24 sm:w-32 relative">
                                    <input type="text" name="events[{{ $key }}][time_end]"
                                        value="{{ $safeEventTimeEnd }}"
                                        class="w-full py-2 px-3 sm:py-2.5 sm:px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange text-sm sm:text-base text-center cursor-pointer init-time"
                                        placeholder="Waktu Selesai">
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nama
                                Tempat/Gedung</label>
                            <input type="text" name="events[{{ $key }}][location]"
                                value="{{ $event['location'] ?? '' }}"
                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Contoh: Grand Ballroom Hotel">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Alamat
                                Lengkap</label>
                            <textarea name="events[{{ $key }}][address]" rows="2"
                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Contoh: Pekanbaru, Riau">{{ $event['address'] ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Link Google Maps
                                (Opsional)
                            </label>
                            <input type="url" name="events[{{ $key }}][map]"
                                value="{{ $event['map'] ?? '' }}"
                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="https://maps.google.com/...">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>