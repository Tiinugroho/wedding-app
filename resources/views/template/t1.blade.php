<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan - {{ $content['groom_nickname'] ?? 'Romeo' }} &
        {{ $content['bride_nickname'] ?? 'Juliet' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            white: '#FFFFFF',
                            elegant: '#FDFDFD',
                            gold: '#C5A065',
                            charcoal: '#333333',
                            lightGold: '#E4D5B7',
                            softWhite: 'rgba(255, 255, 255, 0.9)',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        body {
            overflow-y: hidden;
        }

        .floating-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(197, 160, 101, 0.2);
        }

        .text-protected {
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        #guest-name {
            background: linear-gradient(to bottom, #333333, #555555);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #333333;
        }

        .py-4\.5 {
            padding-top: 1.125rem;
            padding-bottom: 1.125rem;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }

        #mempelai {
            background: linear-gradient(to bottom, #FFFFFF 0%, #FDFDFD 100%);
        }

        #mempelai .group:hover .border-brand-lightGold\/40 {
            border-color: rgba(197, 160, 101, 0.7);
            transform: translate(3px, 3px);
        }

        .scroll-custom::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-custom::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-custom::-webkit-scrollbar-thumb {
            background: #C5A065;
            border-radius: 10px;
        }
    </style>

    @php
        // Ekstraksi Youtube ID agar iFrame berfungsi
        function getYoutubeId($url)
        {
            preg_match(
                '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
                $url,
                $match,
            );
            return $match[1] ?? null;
        }

        // LOGIKA COVER IMAGE (Custom -> Gallery 1 -> Default)
        $firstGallery = $invitation->galleries->where('type', 'photo')->first();
        $coverImg = !empty($content['cover_image'])
            ? asset('storage/' . $content['cover_image'])
            : ($firstGallery
                ? asset('storage/' . $firstGallery->file_path)
                : (!empty($content['bride_photo'])
                    ? asset('storage/' . $content['bride_photo'])
                    : 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1000'));

        // Siapkan variabel urutan cover
        $isGroomFirst = ($content['couple_order'] ?? 'groom_first') == 'groom_first';

        // PETAKAN DATA ORANG PERTAMA (Akan tampil di kartu kiri)
        $firstPerson = [
            'name' => $isGroomFirst
                ? $content['groom_name'] ?? 'Romeo Montague'
                : $content['bride_name'] ?? 'Juliet Capulet',
            'nickname' => $isGroomFirst
                ? $content['groom_nickname'] ?? 'Romeo'
                : $content['bride_nickname'] ?? 'Juliet',
            'father' => $isGroomFirst ? $content['groom_father'] ?? 'Fulan' : $content['bride_father'] ?? 'Fulan',
            'mother' => $isGroomFirst ? $content['groom_mother'] ?? 'Fulanah' : $content['bride_mother'] ?? 'Fulanah',
            'ig' => $isGroomFirst ? $content['groom_ig'] ?? '' : $content['bride_ig'] ?? '',
            'photo' => $isGroomFirst ? $content['groom_photo'] ?? '' : $content['bride_photo'] ?? '',
            'label' => $isGroomFirst ? '- The Groom -' : '- The Bride -',
            'default_img' => $isGroomFirst
                ? 'https://images.soco.id/230-58.jpg.jpeg'
                : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg',
        ];

        // PETAKAN DATA ORANG KEDUA (Akan tampil di kartu kanan)
        $secondPerson = [
            'name' => !$isGroomFirst
                ? $content['groom_name'] ?? 'Romeo Montague'
                : $content['bride_name'] ?? 'Juliet Capulet',
            'nickname' => !$isGroomFirst
                ? $content['groom_nickname'] ?? 'Romeo'
                : $content['bride_nickname'] ?? 'Juliet',
            'father' => !$isGroomFirst ? $content['groom_father'] ?? 'Fulan' : $content['bride_father'] ?? 'Fulan',
            'mother' => !$isGroomFirst ? $content['groom_mother'] ?? 'Fulanah' : $content['bride_mother'] ?? 'Fulanah',
            'ig' => !$isGroomFirst ? $content['groom_ig'] ?? '' : $content['bride_ig'] ?? '',
            'photo' => !$isGroomFirst ? $content['groom_photo'] ?? '' : $content['bride_photo'] ?? '',
            'label' => !$isGroomFirst ? '- The Groom -' : '- The Bride -',
            'default_img' => !$isGroomFirst
                ? 'https://images.soco.id/230-58.jpg.jpeg'
                : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg',
        ];

        // Nama untuk di Cover (Groom & Bride atau sebaliknya)
        $pria = $content['groom_nickname'] ?? 'Romeo';
        $wanita = $content['bride_nickname'] ?? 'Juliet';
        $coupleNameCover = $isGroomFirst ? "$pria & $wanita" : "$wanita & $pria";

        // ==========================================
        // LOGIKA COUNTDOWN & TANGGAL (FOKUS KE RESEPSI)
        // ==========================================
        $countdownDate = '';
        $rawTime = '';

        // 1. Coba ambil dari Event pertama (Resepsi)
        if (!empty($content['events'][0]['date'])) {
            $countdownDate = $content['events'][0]['date'];
            $rawTime = $content['events'][0]['time'] ?? '';
        }
        // 2. Fallback: Jika kosong, pakai Akad
        elseif (!empty($content['akad_date'])) {
            $countdownDate = $content['akad_date'];
            $rawTime = $content['akad_time'] ?? '';
        }

        // Bersihkan Waktu dengan Regex (Membuang "WIB", dsb)
        $countdownTime = '00:00';
        if (!empty($rawTime)) {
            if (preg_match('/([0-9]{1,2}:[0-9]{2})/', $rawTime, $matches)) {
                $countdownTime = str_pad($matches[1], 5, '0', STR_PAD_LEFT);
            }
        }

        // Variabel untuk Teks di Cover
        $coverDate = $countdownDate ? \Carbon\Carbon::parse($countdownDate)->format('d . m . Y') : 'TBA';

        // Target Countdown untuk Javascript (ISO 8601)
        $countdownTarget = $countdownDate ? $countdownDate . 'T' . $countdownTime . ':00' : '';
    @endphp
</head>

<body
    class="bg-brand-elegant text-brand-charcoal font-sans antialiased relative selection:bg-brand-gold selection:text-white">

    {{-- MUSIK --}}
    <audio id="bg-music" loop>
        <source
            src="{{ !empty($invitation->music_id) ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2021/07/18/audio_c993f91966.mp3' }}"
            type="audio/mpeg">
    </audio>

    {{-- COVER PAGE --}}
    <div id="cover-page"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-brand-white transition-transform duration-1000 ease-in-out overflow-hidden">
        {{-- MENGGUNAKAN COVER IMAGE DINAMIS --}}
        <div
            class="absolute inset-0 opacity-[0.22] bg-[url('{{ $coverImg }}')] bg-cover bg-center scale-105 animate-[pulse_10s_infinite]">
        </div>
        <div
            class="absolute inset-0 opacity-[0.025] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-brand-white/10 via-brand-white/70 to-brand-white/95"></div>

        <div class="relative z-10 text-center px-6 max-w-lg w-full">
            <p class="text-xs tracking-[0.4em] uppercase text-brand-gold mb-5 font-semibold drop-shadow-sm">The Wedding
                Of</p>

            <h1
                class="text-6xl md:text-7xl font-serif italic text-brand-gold mb-3 tracking-tight leading-none text-protected">
                {{ $coupleNameCover }}</h1>

            <div class="flex items-center justify-center gap-5 mb-12">
                <div class="h-[1px] w-12 bg-brand-gold/25"></div>
                <p class="text-sm font-sans tracking-[0.25em] text-brand-charcoal uppercase font-light">
                    {{ $coverDate }}</p>
                <div class="h-[1px] w-12 bg-brand-gold/25"></div>
            </div>

            <div
                class="my-9 p-8 md:p-10 bg-white/35 backdrop-blur-sm rounded-[2rem] border border-white/60 shadow-[0_6px_25px_rgba(197,160,101,0.1)] relative overflow-hidden group">
                <p class="text-xs text-gray-500 mb-3.5 italic font-light tracking-wide">
                    {{ $content['cover_greeting'] ?? 'Kepada Yth.' }}</p>
                <h2 id="guest-name"
                    class="text-3xl md:text-4xl font-serif font-semibold text-brand-charcoal leading-snug">Tamu Undangan
                </h2>

                @if (!empty($content['akad_location']))
                    <div
                        class="mt-5 flex items-center justify-center gap-2.5 text-xs text-brand-gold uppercase tracking-[0.1em] font-medium">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ $content['akad_location'] }}</span>
                    </div>
                @endif
            </div>

            <div class="space-y-4 mt-6">
                <button onclick="openInvitation()"
                    class="group relative px-14 py-4.5 bg-brand-gold hover:bg-brand-charcoal text-white rounded-full transition-all duration-500 shadow-xl hover:shadow-brand-gold/15 flex items-center justify-center gap-3.5 mx-auto overflow-hidden">
                    <div
                        class="absolute inset-0 w-1/2 h-full bg-white/25 skew-x-[-25deg] -translate-x-full group-hover:translate-x-[250%] transition-transform duration-1000">
                    </div>
                    <i class="fa-solid fa-envelope-open-text group-hover:scale-110 transition-transform text-lg"></i>
                    <span class="font-semibold tracking-wide text-sm">Buka Undangan</span>
                </button>
            </div>
        </div>
    </div>

    <main id="main-content" class="min-h-screen pb-28 opacity-0 transition-opacity duration-1000">

        {{-- QUOTES & COUNTDOWN --}}
        <section id="home"
            class="min-h-screen flex flex-col items-center justify-center text-center p-4 md:p-8 bg-[url('{{ $coverImg }}')] bg-cover bg-center bg-fixed relative overflow-hidden">
            <div class="absolute inset-0 bg-brand-white/60 backdrop-blur-[2px]"></div>

            <div
                class="relative z-10 max-w-2xl w-full p-6 md:p-14 bg-brand-softWhite/80 rounded-[2.5rem] md:rounded-[3rem] border border-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] backdrop-blur-md">

                <div class="mb-8 md:mb-10">
                    <p class="font-serif italic text-xl md:text-3xl text-brand-gold mb-4 text-protected">
                        "{{ $content['quotes'] ?? 'And they lived happily ever after.' }}"
                    </p>
                    <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mb-6"></div>
                    <p
                        class="text-[12px] md:text-sm text-brand-charcoal max-w-md mx-auto leading-relaxed font-light italic px-4">
                        Dengan memohon rahmat dan ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri
                        acara pernikahan kami.
                    </p>
                </div>

                <div class="pt-8 border-t border-brand-lightGold/20">
                    <p
                        class="text-[9px] md:text-[10px] tracking-[0.4em] uppercase text-brand-gold mb-8 md:mb-10 font-semibold">
                        The Waiting Moment</p>
                    <div class="flex flex-wrap justify-center items-center gap-2 md:gap-8">
                        <div class="relative group p-2">
                            <div
                                class="absolute inset-0 border border-dashed border-brand-gold/20 rounded-full animate-[spin_15s_linear_infinite]">
                            </div>
                            <div
                                class="relative flex flex-col items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-white/40 rounded-full backdrop-blur-sm">
                                <span id="days"
                                    class="text-xl md:text-4xl font-serif font-light text-brand-charcoal leading-none">00</span>
                                <span
                                    class="text-[7px] md:text-[10px] uppercase tracking-widest text-brand-gold font-medium mt-1">Days</span>
                            </div>
                        </div>
                        <span
                            class="text-brand-lightGold/40 font-serif italic text-lg md:text-2xl opacity-50 hidden sm:block">&</span>
                        <div class="relative group p-2">
                            <div
                                class="absolute inset-0 border border-dashed border-brand-gold/20 rounded-full animate-[spin_20s_linear_infinite_reverse]">
                            </div>
                            <div
                                class="relative flex flex-col items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-white/40 rounded-full backdrop-blur-sm">
                                <span id="hours"
                                    class="text-xl md:text-4xl font-serif font-light text-brand-charcoal leading-none">00</span>
                                <span
                                    class="text-[7px] md:text-[10px] uppercase tracking-widest text-brand-gold font-medium mt-1">Hours</span>
                            </div>
                        </div>
                        <span
                            class="text-brand-lightGold/40 font-serif italic text-lg md:text-2xl opacity-50 hidden sm:block">&</span>
                        <div class="relative group p-2">
                            <div
                                class="absolute inset-0 border border-dashed border-brand-gold/20 rounded-full animate-[spin_25s_linear_infinite]">
                            </div>
                            <div
                                class="relative flex flex-col items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-white/40 rounded-full backdrop-blur-sm">
                                <span id="minutes"
                                    class="text-xl md:text-4xl font-serif font-light text-brand-charcoal leading-none">00</span>
                                <span
                                    class="text-[7px] md:text-[10px] uppercase tracking-widest text-brand-gold font-medium mt-1">Mins</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MEMPELAI (SELALU AKTIF, URUTAN DINAMIS) --}}
        <section id="mempelai" class="py-12 px-6 bg-brand-white relative overflow-hidden">
            <div
                class="absolute inset-0 opacity-[0.04] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/linen-headboard.png')]">
            </div>
            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-24">
                    <p class="text-[10px] tracking-[0.5em] uppercase text-brand-gold mb-3 font-semibold drop-shadow-sm">
                        Meet The Couple</p>
                    <div class="h-[1px] w-20 bg-brand-lightGold/30 mx-auto"></div>
                </div>

                {{-- Container dibuat statis (tanpa row-reverse) --}}
                <div class="space-y-24 md:space-y-0 md:flex md:items-center md:justify-center md:gap-20 lg:gap-32">

                    {{-- KARTU KIRI (Selalu Orang Pertama) --}}
                    <div class="flex flex-col items-center md:items-start text-center md:text-left group flex-1">
                        <div
                            class="relative mb-10 w-64 h-80 md:w-72 md:h-96 group-hover:-translate-y-2 transition-transform duration-500 ease-out">
                            {{-- Border menyesuaikan sisi --}}
                            <div
                                class="absolute -bottom-4 -right-4 inset-0 border border-brand-lightGold/40 rounded-t-[10rem] rounded-b-xl z-0">
                            </div>
                            <div
                                class="absolute inset-0 bg-brand-lightGold overflow-hidden rounded-t-[10rem] rounded-b-xl shadow-[0_15px_45px_rgba(197,160,101,0.1)] z-10 border-4 border-white">
                                <img src="{{ !empty($firstPerson['photo']) ? asset('storage/' . $firstPerson['photo']) : $firstPerson['default_img'] }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div class="absolute -top-8 -left-8 w-24 h-24 opacity-60 z-20 pointer-events-none">
                                <img src="https://www.transparentpng.com/thumb/flowers-vectors/pink-and-whire-flower-vector-hq-png-6.png"
                                    class="w-full h-full object-contain">
                            </div>
                        </div>

                        <h3 class="text-3xl lg:text-4xl font-serif font-bold text-brand-charcoal mb-2 leading-tight">
                            {{ $firstPerson['name'] }}</h3>
                        <p
                            class="text-xs text-brand-gold uppercase tracking-[0.3em] font-medium mb-5 border-b border-brand-lightGold/30 inline-block pb-1">
                            {{ $firstPerson['label'] }}</p>

                        <div class="space-y-1 mb-4">
                            <p class="uppercase text-[10px] tracking-[0.3em] text-brand-gold font-bold">Putra/i Tercinta
                                dari</p>
                            <p class="text-sm text-brand-charcoal font-medium">Bapak {{ $firstPerson['father'] }}</p>
                            <p class="text-sm text-brand-charcoal font-medium">& Ibu {{ $firstPerson['mother'] }}</p>
                        </div>

                        @if (!empty($firstPerson['ig']))
                            <a href="https://instagram.com/{{ $firstPerson['ig'] }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-lightGold/50 text-brand-gold hover:bg-brand-gold hover:text-white transition text-xs font-semibold">
                                <i class="fa-brands fa-instagram"></i> {{ $firstPerson['ig'] }}
                            </a>
                        @endif
                    </div>

                    {{-- KARTU KANAN (Selalu Orang Kedua) --}}
                    <div class="flex flex-col items-center md:items-end text-center md:text-right group flex-1">
                        <div
                            class="relative mb-10 w-64 h-80 md:w-72 md:h-96 group-hover:-translate-y-2 transition-transform duration-500 ease-out">
                            {{-- Border menyesuaikan sisi (kiri bawah) --}}
                            <div
                                class="absolute -bottom-4 -left-4 inset-0 border border-brand-lightGold/40 rounded-t-[10rem] rounded-b-xl z-0">
                            </div>
                            <div
                                class="absolute inset-0 bg-brand-lightGold overflow-hidden rounded-t-[10rem] rounded-b-xl shadow-[0_15px_45px_rgba(197,160,101,0.1)] z-10 border-4 border-white">
                                <img src="{{ !empty($secondPerson['photo']) ? asset('storage/' . $secondPerson['photo']) : $secondPerson['default_img'] }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            </div>
                            <div
                                class="absolute -bottom-8 -right-8 w-24 h-24 opacity-60 z-20 pointer-events-none rotate-180">
                                <img src="https://www.transparentpng.com/thumb/flowers-vectors/pink-and-whire-flower-vector-hq-png-6.png"
                                    class="w-full h-full object-contain">
                            </div>
                        </div>

                        <h3 class="text-3xl lg:text-4xl font-serif font-bold text-brand-charcoal mb-2 leading-tight">
                            {{ $secondPerson['name'] }}</h3>
                        <p
                            class="text-xs text-brand-gold uppercase tracking-[0.3em] font-medium mb-5 border-b border-brand-lightGold/30 inline-block pb-1">
                            {{ $secondPerson['label'] }}</p>

                        <div class="space-y-1 mb-4">
                            <p class="uppercase text-[10px] tracking-[0.3em] text-brand-gold font-bold">Putra/i
                                Tercinta dari</p>
                            <p class="text-sm text-brand-charcoal font-medium">Bapak {{ $secondPerson['father'] }}</p>
                            <p class="text-sm text-brand-charcoal font-medium">& Ibu {{ $secondPerson['mother'] }}</p>
                        </div>

                        @if (!empty($secondPerson['ig']))
                            <a href="https://instagram.com/{{ $secondPerson['ig'] }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-lightGold/50 text-brand-gold hover:bg-brand-gold hover:text-white transition text-xs font-semibold">
                                <i class="fa-brands fa-instagram"></i> {{ $secondPerson['ig'] }}
                            </a>
                        @endif
                    </div>
                </div>

                {{-- TURUT MENGU{{-- TURUT MENGUNDANG (TOGGLE) --}}
                @if(!empty($content['is_turut_mengundang_active']))
                    @if(!empty($content['turut_mengundang']))
                        <div class="mt-20 pt-12 border-t border-brand-lightGold/15 bg-brand-elegant/50 rounded-3xl p-10 md:p-16 relative">
                            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-brand-white px-6">
                                <i class="fa-solid fa-users text-brand-lightGold/50 text-3xl"></i>
                            </div>
                            <p class="text-[11px] tracking-[0.4em] uppercase text-gray-400 mb-12 font-medium text-center">Turut Mengundang</p>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-10 gap-y-6 text-xs text-brand-charcoal/80 font-light leading-relaxed max-w-4xl mx-auto text-center">
                                @foreach($content['turut_mengundang'] as $nama)
                                    @if(trim($nama) !== '')
                                        <p>{{ trim($nama) }}</p>
                                    @endif
                                @endforeach
                            </div>

                            <div class="text-center mt-12">
                                <i class="fa-solid fa-quote-right text-brand-lightGold/50 text-3xl opacity-50"></i>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </section>

        {{-- STORY MEMPELAI (TOGGLE) --}}
        @if (!empty($content['is_story_active']) && !empty($content['love_stories']))
            <section id="story" class="py-28 px-6 bg-brand-white relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
                </div>

                <div class="max-w-3xl mx-auto relative z-10">
                    <div class="text-center mb-20">
                        <p class="text-[10px] tracking-[0.5em] uppercase text-brand-gold mb-3 font-semibold">Our
                            Journey</p>
                        <h2 class="text-4xl font-serif italic text-brand-charcoal">Cerita Cinta Kami</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mt-6"></div>
                    </div>

                    <div class="relative space-y-16">
                        <div
                            class="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-[1px] bg-gradient-to-b from-brand-gold/50 via-brand-gold/20 to-transparent">
                        </div>

                        @foreach ($content['love_stories'] as $index => $story)
                            <div
                                class="story-item relative flex flex-col items-center group {{ $index > 2 ? 'hidden extra-story' : '' }}">
                                <div
                                    class="z-10 w-4 h-4 rounded-full bg-brand-white border-2 border-brand-gold mb-6 group-hover:scale-125 transition-transform duration-300">
                                </div>
                                <div
                                    class="w-full bg-brand-elegant/50 p-6 md:p-10 rounded-[2.5rem] border border-brand-lightGold/20 shadow-sm backdrop-blur-sm transition-all duration-500 hover:shadow-md">
                                    @if (!empty($story['image']))
                                        <div
                                            class="aspect-video w-full rounded-2xl overflow-hidden mb-6 bg-brand-lightGold">
                                            <img src="{{ asset('storage/' . $story['image']) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                                alt="{{ $story['title'] }}">
                                        </div>
                                    @endif
                                    <span
                                        class="text-[10px] font-bold tracking-[0.2em] text-brand-gold uppercase">{{ $story['year'] }}</span>
                                    <h4 class="text-xl font-serif font-bold text-brand-charcoal mt-2 mb-3">
                                        {{ $story['title'] }}</h4>
                                    <p class="text-sm text-gray-500 leading-relaxed font-light">
                                        {{ $story['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($content['love_stories']) > 3)
                        <div class="mt-20 text-center">
                            <button id="btn-read-more" onclick="toggleStories()"
                                class="px-10 py-3.5 bg-transparent border border-brand-gold/40 text-brand-gold rounded-full text-xs font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-white transition-all duration-500 shadow-sm">
                                Baca Selengkapnya
                            </button>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- LOKASI DAN WAKTU (TOGGLE) --}}
        @if (!empty($content['is_event_active']))
            <section id="lokasi"
                class="py-24 px-6 bg-brand-elegant border-y border-brand-lightGold/30 shadow-inner">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl font-serif text-brand-charcoal mb-4">Waktu & Lokasi Acara</h2>
                    <div class="h-[1px] w-12 bg-brand-gold/40 mx-auto mb-4"></div>
                    <p class="text-xs text-brand-gold tracking-[0.4em] uppercase font-medium mb-12">Kehadiran Anda
                        Adalah Kehormatan</p>

                    <div
                        class="grid grid-cols-1 {{ count($content['events'] ?? []) > 0 ? 'md:grid-cols-2' : '' }} gap-8">

                        {{-- AKAD NIKAH STATIS --}}
                        @if (!empty($content['akad_location']))
                            <div
                                class="bg-brand-white p-10 rounded-[2.5rem] shadow-lg border border-brand-lightGold/50 h-full flex flex-col justify-between hover:-translate-y-2 transition-transform duration-500">
                                <div>
                                    <i class="fa-solid fa-ring text-4xl text-brand-gold mb-6"></i>
                                    <h3 class="text-2xl font-serif font-bold mb-2">Akad Nikah</h3>
                                    <p class="text-brand-gold font-bold text-sm mb-4">
                                        {{ \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2 font-medium"><i
                                            class="fa-regular fa-clock mr-2"></i>{{ $content['akad_time'] }}</p>
                                    <p class="text-sm text-brand-charcoal font-bold mt-6 mb-1">
                                        {{ $content['akad_location'] }}</p>
                                    <p class="text-xs text-gray-500 leading-relaxed mb-8">
                                        {{ $content['akad_address'] }}</p>
                                </div>
                                @if (!empty($content['akad_map']))
                                    <a href="{{ $content['akad_map'] }}" target="_blank"
                                        class="block w-full py-3 bg-brand-elegant border border-brand-gold text-brand-gold rounded-full hover:bg-brand-gold hover:text-white transition-colors text-xs uppercase tracking-widest font-bold">
                                        <i class="fa-solid fa-map-location-dot mr-2"></i> Buka Peta
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- RESEPSI DINAMIS --}}
                        @if (!empty($content['events']))
                            @foreach ($content['events'] as $event)
                                <div
                                    class="bg-brand-white p-10 rounded-[2.5rem] shadow-lg border border-brand-lightGold/50 h-full flex flex-col justify-between hover:-translate-y-2 transition-transform duration-500">
                                    <div>
                                        <i class="fa-solid fa-champagne-glasses text-4xl text-brand-gold mb-6"></i>
                                        <h3 class="text-2xl font-serif font-bold mb-2">{{ $event['title'] }}</h3>
                                        <p class="text-brand-gold font-bold text-sm mb-4">
                                            {{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600 mb-2 font-medium"><i
                                                class="fa-regular fa-clock mr-2"></i>{{ $event['time'] }}</p>
                                        <p class="text-sm text-brand-charcoal font-bold mt-6 mb-1">
                                            {{ $event['location'] }}</p>
                                        <p class="text-xs text-gray-500 leading-relaxed mb-8">{{ $event['address'] }}
                                        </p>
                                    </div>
                                    @if (!empty($event['map']))
                                        <a href="{{ $event['map'] }}" target="_blank"
                                            class="block w-full py-3 bg-brand-elegant border border-brand-gold text-brand-gold rounded-full hover:bg-brand-gold hover:text-white transition-colors text-xs uppercase tracking-widest font-bold">
                                            <i class="fa-solid fa-map-location-dot mr-2"></i> Buka Peta
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- GUEST INFO / PROKES (TOGGLE) --}}
        @if (!empty($content['is_guest_info_active']))
            <section class="py-16 px-6 bg-brand-white text-center">
                <div class="max-w-2xl mx-auto border-2 border-dashed border-brand-lightGold/50 rounded-[2rem] p-8">
                    <i class="fa-solid fa-circle-info text-2xl text-brand-gold mb-4"></i>
                    <h4 class="text-xl font-serif font-bold mb-6 text-brand-charcoal">Informasi Tamu</h4>

                    @if (!empty($content['enable_dresscode']) && !empty($content['dresscode']))
                        <div class="mb-6">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Dresscode</p>
                            <p class="text-sm font-medium text-brand-charcoal">{{ $content['dresscode'] }}</p>
                        </div>
                    @endif

                    @if (!empty($content['enable_health_protocol']))
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-3">Protokol
                                Kesehatan</p>
                            <div class="flex justify-center gap-6 text-brand-gold text-2xl">
                                <div class="flex flex-col items-center gap-2"><i
                                        class="fa-solid fa-head-side-mask"></i><span
                                        class="text-[9px] text-gray-500">Masker</span></div>
                                <div class="flex flex-col items-center gap-2"><i
                                        class="fa-solid fa-hands-bubbles"></i><span
                                        class="text-[9px] text-gray-500">Cuci Tangan</span></div>
                                <div class="flex flex-col items-center gap-2"><i
                                        class="fa-solid fa-people-arrows"></i><span
                                        class="text-[9px] text-gray-500">Jaga Jarak</span></div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- GALERI & VIDEO (TOGGLE) --}}
        @if (!empty($content['is_gallery_active']))
            <section id="gallery" class="py-24 px-6 max-w-6xl mx-auto text-center overflow-hidden">
                <div class="mb-16">
                    <h2 class="text-5xl font-serif text-brand-charcoal mb-4 italic">Our Gallery</h2>
                    <div class="h-[1px] w-12 bg-brand-gold/40 mx-auto mb-4"></div>
                    <p class="text-xs text-brand-gold tracking-[0.4em] uppercase font-medium">Momen Bahagia Kami</p>
                </div>

                {{-- YOUTUBE DINAMIS --}}
                @if (!empty($content['youtube_links']))
                    @foreach ($content['youtube_links'] as $yt)
                        @php $ytId = getYoutubeId($yt); @endphp
                        @if ($ytId)
                            <div class="mb-12 animate-fade-in max-w-4xl mx-auto">
                                <div
                                    class="relative w-full pb-[56.25%] rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white">
                                    <iframe class="absolute top-0 left-0 w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                {{-- FOTO GALERI --}}
                @if ($invitation->galleries->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6" id="photo-grid">
                        @foreach ($invitation->galleries->where('type', 'photo') as $idx => $photo)
                            <div class="group relative aspect-square bg-brand-lightGold rounded-3xl overflow-hidden shadow-sm border-4 border-white cursor-pointer"
                                onclick="openLightbox({{ $idx }})">
                                <img src="{{ asset('storage/' . $photo->file_path) }}"
                                    class="gallery-img w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    alt="Gallery">
                                <div
                                    class="absolute inset-0 bg-brand-charcoal/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="fa-solid fa-magnifying-glass-plus text-white text-2xl"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <div id="lightbox"
                class="fixed inset-0 z-[200] bg-brand-charcoal/95 backdrop-blur-lg hidden flex-col items-center justify-center p-4">
                <button onclick="closeLightbox()"
                    class="absolute top-6 right-6 text-white text-3xl hover:text-brand-gold transition-colors z-[210]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <button onclick="prevImg()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white text-4xl p-4 z-[210]">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button onclick="nextImg()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white text-4xl p-4 z-[210]">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <div class="relative max-w-5xl max-h-[80vh] flex items-center justify-center select-none"
                    id="lightbox-container">
                    <img id="lightbox-img" src=""
                        class="max-w-full max-h-[80vh] rounded-xl shadow-2xl object-contain transition-all duration-500">
                </div>
            </div>
        @endif

        {{-- HADIAH / REKENING (TOGGLE) --}}
        @if (!empty($content['is_gift_active']) && !empty($content['banks']))
            <section id="hadiah"
                class="py-24 px-6 bg-brand-white relative overflow-hidden border-t border-brand-lightGold/20">
                <div
                    class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
                </div>

                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <i class="fa-solid fa-gift text-brand-gold text-3xl mb-4 opacity-70"></i>
                        <h2 class="text-4xl font-serif italic text-brand-charcoal mb-4">Kado Digital</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mb-6"></div>
                        <p class="text-sm text-gray-500 font-light leading-relaxed max-w-lg mx-auto">
                            Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika Anda ingin
                            memberikan tanda kasih, Anda dapat mengirimkannya melalui:
                        </p>
                    </div>
                    @php
                        // Ambil semua logo bank yang aktif untuk dipasangkan dengan data inputan user
                        $bankLogos = \App\Models\Bank::where('is_active', true)->pluck('logo', 'name')->toArray();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                        @foreach ($content['banks'] as $idx => $bank)
                            <div
                                class="group relative p-10 bg-brand-elegant/40 rounded-[3rem] border border-brand-lightGold/20 backdrop-blur-sm transition-all duration-500 hover:shadow-2xl hover:shadow-brand-gold/10 hover:-translate-y-2">
                                <div class="flex flex-col items-center">

                                    {{-- LINGKARAN LOGO --}}
                                    <div
                                        class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm border border-brand-lightGold/30 mb-6 overflow-hidden p-4">
                                        @php
                                            $logoPath = $bankLogos[$bank['name']] ?? null;
                                        @endphp

                                        @if ($logoPath)
                                            @if (str_starts_with($logoPath, 'http'))
                                                {{-- Tampilkan jika URL External (Wikipedia/Wikimedia) --}}
                                                <img src="{{ $logoPath }}" alt="{{ $bank['name'] }}"
                                                    class="w-full h-full object-contain">
                                            @else
                                                {{-- Tampilkan jika File Lokal (Storage) --}}
                                                <img src="{{ asset('storage/' . $logoPath) }}"
                                                    alt="{{ $bank['name'] }}" class="w-full h-full object-contain">
                                            @endif
                                        @else
                                            {{-- Fallback ke Ikon jika logo tidak ada di database --}}
                                            <i class="fa-solid fa-building-columns text-brand-gold text-2xl"></i>
                                        @endif
                                    </div>

                                    <p class="text-sm uppercase tracking-widest text-brand-charcoal mb-4 font-bold">
                                        {{ $bank['name'] }}</p>
                                    <p class="text-[10px] uppercase tracking-[0.3em] text-brand-gold mb-2 font-bold">
                                        Nomor Rekening</p>
                                    <h3 id="rek-{{ $idx }}"
                                        class="text-3xl font-serif font-bold text-brand-charcoal mb-2 tracking-widest">
                                        {{ $bank['account_number'] }}</h3>
                                    <p class="text-sm text-gray-400 italic mb-8 font-light">a.n
                                        {{ $bank['account_name'] }}</p>

                                    <button onclick="copyToClipboard('rek-{{ $idx }}', this)"
                                        class="w-full py-4 bg-brand-gold text-white rounded-2xl text-[11px] font-bold uppercase tracking-[0.2em] transition-all duration-300 hover:bg-brand-charcoal shadow-lg shadow-brand-gold/20 active:scale-95">
                                        <i class="fa-regular fa-copy mr-2"></i>
                                        <span>Salin Nomor</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="copy-toast"
                        class="fixed bottom-28 left-1/2 -translate-x-1/2 z-[300] px-8 py-3.5 bg-brand-charcoal/90 backdrop-blur-md text-white text-[10px] rounded-full tracking-[0.3em] uppercase font-bold opacity-0 transition-all duration-500 pointer-events-none shadow-2xl border border-white/10">
                        Berhasil Disalin
                    </div>
                </div>
            </section>
        @endif

        {{-- UCAPAN & DOA (HASIL RSVP) - (TOGGLE SHOW/HIDE UCAPAN) --}}
        @if (!empty($content['is_wishes_active']))
            <section id="guest-stats"
                class="py-20 px-6 bg-brand-elegant/30 relative overflow-hidden border-t border-brand-lightGold/20">
                <div class="max-w-4xl mx-auto relative z-10">
                    <div class="text-center mb-12">
                        <p class="text-[10px] tracking-[0.4em] uppercase text-brand-gold mb-3 font-semibold">Guest
                            Participation</p>
                        <h2 class="text-3xl font-serif italic text-brand-charcoal">Kehadiran & Doa</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/20 mx-auto mt-4"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10">
                        <div
                            class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-brand-lightGold/20 flex items-center gap-6 transition-transform hover:scale-[1.02] duration-300">
                            <div
                                class="w-16 h-16 bg-brand-elegant rounded-full flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-users text-brand-gold text-2xl"></i>
                            </div>
                            <div>
                                <h4 id="total-attendance" class="text-4xl font-serif font-bold text-brand-charcoal">0
                                </h4>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-medium">Orang Akan
                                    Hadir</p>
                            </div>
                        </div>
                        <div
                            class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-brand-lightGold/20 flex items-center gap-6 transition-transform hover:scale-[1.02] duration-300">
                            <div
                                class="w-16 h-16 bg-brand-elegant rounded-full flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-comment-dots text-brand-gold text-2xl"></i>
                            </div>
                            <div>
                                <h4 id="total-wishes" class="text-4xl font-serif font-bold text-brand-charcoal">0</h4>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-medium">Ucapan
                                    Hangat</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-16 bg-white/60 backdrop-blur-md rounded-[3rem] border border-brand-lightGold/20 p-6 md:p-10 shadow-sm relative overflow-hidden">
                        <div
                            class="flex items-center justify-between mb-8 border-b border-brand-lightGold/10 pb-4 px-2">
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-brand-gold">Ucapan
                                Sahabat & Keluarga</span>
                            <i class="fa-solid fa-feather-pointed text-brand-gold/40"></i>
                        </div>

                        <div id="wishes-container" class="space-y-6">
                            {{-- Ucapan akan di-render di sini oleh JavaScript --}}
                        </div>

                        <div class="mt-12 text-center">
                            <button id="btn-load-more" onclick="loadMoreWishes()"
                                class="px-8 py-3 bg-transparent border border-brand-gold/40 text-brand-gold rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-white transition-all duration-500 shadow-sm">
                                Lihat Ucapan Lainnya
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- FORM RSVP SELALU AKTIF (TERSEMBUNYI DALAM MODAL) --}}
        <section id="rsvp-modal" class="fixed inset-0 z-[100] invisible transition-all duration-500">
            <div onclick="closeRSVP()"
                class="absolute inset-0 bg-brand-charcoal/40 backdrop-blur-sm opacity-0 transition-opacity duration-500"
                id="rsvp-overlay"></div>

            <div id="rsvp-content"
                class="absolute bottom-0 left-0 right-0 bg-brand-white rounded-t-[3rem] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] transform translate-y-full transition-transform duration-500 ease-out px-6 pb-10 pt-4 max-w-2xl mx-auto">
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8"></div>

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-serif text-brand-charcoal mb-2">RSVP & Ucapan</h2>
                    <p class="text-xs text-brand-gold tracking-widest uppercase">Konfirmasi Kehadiran Anda</p>
                </div>

                <form id="rsvpForm" class="space-y-4 text-left" onsubmit="submitRSVP(event)">
                    <div>
                        <input type="text" id="input-nama-rsvp" name="name" placeholder="Nama Lengkap"
                            class="w-full p-4 rounded-2xl bg-brand-elegant border border-brand-lightGold/50 focus:outline-none focus:border-brand-gold text-sm placeholder-gray-500"
                            required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir"
                            class="py-3 rounded-xl border border-brand-lightGold/50 text-sm font-medium transition-all bg-brand-elegant text-gray-600">
                            <i class="fa-solid fa-check mr-2"></i>Hadir
                        </button>
                        <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen"
                            class="py-3 rounded-xl border border-brand-lightGold/50 text-sm font-medium transition-all bg-brand-elegant text-gray-600">
                            <i class="fa-solid fa-xmark mr-2"></i>Absen
                        </button>
                        <input type="hidden" name="status" id="input-status" required>
                    </div>

                    <div id="guest-selection" class="hidden animate-fade-in">
                        <label
                            class="text-[10px] uppercase tracking-[0.2em] text-brand-gold ml-1 mb-2 block font-semibold">Membawa
                            Tamu?</label>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectGuestCount(1, this)"
                                class="guest-btn flex-1 py-3 rounded-xl border border-brand-lightGold/30 bg-brand-elegant text-xs text-gray-600 hover:border-brand-gold transition-colors">1
                                Orang</button>
                            <button type="button" onclick="selectGuestCount(2, this)"
                                class="guest-btn flex-1 py-3 rounded-xl border border-brand-lightGold/30 bg-brand-elegant text-xs text-gray-600 hover:border-brand-gold transition-colors">2
                                Orang</button>
                            <button type="button" onclick="selectGuestCount(3, this)"
                                class="guest-btn flex-1 py-3 rounded-xl border border-brand-lightGold/30 bg-brand-elegant text-xs text-gray-600 hover:border-brand-gold transition-colors">+3
                                Orang</button>
                            <input type="hidden" name="guest_count" id="input-guest-count" value="1">
                        </div>
                    </div>

                    <div>
                        <textarea name="message" rows="4" placeholder="Tuliskan doa & ucapan manis Anda..."
                            class="w-full p-4 rounded-2xl bg-brand-elegant border border-brand-lightGold/50 focus:outline-none focus:border-brand-gold text-sm placeholder-gray-500"
                            required></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeRSVP()"
                            class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-semibold text-sm hover:bg-gray-200 transition-colors">Batal</button>
                        <button type="submit" id="btnSubmitRsvp"
                            class="flex-[2] py-4 bg-brand-gold hover:bg-brand-charcoal text-white rounded-2xl font-semibold text-sm transition-all shadow-lg shadow-brand-gold/20">Kirim
                            RSVP</button>
                    </div>
                </form>
            </div>
        </section>

        <footer class="py-12 px-6 bg-brand-white border-t border-brand-lightGold/20 text-center">
            <div class="max-w-md mx-auto">
                <div class="mb-4 opacity-30 italic font-serif text-brand-gold text-lg">
                    {{ substr($pria, 0, 1) }} & {{ substr($wanita, 0, 1) }}
                </div>

                <p class="text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-4 font-light">Terima kasih atas doa &
                    restu Anda</p>

                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-elegant border border-brand-lightGold/30 shadow-sm">
                    <span class="text-[10px] text-gray-500 font-sans uppercase tracking-wider">Created with love
                        by</span>
                    <a href="https://instagram.com/ruangrestu.undangan" target="_blank" rel="noopener noreferrer"
                        class="text-[11px] font-semibold text-brand-gold hover:text-brand-charcoal transition-colors flex items-center gap-1">
                        <i class="fa-brands fa-instagram text-sm"></i> @ruangrestu.undangan
                    </a>
                </div>

                <p class="mt-8 text-[9px] text-gray-300 font-light italic">
                    &copy; 2026 {{ $pria }} & {{ $wanita }} Wedding Invitation.
                </p>
            </div>
        </footer>

    </main>

    <div id="fab-container"
        class="fixed right-5 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000">
        <div class="relative flex items-center group">
            <div id="music-info"
                class="absolute right-full mr-3 px-3 py-1 bg-brand-white/90 backdrop-blur border border-brand-lightGold/50 rounded-lg text-brand-gold text-xs whitespace-nowrap shadow-md opacity-0 translate-x-4 pointer-events-none transition-all duration-500 group-hover:opacity-100 group-hover:translate-x-0">
                🎵 Memutar Musik
            </div>
            <button id="btn-music" onclick="toggleMusic()"
                class="w-11 h-11 bg-brand-white backdrop-blur border border-brand-lightGold/70 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-white transition-all">
                <i class="fa-solid fa-music animate-spin-slow" id="icon-music"></i>
            </button>
        </div>

        <button id="btn-scroll" onclick="toggleAutoScroll()"
            class="w-11 h-11 bg-brand-white backdrop-blur border border-brand-lightGold/70 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-white transition-all">
            <i class="fa-solid fa-angles-down" id="icon-scroll"></i>
        </button>
    </div>

    <nav id="bottom-nav"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 floating-nav rounded-full transition-transform duration-700 translate-y-32 shadow-2xl">
        <ul class="flex justify-around items-center h-16 w-[320px] md:w-[400px] px-3">
            <li><a href="#home"
                    class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5"><i
                        class="fa-solid fa-house text-xl"></i><span>Home</span></a></li>
            @if (!empty($content['is_gallery_active']))
                <li><a href="#gallery"
                        class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5"><i
                            class="fa-solid fa-images text-xl"></i><span>Gallery</span></a></li>
            @endif
            @if (!empty($content['is_event_active']))
                <li><a href="#lokasi"
                        class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5"><i
                            class="fa-solid fa-map-location text-xl"></i><span>Lokasi</span></a></li>
            @endif
            <li><a href="javascript:void(0)" onclick="openRSVP()"
                    class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5"><i
                        class="fa-solid fa-envelope text-xl"></i><span>RSVP</span></a></li>
        </ul>
    </nav>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        let guestName = urlParams.get('to');
        if (guestName) {
            guestName = decodeURIComponent(guestName);
        } else {
            guestName = 'Tamu Undangan';
        }

        const guestNameEl = document.getElementById('guest-name');
        if (guestNameEl) guestNameEl.innerText = guestName;

        const inputRSVP = document.getElementById('input-nama-rsvp');
        if (inputRSVP) {
            inputRSVP.value = guestName !== 'Tamu Undangan' ? guestName : '';
        }

        const audio = document.getElementById('bg-music');
        let isMusicPlaying = false;
        let isAutoScrolling = false;
        let scrollInterval;

        function openInvitation() {
            document.getElementById('cover-page').classList.add('-translate-y-full');
            document.body.style.overflowY = 'auto';
            document.getElementById('main-content').classList.remove('opacity-0');
            document.getElementById('fab-container').classList.remove('opacity-0');
            document.getElementById('bottom-nav').classList.remove('translate-y-32');

            toggleMusic(true);
            toggleAutoScroll(true);
        }

        function toggleMusic(forcePlay = false) {
            const icon = document.getElementById('icon-music');
            if (isMusicPlaying && !forcePlay) {
                audio.pause();
                isMusicPlaying = false;
                icon.classList.remove('fa-music', 'animate-spin');
                icon.classList.add('fa-volume-xmark');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.remove('fa-volume-xmark');
                    icon.classList.add('fa-music', 'animate-spin');
                }).catch(() => {
                    console.log("Autoplay dicegah browser.");
                });
            }
        }

        function toggleAutoScroll(forceStart = false) {
            const btn = document.getElementById('btn-scroll');
            const icon = document.getElementById('icon-scroll');
            if (isAutoScrolling && !forceStart) {
                clearInterval(scrollInterval);
                isAutoScrolling = false;
                btn.classList.remove('bg-brand-gold', 'text-white');
                btn.classList.add('bg-brand-white', 'text-brand-gold');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-angles-down');
            } else {
                isAutoScrolling = true;
                btn.classList.remove('bg-brand-white', 'text-brand-gold');
                btn.classList.add('bg-brand-gold', 'text-white');
                icon.classList.remove('fa-angles-down');
                icon.classList.add('fa-pause');
                scrollInterval = setInterval(() => {
                    window.scrollBy({
                        top: 1,
                        behavior: 'auto'
                    });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) toggleAutoScroll();
                }, 35);
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const musicInfo = document.getElementById('music-info');
            setTimeout(() => {
                if (musicInfo) {
                    musicInfo.classList.remove('opacity-0', 'translate-x-4', 'pointer-events-none');
                    musicInfo.classList.add('opacity-100', 'translate-x-0');
                    setTimeout(() => {
                        musicInfo.classList.remove('opacity-100', 'translate-x-0');
                        musicInfo.classList.add('opacity-0', 'translate-x-4',
                            'pointer-events-none');
                        setTimeout(() => musicInfo.classList.remove('pointer-events-none'), 500);
                    }, 3000);
                }
            }, 1200);
        });

        window.addEventListener('wheel', () => {
            if (isAutoScrolling) toggleAutoScroll();
        }, {
            passive: true
        });
        window.addEventListener('touchmove', () => {
            if (isAutoScrolling) toggleAutoScroll();
        }, {
            passive: true
        });

        // ==========================================
        // SCRIPT COUNTDOWN
        // ==========================================
        const targetDateStr = "{{ $countdownTarget }}";

        if (targetDateStr.length > 10) {
            const weddingDate = new Date(targetDateStr).getTime();

            if (!isNaN(weddingDate)) {
                const countdownFunction = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = weddingDate - now;

                    if (distance < 0) {
                        clearInterval(countdownFunction);
                        document.getElementById("days").innerText = "00";
                        document.getElementById("hours").innerText = "00";
                        document.getElementById("minutes").innerText = "00";
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

                    document.getElementById("days").innerText = days < 10 ? "0" + days : days;
                    document.getElementById("hours").innerText = hours < 10 ? "0" + hours : hours;
                    document.getElementById("minutes").innerText = minutes < 10 ? "0" + minutes : minutes;

                }, 1000);
            } else {
                console.error("Format waktu gagal diproses oleh sistem Countdown.");
            }
        }

        function toggleStories() {
            const extraStories = document.querySelectorAll('.extra-story');
            const btn = document.getElementById('btn-read-more');
            let isHidden = false;

            extraStories.forEach(story => {
                if (story.classList.contains('hidden')) {
                    story.classList.remove('hidden');
                    story.classList.add('animate-fade-in');
                    isHidden = true;
                } else {
                    story.classList.add('hidden');
                    story.classList.remove('animate-fade-in');
                }
            });

            btn.innerText = isHidden ? 'Sembunyikan Cerita' : 'Baca Selengkapnya';
        }

        const images = Array.from(document.querySelectorAll('.gallery-img')).map(img => img.src);
        let currentIndex = 0;

        function openLightbox(index) {
            if (images.length === 0) return;
            currentIndex = index;
            updateLightbox();
            const modal = document.getElementById('lightbox');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const modal = document.getElementById('lightbox');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function updateLightbox() {
            const imgElement = document.getElementById('lightbox-img');
            imgElement.style.opacity = '0';
            setTimeout(() => {
                imgElement.src = images[currentIndex];
                imgElement.style.opacity = '1';
            }, 200);
        }

        function nextImg() {
            currentIndex = (currentIndex + 1) % images.length;
            updateLightbox();
        }

        function prevImg() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateLightbox();
        }

        let touchStartX = 0,
            touchEndX = 0;
        const lightboxEl = document.getElementById('lightbox');
        if (lightboxEl) {
            lightboxEl.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX);
            lightboxEl.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX - 50) nextImg();
                if (touchEndX > touchStartX + 50) prevImg();
            });
        }
        document.addEventListener('keydown', (e) => {
            if (lightboxEl && !lightboxEl.classList.contains('hidden')) {
                if (e.key === "ArrowRight") nextImg();
                if (e.key === "ArrowLeft") prevImg();
                if (e.key === "Escape") closeLightbox();
            }
        });

        function copyToClipboard(id, btn) {
            const textToCopy = document.getElementById(id).innerText;
            const toast = document.getElementById('copy-toast');
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Tersalin!</span>';
                btn.classList.replace('bg-brand-gold', 'bg-green-600');
                toast.classList.replace('opacity-0', 'opacity-100');
                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.replace('bg-green-600', 'bg-brand-gold');
                    toast.classList.replace('opacity-100', 'opacity-0');
                }, 2000);
            });
        }

        let hasShownRSVPAtEnd = false;

        function openRSVP() {
            const modal = document.getElementById('rsvp-modal');
            const overlay = document.getElementById('rsvp-overlay');
            const content = document.getElementById('rsvp-content');
            modal.classList.remove('invisible');
            setTimeout(() => {
                overlay.classList.replace('opacity-0', 'opacity-100');
                content.classList.replace('translate-y-full', 'translate-y-0');
            }, 10);
        }

        function closeRSVP() {
            const overlay = document.getElementById('rsvp-overlay');
            const content = document.getElementById('rsvp-content');
            const modal = document.getElementById('rsvp-modal');
            overlay.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('translate-y-0', 'translate-y-full');
            setTimeout(() => modal.classList.add('invisible'), 500);
        }

        function selectAttendance(status) {
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');
            document.getElementById('input-status').value = status;

            if (status === 'Hadir') {
                btnHadir.classList.add('bg-brand-gold', 'text-white', 'border-brand-gold');
                btnAbsen.classList.remove('bg-brand-gold', 'text-white', 'border-brand-gold');
                guestDiv.classList.remove('hidden');
            } else {
                btnAbsen.classList.add('bg-brand-gold', 'text-white', 'border-brand-gold');
                btnHadir.classList.remove('bg-brand-gold', 'text-white', 'border-brand-gold');
                guestDiv.classList.add('hidden');
            }
        }

        function selectGuestCount(count, btnElement) {
            // 1. Ubah value yang akan dikirim ke database
            document.getElementById('input-guest-count').value = count;

            // 2. Matikan semua tombol (kembalikan ke warna abu-abu elegan)
            document.querySelectorAll('.guest-btn').forEach(btn => {
                btn.classList.remove('border-brand-gold', 'text-brand-gold', 'font-bold', 'bg-brand-gold/10');
                btn.classList.add('border-brand-lightGold/30', 'text-gray-600', 'bg-brand-elegant');
            });

            // 3. Nyalakan tombol yang baru saja diklik (ubah ke warna emas)
            btnElement.classList.remove('border-brand-lightGold/30', 'text-gray-600', 'bg-brand-elegant');
            btnElement.classList.add('border-brand-gold', 'text-brand-gold', 'font-bold', 'bg-brand-gold/10');
        }

        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50) {
                if (!hasShownRSVPAtEnd) {
                    openRSVP();
                    hasShownRSVPAtEnd = true;
                    if (isAutoScrolling) toggleAutoScroll();
                }
            }
        }, {
            passive: true
        });

        // ==========================================
        // SISTEM RSVP & UCAPAN (REAL DATABASE)
        // ==========================================
        let countAttendance = {{ $invitation->rsvps->where('status_rsvp', 'hadir')->count() }};
        let countWishes = {{ $invitation->rsvps->count() }};
        const totalHadirEl = document.getElementById('total-attendance');
        const totalUcapanEl = document.getElementById('total-wishes');
        if (totalHadirEl) totalHadirEl.innerText = countAttendance;
        if (totalUcapanEl) totalUcapanEl.innerText = countWishes;

        function submitRSVP(event) {
            event.preventDefault(); // Mencegah form reload halaman

            const status = document.getElementById('input-status').value;
            const nama = document.getElementById('input-nama-rsvp').value;
            const pesan = document.querySelector('textarea[name="message"]').value;
            const guestCount = document.getElementById('input-guest-count').value;

            if (!status || !nama || !pesan) return alert('Mohon lengkapi nama, kehadiran, dan ucapan Anda.');

            const btn = document.getElementById('btnSubmitRsvp');
            const originalBtnText = btn.innerHTML;
            btn.innerHTML = 'MENGIRIM...';
            btn.disabled = true;

            // Ambil data dari Guest URL jika ada (Penting untuk validasi Controller)
            const urlParams = new URLSearchParams(window.location.search);
            const guestId = urlParams.get('id'); // Opsional jika kamu menggunakan ID di URL

            // Siapkan data untuk dikirim ke Controller
            const formData = {
                guest_name: nama,
                status_rsvp: status === 'Hadir' ? 'hadir' : (status === 'Tidak Hadir' ? 'tidak_hadir' : 'ragu'),
                pax: guestCount,
                message: pesan,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            // Kirim via AJAX Fetch ke Route: /{slug}/rsvp
            fetch("{{ route('rsvp.store', $invitation->slug) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        countWishes++;
                        if (status === "Hadir") countAttendance++;

                        if (totalHadirEl) totalHadirEl.innerText = countAttendance;
                        if (totalUcapanEl) totalUcapanEl.innerText = countWishes;

                        alert('Terima kasih, RSVP Anda telah tersimpan di sistem!');
                        closeRSVP();

                        // Tambahkan Card Ucapan Baru secara Real-time ke tampilan
                        addNewWishCard(nama, pesan, 'Baru saja');
                    } else {
                        alert('Gagal mengirim RSVP. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada server.');
                })
                .finally(() => {
                    btn.innerHTML = originalBtnText;
                    btn.disabled = false;
                    document.getElementById('rsvpForm').reset(); // Bersihkan form
                });
        }

        // Fungsi Helper untuk menambah kartu ucapan ke tampilan HTML (Berdasarkan Tema)
        function addNewWishCard(nama, pesan, waktu) {
            const container = document.getElementById('wishes-container');
            if (!container) return;

            const card = document.createElement('div');

            // CEK TEMA SAAT INI UNTUK CSS CLASS
            const isDarkTheme = document.body.classList.contains('bg-brand-dark');

            if (isDarkTheme) {
                // Style untuk Template 2 (Dark)
                card.className =
                    'wish-card bg-white/5 p-6 rounded-[1.5rem] border border-white/10 animate-fade-in-up transition-all duration-500 hover:bg-white/10 hover:border-brand-gold/30';
                card.innerHTML =
                    `<div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-2"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-brand-gold/20 flex items-center justify-center border border-brand-gold/30"><i class="fa-solid fa-user text-[10px] text-brand-gold"></i></div><h5 class="text-sm font-serif font-bold text-white tracking-wide">${nama}</h5></div><span class="text-[8px] text-brand-gold/60 italic uppercase tracking-[0.2em] flex items-center"><i class="fa-regular fa-clock mr-1.5"></i> ${waktu}</span></div><div class="relative"><i class="fa-solid fa-quote-left absolute -top-2 -left-2 text-brand-gold/10 text-2xl"></i><p class="text-[13px] text-white/70 leading-relaxed font-light italic pl-4">${pesan}</p></div>`;
            } else {
                // Style untuk Template 1 (Light)
                card.className =
                    'wish-card bg-brand-elegant/40 p-6 rounded-[2rem] border border-brand-lightGold/20 animate-fade-in transition-all hover:bg-white hover:shadow-md';
                card.innerHTML =
                    `<div class="flex flex-col md:flex-row md:items-center justify-between mb-3 gap-1"><h5 class="text-sm font-serif font-bold text-brand-charcoal">${nama}</h5><span class="text-[9px] text-gray-400 italic uppercase tracking-wider"><i class="fa-regular fa-clock mr-1"></i> ${waktu}</span></div><p class="text-xs text-gray-500 leading-relaxed font-light italic">"${pesan}"</p>`;
            }

            container.prepend(card);
        }

        // ==========================================
        // LOAD UCAPAN DARI DATABASE
        // ==========================================
        const allWishes = [
            @foreach ($invitation->rsvps()->latest()->get() as $wish)
                {
                    nama: "{{ addslashes($wish->guest_name) }}",
                    pesan: "{{ addslashes(trim(preg_replace('/\s\s+/', ' ', $wish->message))) }}",
                    waktu: "{{ $wish->created_at->diffForHumans() }}"
                },
            @endforeach
        ];

        let displayedCount = 0;

        function renderWishes() {
            const container = document.getElementById('wishes-container');
            const btnLoadMore = document.getElementById('btn-load-more');
            if (!container) return;

            // Bersihkan placeholder/loading jika ada pada pemanggilan pertama
            if (displayedCount === 0) container.innerHTML = '';

            let nextLimit = displayedCount + 3;
            const wishesToDisplay = allWishes.slice(displayedCount, nextLimit);

            wishesToDisplay.forEach(wish => {
                // Panggil fungsi Helper untuk membuat elemen HTML
                addNewWishCard(wish.nama, wish.pesan, wish.waktu);
            });

            displayedCount = Math.min(nextLimit, allWishes.length);

            // Sembunyikan tombol jika semua data sudah ditampilkan
            if (displayedCount >= allWishes.length && btnLoadMore) {
                btnLoadMore.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderWishes();
        });

        function loadMoreWishes() {
            renderWishes();
        }
    </script>
</body>

</html>
