@extends('customer.partials.app')
@section('title', 'Kelola Isi Undangan')

@section('content')
    <header class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.invitations.index') }}"
                class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 transition shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800">Kelola Isi Undangan</h2>
                <p class="text-slate-400 mt-1">Lengkapi data untuk <span
                        class="text-rOrange font-semibold">ruangrestu.com/{{ $invitation->slug }}</span></p>
            </div>
        </div>

        <a href="{{ url('/' . $invitation->slug) }}" target="_blank" id="btn-live-preview"
            class="flex items-center justify-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg shrink-0">
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
                <strong>Gagal Menyimpan! Periksa isian Anda:</strong>
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

                {{-- PENGATURAN COVER & QUOTES --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
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
                            placeholder="Contoh: Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri...">{{ old('quotes', $content['quotes'] ?? '') }}</textarea>
                    </div>
                </div>

                {{-- DATA MEMPELAI PRIA --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div
                            class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
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
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ayah</label>
                            <input type="text" name="groom_father"
                                value="{{ old('groom_father', $content['groom_father'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Bpk. Fulan">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ibu</label>
                            <input type="text" name="groom_mother"
                                value="{{ old('groom_mother', $content['groom_mother'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Ibu Fulanah">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Username Instagram
                                (Opsional)</label>
                            <div class="flex items-center">
                                <span
                                    class="bg-slate-100 border border-slate-200 border-r-0 text-slate-500 px-4 py-3 rounded-l-xl font-medium text-sm">instagram.com/</span>
                                <input type="text" name="groom_ig"
                                    value="{{ old('groom_ig', $content['groom_ig'] ?? '') }}"
                                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-r-xl focus:ring-rOrange"
                                    placeholder="username">
                            </div>
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
                        <div
                            class="w-10 h-10 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center shrink-0">
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
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ayah</label>
                            <input type="text" name="bride_father"
                                value="{{ old('bride_father', $content['bride_father'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Bpk. Fulan">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ibu</label>
                            <input type="text" name="bride_mother"
                                value="{{ old('bride_mother', $content['bride_mother'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Ibu Fulanah">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Username Instagram
                                (Opsional)</label>
                            <div class="flex items-center">
                                <span
                                    class="bg-slate-100 border border-slate-200 border-r-0 text-slate-500 px-4 py-3 rounded-l-xl font-medium text-sm">instagram.com/</span>
                                <input type="text" name="bride_ig"
                                    value="{{ old('bride_ig', $content['bride_ig'] ?? '') }}"
                                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-r-xl focus:ring-rOrange"
                                    placeholder="username">
                            </div>
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

                {{-- TURUT MENGUNDANG + TOGGLE --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            Turut Mengundang
                        </h4>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <span class="mr-3 text-sm font-bold text-slate-500">Tampilkan</span>
                            <input type="checkbox" name="is_turut_mengundang_active" value="1" class="sr-only peer"
                                {{ $content['is_turut_mengundang_active'] ?? false ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[75px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rOrange">
                            </div>
                        </label>
                    </div>

                    <div
                        class="transition-opacity duration-300 {{ empty($content['is_turut_mengundang_active']) ? 'opacity-40 pointer-events-none' : '' }}">
                        @php
                            $tmGroomStr = is_array($content['turut_mengundang_groom'] ?? null)
                                ? implode("\n", $content['turut_mengundang_groom'])
                                : $content['turut_mengundang_groom'] ?? '';
                            $tmBrideStr = is_array($content['turut_mengundang_bride'] ?? null)
                                ? implode("\n", $content['turut_mengundang_bride'])
                                : $content['turut_mengundang_bride'] ?? '';
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Keluarga Pihak Pria</label>
                                <textarea name="turut_mengundang_groom" rows="4"
                                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                    placeholder="Contoh: Keluarga Bapak A, Keluarga Bapak B">{{ old('turut_mengundang_groom', $tmGroomStr) }}</textarea>
                                <p class="text-[10px] text-slate-400 mt-1">Gunakan 'Enter' atau Koma ( , ) untuk baris
                                    baru.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Keluarga Pihak Wanita</label>
                                <textarea name="turut_mengundang_bride" rows="4"
                                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                    placeholder="Contoh: Keluarga Bapak X, Keluarga Ibu Y">{{ old('turut_mengundang_bride', $tmBrideStr) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WAKTU & LOKASI ACARA + TOGGLE --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
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
                                class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">+
                                Tambah Resepsi</button>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                                <input type="checkbox" name="is_event_active" value="1" class="sr-only peer"
                                    {{ $content['is_event_active'] ?? true ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] sm:after:left-[auto] sm:after:right-[24px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rOrange">
                                </div>
                            </label>
                        </div>
                    </div>

                    <div
                        class="space-y-6 transition-opacity duration-300 {{ empty($content['is_event_active']) && isset($content['is_event_active']) ? 'opacity-40 pointer-events-none' : '' }}">

                        {{-- 1. AKAD NIKAH (STATIS - TIDAK BISA DIHAPUS) --}}
                        <div class="p-5 bg-orange-50/50 rounded-2xl border border-orange-100 relative">
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

                        {{-- 2. RESEPSI DAN LAINNYA (DINAMIS - BISA DITAMBAH/HAPUS) --}}
                        <div id="event-wrapper" class="space-y-6">
                            @php
                                $eventsData =
                                    !empty($content['events']) && is_array($content['events'])
                                        ? $content['events']
                                        : [];

                                // Jika data lama masih format statis, kita migrasi khusus untuk Resepsi
                                if (empty($eventsData) && !empty($content['resepsi_location'])) {
                                    $eventsData[] = [
                                        'title' => 'Resepsi Pernikahan',
                                        'date' => $content['resepsi_date'] ?? '',
                                        'time' => $content['resepsi_time'] ?? '',
                                        'location' => $content['resepsi_location'] ?? '',
                                        'address' => $content['resepsi_address'] ?? '',
                                        'map' => $content['resepsi_map'] ?? '',
                                    ];
                                }

                                // Berikan minimal 1 kotak resepsi kosong jika belum ada sama sekali
                                if (empty($eventsData)) {
                                    $eventsData[] = [
                                        'title' => 'Resepsi Pernikahan',
                                        'date' => '',
                                        'time' => '',
                                        'location' => '',
                                        'address' => '',
                                        'map' => '',
                                    ];
                                }
                            @endphp

                            @foreach ($eventsData as $key => $event)
                                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 relative event-item">
                                    {{-- Hapus tombol untuk index ke-0 agar selalu ada minimal 1 resepsi --}}
                                    @if ($key > 0)
                                        <button type="button" onclick="this.closest('.event-item').remove()"
                                            class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-xs bg-white px-3 py-1.5 rounded-lg shadow-sm">Hapus</button>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-600 mb-1">Nama Acara</label>
                                            <input type="text" name="events[{{ $key }}][title]"
                                                value="{{ $event['title'] ?? '' }}"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                                placeholder="Contoh: Resepsi Pernikahan / Unduh Mantu">
                                        </div>
                                        <div><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label><input
                                                type="date" name="events[{{ $key }}][date]"
                                                value="{{ $event['date'] ?? '' }}"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl">
                                        </div>
                                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Waktu /
                                                Jam</label><input type="text" name="events[{{ $key }}][time]"
                                                value="{{ $event['time'] ?? '' }}"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                                placeholder="Contoh: 11:00 - 16:00 WIB"></div>
                                        <div class="md:col-span-2"><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Nama
                                                Tempat/Gedung</label><input type="text"
                                                name="events[{{ $key }}][location]"
                                                value="{{ $event['location'] ?? '' }}"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                                placeholder="Contoh: Grand Ballroom Hotel"></div>
                                        <div class="md:col-span-2"><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Alamat Lengkap</label>
                                            <textarea name="events[{{ $key }}][address]" rows="2"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl" placeholder="Contoh: Pekanbaru, Riau">{{ $event['address'] ?? '' }}</textarea>
                                        </div>
                                        <div class="md:col-span-2"><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Link Google
                                                Maps</label><input type="url" name="events[{{ $key }}][map]"
                                                value="{{ $event['map'] ?? '' }}"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                                placeholder="https://maps.google.com/..."></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- INFO TAMBAHAN (DRESSCODE & PROKES) + TOGGLE --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Guest Info & Prokes
                        </h4>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                            <input type="checkbox" name="is_guest_info_active" value="1" class="sr-only peer"
                                {{ $content['is_guest_info_active'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] sm:after:left-[auto] sm:after:right-[24px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rOrange">
                            </div>
                        </label>
                    </div>

                    <div
                        class="space-y-6 transition-opacity duration-300 {{ empty($content['is_guest_info_active']) && isset($content['is_guest_info_active']) ? 'opacity-40 pointer-events-none' : '' }}">
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
                        </div>
                    </div>
                </div>

                @include('customer.invitations.partials.music_selector')

                {{-- CERITA CINTA + TOGGLE --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
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
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                                <input type="checkbox" name="is_story_active" value="1" class="sr-only peer"
                                    {{ $content['is_story_active'] ?? true ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] sm:after:left-[auto] sm:after:right-[24px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rOrange">
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
                        class="space-y-6 transition-opacity duration-300 {{ empty($packageLogic['has_love_story']) || !$packageLogic['has_love_story'] || (empty($content['is_story_active']) && isset($content['is_story_active'])) ? 'opacity-30 pointer-events-none' : '' }}">
                        @if (!empty($content['love_stories']) && is_array($content['love_stories']))
                            @foreach ($content['love_stories'] as $key => $story)
                                <div
                                    class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item">
                                    <button type="button" onclick="this.closest('.love-story-item').remove()"
                                        class="absolute top-5 right-5 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                                    <input type="hidden" name="love_stories[{{ $key }}][old_image]"
                                        value="{{ $story['image'] ?? '' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                                        <div><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label><input
                                                type="text" name="love_stories[{{ $key }}][year]"
                                                value="{{ $story['year'] ?? '' }}"
                                                class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                                placeholder="Contoh: Januari 2021"></div>
                                        <div class="pr-12 md:pr-0"><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Judul
                                                Momen</label><input type="text"
                                                name="love_stories[{{ $key }}][title]"
                                                value="{{ $story['title'] ?? '' }}"
                                                class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                                placeholder="Contoh: Awal Bertemu"></div>
                                        <div class="md:col-span-2"><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Deskripsi
                                                Cerita</label>
                                            <textarea name="love_stories[{{ $key }}][description]" rows="3"
                                                class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                                placeholder="Ceritakan momen tersebut...">{{ $story['description'] ?? '' }}</textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-600 mb-2">Foto Momen
                                                (Opsional)</label>
                                            <div class="flex items-center gap-4">
                                                @if (!empty($story['image']))
                                                    <img src="{{ asset('storage/' . $story['image']) }}"
                                                        class="w-14 h-14 rounded-xl object-cover shadow-sm border border-slate-200">
                                                @else
                                                    <div
                                                        class="w-14 h-14 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg></div>
                                                @endif
                                                <input type="file" name="love_stories[{{ $key }}][image]"
                                                    accept="image/*"
                                                    class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item">
                                <button type="button" onclick="this.closest('.love-story-item').remove()"
                                    class="absolute top-5 right-5 text-red-500 hover:text-white bg-red-50 hover:bg-red-500 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-2">
                                    <div><label
                                            class="block text-xs font-bold text-slate-600 mb-1">Tahun/Waktu</label><input
                                            type="text" name="love_stories[0][year]"
                                            class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                            placeholder="Contoh: Januari 2021"></div>
                                    <div class="pr-12 md:pr-0"><label
                                            class="block text-xs font-bold text-slate-600 mb-1">Judul Momen</label><input
                                            type="text" name="love_stories[0][title]"
                                            class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                            placeholder="Contoh: Awal Bertemu"></div>
                                    <div class="md:col-span-2"><label
                                            class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Cerita</label>
                                        <textarea name="love_stories[0][description]" rows="3"
                                            class="w-full py-3 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"
                                            placeholder="Ceritakan momen tersebut..."></textarea>
                                    </div>
                                    <div class="md:col-span-2"><label
                                            class="block text-xs font-bold text-slate-600 mb-2">Foto Momen
                                            (Opsional)</label><input type="file" name="love_stories[0][image]"
                                            accept="image/*"
                                            class="w-full py-2 px-4 bg-white border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- GALERI & YOUTUBE + TOGGLE --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            Galeri Foto & Video
                        </h4>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                            <input type="checkbox" name="is_gallery_active" value="1" class="sr-only peer"
                                {{ $content['is_gallery_active'] ?? true ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] sm:after:left-[auto] sm:after:right-[24px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rOrange">
                            </div>
                        </label>
                    </div>

                    <div
                        class="transition-opacity duration-300 {{ empty($content['is_gallery_active']) && isset($content['is_gallery_active']) ? 'opacity-40 pointer-events-none' : '' }}">
                        <p class="font-bold text-slate-700 text-sm mb-3">Foto Album
                            ({{ $invitation->galleries->where('type', 'photo')->count() }} /
                            {{ $packageLogic['gallery_limit'] ?? 5 }})</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            @foreach ($invitation->galleries->where('type', 'photo') as $img)
                                <div
                                    class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-100">
                                    <img src="{{ asset('storage/' . $img->file_path) }}"
                                        class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button type="button" onclick="handleDeletePhoto('{{ $img->id }}')"
                                            class="bg-white text-red-500 p-2 rounded-full hover:bg-red-500 hover:text-white transition"><svg
                                                class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg></button>
                                    </div>
                                </div>
                            @endforeach

                            @if ($invitation->galleries->where('type', 'photo')->count() < ($packageLogic['gallery_limit'] ?? 5))
                                <div class="relative aspect-square">
                                    <input type="file" name="gallery_files[]" id="gallery-input" multiple
                                        class="hidden" onchange="previewImages(this)">
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
                        <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8"></div>

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
                                            <span class="pl-4 pr-2 text-slate-400"><svg class="w-5 h-5"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z">
                                                    </path>
                                                </svg></span>
                                            <input type="url" name="youtube_links[]" value="{{ $yt }}"
                                                class="w-full py-3 px-2 bg-transparent border-0 focus:ring-0 text-sm"
                                                placeholder="https://www.youtube.com/watch?v=...">
                                        </div>
                                        <button type="button" onclick="this.closest('.youtube-item').remove()"
                                            class="w-12 h-12 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shrink-0"><svg
                                                class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg></button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KADO DIGITAL + TOGGLE --}}
                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-slate-100 mb-6 gap-4">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002-2h10a2 2 0 002-2v-7">
                                    </path>
                                </svg>
                            </div>
                            Kado Digital / Amplop
                        </h4>
                        <div class="flex items-center gap-4">
                            <button type="button" onclick="addBankRow()"
                                class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition shadow-sm">+
                                Rekening</button>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <span class="mr-3 text-sm font-bold text-slate-500 hidden sm:block">Tampilkan</span>
                                <input type="checkbox" name="is_gift_active" value="1" class="sr-only peer"
                                    {{ $content['is_gift_active'] ?? true ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] sm:after:left-[auto] sm:after:right-[24px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rOrange">
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

                    <div id="bank-wrapper"
                        class="space-y-4 transition-opacity duration-300 {{ empty($packageLogic['has_digital_gift']) || !$packageLogic['has_digital_gift'] || (empty($content['is_gift_active']) && isset($content['is_gift_active'])) ? 'opacity-30 pointer-events-none' : '' }}">
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
                                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Atas
                                                Nama</label><input type="text"
                                                name="banks[{{ $key }}][account_name]"
                                                value="{{ $bank['account_name'] ?? '' }}"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                                placeholder="Nama Pemilik Rekening"></div>
                                        <div class="md:col-span-2"><label
                                                class="block text-xs font-bold text-slate-600 mb-1">Nomor Rekening / No
                                                HP</label><input type="text"
                                                name="banks[{{ $key }}][account_number]"
                                                value="{{ $bank['account_number'] ?? '' }}"
                                                class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                                placeholder="Contoh: 1234567890"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 bank-item">
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

                {{-- RSVP & UCAPAN --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div
                            class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-500 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                </path>
                            </svg>
                        </div>
                        Pengaturan RSVP & Ucapan
                    </h4>
                    <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50">
                        <p class="text-sm text-slate-600 font-medium mb-4">Formulir kehadiran (RSVP) akan selalu terbuka
                            agar tamu bisa mengonfirmasi kehadirannya. Namun Anda dapat memilih apakah ingin menampilkan
                            hasil ucapan doa dari tamu di halaman undangan publik.</p>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_wishes_active" value="1"
                                {{ $content['is_wishes_active'] ?? true ? 'checked' : '' }}
                                class="w-5 h-5 text-rOrange rounded border-slate-300 focus:ring-rOrange">
                            <span class="font-bold text-slate-700">Tampilkan Hasil Ucapan Tamu di Undangan Publik</span>
                        </label>
                    </div>
                </div>

                {{-- GANTI TEMA / TEMPLATE --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                </path>
                            </svg>
                        </div>
                        Ganti Desain Tema
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($templates as $template)
                            <label class="cursor-pointer relative group">
                                <input type="radio" name="template_id" value="{{ $template->id }}"
                                    class="peer sr-only" required
                                    {{ old('template_id', $invitation->template_id) == $template->id ? 'checked' : '' }}>

                                <div
                                    class="h-full border-2 border-slate-100 rounded-3xl overflow-hidden hover:border-rOrange/50 transition peer-checked:border-rOrange peer-checked:shadow-lg peer-checked:shadow-rOrange/10">
                                    <div class="relative h-56 bg-slate-200 overflow-hidden">
                                        <div
                                            class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-110">
                                            <div
                                                class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden bg-stone-900">
                                                <iframe
                                                    src="{{ asset('preview/' . $template->view_path . '/index.html') }}?thumbnail=1"
                                                    class="absolute top-0 left-0 w-[400%] h-[400%] origin-top-left scale-[0.25] border-0"
                                                    scrolling="no" tabindex="-1">
                                                </iframe>
                                            </div>
                                        </div>

                                        <div
                                            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center z-10">
                                            <button type="button"
                                                class="preview-btn bg-white text-slate-800 px-5 py-2.5 rounded-full font-bold text-xs hover:bg-rOrange hover:text-white transition transform translate-y-4 group-hover:translate-y-0"
                                                data-title="{{ $template->name }}"
                                                data-path="{{ asset('preview/' . $template->view_path . '/index.html') }}"
                                                data-category="{{ $template->category->name ?? 'Umum' }}">
                                                <svg class="w-4 h-4 inline-block mr-1 mb-0.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Preview Full
                                            </button>
                                        </div>

                                        <div
                                            class="absolute top-4 right-4 w-7 h-7 rounded-full bg-rOrange text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition shadow-md z-10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="p-5 bg-white peer-checked:bg-rOrange/5 transition">
                                        <h5 class="font-bold text-slate-800 mb-1 text-lg">{{ $template->name }}</h5>
                                        <span
                                            class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-1 rounded border border-slate-200 uppercase tracking-tighter">
                                            {{ $template->category->name ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
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

    {{-- MODAL PREVIEW TEMA IFRAME --}}
    <div id="themeModal"
        class="fixed inset-0 z-[60] hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[2rem] w-full max-w-4xl h-[90vh] overflow-hidden flex flex-col shadow-2xl transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white shrink-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-slate-800" id="modalTitle">Nama Tema</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider" id="modalCategory">Kategori
                    </p>
                </div>
                <button type="button" id="closeModalBtn"
                    class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-red-100 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-hidden bg-slate-100/50 relative">
                <iframe id="modalIframe" src="" class="absolute inset-0 w-full h-full border-0"></iframe>
            </div>
            <div class="p-5 border-t border-slate-100 bg-white text-center shrink-0">
                <p class="text-xs text-slate-500 mb-3 font-medium">Tutup jendela preview ini dan klik area kotak tema untuk
                    memilihnya.</p>
                <button type="button"
                    class="px-8 py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 w-full transition"
                    id="footerCloseBtn">
                    Tutup Preview
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function addEventRow() {
            const id = Date.now();
            const html = `
                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 relative event-item mt-4 animate-fade-in">
                    <button type="button" onclick="this.closest('.event-item').remove()" class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-xs bg-white px-3 py-1.5 rounded-lg shadow-sm">Hapus</button>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nama Acara</label>
                            <input type="text" name="events[${id}][title]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Resepsi Tambahan / After Party">
                        </div>
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Tanggal</label><input type="date" name="events[${id}][date]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"></div>
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Waktu / Jam</label><input type="text" name="events[${id}][time]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: 18:00 - Selesai"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nama Tempat/Gedung</label><input type="text" name="events[${id}][location]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: Gedung Serbaguna"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Alamat Lengkap</label><textarea name="events[${id}][address]" rows="2" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange"></textarea></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Link Google Maps (Opsional)</label><input type="url" name="events[${id}][map]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="https://maps.google.com/..."></div>
                    </div>
                </div>
            `;
            document.getElementById('event-wrapper').insertAdjacentHTML('beforeend', html);
        }

        function addLoveStoryRow() {
            const id = Date.now();
            const html = `
                <div class="relative p-6 border-2 border-slate-100 rounded-[2rem] bg-slate-50 love-story-item mt-4 animate-fade-in">
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

        const bankOptions = `{!! $masterBanks->map(function ($b) {
                return '<option value="' . $b->name . '">' . $b->name . '</option>';
            })->implode('') !!}`;

        function addBankRow() {
            const id = Date.now();
            const html = `
                <div class="relative p-5 border border-slate-200 rounded-2xl bg-slate-50 bank-item mt-4 animate-fade-in">
                    <button type="button" onclick="this.closest('.bank-item').remove()" class="absolute top-4 right-4 text-red-500 hover:text-red-700 font-bold text-sm">Hapus</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Pilih Bank / E-Wallet</label>
                            <select name="banks[${id}][name]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange cursor-pointer">
                                <option value="">-- Pilih Pembayaran --</option>
                                ${bankOptions}
                            </select>
                        </div>
                        <div><label class="block text-xs font-bold text-slate-600 mb-1">Atas Nama</label><input type="text" name="banks[${id}][account_name]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Nama Pemilik Rekening"></div>
                        <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-600 mb-1">Nomor Rekening / No HP</label><input type="text" name="banks[${id}][account_number]" class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl focus:ring-rOrange" placeholder="Contoh: 1234567890"></div>
                    </div>
                </div>
            `;
            document.getElementById('bank-wrapper').insertAdjacentHTML('beforeend', html);
        }

        function addYoutubeRow() {
            const html = `
                <div class="flex items-center gap-2 youtube-item mt-3">
                    <div class="flex-1 flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:ring-1 focus-within:ring-rOrange focus-within:border-rOrange transition">
                        <span class="pl-4 pr-2 text-slate-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg>
                        </span>
                        <input type="url" name="youtube_links[]" class="w-full py-3 px-2 bg-transparent border-0 focus:ring-0 text-sm" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <button type="button" onclick="this.closest('.youtube-item').remove()" class="w-12 h-12 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
            document.getElementById('youtube-wrapper').insertAdjacentHTML('beforeend', html);
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
            // Interaksi Toggle Switch
            const toggles = document.querySelectorAll('input[type="checkbox"][name^="is_"]');
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    // Cari wrapper terdekat yang memiliki efek transisi
                    const wrapper = this.closest('.bg-white').querySelector('.transition-opacity');

                    if (wrapper) {
                        if (this.checked) {
                            wrapper.classList.remove('opacity-40', 'pointer-events-none');
                        } else {
                            wrapper.classList.add('opacity-40', 'pointer-events-none');
                        }
                    }
                });
            });

            // Modal Upgrade Logic
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

            // Modal Preview Tema Logic
            const modal = document.getElementById('themeModal');
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalTitle');
            const modalCategory = document.getElementById('modalCategory');
            const modalIframe = document.getElementById('modalIframe');
            const closeBtn = document.getElementById('closeModalBtn');
            const footerCloseBtn = document.getElementById('footerCloseBtn');
            const previewBtns = document.querySelectorAll('.preview-btn');

            function openModal(title, category, pathUrl) {
                modalTitle.textContent = title;
                modalCategory.textContent = category;
                modalIframe.src = pathUrl;

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 20);
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('opacity-0');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modalIframe.src = '';
                }, 300);
                document.body.style.overflow = '';
            }

            previewBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openModal(this.getAttribute('data-title'), this.getAttribute('data-category'),
                        this.getAttribute('data-path'));
                });
            });

            closeBtn.addEventListener('click', closeModal);
            footerCloseBtn.addEventListener('click', closeModal);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        });
    </script>
@endpush
