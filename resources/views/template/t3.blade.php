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
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400;1,600&family=Montserrat:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        theme: {
                            bg: '#F9F8F3', // Warm white / Alabaster
                            primary: '#5C715E', // Sage Green
                            secondary: '#D4B59E', // Soft Terracotta / Peach
                            text: '#2C352D', // Dark Green/Gray
                            muted: '#8E9A90', // Muted Sage
                            card: '#FFFFFF',
                        }
                    },
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'serif'],
                        sans: ['"Montserrat"', 'sans-serif'],
                    },
                    animation: {
                        'slow-zoom': 'zoom 25s infinite alternate',
                        'float': 'floating 4s ease-in-out infinite',
                        'spin-slow': 'spin 8s linear infinite',
                    },
                    keyframes: {
                        zoom: {
                            '0%': { transform: 'scale(1)' },
                            '100%': { transform: 'scale(1.15)' },
                        },
                        floating: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-12px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar {
            width: 6px;
            background: #F9F8F3;
        }
        ::-webkit-scrollbar-thumb {
            background: #D4B59E;
            border-radius: 10px;
        }

        body {
            overflow-y: hidden;
            background-color: #F9F8F3;
            color: #2C352D;
            -webkit-font-smoothing: antialiased;
        }

        .floating-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(92, 113, 94, 0.15);
            box-shadow: 0 10px 40px rgba(44, 53, 45, 0.08);
        }

        .text-protected {
            text-shadow: 0 2px 15px rgba(255, 255, 255, 0.8);
        }
        
        .text-protected-dark {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
        }

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        .elegant-card {
            background: #FFFFFF;
            border: 1px solid rgba(92, 113, 94, 0.1);
            box-shadow: 0 15px 35px rgba(92, 113, 94, 0.05);
            transition: all 0.4s ease;
        }

        .elegant-card:hover {
            box-shadow: 0 20px 45px rgba(92, 113, 94, 0.1);
            transform: translateY(-5px);
        }

        .arch-shape {
            border-radius: 999px 999px 20px 20px;
        }

        .input-rustic {
            background: #F9F8F3;
            border: 1px solid rgba(92, 113, 94, 0.2);
            color: #2C352D;
            transition: all 0.3s ease;
        }

        .input-rustic:focus {
            border-color: #5C715E;
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(92, 113, 94, 0.1);
            outline: none;
        }

        .scroll-custom::-webkit-scrollbar {
            width: 4px;
        }
        .scroll-custom::-webkit-scrollbar-track {
            background: rgba(92, 113, 94, 0.05);
        }
        .scroll-custom::-webkit-scrollbar-thumb {
            background: #5C715E;
            border-radius: 10px;
        }

        .leaf-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.83-28.285 28.284-.828-.828L54.627 0zm-16.97 0l2.485 2.485-26.87 26.87-2.485-2.485L37.657 0zm11.314 0l1.657 1.657-27.698 27.7-1.657-1.657L48.97 0zM12.204 0l3.314 3.314-25.213 25.213-3.314-3.314L12.204 0zm11.314 0l4.142 4.142-24.385 24.385-4.142-4.142L23.518 0zm-16.97 60l-.83-.83 28.285-28.284.828.828L6.548 60zm16.97 0l-2.485-2.485 26.87-26.87 2.485 2.485L23.518 60zm-11.314 0l-1.657-1.657 27.698-27.7 1.657 1.657L12.204 60zm25.456 0l-3.314-3.314 25.213-25.213 3.314 3.314L37.66 60zm-11.314 0l-4.142-4.142 24.385-24.385 4.142 4.142L26.346 60z' fill='%235c715e' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
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
                    : 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop'));

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
                ? 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=1887&auto=format&fit=crop'
                : 'https://images.unsplash.com/photo-1550927407-50e2bd128b81?q=80&w=1887&auto=format&fit=crop',
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
                ? 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=1887&auto=format&fit=crop'
                : 'https://images.unsplash.com/photo-1550927407-50e2bd128b81?q=80&w=1887&auto=format&fit=crop',
        ];

        // Nama untuk di Cover
        $pria = $content['groom_nickname'] ?? 'Romeo';
        $wanita = $content['bride_nickname'] ?? 'Juliet';
        $coupleNameCover = $isGroomFirst ? "$pria & $wanita" : "$wanita & $pria";

        // ==========================================
        // LOGIKA COUNTDOWN
        // ==========================================
        $countdownDate = '';
        $rawTime = '';

        if (!empty($content['events'][0]['date'])) {
            $countdownDate = $content['events'][0]['date'];
            $rawTime = $content['events'][0]['time'] ?? '';
        } elseif (!empty($content['akad_date'])) {
            $countdownDate = $content['akad_date'];
            $rawTime = $content['akad_time'] ?? '';
        }

        $countdownTime = '00:00';
        if (!empty($rawTime)) {
            if (preg_match('/([0-9]{1,2}:[0-9]{2})/', $rawTime, $matches)) {
                $countdownTime = str_pad($matches[1], 5, '0', STR_PAD_LEFT);
            }
        }

        $coverDate = $countdownDate ? \Carbon\Carbon::parse($countdownDate)->format('d . m . Y') : 'TBA';
        $countdownTarget = $countdownDate ? $countdownDate . 'T' . $countdownTime . ':00' : '';
    @endphp
</head>

<body class="bg-theme-bg text-theme-text font-sans antialiased relative selection:bg-theme-secondary selection:text-white">

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music_id) ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3' }}" type="audio/mpeg">
    </audio>

    {{-- COVER PAGE --}}
    <div id="cover-page" class="fixed inset-0 z-50 flex items-center justify-center w-screen h-screen bg-theme-bg overflow-hidden transition-transform duration-1000 ease-in-out">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[url('{{ $coverImg }}')] bg-cover bg-center animate-slow-zoom"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-theme-bg/90 via-theme-bg/40 to-theme-bg/20 backdrop-blur-[1px]"></div>
        </div>

        <div class="hidden lg:block absolute inset-0 z-10 pointer-events-none p-8">
            <div class="w-full h-full border border-theme-primary/20 rounded-3xl"></div>
        </div>

        <div class="relative z-20 w-full max-w-[90%] md:max-w-2xl lg:max-w-3xl px-6 py-12 flex flex-col items-center justify-center min-h-[600px] mt-24">
            
            <p class="text-[10px] md:text-xs tracking-[0.4em] uppercase text-theme-primary mb-4 font-medium text-center">
                The Wedding Of
            </p>

            <div class="text-center mb-6">
                <h1 class="text-6xl md:text-8xl lg:text-9xl font-serif text-theme-text tracking-tight leading-[0.9] flex flex-col md:flex-row items-center justify-center md:gap-6 text-protected">
                    {{ $coupleNameCover }}
                </h1>
            </div>

            <div class="flex items-center justify-center gap-4 mb-10 w-full max-w-sm">
                <div class="h-[1px] flex-1 bg-theme-primary/30"></div>
                <p class="text-xs md:text-sm font-sans tracking-[0.3em] text-theme-primary uppercase whitespace-nowrap font-medium">
                    {{ $coverDate }}
                </p>
                <div class="h-[1px] flex-1 bg-theme-primary/30"></div>
            </div>

            <div class="elegant-card p-8 md:p-10 rounded-2xl mb-12 relative w-full max-w-md text-center">
                <p class="text-xs text-theme-muted mb-3 tracking-widest uppercase italic">
                    {{ $content['cover_greeting'] ?? 'Dear Bapak/Ibu/Saudara/i' }}
                </p>
                <h2 id="guest-name" class="text-2xl md:text-3xl font-serif text-theme-text mb-4 leading-tight">
                    Tamu Undangan
                </h2>
                @if (!empty($content['akad_location']))
                    <div class="flex items-center justify-center gap-2 text-[10px] text-theme-primary tracking-widest border-t border-theme-primary/10 pt-4">
                        <i class="fa-solid fa-location-dot"></i>
                        <span class="uppercase">{{ $content['akad_location'] }}</span>
                    </div>
                @endif
            </div>

            <button onclick="openInvitation()" class="relative px-10 py-4 bg-theme-primary text-white rounded-full transition-all duration-500 hover:-translate-y-1 hover:shadow-xl active:scale-95 flex items-center justify-center gap-3 overflow-hidden group">
                <div class="absolute inset-0 bg-theme-text opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <i class="fa-solid fa-envelope-open text-sm relative z-10"></i>
                <span class="font-medium uppercase tracking-[0.2em] text-xs relative z-10">Buka Undangan</span>
            </button>
        </div>
    </div>

    <main id="main-content" class="min-h-screen pb-28 opacity-0 transition-opacity duration-1000 leaf-pattern bg-theme-bg">

        {{-- HOME / QUOTES --}}
        <section id="home" class="min-h-screen flex flex-col items-center justify-center text-center p-6 md:p-12 relative overflow-hidden">
            
            <div class="relative z-10 max-w-4xl w-full mt-10">
                <div class="mb-16 md:mb-24 animate-fade-in flex flex-col items-center">
                    <i class="fa-brands fa-pagelines text-4xl text-theme-secondary mb-6 opacity-80"></i>
                    <h3 class="font-serif italic text-2xl md:text-4xl text-theme-text mb-6 leading-relaxed max-w-3xl">
                        "{{ $content['quotes'] ?? 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa kasih dan sayang.' }}"
                    </h3>
                    <div class="w-16 h-[1px] bg-theme-secondary mb-6"></div>
                    <p class="text-sm text-theme-muted max-w-lg mx-auto leading-loose tracking-wide font-light px-4">
                        Dengan memohon rahmat dan ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri momen bahagia pernikahan kami.
                    </p>
                </div>

                <div class="elegant-card md:max-w-3xl mx-auto p-8 md:p-12 rounded-[2rem] relative overflow-hidden">
                    <p class="text-[10px] md:text-xs tracking-[0.4em] uppercase text-theme-primary mb-10 font-semibold">Menuju Hari Bahagia</p>

                    <div class="flex flex-row justify-center items-center gap-6 md:gap-12">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 md:w-24 md:h-24 rounded-full border border-theme-secondary/50 flex items-center justify-center bg-theme-secondary/5 mb-3">
                                <span id="days" class="text-2xl md:text-4xl font-serif text-theme-primary">00</span>
                            </div>
                            <span class="text-[9px] uppercase tracking-[0.2em] text-theme-muted">Hari</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 md:w-24 md:h-24 rounded-full border border-theme-secondary/50 flex items-center justify-center bg-theme-secondary/5 mb-3">
                                <span id="hours" class="text-2xl md:text-4xl font-serif text-theme-primary">00</span>
                            </div>
                            <span class="text-[9px] uppercase tracking-[0.2em] text-theme-muted">Jam</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 md:w-24 md:h-24 rounded-full border border-theme-secondary/50 flex items-center justify-center bg-theme-secondary/5 mb-3">
                                <span id="minutes" class="text-2xl md:text-4xl font-serif text-theme-primary">00</span>
                            </div>
                            <span class="text-[9px] uppercase tracking-[0.2em] text-theme-muted">Menit</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MEMPELAI --}}
        <section id="mempelai" class="py-24 px-6 relative overflow-hidden">
            <div class="max-w-6xl mx-auto relative z-10">
                <div class="flex flex-col items-center mb-20 text-center">
                    <span class="text-[10px] tracking-[0.4em] uppercase text-theme-secondary mb-3 font-semibold">The Bride & Groom</span>
                    <h2 class="text-4xl md:text-6xl font-serif text-theme-text italic">Mempelai</h2>
                    <i class="fa-brands fa-pagelines text-2xl text-theme-primary mt-6 opacity-60"></i>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 lg:gap-16 items-center">

                    {{-- KARTU KIRI --}}
                    <div class="flex flex-col md:flex-row items-center gap-8 lg:pr-8 group">
                        <div class="relative w-64 h-80 md:w-72 md:h-[26rem] order-1 md:order-2 flex-shrink-0">
                            <div class="absolute inset-0 border border-theme-primary/30 translate-x-3 translate-y-3 arch-shape z-0 transition-transform group-hover:translate-x-4 group-hover:translate-y-4"></div>
                            <div class="absolute inset-0 overflow-hidden arch-shape shadow-xl z-10 bg-theme-card">
                                <img src="{{ !empty($firstPerson['photo']) ? asset('storage/' . $firstPerson['photo']) : $firstPerson['default_img'] }}"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                            </div>
                        </div>
                        <div class="text-center md:text-right order-2 md:order-1 flex-1">
                            <p class="text-theme-secondary font-serif italic text-2xl mb-2">{{ $firstPerson['label'] }}</p>
                            <h3 class="text-4xl md:text-5xl font-serif text-theme-text mb-6 leading-tight">{{ $firstPerson['name'] }}</h3>
                            <div class="space-y-2">
                                <p class="uppercase text-[9px] tracking-[0.2em] text-theme-muted font-semibold">Putra/i Tercinta dari</p>
                                <p class="text-theme-text text-sm md:text-base">Bapak {{ $firstPerson['father'] }}</p>
                                <p class="text-theme-text text-sm md:text-base">& Ibu {{ $firstPerson['mother'] }}</p>
                            </div>
                            @if (!empty($firstPerson['ig']))
                                <a href="https://instagram.com/{{ $firstPerson['ig'] }}" target="_blank"
                                    class="inline-flex items-center gap-2 mt-6 text-theme-primary hover:text-theme-secondary transition-colors text-xs font-medium tracking-widest uppercase bg-white px-4 py-2 rounded-full border border-theme-primary/10 shadow-sm">
                                    <i class="fa-brands fa-instagram"></i> {{ $firstPerson['ig'] }}
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- KARTU KANAN --}}
                    <div class="flex flex-col md:flex-row items-center gap-8 lg:pl-8 group lg:mt-24">
                        <div class="relative w-64 h-80 md:w-72 md:h-[26rem] flex-shrink-0">
                            <div class="absolute inset-0 border border-theme-primary/30 -translate-x-3 translate-y-3 arch-shape z-0 transition-transform group-hover:-translate-x-4 group-hover:translate-y-4"></div>
                            <div class="absolute inset-0 overflow-hidden arch-shape shadow-xl z-10 bg-theme-card">
                                <img src="{{ !empty($secondPerson['photo']) ? asset('storage/' . $secondPerson['photo']) : $secondPerson['default_img'] }}"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                            </div>
                        </div>
                        <div class="text-center md:text-left flex-1">
                            <p class="text-theme-secondary font-serif italic text-2xl mb-2">{{ $secondPerson['label'] }}</p>
                            <h3 class="text-4xl md:text-5xl font-serif text-theme-text mb-6 leading-tight">{{ $secondPerson['name'] }}</h3>
                            <div class="space-y-2">
                                <p class="uppercase text-[9px] tracking-[0.2em] text-theme-muted font-semibold">Putra/i Tercinta dari</p>
                                <p class="text-theme-text text-sm md:text-base">Bapak {{ $secondPerson['father'] }}</p>
                                <p class="text-theme-text text-sm md:text-base">& Ibu {{ $secondPerson['mother'] }}</p>
                            </div>
                            @if (!empty($secondPerson['ig']))
                                <a href="https://instagram.com/{{ $secondPerson['ig'] }}" target="_blank"
                                    class="inline-flex items-center gap-2 mt-6 text-theme-primary hover:text-theme-secondary transition-colors text-xs font-medium tracking-widest uppercase bg-white px-4 py-2 rounded-full border border-theme-primary/10 shadow-sm">
                                    <i class="fa-brands fa-instagram"></i> {{ $secondPerson['ig'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TURUT MENGUNDANG --}}
                @if (!empty($content['is_turut_mengundang_active']) && !empty($content['turut_mengundang']))
                    <div class="mt-32 max-w-4xl mx-auto elegant-card rounded-3xl p-10 text-center relative">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-theme-bg px-4 py-2 text-[10px] tracking-[0.4em] uppercase text-theme-primary font-semibold border border-theme-primary/20 rounded-full">
                            Turut Mengundang
                        </div>
                        <div class="flex flex-wrap justify-center gap-x-8 gap-y-4 mt-4">
                            @foreach ($content['turut_mengundang'] as $nama)
                                @if (trim($nama) !== '')
                                    <span class="text-theme-text text-sm font-medium">{{ trim($nama) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- STORY MEMPELAI --}}
        @if (!empty($content['is_story_active']) && !empty($content['love_stories']))
            <section id="story" class="py-24 px-6 relative overflow-hidden bg-white/50">
                <div class="max-w-5xl mx-auto relative z-10">
                    <div class="text-center mb-20 flex flex-col items-center">
                        <span class="text-[10px] tracking-[0.4em] uppercase text-theme-secondary mb-3 font-semibold">Our Journey</span>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-theme-text">Kisah Cinta Kami</h2>
                        <div class="h-[1px] w-16 bg-theme-primary/30 mt-6"></div>
                    </div>

                    <div class="relative space-y-16 md:space-y-24">
                        <div class="hidden md:block absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-[1px] bg-theme-primary/20"></div>

                        @foreach ($content['love_stories'] as $index => $story)
                            @php $isEven = $index % 2 == 0; @endphp

                            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 items-center group {{ $index > 2 ? 'hidden extra-story' : '' }}">
                                <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 z-20 w-8 h-8 rounded-full bg-theme-bg border border-theme-primary items-center justify-center transition-transform duration-500">
                                    <div class="w-2 h-2 rounded-full bg-theme-secondary"></div>
                                </div>

                                <div class="relative {{ $isEven ? 'order-1 md:order-1 flex justify-center md:justify-end' : 'order-1 md:order-2 flex justify-center md:justify-start' }}">
                                    @if (!empty($story['image']))
                                        <div class="relative w-full max-w-[320px] aspect-[4/5] rounded-[2rem] overflow-hidden shadow-lg border border-theme-primary/10">
                                            <img src="{{ asset('storage/' . $story['image']) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" alt="Momen">
                                        </div>
                                    @endif
                                </div>

                                <div class="{{ $isEven ? 'order-2 md:order-2 text-center md:text-left flex flex-col items-center md:items-start' : 'order-2 md:order-1 text-center md:text-right flex flex-col items-center md:items-end' }}">
                                    <span class="text-[10px] font-semibold tracking-[0.2em] text-theme-bg bg-theme-primary px-4 py-1.5 rounded-full inline-block mb-4">{{ $story['year'] }}</span>
                                    <h4 class="text-2xl md:text-3xl font-serif text-theme-text mb-4">{{ $story['title'] }}</h4>
                                    <p class="text-sm text-theme-text/80 leading-relaxed font-light max-w-sm">
                                        {{ $story['description'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($content['love_stories']) > 3)
                        <div class="mt-20 text-center">
                            <button id="btn-read-more" onclick="toggleStories()" class="px-8 py-3 bg-white border border-theme-primary/30 text-theme-primary rounded-full text-xs font-semibold uppercase tracking-widest hover:bg-theme-primary hover:text-white transition-colors duration-300 shadow-sm">
                                Baca Selengkapnya
                            </button>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- LOKASI DAN WAKTU --}}
        @if (!empty($content['is_event_active']))
            <section id="lokasi" class="py-24 px-6 relative overflow-hidden">
                <div class="max-w-6xl mx-auto relative z-10">
                    <div class="text-center mb-16 flex flex-col items-center">
                        <span class="text-[10px] tracking-[0.4em] uppercase text-theme-secondary mb-3 font-semibold">The Celebration</span>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-theme-text">Waktu & Tempat</h2>
                        <i class="fa-brands fa-pagelines text-2xl text-theme-primary mt-6 opacity-60"></i>
                    </div>

                    <div class="grid grid-cols-1 {{ count($content['events'] ?? []) > 0 ? 'md:grid-cols-2' : '' }} gap-8 lg:gap-10 items-stretch">

                        {{-- AKAD NIKAH --}}
                        @if (!empty($content['akad_location']))
                            <div class="elegant-card p-10 md:p-12 rounded-[2.5rem] flex flex-col items-center text-center h-full relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-2 bg-theme-secondary/50"></div>
                                
                                <div class="w-16 h-16 rounded-full bg-theme-bg flex items-center justify-center mb-6 border border-theme-primary/10 text-theme-primary text-2xl shadow-sm">
                                    <i class="fa-solid fa-ring"></i>
                                </div>

                                <h3 class="text-3xl font-serif text-theme-text mb-1">Akad Nikah</h3>
                                <p class="text-theme-secondary text-[9px] tracking-[0.3em] uppercase font-semibold mb-8">Sacred Union</p>

                                <div class="space-y-6 mb-10 flex-1 w-full">
                                    <div class="flex flex-col items-center p-4 bg-theme-bg/50 rounded-2xl">
                                        <i class="fa-regular fa-calendar text-theme-primary mb-2"></i>
                                        <p class="text-theme-text text-sm font-medium">
                                            {{ \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center p-4 bg-theme-bg/50 rounded-2xl">
                                        <i class="fa-regular fa-clock text-theme-primary mb-2"></i>
                                        <p class="text-theme-text text-sm font-medium">{{ $content['akad_time'] }}</p>
                                    </div>
                                    <div class="flex flex-col items-center p-4 bg-theme-bg/50 rounded-2xl">
                                        <i class="fa-solid fa-location-dot text-theme-primary mb-2"></i>
                                        <p class="text-theme-text/80 text-xs md:text-sm leading-relaxed">
                                            <span class="font-bold text-theme-text block mb-1">{{ $content['akad_location'] }}</span>
                                            {{ $content['akad_address'] }}
                                        </p>
                                    </div>
                                </div>

                                @if (!empty($content['akad_map']))
                                    <a href="{{ $content['akad_map'] }}" target="_blank" class="w-full py-3.5 bg-theme-primary text-white rounded-full text-xs font-semibold uppercase tracking-widest hover:bg-theme-text transition-colors shadow-md flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-map-location-dot"></i> Buka Google Maps
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- RESEPSI --}}
                        @if (!empty($content['events']))
                            @foreach ($content['events'] as $event)
                                <div class="elegant-card p-10 md:p-12 rounded-[2.5rem] flex flex-col items-center text-center h-full relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-full h-2 bg-theme-primary/50"></div>
                                    
                                    <div class="w-16 h-16 rounded-full bg-theme-bg flex items-center justify-center mb-6 border border-theme-primary/10 text-theme-primary text-2xl shadow-sm">
                                        <i class="fa-solid fa-champagne-glasses"></i>
                                    </div>

                                    <h3 class="text-3xl font-serif text-theme-text mb-1">{{ $event['title'] }}</h3>
                                    <p class="text-theme-secondary text-[9px] tracking-[0.3em] uppercase font-semibold mb-8">Grand Celebration</p>

                                    <div class="space-y-6 mb-10 flex-1 w-full">
                                        <div class="flex flex-col items-center p-4 bg-theme-bg/50 rounded-2xl">
                                            <i class="fa-regular fa-calendar text-theme-primary mb-2"></i>
                                            <p class="text-theme-text text-sm font-medium">
                                                {{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-center p-4 bg-theme-bg/50 rounded-2xl">
                                            <i class="fa-regular fa-clock text-theme-primary mb-2"></i>
                                            <p class="text-theme-text text-sm font-medium">{{ $event['time'] }}</p>
                                        </div>
                                        <div class="flex flex-col items-center p-4 bg-theme-bg/50 rounded-2xl">
                                            <i class="fa-solid fa-building text-theme-primary mb-2"></i>
                                            <p class="text-theme-text/80 text-xs md:text-sm leading-relaxed">
                                                <span class="font-bold text-theme-text block mb-1">{{ $event['location'] }}</span>
                                                {{ $event['address'] }}
                                            </p>
                                        </div>
                                    </div>

                                    @if (!empty($event['map']))
                                        <a href="{{ $event['map'] }}" target="_blank" class="w-full py-3.5 bg-theme-primary text-white rounded-full text-xs font-semibold uppercase tracking-widest hover:bg-theme-text transition-colors shadow-md flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-map-location-dot"></i> Buka Google Maps
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
        @endif

        {{-- GUEST INFO --}}
        @if (!empty($content['is_guest_info_active']))
            <section class="py-16 px-6 relative">
                <div class="max-w-3xl mx-auto elegant-card rounded-[2rem] p-10 md:p-12 text-center relative border-t-4 border-theme-secondary">
                    <i class="fa-solid fa-circle-info text-2xl text-theme-secondary mb-4"></i>
                    <h4 class="text-2xl font-serif text-theme-text mb-6 italic">Informasi Tambahan</h4>

                    @if (!empty($content['enable_dresscode']) && !empty($content['dresscode']))
                        <div class="mb-8">
                            <p class="text-[10px] uppercase tracking-widest text-theme-muted font-bold mb-2">Dresscode</p>
                            <p class="text-sm font-medium text-theme-text bg-theme-bg py-2 px-4 rounded-lg inline-block">{{ $content['dresscode'] }}</p>
                        </div>
                    @endif

                    @if (!empty($content['enable_health_protocol']))
                        <div class="pt-6 border-t border-theme-primary/10 w-full mx-auto">
                            <p class="text-[10px] uppercase tracking-widest text-theme-muted font-bold mb-6">Protokol Kesehatan</p>
                            <div class="flex justify-center gap-10 text-theme-primary text-2xl">
                                <div class="flex flex-col items-center gap-2"><i class="fa-solid fa-head-side-mask"></i><span class="text-[9px] text-theme-text uppercase tracking-widest font-medium">Masker</span></div>
                                <div class="flex flex-col items-center gap-2"><i class="fa-solid fa-hands-bubbles"></i><span class="text-[9px] text-theme-text uppercase tracking-widest font-medium">Cuci Tangan</span></div>
                                <div class="flex flex-col items-center gap-2"><i class="fa-solid fa-people-arrows"></i><span class="text-[9px] text-theme-text uppercase tracking-widest font-medium">Jaga Jarak</span></div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- GALERI --}}
        @if (!empty($content['is_gallery_active']))
            <section id="gallery" class="py-24 px-6 relative overflow-hidden bg-white/40">
                <div class="max-w-6xl mx-auto relative z-10">
                    <div class="text-center mb-16">
                        <span class="text-[10px] tracking-[0.4em] uppercase text-theme-secondary mb-3 font-semibold">Captured Moments</span>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-theme-text">Galeri Foto</h2>
                        <div class="h-[1px] w-12 bg-theme-primary/30 mx-auto mt-6"></div>
                    </div>

                    @if (!empty($content['youtube_links']))
                        @foreach ($content['youtube_links'] as $yt)
                            @php $ytId = getYoutubeId($yt); @endphp
                            @if ($ytId)
                                <div class="mb-12">
                                    <div class="relative w-full max-w-4xl mx-auto pb-[56.25%] rounded-3xl overflow-hidden shadow-lg border border-theme-primary/10">
                                        <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @if ($invitation->galleries->count() > 0)
                        <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4" id="photo-grid">
                            @foreach ($invitation->galleries->where('type', 'photo') as $idx => $photo)
                                <div class="break-inside-avoid relative rounded-xl overflow-hidden cursor-pointer shadow-sm border border-theme-primary/5 group" onclick="openLightbox({{ $idx }})">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" class="gallery-img w-full h-auto object-cover transition-transform duration-700 group-hover:scale-105" alt="Gallery">
                                    <div class="absolute inset-0 bg-theme-text/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <i class="fa-solid fa-expand text-white text-xl drop-shadow-md"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>

            <div id="lightbox" class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-theme-bg/95 backdrop-blur-sm p-4 transition-all duration-300">
                <button onclick="closeLightbox()" class="absolute top-6 right-6 text-theme-text hover:text-theme-primary transition-colors text-3xl z-[110] w-12 h-12 bg-white rounded-full shadow-md flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="relative w-full max-w-5xl flex items-center justify-center group">
                    <button onclick="prevImg()" class="absolute left-2 md:-left-16 text-theme-text hover:text-theme-primary transition-all text-2xl w-10 h-10 bg-white rounded-full shadow-md z-10"><i class="fa-solid fa-chevron-left"></i></button>
                    <img id="lightbox-img" src="" class="max-h-[85vh] max-w-full rounded-lg shadow-2xl border border-theme-primary/20 transition-opacity duration-300" alt="Zoomed Photo">
                    <button onclick="nextImg()" class="absolute right-2 md:-right-16 text-theme-text hover:text-theme-primary transition-all text-2xl w-10 h-10 bg-white rounded-full shadow-md z-10"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <p class="mt-6 text-theme-text tracking-[0.2em] text-[10px] uppercase font-bold bg-white px-4 py-1.5 rounded-full shadow-sm">
                    Image <span id="current-count">1</span> / <span id="total-count">5</span>
                </p>
            </div>
        @endif

        {{-- HADIAH --}}
        @if (!empty($content['is_gift_active']) && !empty($content['banks']))
            <section id="hadiah" class="py-24 px-6 relative overflow-hidden">
                <div class="max-w-3xl mx-auto text-center relative z-10">
                    <div class="mb-14">
                        <span class="text-[10px] tracking-[0.4em] uppercase text-theme-secondary font-semibold mb-3 block">Wedding Gift</span>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-theme-text">Tanda Kasih</h2>
                        <i class="fa-brands fa-pagelines text-2xl text-theme-primary mt-4 opacity-60 block"></i>
                        <p class="text-sm text-theme-text/70 font-light leading-relaxed max-w-md mx-auto mt-6">
                            Doa restu Anda adalah karunia terindah. Namun jika Anda ingin memberikan tanda kasih, dapat melalui:
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        @foreach ($content['banks'] as $idx => $bank)
                            <div class="elegant-card p-8 rounded-[2rem] transition-transform duration-300 hover:-translate-y-2 relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 opacity-5 text-theme-primary text-9xl"><i class="fa-solid fa-building-columns"></i></div>
                                
                                <div class="relative z-10 flex flex-col items-center">
                                    <p class="text-sm uppercase tracking-widest text-theme-text mb-6 font-bold">{{ $bank['name'] }}</p>
                                    <p class="text-[9px] uppercase tracking-[0.3em] text-theme-muted mb-2 font-semibold">Nomor Rekening</p>
                                    <h3 id="rek-{{ $idx }}" class="text-2xl font-sans font-semibold text-theme-primary mb-2 tracking-wider">{{ $bank['account_number'] }}</h3>
                                    <p class="text-xs text-theme-text/60 mb-8 font-medium">a.n {{ $bank['account_name'] }}</p>

                                    <button onclick="copyToClipboard('rek-{{ $idx }}', this)" class="w-full py-3 bg-theme-bg border border-theme-primary/30 text-theme-primary rounded-xl text-xs font-semibold uppercase tracking-widest transition-all hover:bg-theme-primary hover:text-white flex items-center justify-center gap-2">
                                        <i class="fa-regular fa-copy"></i> Salin Nomor
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="copy-toast" class="fixed bottom-28 left-1/2 -translate-x-1/2 z-[300] px-6 py-3 bg-theme-text text-white text-xs rounded-full tracking-widest uppercase font-semibold opacity-0 translate-y-10 transition-all duration-500 shadow-xl flex items-center gap-2">
                        <i class="fa-solid fa-check-circle text-theme-secondary"></i> Tersalin!
                    </div>
                </div>
            </section>
        @endif

        {{-- UCAPAN & DOA --}}
        @if (!empty($content['is_wishes_active']))
            <section id="guest-stats" class="py-24 px-6 relative bg-white/60 border-t border-theme-primary/10">
                <div class="max-w-4xl mx-auto relative z-10">
                    <div class="text-center mb-14">
                        <span class="text-[10px] tracking-[0.4em] uppercase text-theme-secondary mb-3 font-semibold">Guest Book</span>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-theme-text">Kehadiran & Doa</h2>
                        <div class="h-[1px] w-12 bg-theme-primary/30 mx-auto mt-6"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 md:gap-8 mb-10">
                        <div class="elegant-card p-6 md:p-8 rounded-3xl flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-theme-bg rounded-full flex items-center justify-center mb-4 text-theme-primary text-xl">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <h4 id="total-attendance" class="text-3xl md:text-4xl font-serif font-bold text-theme-text mb-1">0</h4>
                            <p class="text-[9px] uppercase tracking-[0.2em] text-theme-muted font-semibold">Tamu Hadir</p>
                        </div>

                        <div class="elegant-card p-6 md:p-8 rounded-3xl flex flex-col items-center text-center">
                            <div class="w-12 h-12 bg-theme-bg rounded-full flex items-center justify-center mb-4 text-theme-primary text-xl">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <h4 id="total-wishes" class="text-3xl md:text-4xl font-serif font-bold text-theme-text mb-1">0</h4>
                            <p class="text-[9px] uppercase tracking-[0.2em] text-theme-muted font-semibold">Ucapan</p>
                        </div>
                    </div>

                    <div class="elegant-card rounded-[2rem] overflow-hidden">
                        <div class="bg-theme-bg py-4 px-6 border-b border-theme-primary/10 flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-theme-text">Dinding Ucapan</span>
                            <i class="fa-solid fa-message text-theme-muted text-sm"></i>
                        </div>

                        <div id="wishes-container" class="max-h-[450px] overflow-y-auto scroll-custom p-6 md:p-8 space-y-4 bg-white">
                            {{-- Diisi Oleh Script JS --}}
                        </div>

                        <div class="p-6 text-center border-t border-theme-primary/10 bg-theme-bg/50">
                            <button id="btn-load-more" onclick="loadMoreWishes()" class="px-6 py-2.5 bg-white border border-theme-primary/20 text-theme-text rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-theme-primary hover:text-white transition-colors shadow-sm">
                                Lihat Lebih Banyak
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- FORM RSVP MODAL --}}
        <section id="rsvp-modal" class="fixed inset-0 z-[100] invisible transition-all duration-500 overflow-hidden">
            <div onclick="closeRSVP()" class="absolute inset-0 bg-theme-text/40 backdrop-blur-sm opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>

            <div id="rsvp-content" class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] transform translate-y-full transition-transform duration-700 ease-[cubic-bezier(0.2,0.8,0.2,1)] px-6 pb-12 pt-4 max-w-2xl mx-auto">
                <div class="w-16 h-1.5 bg-theme-bg rounded-full mx-auto mb-8"></div>

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-serif text-theme-text italic">Konfirmasi Kehadiran</h2>
                    <p class="text-xs text-theme-muted mt-2">Bantu kami mempersiapkan yang terbaik untuk Anda.</p>
                </div>

                <form id="rsvpForm" class="space-y-5 text-left" onsubmit="submitRSVP(event)">
                    <div>
                        <label class="text-[10px] uppercase tracking-widest text-theme-text font-bold ml-2 mb-2 block">Nama Lengkap</label>
                        <input type="text" id="input-nama-rsvp" name="name" placeholder="Tulis nama Anda..." class="input-rustic w-full p-4 rounded-xl text-sm placeholder-theme-muted/50" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir" class="py-3.5 rounded-xl border border-theme-primary/20 text-xs font-semibold uppercase tracking-widest transition-all bg-theme-bg text-theme-muted hover:border-theme-primary">
                            Hadir
                        </button>
                        <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen" class="py-3.5 rounded-xl border border-theme-primary/20 text-xs font-semibold uppercase tracking-widest transition-all bg-theme-bg text-theme-muted hover:border-theme-primary">
                            Maaf, Absen
                        </button>
                        <input type="hidden" name="status" id="input-status" required>
                    </div>

                    <div id="guest-selection" class="hidden animate-slide-up bg-theme-bg p-5 rounded-xl border border-theme-primary/10">
                        <label class="text-[10px] uppercase tracking-widest text-theme-text mb-3 block font-bold text-center">Jumlah Tamu (Termasuk Anda)</label>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectGuestCount(1, this)" class="guest-btn flex-1 py-2.5 rounded-lg border border-theme-primary/20 bg-white text-xs text-theme-text hover:bg-theme-primary hover:text-white transition-all">1 Orang</button>
                            <button type="button" onclick="selectGuestCount(2, this)" class="guest-btn flex-1 py-2.5 rounded-lg border border-theme-primary/20 bg-white text-xs text-theme-text hover:bg-theme-primary hover:text-white transition-all">2 Orang</button>
                            <button type="button" onclick="selectGuestCount(3, this)" class="guest-btn flex-1 py-2.5 rounded-lg border border-theme-primary/20 bg-white text-xs text-theme-text hover:bg-theme-primary hover:text-white transition-all">3+ Orang</button>
                            <input type="hidden" name="guest_count" id="input-guest-count" value="1">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase tracking-widest text-theme-text font-bold ml-2 mb-2 block">Pesan & Doa</label>
                        <textarea id="input-pesan-rsvp" name="message" rows="3" placeholder="Berikan doa terbaik Anda..." class="input-rustic w-full p-4 rounded-xl text-sm placeholder-theme-muted/50 resize-none" required></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeRSVP()" class="w-1/3 py-4 bg-theme-bg text-theme-text rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" id="btnSubmitRsvp" class="w-2/3 py-4 bg-theme-primary hover:bg-theme-text text-white rounded-xl font-bold text-[11px] uppercase tracking-widest transition-all shadow-md">
                            Kirim Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <footer class="py-12 px-6 bg-theme-bg border-t border-theme-primary/10 text-center relative">
            <i class="fa-brands fa-pagelines text-3xl text-theme-primary mb-6 opacity-40"></i>
            <div class="max-w-md mx-auto">
                <div class="mb-4 font-serif text-theme-text text-2xl italic">
                    {{ $pria }} & {{ $wanita }}
                </div>

                <p class="text-[9px] tracking-[0.3em] uppercase text-theme-muted mb-8 font-medium">
                    Terima kasih atas doa & restu Anda
                </p>

                <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-theme-primary/10 shadow-sm">
                    <span class="text-[9px] text-theme-muted uppercase tracking-widest font-semibold">Created with love by</span>
                    <a href="https://instagram.com/ruangrestu.undangan" target="_blank" rel="noopener noreferrer" class="text-[10px] font-bold text-theme-primary hover:text-theme-secondary transition-colors flex items-center gap-1">
                        <i class="fa-brands fa-instagram text-sm"></i> ruangrestu
                    </a>
                </div>
            </div>
        </footer>

    </main>

    <div id="fab-container" class="fixed right-4 bottom-28 flex flex-col gap-3 z-40 opacity-0 transition-opacity duration-1000">
        <button id="btn-music" onclick="toggleMusic()" class="w-12 h-12 bg-white backdrop-blur border border-theme-primary/20 rounded-full flex items-center justify-center text-theme-primary shadow-lg hover:bg-theme-primary hover:text-white transition-all">
            <i class="fa-solid fa-music animate-spin-slow" id="icon-music"></i>
        </button>

        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-12 h-12 bg-white backdrop-blur border border-theme-primary/20 rounded-full flex items-center justify-center text-theme-primary shadow-lg hover:bg-theme-primary hover:text-white transition-all">
            <i class="fa-solid fa-angles-down" id="icon-scroll"></i>
        </button>
    </div>

    <nav id="bottom-nav" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 floating-nav rounded-full transition-all duration-1000 translate-y-32">
        <ul class="flex justify-around items-center h-16 w-[320px] md:w-[400px] px-2">
            <li><a href="#home" class="nav-link flex flex-col items-center text-[9px] uppercase tracking-[0.1em] font-semibold gap-1 text-theme-muted hover:text-theme-primary transition-colors p-2"><i class="fa-solid fa-house text-base"></i><span class="hidden md:block">Home</span></a></li>
            @if (!empty($content['is_gallery_active']))
                <li><a href="#gallery" class="nav-link flex flex-col items-center text-[9px] uppercase tracking-[0.1em] font-semibold gap-1 text-theme-muted hover:text-theme-primary transition-colors p-2"><i class="fa-regular fa-images text-base"></i><span class="hidden md:block">Gallery</span></a></li>
            @endif
            @if (!empty($content['is_event_active']))
                <li><a href="#lokasi" class="nav-link flex flex-col items-center text-[9px] uppercase tracking-[0.1em] font-semibold gap-1 text-theme-muted hover:text-theme-primary transition-colors p-2"><i class="fa-solid fa-map-pin text-base"></i><span class="hidden md:block">Venue</span></a></li>
            @endif
            <li>
                <a href="javascript:void(0)" onclick="openRSVP()" class="nav-link flex flex-col items-center text-[9px] uppercase tracking-[0.1em] font-semibold gap-1 text-theme-primary p-2">
                    <div class="w-10 h-10 -mt-6 bg-theme-secondary text-white rounded-full flex items-center justify-center shadow-lg border-[3px] border-[#F9F8F3]">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </div>
                    <span class="hidden md:block">RSVP</span>
                </a>
            </li>
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
            const cover = document.getElementById('cover-page');
            if (cover) {
                cover.classList.add('-translate-y-full');
                document.body.style.overflowY = 'auto';
                document.getElementById('main-content').classList.remove('opacity-0');
                document.getElementById('fab-container').classList.remove('opacity-0');
                document.getElementById('bottom-nav').classList.remove('translate-y-32');

                toggleMusic(true);
                toggleAutoScroll(true);
            }
        }

        function toggleMusic(forcePlay = false) {
            const icon = document.getElementById('icon-music');
            if (isMusicPlaying && !forcePlay) {
                audio.pause();
                isMusicPlaying = false;
                icon.classList.remove('fa-music', 'animate-spin-slow');
                icon.classList.add('fa-volume-xmark');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.remove('fa-volume-xmark');
                    icon.classList.add('fa-music', 'animate-spin-slow');
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
                btn.classList.remove('bg-theme-primary', 'text-white');
                btn.classList.add('bg-white', 'text-theme-primary');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-angles-down');
            } else {
                isAutoScrolling = true;
                btn.classList.remove('bg-white', 'text-theme-primary');
                btn.classList.add('bg-theme-primary', 'text-white');
                icon.classList.remove('fa-angles-down');
                icon.classList.add('fa-pause');
                scrollInterval = setInterval(() => {
                    window.scrollBy({ top: 1, behavior: 'auto' });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) toggleAutoScroll();
                }, 35);
            }
        }

        window.addEventListener('wheel', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });
        window.addEventListener('touchmove', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });

        // COUNTDOWN
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
            }
        }

        function toggleStories() {
            const extraStories = document.querySelectorAll('.extra-story');
            const btn = document.getElementById('btn-read-more');
            let isHidden = false;
            extraStories.forEach(story => {
                if (story.classList.contains('hidden')) {
                    story.classList.remove('hidden');
                    story.classList.add('animate-fade-in-up');
                    isHidden = true;
                } else {
                    story.classList.add('hidden');
                    story.classList.remove('animate-fade-in-up');
                }
            });
            btn.innerText = isHidden ? 'Sembunyikan' : 'Baca Selengkapnya';
        }

        // COPY TO CLIPBOARD
        function copyToClipboard(elementId, btnElement) {
            const textToCopy = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const toast = document.getElementById('copy-toast');
                toast.classList.remove('opacity-0', 'translate-y-10');
                toast.classList.add('opacity-100', 'translate-y-0');
                
                const originalText = btnElement.innerHTML;
                btnElement.innerHTML = '<i class="fa-solid fa-check"></i> Tersalin';
                btnElement.classList.add('bg-theme-primary', 'text-white');
                
                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', 'translate-y-10');
                    btnElement.innerHTML = originalText;
                    btnElement.classList.remove('bg-theme-primary', 'text-white');
                }, 2500);
            });
        }

        // RSVP & UCAPAN
        let countAttendance = {{ $invitation->rsvps->where('status_rsvp', 'hadir')->count() }};
        let countWishes = {{ $invitation->rsvps->count() }};

        const totalHadirEl = document.getElementById('total-attendance');
        const totalUcapanEl = document.getElementById('total-wishes');
        if (totalHadirEl) totalHadirEl.innerText = countAttendance;
        if (totalUcapanEl) totalUcapanEl.innerText = countWishes;

        function submitRSVP(event) {
            event.preventDefault();
            const status = document.getElementById('input-status').value;
            const nama = document.getElementById('input-nama-rsvp').value;
            const pesan = document.querySelector('textarea[name="message"]').value;
            const guestCount = document.getElementById('input-guest-count').value;

            if (!status || !nama || !pesan) return alert('Mohon lengkapi data Anda.');

            const btn = document.getElementById('btnSubmitRsvp');
            btn.innerHTML = 'MENGIRIM...';
            btn.disabled = true;

            const formData = {
                guest_name: nama,
                status_rsvp: status === 'Hadir' ? 'hadir' : (status === 'Tidak Hadir' ? 'tidak_hadir' : 'ragu'),
                pax: guestCount,
                message: pesan,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            fetch("{{ route('rsvp.store', $invitation->slug) }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    countWishes++;
                    if (status === "Hadir") countAttendance++;
                    if (totalHadirEl) totalHadirEl.innerText = countAttendance;
                    if (totalUcapanEl) totalUcapanEl.innerText = countWishes;
                    alert('Terima kasih, RSVP Anda telah tersimpan!');
                    closeRSVP();
                    addNewWishCard(nama, pesan, 'Baru saja');
                } else {
                    alert('Gagal mengirim RSVP.');
                }
            })
            .catch(() => { alert('Terjadi kesalahan server.'); })
            .finally(() => {
                btn.innerHTML = 'Kirim Konfirmasi';
                btn.disabled = false;
                document.getElementById('rsvpForm').reset();
            });
        }

        function addNewWishCard(nama, pesan, waktu) {
            const container = document.getElementById('wishes-container');
            if (!container) return;
            const card = document.createElement('div');
            card.className = 'bg-theme-bg p-5 rounded-2xl border border-theme-primary/10 animate-fade-in-up';
            card.innerHTML = `<div class="flex justify-between items-start mb-2"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center border border-theme-primary/20"><i class="fa-solid fa-user text-[10px] text-theme-primary"></i></div><h5 class="text-sm font-semibold text-theme-text">${nama}</h5></div><span class="text-[9px] text-theme-muted font-medium">${waktu}</span></div><p class="text-sm text-theme-text/80 leading-relaxed font-light mt-3 pl-11">${pesan}</p>`;
            container.prepend(card);
        }

        const allWishes = [
            @foreach ($invitation->rsvps()->latest()->get() as $wish)
                { nama: "{{ addslashes($wish->guest_name) }}", pesan: "{{ addslashes(trim(preg_replace('/\s\s+/', ' ', $wish->message))) }}", waktu: "{{ $wish->created_at->diffForHumans() }}" },
            @endforeach
        ];

        let displayedCount = 0;

        function renderWishes() {
            const container = document.getElementById('wishes-container');
            const btnLoadMore = document.getElementById('btn-load-more');
            if (!container) return;
            if (displayedCount === 0) container.innerHTML = '';
            let nextLimit = displayedCount + 4;
            const wishesToDisplay = allWishes.slice(displayedCount, nextLimit);
            wishesToDisplay.forEach(wish => { addNewWishCard(wish.nama, wish.pesan, wish.waktu); });
            displayedCount = Math.min(nextLimit, allWishes.length);
            if (displayedCount >= allWishes.length && btnLoadMore) btnLoadMore.style.display = 'none';
        }

        function selectAttendance(status) {
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');
            document.getElementById('input-status').value = status;
            
            [btnHadir, btnAbsen].forEach(btn => {
                btn.classList.remove('bg-theme-primary', 'text-white', 'border-theme-primary');
                btn.classList.add('bg-theme-bg', 'text-theme-muted', 'border-theme-primary/20');
            });

            if (status === 'Hadir') {
                btnHadir.classList.replace('bg-theme-bg', 'bg-theme-primary');
                btnHadir.classList.replace('text-theme-muted', 'text-white');
                guestDiv.classList.remove('hidden');
            } else {
                btnAbsen.classList.replace('bg-theme-bg', 'bg-theme-primary');
                btnAbsen.classList.replace('text-theme-muted', 'text-white');
                guestDiv.classList.add('hidden');
            }
        }

        function selectGuestCount(count, btnElement) {
            document.getElementById('input-guest-count').value = count;
            document.querySelectorAll('.guest-btn').forEach(btn => {
                btn.classList.remove('bg-theme-primary', 'text-white', 'border-theme-primary');
                btn.classList.add('bg-white', 'text-theme-text');
            });
            btnElement.classList.remove('bg-white', 'text-theme-text');
            btnElement.classList.add('bg-theme-primary', 'text-white', 'border-theme-primary');
        }

        function openRSVP() {
            const modal = document.getElementById('rsvp-modal');
            if (modal) {
                modal.classList.remove('invisible');
                document.getElementById('rsvp-overlay').classList.replace('opacity-0', 'opacity-100');
                document.getElementById('rsvp-content').classList.replace('translate-y-full', 'translate-y-0');
            }
        }

        function closeRSVP() {
            document.getElementById('rsvp-overlay').classList.replace('opacity-100', 'opacity-0');
            document.getElementById('rsvp-content').classList.replace('translate-y-0', 'translate-y-full');
            setTimeout(() => document.getElementById('rsvp-modal').classList.add('invisible'), 500);
        }

        document.addEventListener('DOMContentLoaded', () => { renderWishes(); });
        function loadMoreWishes() { renderWishes(); }

        // LIGHTBOX
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
                document.getElementById('current-count').innerText = currentIndex + 1;
                document.getElementById('total-count').innerText = images.length;
            }, 200);
        }

        function prevImg() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
            updateLightbox();
        }

        function nextImg() {
            currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
            updateLightbox();
        }

        let hasShownRSVPAtEnd = false;
        window.addEventListener('scroll', () => {
            const scrollPosition = window.innerHeight + window.scrollY;
            const threshold = document.body.offsetHeight - 150;
            if (scrollPosition >= threshold) {
                if (!hasShownRSVPAtEnd) {
                    openRSVP();
                    hasShownRSVPAtEnd = true;
                    if (typeof isAutoScrolling !== 'undefined' && isAutoScrolling) toggleAutoScroll();
                }
            }
        }, { passive: true });
    </script>
</body>
</html>