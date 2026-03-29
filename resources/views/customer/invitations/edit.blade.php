@extends('customer.partials.app')
@section('title', 'Kelola Isi Undangan')

@section('content')
    <header class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.invitations.index') }}"
                class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800">Kelola Isi Undangan</h2>
                <p class="text-slate-400 mt-1">Lengkapi data mempelai dan acara untuk <span
                        class="text-rOrange font-semibold">ruangrestu.com/{{ $invitation->slug }}</span></p>
            </div>
        </div>

        <a href="{{ url('/' . $invitation->slug) }}" target="_blank" id="btn-live-preview"
            class="hidden md:flex items-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                </path>
            </svg>
            Lihat Live Preview
        </a>
    </header>

    @if (session('success'))
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 font-medium shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <strong>Gagal Menyimpan! Tolong periksa isian Anda:</strong>
            </div>
            <ul class="list-disc list-inside ml-9 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.invitations.update', $invitation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            <div class="xl:col-span-8 space-y-8">

                {{-- DATA MEMPELAI PRIA --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        Data Mempelai Pria
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="groom_name"
                                value="{{ old('groom_name', $content['groom_name'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Lengkap Pria">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Panggilan</label>
                            <input type="text" name="groom_nickname"
                                value="{{ old('groom_nickname', $content['groom_nickname'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Panggilan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Orang Tua</label>
                            <input type="text" name="groom_parents"
                                value="{{ old('groom_parents', $content['groom_parents'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Putra dari...">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Link Instagram (Opsional)</label>
                            <input type="url" name="groom_ig" value="{{ old('groom_ig', $content['groom_ig'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="https://instagram.com/username">
                        </div>
                        <div class="md:col-span-2 mt-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Foto Mempelai Pria</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ !empty($content['groom_photo']) ? asset('storage/' . $content['groom_photo']) : 'https://ui-avatars.com/api/?name=Pria' }}"
                                    class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                                <input type="file" name="groom_photo" accept="image/*"
                                    class="w-full py-2 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DATA MEMPELAI WANITA --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        Data Mempelai Wanita
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="bride_name"
                                value="{{ old('bride_name', $content['bride_name'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Lengkap Wanita">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Panggilan</label>
                            <input type="text" name="bride_nickname"
                                value="{{ old('bride_nickname', $content['bride_nickname'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Panggilan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Orang Tua</label>
                            <input type="text" name="bride_parents"
                                value="{{ old('bride_parents', $content['bride_parents'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Putri dari...">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Link Instagram (Opsional)</label>
                            <input type="url" name="bride_ig"
                                value="{{ old('bride_ig', $content['bride_ig'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="https://instagram.com/username">
                        </div>
                        <div class="md:col-span-2 mt-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Foto Mempelai Wanita</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ !empty($content['bride_photo']) ? asset('storage/' . $content['bride_photo']) : 'https://ui-avatars.com/api/?name=Wanita' }}"
                                    class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                                <input type="file" name="bride_photo" accept="image/*"
                                    class="w-full py-2 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TURUT MENGUNDANG (DIPISAH) --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        Turut Mengundang
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Keluarga Pihak Pria
                                (Opsional)</label>
                            <textarea name="turut_mengundang_groom" rows="4"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Contoh:&#10;Keluarga Besar Bpk. Kakek&#10;Paman & Bibi">{{ old('turut_mengundang_groom', $content['turut_mengundang_groom'] ?? '') }}</textarea>
                            <p class="text-[10px] text-slate-400 mt-1">Gunakan 'Enter' untuk baris baru.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Keluarga Pihak Wanita
                                (Opsional)</label>
                            <textarea name="turut_mengundang_bride" rows="4"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Contoh:&#10;Keluarga Besar Bpk. Kakek&#10;Paman & Bibi">{{ old('turut_mengundang_bride', $content['turut_mengundang_bride'] ?? '') }}</textarea>
                            <p class="text-[10px] text-slate-400 mt-1">Gunakan 'Enter' untuk baris baru.</p>
                        </div>
                    </div>
                </div>

                {{-- WAKTU & LOKASI ACARA --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        Waktu & Lokasi Acara
                    </h4>

                    <div class="space-y-6">
                        {{-- Form Akad --}}
                        <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100">
                            <h5 class="font-bold text-orange-600 mb-4">Akad Nikah / Pemberkatan</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label><input
                                        type="date" name="akad_date"
                                        value="{{ old('akad_date', $content['akad_date'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"></div>
                                <div><label class="block text-xs font-bold text-slate-600 mb-1">Waktu / Jam</label><input
                                        type="text" name="akad_time"
                                        value="{{ old('akad_time', $content['akad_time'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: 08:00 - 10:00 WIB"></div>
                                <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nama
                                        Tempat/Gedung</label><input type="text" name="akad_location"
                                        value="{{ old('akad_location', $content['akad_location'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: Masjid Raya Pekanbaru"></div>
                                <div class="md:col-span-2"><label
                                        class="block text-xs font-bold text-slate-600 mb-1">Alamat Lengkap</label>
                                    <textarea name="akad_address" rows="2" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: Jl. Senapelan No. 128, Riau">{{ old('akad_address', $content['akad_address'] ?? '') }}</textarea>
                                </div>
                                <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Link
                                        Google Maps</label><input type="url" name="akad_map"
                                        value="{{ old('akad_map', $content['akad_map'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="https://maps.google.com/..."></div>
                            </div>
                        </div>

                        {{-- Form Resepsi --}}
                        <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                            <h5 class="font-bold text-blue-600 mb-4">Resepsi Pernikahan</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label><input
                                        type="date" name="resepsi_date"
                                        value="{{ old('resepsi_date', $content['resepsi_date'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"></div>
                                <div><label class="block text-xs font-bold text-slate-600 mb-1">Waktu / Jam</label><input
                                        type="text" name="resepsi_time"
                                        value="{{ old('resepsi_time', $content['resepsi_time'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: 11:00 - 16:00 WIB"></div>
                                <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nama
                                        Tempat/Gedung</label><input type="text" name="resepsi_location"
                                        value="{{ old('resepsi_location', $content['resepsi_location'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="Contoh: Grand Ballroom Hotel"></div>
                                <div class="md:col-span-2"><label
                                        class="block text-xs font-bold text-slate-600 mb-1">Alamat Lengkap</label>
                                    <textarea name="resepsi_address" rows="2"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl" placeholder="Contoh: Pekanbaru, Riau">{{ old('resepsi_address', $content['resepsi_address'] ?? '') }}</textarea>
                                </div>
                                <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Link
                                        Google Maps</label><input type="url" name="resepsi_map"
                                        value="{{ old('resepsi_map', $content['resepsi_map'] ?? '') }}"
                                        class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                        placeholder="https://maps.google.com/..."></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INFO TAMBAHAN (DRESSCODE & PROKES) --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Guest Info & Prokes
                    </h4>
                    <div class="space-y-6">
                        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
                            <label class="flex items-center gap-3 cursor-pointer mb-3">
                                <input type="checkbox" name="enable_dresscode" value="1"
                                    {{ !empty($content['enable_dresscode']) ? 'checked' : '' }}
                                    class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                                <span class="font-bold text-slate-700">Aktifkan Informasi Dresscode</span>
                            </label>
                            <input type="text" name="dresscode"
                                value="{{ old('dresscode', $content['dresscode'] ?? '') }}"
                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                placeholder="Contoh: Formal / Batik Modern (Earth Tone)">
                        </div>

                        <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="enable_health_protocol" value="1"
                                    {{ !empty($content['enable_health_protocol']) ? 'checked' : '' }}
                                    class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                                <span class="font-bold text-slate-700">Aktifkan Tampilan Protokol Kesehatan</span>
                            </label>
                            <p class="text-xs text-slate-500 ml-8 mt-1">Jika dicentang, section himbauan memakai masker,
                                cuci tangan, dll akan ditampilkan di undangan.</p>
                        </div>
                    </div>
                </div>

                @include('customer.invitations.partials.music_selector')

                {{-- LOVE STORY (DINAMIS) --}}
                {{-- LOVE STORY (DINAMIS) --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                            Cerita Cinta
                        </h4>
                        <button type="button" onclick="addLoveStoryRow()" class="px-5 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition flex items-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Cerita
                        </button>
                    </div>

                    @if (empty($packageLogic['has_love_story']) || $packageLogic['has_love_story'] == false)
                        <div class="absolute inset-0 z-10 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 mt-16">
                            <h5 class="font-bold text-slate-800 mb-1">Fitur Terkunci di Paket {{ $currentPackageName }}</h5>
                            <button type="button" class="btn-open-upgrade bg-rOrange text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg">Upgrade Paket</button>
                        </div>
                    @endif

                    <div id="love-story-wrapper" class="space-y-6 {{ empty($packageLogic['has_love_story']) || !$packageLogic['has_love_story'] ? 'opacity-30 pointer-events-none' : '' }}">
                        @if (!empty($content['love_stories']) && is_array($content['love_stories']))
                            @foreach ($content['love_stories'] as $key => $story)
                                <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item">
                                    <button type="button" onclick="this.closest('.love-story-item').remove()" class="absolute top-5 right-5 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                                    
                                    {{-- Simpan path gambar lama --}}
                                    <input type="hidden" name="love_stories[{{ $key }}][old_image]" value="{{ $story['image'] ?? '' }}">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label>
                                            <input type="text" name="love_stories[{{ $key }}][year]" value="{{ $story['year'] ?? '' }}" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Januari 2021">
                                        </div>
                                        <div class="pr-12 md:pr-0">
                                            <label class="block text-xs font-bold text-slate-600 mb-1">Judul Momen</label>
                                            <input type="text" name="love_stories[{{ $key }}][title]" value="{{ $story['title'] ?? '' }}" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Awal Bertemu">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Cerita</label>
                                            <textarea name="love_stories[{{ $key }}][description]" rows="3" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Ceritakan momen tersebut...">{{ $story['description'] ?? '' }}</textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-600 mb-2">Foto Momen (Opsional)</label>
                                            <div class="flex items-center gap-4">
                                                @if (!empty($story['image']))
                                                    <img src="{{ asset('storage/' . $story['image']) }}" class="w-14 h-14 rounded-xl object-cover shadow-sm border border-slate-200">
                                                @else
                                                    <div class="w-14 h-14 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                                <input type="file" name="love_stories[{{ $key }}][image]" accept="image/*" class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Baris Kosong Default --}}
                            <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item">
                                <button type="button" onclick="this.closest('.love-story-item').remove()" class="absolute top-5 right-5 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                                <input type="hidden" name="love_stories[0][old_image]" value="">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label><input type="text" name="love_stories[0][year]" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Januari 2021"></div>
                                    <div class="pr-12 md:pr-0"><label class="block text-xs font-bold text-slate-600 mb-1">Judul Momen</label><input type="text" name="love_stories[0][title]" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Awal Bertemu"></div>
                                    <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Cerita</label><textarea name="love_stories[0][description]" rows="3" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Ceritakan momen tersebut..."></textarea></div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-600 mb-2">Foto Momen (Opsional)</label>
                                        <input type="file" name="love_stories[0][image]" accept="image/*" class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KADO DIGITAL (DINAMIS) --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                    </path>
                                </svg>
                            </div>
                            Kado Digital / Amplop
                        </h4>
                        <button type="button" onclick="addBankRow()"
                            class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">+
                            Tambah Rekening</button>
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

                    <div id="bank-wrapper"
                        class="space-y-4 {{ empty($packageLogic['has_digital_gift']) || !$packageLogic['has_digital_gift'] ? 'opacity-30 pointer-events-none' : '' }}">
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
                                                        {{ $mb->name }}
                                                    </option>
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
                            {{-- JIKA KOSONG, TAMPILKAN 1 BARIS KOSONG --}}
                            <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 bank-item">
                                <button type="button" onclick="this.closest('.bank-item').remove()"
                                    class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Pilih Bank /
                                            E-Wallet</label>
                                        <select name="banks[0][name]"
                                            class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer">
                                            <option value="">-- Pilih Pembayaran --</option>
                                            @foreach ($masterBanks as $mb)
                                                <option value="{{ $mb->name }}">{{ $mb->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Atas Nama</label><input
                                            type="text" name="banks[0][account_name]"
                                            class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                            placeholder="Nama Pemilik Rekening"></div>
                                    <div class="md:col-span-2"><label
                                            class="block text-xs font-bold text-slate-600 mb-1">Nomor Rekening / No
                                            HP</label><input type="text" name="banks[0][account_number]"
                                            class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                            placeholder="Contoh: 1234567890"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- GALERI --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        Galeri Foto ({{ $invitation->galleries->where('type', 'photo')->count() }} /
                        {{ $packageLogic['gallery_limit'] ?? 5 }})
                    </h4>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach ($invitation->galleries->where('type', 'photo') as $img)
                            <div class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-100">
                                <img src="{{ asset('storage/' . $img->file_path) }}" class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button type="button" onclick="handleDeletePhoto('{{ $img->id }}')"
                                        class="bg-white text-red-500 p-2 rounded-full hover:bg-red-500 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        @if ($invitation->galleries->where('type', 'photo')->count() < ($packageLogic['gallery_limit'] ?? 5))
                            <div class="relative aspect-square">
                                <input type="file" name="gallery_files[]" id="gallery-input" multiple class="hidden"
                                    onchange="previewImages(this)">
                                <button type="button" onclick="document.getElementById('gallery-input').click()"
                                    class="w-full h-full border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center hover:border-rOrange hover:bg-orange-50 transition">
                                    <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-slate-500">Pilih Foto</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            <div class="xl:col-span-4">
                <div class="sticky top-10 bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-xl text-center">
                    <h4 class="font-extrabold text-2xl mb-2">Simpan Perubahan</h4>
                    <p class="text-sm text-slate-400 mb-8 leading-relaxed">Pastikan semua data sudah benar sebelum
                        disimpan.</p>
                    <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-rRed to-rOrange rounded-2xl font-bold text-white hover:scale-105 transition shadow-lg flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                        <span>Simpan Data Undangan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <form id="global-delete-photo-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- MODAL UPGRADE --}}
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
                    class="w-10 h-10 rounded-full bg-white border text-slate-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-8">
                @if ($upgradePackages->isEmpty())
                    <p class="text-center text-slate-500">Anda sudah menggunakan paket tertinggi.</p>
                @else
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
@endsection

@push('scripts')
    <script>
        // Fungsi Tambah Baris Cerita Cinta Dinamis
        // Fungsi Tambah Baris Cerita Cinta Dinamis
        function addLoveStoryRow() {
            const id = Date.now(); // Gunakan timestamp sebagai index unik
            const html = `
                <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item mt-4 transition-all duration-300">
                    <button type="button" onclick="this.closest('.love-story-item').remove()" class="absolute top-5 right-5 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                    
                    <input type="hidden" name="love_stories[${id}][old_image]" value="">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label>
                            <input type="text" name="love_stories[${id}][year]" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Januari 2021">
                        </div>
                        <div class="pr-12 md:pr-0">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Judul Momen</label>
                            <input type="text" name="love_stories[${id}][title]" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Awal Bertemu">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Cerita</label>
                            <textarea name="love_stories[${id}][description]" rows="3" class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Ceritakan momen tersebut..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-2">Foto Momen (Opsional)</label>
                            <input type="file" name="love_stories[${id}][image]" accept="image/*" class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer">
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('love-story-wrapper').insertAdjacentHTML('beforeend', html);
        }

        // Fungsi Tambah Baris Rekening Dinamis
        // Siapkan opsi bank dari PHP ke variabel JavaScript
        const bankOptions = `{!! $masterBanks->map(function ($b) {
                return '<option value="' . $b->name . '">' . $b->name . '</option>';
            })->implode('') !!}`;

        function addBankRow() {
            const id = Date.now();
            const html = `
                <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 bank-item animate-fade-in">
                    <button type="button" onclick="this.closest('.bank-item').remove()" class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Pilih Bank / E-Wallet</label>
                            <select name="banks[${id}][name]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer">
                                <option value="">-- Pilih Pembayaran --</option>
                                ${bankOptions}
                            </select>
                        </div>
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Atas Nama</label><input type="text" name="banks[${id}][account_name]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl" placeholder="Nama Pemilik Rekening"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nomor Rekening / No HP</label><input type="text" name="banks[${id}][account_number]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl" placeholder="Contoh: 1234567890"></div>
                    </div>
                </div>
            `;
            document.getElementById('bank-wrapper').insertAdjacentHTML('beforeend', html);
        }

        function previewImages(input) {
            const container = document.getElementById('preview-container');
            container.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className =
                            'relative aspect-square rounded-2xl overflow-hidden border-2 border-rOrange';
                        div.innerHTML =
                            `<img src="${e.target.result}" class="w-full h-full object-cover opacity-60"><div class="absolute inset-0 flex items-center justify-center"><span class="bg-rOrange text-white text-[10px] px-2 py-1 rounded-lg font-bold uppercase">Ready</span></div>`;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function handleDeletePhoto(photoId) {
            if (confirm('Hapus foto ini secara permanen?')) {
                const deleteForm = document.getElementById('global-delete-photo-form');
                deleteForm.action = `/customer/gallery/${photoId}`;
                deleteForm.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const upgradeModal = document.getElementById('upgradeModal');
            const openUpgradeBtns = document.querySelectorAll('.btn-open-upgrade');
            const closeUpgradeBtn = document.getElementById('closeUpgradeBtn');

            openUpgradeBtns.forEach(btn => btn.addEventListener('click', () => {
                upgradeModal.classList.remove('hidden');
                setTimeout(() => upgradeModal.classList.remove('opacity-0'), 20);
            }));

            closeUpgradeBtn.addEventListener('click', () => {
                upgradeModal.classList.add('opacity-0');
                setTimeout(() => upgradeModal.classList.add('hidden'), 300);
            });

            // LOGIKA LIVE PREVIEW SEBELUM SAVE
            const previewBtn = document.getElementById('btn-live-preview');
            const mainForm = document.querySelector('form[action*="invitations"]');

            if (previewBtn && mainForm) {
                previewBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const formData = new FormData(mainForm);
                    const params = new URLSearchParams();
                    for (const [key, value] of formData.entries()) {
                        if (typeof value === 'string' && value.trim() !== '') {
                            params.append(key, value);
                        }
                    }
                    const baseUrl = this.getAttribute('href').split('?')[0];
                    const previewUrl = baseUrl + '?' + params.toString();
                    window.open(previewUrl, '_blank');
                });
            }
        });

        // Tunggu sampai seluruh elemen HTML (DOM) selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {

            const rsvpForm = document.getElementById('rsvpForm');

            if (rsvpForm) {
                rsvpForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Mencegah halaman me-refresh dan mematikan musik

                    let form = this;
                    let btn = document.getElementById('btnSubmitRsvp');
                    let originalText = btn.innerHTML;

                    // 1. Ubah tombol menjadi status Loading
                    btn.innerHTML = 'MENGIRIM...';
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'cursor-not-allowed');

                    // 2. Ambil semua data form (otomatis mengambil @csrf dan guest_id)
                    let formData = new FormData(form);

                    // 3. Kirim data ke Controller menggunakan Fetch API (AJAX)
                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest', // Penanda bahwa ini adalah request AJAX
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                // Menangkap error dari server (misal: 422 Validasi Error atau 500 Server Error)
                                return response.json().then(errData => {
                                    throw errData;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // 4. Jika berhasil, tampilkan Modal Sukses
                                document.getElementById('rsvpModal').classList.remove('hidden');
                                form.reset(); // Kosongkan isian form
                            } else {
                                alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Menampilkan pesan error validasi (jika ada form yang kosong/tidak valid)
                            if (error.errors) {
                                let errorMessages = Object.values(error.errors).map(val => val.join(
                                    ', ')).join('\n');
                                alert("Gagal mengirim:\n" + errorMessages);
                            } else {
                                alert(
                                    'Koneksi terputus atau terjadi kesalahan server. Gagal mengirim pesan.');
                            }
                        })
                        .finally(() => {
                            // 5. Kembalikan tombol ke keadaan semula (baik berhasil maupun gagal)
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                            btn.classList.remove('opacity-70', 'cursor-not-allowed');
                        });
                });
            }
        });

        // Fungsi khusus untuk menutup Modal RSVP saat tombol "Tutup" diklik
        function closeRsvpModal() {
            const modal = document.getElementById('rsvpModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
@endpush
