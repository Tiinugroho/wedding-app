<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan - {{ $content['groom_nickname'] ?? 'Romeo' }} & {{ $content['bride_nickname'] ?? 'Juliet' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0F172A',
                            gold: '#D4AF37',
                            accent: '#1E293B',
                            muted: '#94A3B8',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    animation: {
                        'slow-zoom': 'zoom 20s infinite alternate',
                        'float': 'floating 3s ease-in-out infinite',
                    },
                    keyframes: {
                        zoom: {
                            '0%': {
                                transform: 'scale(1)'
                            },
                            '100%': {
                                transform: 'scale(1.1)'
                            },
                        },
                        floating: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        }
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
            background-color: #0F0F0F;
            color: #FFFFFF;
            -webkit-font-smoothing: antialiased;
        }

        .floating-nav {
            background: rgba(15, 15, 15, 0.75);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(197, 160, 101, 0.25);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }

        .landing-mist {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(15, 15, 15, 0.95) 100%);
        }

        .text-protected {
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1.2s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        .gold-glow {
            filter: drop-shadow(0 0 5px rgba(197, 160, 101, 0.3));
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .shimmer {
            position: relative;
            overflow: hidden;
        }

        .shimmer::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer-effect 4s infinite;
        }

        @keyframes shimmer-effect {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        @keyframes slide-up-soft {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            animation: slide-up-soft 0.5s ease-out forwards;
        }

        .input-luxury {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .input-luxury:focus {
            border-color: #C5A065;
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 0 15px rgba(197, 160, 101, 0.1);
        }

        .scroll-custom::-webkit-scrollbar {
            width: 3px;
        }

        .scroll-custom::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .scroll-custom::-webkit-scrollbar-thumb {
            background: #C5A065;
            border-radius: 10px;
        }

        .glass-stat {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

    @php
    // Ekstraksi Youtube ID agar iFrame berfungsi
    function getYoutubeId($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match);
    return $match[1] ?? null;
    }

    // LOGIKA COVER IMAGE (Custom -> Gallery 1 -> Default)
    $firstGallery = $invitation->galleries->where('type', 'photo')->first();
    $coverImg = !empty($content['cover_image'])
    ? asset('storage/'.$content['cover_image'])
    : ($firstGallery ? asset('storage/'.$firstGallery->file_path)
    : (!empty($content['bride_photo']) ? asset('storage/'.$content['bride_photo'])
    : 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop'));

    $isGroomFirst = ($content['couple_order'] ?? 'groom_first') == 'groom_first';

        // PETAKAN DATA ORANG PERTAMA (Akan tampil di kartu kiri)
        $firstPerson = [
            'name'     => $isGroomFirst ? ($content['groom_name'] ?? 'Romeo Montague') : ($content['bride_name'] ?? 'Juliet Capulet'),
            'nickname' => $isGroomFirst ? ($content['groom_nickname'] ?? 'Romeo') : ($content['bride_nickname'] ?? 'Juliet'),
            'father'   => $isGroomFirst ? ($content['groom_father'] ?? 'Fulan') : ($content['bride_father'] ?? 'Fulan'),
            'mother'   => $isGroomFirst ? ($content['groom_mother'] ?? 'Fulanah') : ($content['bride_mother'] ?? 'Fulanah'),
            'ig'       => $isGroomFirst ? ($content['groom_ig'] ?? '') : ($content['bride_ig'] ?? ''),
            'photo'    => $isGroomFirst ? ($content['groom_photo'] ?? '') : ($content['bride_photo'] ?? ''),
            'label'    => $isGroomFirst ? '- The Groom -' : '- The Bride -',
            'default_img' => $isGroomFirst ? 'https://images.soco.id/230-58.jpg.jpeg' : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg'
        ];

        // PETAKAN DATA ORANG KEDUA (Akan tampil di kartu kanan)
        $secondPerson = [
            'name'     => !$isGroomFirst ? ($content['groom_name'] ?? 'Romeo Montague') : ($content['bride_name'] ?? 'Juliet Capulet'),
            'nickname' => !$isGroomFirst ? ($content['groom_nickname'] ?? 'Romeo') : ($content['bride_nickname'] ?? 'Juliet'),
            'father'   => !$isGroomFirst ? ($content['groom_father'] ?? 'Fulan') : ($content['bride_father'] ?? 'Fulan'),
            'mother'   => !$isGroomFirst ? ($content['groom_mother'] ?? 'Fulanah') : ($content['bride_mother'] ?? 'Fulanah'),
            'ig'       => !$isGroomFirst ? ($content['groom_ig'] ?? '') : ($content['bride_ig'] ?? ''),
            'photo'    => !$isGroomFirst ? ($content['groom_photo'] ?? '') : ($content['bride_photo'] ?? ''),
            'label'    => !$isGroomFirst ? '- The Groom -' : '- The Bride -',
            'default_img' => !$isGroomFirst ? 'https://images.soco.id/230-58.jpg.jpeg' : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg'
        ];

        // Nama untuk di Cover (Groom & Bride atau sebaliknya)
        $pria = $content['groom_nickname'] ?? 'Romeo';
        $wanita = $content['bride_nickname'] ?? 'Juliet';
        $coupleNameCover = $isGroomFirst ? "$pria & $wanita" : "$wanita & $pria";

    // ==========================================
    // LOGIKA COUNTDOWN (FOKUS KE RESEPSI 1)
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
    $countdownTime = str_pad($matches[1], 5, "0", STR_PAD_LEFT);
    }
    }

    // Variabel untuk Cover
    $coverDate = $countdownDate ? \Carbon\Carbon::parse($countdownDate)->format('d . m . Y') : 'TBA';
    $countdownTarget = $countdownDate ? $countdownDate . 'T' . $countdownTime . ':00' : '';
    @endphp
</head>

<body class="bg-brand-dark text-white font-sans antialiased relative selection:bg-brand-gold selection:text-brand-dark">

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music_id) ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2021/07/18/audio_c993f91966.mp3' }}" type="audio/mpeg">
    </audio>

    {{-- COVER PAGE --}}
    <div id="cover-page" class="fixed inset-0 z-50 flex items-center justify-center w-screen h-screen bg-brand-dark overflow-hidden transition-transform duration-1000 ease-in-out">
        <div class="absolute inset-0 z-0">
            {{-- MENGGUNAKAN COVER IMAGE DINAMIS --}}
            <div class="absolute inset-0 opacity-30 animate-slow-zoom bg-[url('{{ $coverImg }}')] bg-cover bg-center"></div>
            <div class="absolute inset-0 bg-brand-dark/80 backdrop-blur-[2px]"></div>
        </div>

        <div class="hidden lg:block absolute inset-0 z-10 pointer-events-none">
            <div class="absolute inset-12 border border-brand-gold/20"></div>
            <div class="absolute top-20 left-20 w-32 h-32 border-t border-l border-brand-gold/40"></div>
            <div class="absolute bottom-20 right-20 w-32 h-32 border-b border-r border-brand-gold/40"></div>
            <div class="absolute left-20 top-1/2 -translate-y-1/2 -rotate-90 origin-left">
                <p class="text-[10px] tracking-[1em] uppercase text-brand-gold/40 font-light whitespace-nowrap">The Wedding of {{ $pria }} & {{ $wanita }} — {{ $coverDate }}</p>
            </div>
        </div>

        <div class="relative z-20 w-full max-w-[90%] md:max-w-2xl lg:max-w-3xl px-6 py-12 flex flex-col items-center justify-center min-h-[600px]">
            <div class="mb-8 animate-float">
                <div class="w-12 h-12 border border-brand-gold/40 rotate-45 flex items-center justify-center">
                    <div class="w-8 h-8 border border-brand-gold"></div>
                </div>
            </div>

            <p class="text-[10px] md:text-xs tracking-[0.5em] md:tracking-[0.8em] uppercase text-brand-gold/90 mb-6 font-medium text-center">The Wedding Celebration of</p>

            <div class="text-center mb-8">
                <h1 class="text-6xl md:text-8xl lg:text-9xl font-serif text-white tracking-tighter leading-[0.85] flex flex-col md:flex-row items-center justify-center md:gap-8 text-protected">
                    {{ $coupleNameCover }}
                </h1>
            </div>

            <div class="flex items-center justify-center gap-6 mb-12 w-full max-w-sm">
                <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent via-brand-gold/50 to-brand-gold/50"></div>
                <p class="text-xs md:text-sm font-sans tracking-[0.4em] text-brand-muted uppercase whitespace-nowrap font-light">{{ $coverDate }}</p>
                <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent via-brand-gold/50 to-brand-gold/50"></div>
            </div>

            <div class="glass-card p-8 md:p-12 rounded-3xl mb-12 relative w-full max-w-lg transition-all duration-700 hover:bg-white/5 border border-white/10 group text-center">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-brand-gold text-brand-dark text-[10px] uppercase font-bold tracking-widest rounded-full shadow-lg">
                    Special Guest
                </div>
                <p class="text-[11px] text-brand-muted mb-4 tracking-widest uppercase italic font-light">{{ $content['cover_greeting'] ?? 'Dear Bapak/Ibu/Saudara/i' }}</p>
                <h2 id="guest-name" class="text-3xl md:text-5xl font-serif text-white mb-6 leading-tight drop-shadow-lg text-protected">
                    Tamu Undangan
                </h2>
                @if(!empty($content['akad_location']))
                <div class="flex items-center justify-center gap-3 text-xs text-brand-gold tracking-widest border-t border-white/5 pt-6">
                    <i class="fa-solid fa-location-dot"></i>
                    <span class="uppercase">{{ $content['akad_location'] }}</span>
                </div>
                @endif
            </div>

            <div class="relative">
                <div class="absolute inset-0 bg-brand-gold blur-xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <button onclick="openInvitation()" class="shimmer relative px-12 py-4 bg-brand-gold text-brand-dark rounded-full transition-all duration-500 hover:scale-110 hover:shadow-[0_0_40px_rgba(212,175,55,0.4)] active:scale-95 flex items-center justify-center gap-4">
                    <i class="fa-solid fa-envelope-open-text text-sm"></i>
                    <span class="font-bold uppercase tracking-[0.3em] text-[11px]">Open Invitation</span>
                </button>
            </div>
        </div>

        <div class="hidden lg:block absolute bottom-10 left-10 opacity-20">
            <div class="w-40 h-[1px] bg-brand-gold mb-2"></div>
            <div class="w-20 h-[1px] bg-brand-gold"></div>
        </div>
    </div>

    <main id="main-content" class="min-h-screen pb-28 opacity-0 transition-opacity duration-1000 bg-brand-dark">

        {{-- HOME / QUOTES --}}
        <section id="home" class="min-h-screen flex flex-col items-center justify-center text-center p-6 md:p-12 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[url('{{ $coverImg }}')] bg-cover bg-center bg-fixed opacity-20"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-brand-dark via-transparent to-brand-dark"></div>
            </div>

            <div class="relative z-10 max-w-4xl w-full">
                <div class="mb-12 md:mb-20 animate-fade-in">
                    <span class="inline-block w-12 h-[1px] bg-brand-gold/50 mb-6"></span>
                    <h3 class="font-serif italic text-2xl md:text-4xl text-white mb-6 leading-relaxed text-protected">
                        "{{ $content['quotes'] ?? 'And they lived happily ever after.' }}"
                    </h3>
                    <p class="text-xs md:text-sm text-brand-muted max-w-lg mx-auto leading-loose tracking-wide font-light px-4">
                        Dengan memohon rahmat dan ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri momen bahagia pernikahan kami.
                    </p>
                </div>

                <div class="glass-card md:max-w-3xl mx-auto p-8 md:p-16 rounded-[3rem] border border-white/5 relative overflow-hidden group">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 opacity-10">
                        <i class="fa-solid fa-quote-right text-9xl text-brand-gold"></i>
                    </div>

                    <p class="text-[10px] md:text-xs tracking-[0.5em] uppercase text-brand-gold mb-12 font-bold">The Waiting Moment</p>

                    <div class="flex flex-row justify-center items-center gap-4 md:gap-12">
                        <div class="flex flex-col items-center">
                            <div class="relative flex items-center justify-center w-20 h-20 md:w-32 md:h-32">
                                <svg class="absolute inset-0 w-full h-full -rotate-90 opacity-20">
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="2" fill="none" class="text-brand-gold"></circle>
                                </svg>
                                <span id="days" class="text-3xl md:text-5xl font-serif text-white leading-none">00</span>
                            </div>
                            <span class="text-[8px] md:text-[10px] uppercase tracking-[0.3em] text-brand-muted mt-4">Days</span>
                        </div>

                        <div class="text-brand-gold/30 font-serif italic text-2xl hidden md:block mt-[-2rem]">:</div>

                        <div class="flex flex-col items-center">
                            <div class="relative flex items-center justify-center w-20 h-20 md:w-32 md:h-32">
                                <svg class="absolute inset-0 w-full h-full -rotate-90 opacity-20">
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="2" fill="none" class="text-brand-gold"></circle>
                                </svg>
                                <span id="hours" class="text-3xl md:text-5xl font-serif text-white leading-none">00</span>
                            </div>
                            <span class="text-[8px] md:text-[10px] uppercase tracking-[0.3em] text-brand-muted mt-4">Hours</span>
                        </div>

                        <div class="text-brand-gold/30 font-serif italic text-2xl hidden md:block mt-[-2rem]">:</div>

                        <div class="flex flex-col items-center">
                            <div class="relative flex items-center justify-center w-20 h-20 md:w-32 md:h-32">
                                <svg class="absolute inset-0 w-full h-full -rotate-90 opacity-20">
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="2" fill="none" class="text-brand-gold"></circle>
                                </svg>
                                <span id="minutes" class="text-3xl md:text-5xl font-serif text-white leading-none">00</span>
                            </div>
                            <span class="text-[8px] md:text-[10px] uppercase tracking-[0.3em] text-brand-muted mt-4">Minutes</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50">
                <span class="text-[8px] uppercase tracking-[0.4em] text-brand-gold">Scroll</span>
                <div class="w-[1px] h-12 bg-gradient-to-b from-brand-gold to-transparent"></div>
            </div>
        </section>

        {{-- MEMPELAI (SELALU AKTIF, URUTAN DINAMIS) --}}
        {{-- MEMPELAI (SELALU AKTIF, URUTAN DATA DINAMIS BERDASARKAN FIRST & SECOND PERSON) --}}
<section id="mempelai" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/black-linen.png')] opacity-30 pointer-events-none"></div>
    <div class="max-w-6xl mx-auto relative z-10">
        <div class="flex flex-col items-center mb-24">
            <span class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold text-center">The Happy Couple</span>
            <h2 class="text-4xl md:text-6xl font-serif text-white italic drop-shadow-md">Mempelai</h2>
            <div class="h-[2px] w-16 bg-brand-gold mt-6"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 lg:gap-12 items-center">
            
            {{-- KARTU KIRI (Menggunakan Data First Person) --}}
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 lg:pr-12 group">
                <div class="relative w-64 h-80 md:w-72 md:h-[28rem] order-1 md:order-2 flex-shrink-0">
                    <div class="absolute inset-0 border-2 border-brand-gold translate-x-4 translate-y-4 rounded-2xl z-0"></div>
                    <div class="absolute inset-0 bg-brand-accent overflow-hidden rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-10">
                        {{-- Perbaikan variabel photo dan default_img --}}
                        <img src="{{ !empty($firstPerson['photo']) ? asset('storage/'.$firstPerson['photo']) : $firstPerson['default_img'] }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    </div>
                </div>
                <div class="text-center md:text-right order-2 md:order-1 flex-1">
                    <p class="text-brand-gold font-serif italic text-2xl mb-2">{{ $firstPerson['label'] }}</p>
                    <h3 class="text-4xl md:text-5xl font-serif text-white mb-6 tracking-wide leading-tight">{{ $firstPerson['name'] }}</h3>
                    <div class="space-y-3">
                        <p class="uppercase text-[10px] tracking-[0.3em] text-brand-gold font-bold">Putra/i Tercinta dari</p>
                        <p class="text-white text-lg font-medium">Bapak {{ $firstPerson['father'] }}</p>
                        <p class="text-white text-lg font-medium">& Ibu {{ $firstPerson['mother'] }}</p>
                    </div>
                    @if(!empty($firstPerson['ig']))
                        <a href="https://instagram.com/{{ $firstPerson['ig'] }}" target="_blank" class="inline-flex items-center gap-2 mt-6 text-brand-gold/60 hover:text-brand-gold transition text-xs font-semibold tracking-widest uppercase">
                            <i class="fa-brands fa-instagram"></i> {{ $firstPerson['ig'] }}
                        </a>
                    @endif
                </div>
            </div>

            {{-- KARTU KANAN (Menggunakan Data Second Person) --}}
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 lg:pl-12 group lg:mt-40">
                <div class="relative w-64 h-80 md:w-72 md:h-[28rem] flex-shrink-0">
                    <div class="absolute inset-0 border-2 border-brand-gold -translate-x-4 translate-y-4 rounded-2xl z-0"></div>
                    <div class="absolute inset-0 bg-brand-accent overflow-hidden rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-10">
                        {{-- Perbaikan variabel photo dan default_img --}}
                        <img src="{{ !empty($secondPerson['photo']) ? asset('storage/'.$secondPerson['photo']) : $secondPerson['default_img'] }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    </div>
                </div>
                <div class="text-center md:text-left flex-1">
                    <p class="text-brand-gold font-serif italic text-2xl mb-2">{{ $secondPerson['label'] }}</p>
                    <h3 class="text-4xl md:text-5xl font-serif text-white mb-6 tracking-wide leading-tight">{{ $secondPerson['name'] }}</h3>
                    <div class="space-y-3">
                        <p class="uppercase text-[10px] tracking-[0.3em] text-brand-gold font-bold">Putra/i Tercinta dari</p>
                        <p class="text-white text-lg font-medium">Bapak {{ $secondPerson['father'] }}</p>
                        <p class="text-white text-lg font-medium">& Ibu {{ $secondPerson['mother'] }}</p>
                    </div>
                    @if(!empty($secondPerson['ig']))
                        <a href="https://instagram.com/{{ $secondPerson['ig'] }}" target="_blank" class="inline-flex items-center gap-2 mt-6 text-brand-gold/60 hover:text-brand-gold transition text-xs font-semibold tracking-widest uppercase">
                            <i class="fa-brands fa-instagram"></i> {{ $secondPerson['ig'] }}
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

        {{-- STORY MEMPELAI (TOGGLE) --}}
        @if(!empty($content['is_story_active']) && !empty($content['love_stories']))
        <section id="story" class="py-24 px-6 bg-brand-dark relative overflow-hidden border-t border-brand-gold/20">
            <div class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/black-linen.png')]"></div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-24 flex flex-col items-center">
                    <span class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold">Our Journey</span>
                    <h2 class="text-4xl md:text-6xl font-serif italic text-white drop-shadow-md">Cerita Cinta Kami</h2>
                    <div class="h-[2px] w-16 bg-brand-gold mt-8"></div>
                </div>

                <div class="relative space-y-20 md:space-y-32">
                    <div class="hidden md:block absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-[1px] bg-gradient-to-b from-brand-gold/50 via-brand-gold/10 to-transparent"></div>

                    @foreach($content['love_stories'] as $index => $story)
                    @php $isEven = $index % 2 == 0; @endphp

                    <div class="relative grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center group {{ $index > 2 ? 'hidden extra-story' : '' }}">
                        <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 z-20 w-12 h-12 rounded-full bg-brand-dark border-2 border-brand-gold items-center justify-center group-hover:scale-110 transition-transform duration-500 shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                            <div class="w-3 h-3 rounded-full bg-brand-gold animate-pulse"></div>
                        </div>

                        <div class="relative {{ $isEven ? 'order-1 md:order-1 flex justify-center md:justify-end md:pr-10 lg:pr-16' : 'order-1 md:order-2 flex justify-center md:justify-start md:pl-10 lg:pl-16' }}">
                            @if(!empty($story['image']))
                            <div class="relative w-full max-w-md aspect-[4/3] rounded-3xl overflow-hidden border border-white/5 shadow-2xl transition-all duration-700 hover:border-brand-gold/30">
                                <img src="{{ asset('storage/'.$story['image']) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Momen">
                                <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-transparent to-transparent"></div>
                            </div>
                            @endif
                        </div>

                        <div class="{{ $isEven ? 'order-2 md:order-2 text-center md:text-left md:pl-10 lg:pl-16 flex flex-col items-center md:items-start' : 'order-2 md:order-1 text-center md:text-right md:pr-10 lg:pr-16 flex flex-col items-center md:items-end' }}">
                            <span class="text-[10px] font-bold tracking-[0.3em] text-brand-gold uppercase bg-brand-gold/10 px-3 py-1.5 rounded-full inline-block mb-6">{{ $story['year'] }}</span>
                            <h4 class="text-3xl md:text-4xl font-serif text-white mb-5 leading-snug">{{ $story['title'] }}</h4>
                            <p class="text-sm md:text-base text-white/80 leading-relaxed font-light max-w-xl mx-auto md:mx-0">
                                {{ $story['description'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(count($content['love_stories']) > 3)
                <div class="mt-24 text-center">
                    <button id="btn-read-more" onclick="toggleStories()" class="group relative px-12 py-4 bg-transparent border border-brand-gold/50 text-brand-gold rounded-full overflow-hidden transition-all duration-500 hover:border-brand-gold active:scale-95 shadow-[0_0_20px_rgba(212,175,55,0.1)]">
                        <span class="relative z-10 text-[11px] font-bold uppercase tracking-[0.3em] group-hover:text-brand-dark transition-colors duration-500">Baca Selengkapnya</span>
                        <div class="absolute inset-0 bg-brand-gold translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                    </button>
                </div>
                @endif
            </div>
        </section>
        @endif

        {{-- LOKASI DAN WAKTU (TOGGLE) --}}
        @if(!empty($content['is_event_active']))
        <section id="lokasi" class="py-24 px-6 bg-brand-dark relative overflow-hidden border-t border-brand-gold/20">
            <div class="absolute inset-0 opacity-10 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-gold/10 rounded-full blur-[100px]"></div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-16 flex flex-col items-center">
                    <span class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold">The Celebration</span>
                    <h2 class="text-5xl md:text-6xl font-serif italic text-white drop-shadow-md">Waktu & Tempat</h2>
                    <div class="h-[2px] w-16 bg-brand-gold mt-8"></div>
                </div>

                <div class="grid grid-cols-1 {{ count($content['events'] ?? []) > 0 ? 'md:grid-cols-2' : '' }} gap-8 lg:gap-12 items-stretch">

                    {{-- AKAD NIKAH STATIS --}}
                    @if(!empty($content['akad_location']))
                    <div class="glass-card p-10 md:p-14 rounded-[3rem] border border-white/10 relative group hover:border-brand-gold/40 transition-all duration-500 flex flex-col items-center text-center h-full">
                        <div class="w-20 h-20 rounded-full bg-brand-gold/10 flex items-center justify-center mb-8 border border-brand-gold/20 group-hover:scale-110 transition-transform duration-500">
                            <i class="fa-solid fa-ring text-3xl text-brand-gold"></i>
                        </div>

                        <h3 class="text-3xl font-serif text-white mb-2 italic">Akad Nikah</h3>
                        <p class="text-brand-gold text-[10px] tracking-[0.4em] uppercase font-bold mb-8">Sacred Union</p>

                        <div class="space-y-6 mb-10 flex-1">
                            <div class="flex flex-col items-center">
                                <i class="fa-regular fa-calendar-check text-brand-gold/60 mb-2"></i>
                                <p class="text-white font-medium">{{ \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fa-regular fa-clock text-brand-gold/60 mb-2"></i>
                                <p class="text-white font-medium">{{ $content['akad_time'] }}</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-location-dot text-brand-gold/60 mb-2"></i>
                                <p class="text-white/80 text-sm leading-relaxed max-w-[200px]">
                                    <span class="font-bold">{{ $content['akad_location'] }}</span><br>
                                    {{ $content['akad_address'] }}
                                </p>
                            </div>
                        </div>

                        @if(!empty($content['akad_map']))
                        <a href="{{ $content['akad_map'] }}" target="_blank" class="w-full py-4 bg-transparent border border-brand-gold/50 text-brand-gold rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-brand-dark transition-all duration-500 flex items-center justify-center gap-3">
                            <i class="fa-solid fa-map-location-dot text-sm"></i> Petunjuk Lokasi
                        </a>
                        @endif
                    </div>
                    @endif

                    {{-- RESEPSI DINAMIS --}}
                    @if(!empty($content['events']))
                    @foreach($content['events'] as $event)
                    <div class="glass-card p-10 md:p-14 rounded-[3rem] border border-white/10 relative group hover:border-brand-gold/40 transition-all duration-500 flex flex-col items-center text-center h-full">
                        <div class="w-20 h-20 rounded-full bg-brand-gold/10 flex items-center justify-center mb-8 border border-brand-gold/20 group-hover:scale-110 transition-transform duration-500">
                            <i class="fa-solid fa-champagne-glasses text-3xl text-brand-gold"></i>
                        </div>

                        <h3 class="text-3xl font-serif text-white mb-2 italic">{{ $event['title'] }}</h3>
                        <p class="text-brand-gold text-[10px] tracking-[0.4em] uppercase font-bold mb-8">Grand Celebration</p>

                        <div class="space-y-6 mb-10 flex-1">
                            <div class="flex flex-col items-center">
                                <i class="fa-regular fa-calendar-check text-brand-gold/60 mb-2"></i>
                                <p class="text-white font-medium">{{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fa-regular fa-clock text-brand-gold/60 mb-2"></i>
                                <p class="text-white font-medium">{{ $event['time'] }}</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-hotel text-brand-gold/60 mb-2"></i>
                                <p class="text-white/80 text-sm leading-relaxed max-w-[200px]">
                                    <span class="font-bold">{{ $event['location'] }}</span><br>
                                    {{ $event['address'] }}
                                </p>
                            </div>
                        </div>

                        @if(!empty($event['map']))
                        <a href="{{ $event['map'] }}" target="_blank" class="w-full py-4 bg-transparent border border-brand-gold/50 text-brand-gold rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-brand-dark transition-all duration-500 flex items-center justify-center gap-3">
                            <i class="fa-solid fa-map-location-dot text-sm"></i> Petunjuk Lokasi
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
        @if(!empty($content['is_guest_info_active']))
        <section class="py-20 px-6 bg-brand-dark relative overflow-hidden border-t border-brand-gold/20">
            <div class="max-w-3xl mx-auto text-center border border-brand-gold/20 rounded-[3rem] p-10 md:p-16 relative bg-white/5 backdrop-blur-sm shadow-xl">
                <i class="fa-solid fa-circle-info text-3xl text-brand-gold mb-6"></i>
                <h4 class="text-2xl md:text-3xl font-serif text-white mb-8 italic">Informasi Tamu</h4>

                @if(!empty($content['enable_dresscode']) && !empty($content['dresscode']))
                <div class="mb-10">
                    <p class="text-[10px] uppercase tracking-widest text-brand-gold/60 font-bold mb-2">Dresscode</p>
                    <p class="text-sm font-medium text-white/90">{{ $content['dresscode'] }}</p>
                </div>
                @endif

                @if(!empty($content['enable_health_protocol']))
                <div class="pt-6 border-t border-brand-gold/20 w-4/5 mx-auto">
                    <p class="text-[10px] uppercase tracking-widest text-brand-gold/60 font-bold mb-6">Protokol Kesehatan</p>
                    <div class="flex justify-center gap-8 text-brand-gold text-3xl">
                        <div class="flex flex-col items-center gap-3 group"><i class="fa-solid fa-head-side-mask group-hover:scale-110 transition-transform"></i><span class="text-[9px] text-white/50 uppercase tracking-widest">Masker</span></div>
                        <div class="flex flex-col items-center gap-3 group"><i class="fa-solid fa-hands-bubbles group-hover:scale-110 transition-transform"></i><span class="text-[9px] text-white/50 uppercase tracking-widest">Cuci Tangan</span></div>
                        <div class="flex flex-col items-center gap-3 group"><i class="fa-solid fa-people-arrows group-hover:scale-110 transition-transform"></i><span class="text-[9px] text-white/50 uppercase tracking-widest">Jaga Jarak</span></div>
                    </div>
                </div>
                @endif
            </div>
        </section>
        @endif

        {{-- GALERI & VIDEO (TOGGLE) --}}
        @if(!empty($content['is_gallery_active']))
        <section id="gallery" class="py-24 px-6 bg-brand-dark relative overflow-hidden border-t border-brand-gold/20">
            <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-brand-dark/50 to-transparent"></div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <p class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold">Captured Moments</p>
                    <h2 class="text-5xl md:text-6xl font-serif italic text-white drop-shadow-md">Our Gallery</h2>
                    <div class="h-[2px] w-12 bg-brand-gold mx-auto mt-6"></div>
                </div>

                {{-- YOUTUBE DINAMIS --}}
                @if(!empty($content['youtube_links']))
                @foreach($content['youtube_links'] as $yt)
                @php $ytId = getYoutubeId($yt); @endphp
                @if($ytId)
                <div class="mb-16 animate-fade-in group">
                    <div class="relative w-full max-w-4xl mx-auto pb-[56.25%] rounded-[2.5rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 group-hover:border-brand-gold/30 transition-colors duration-500">
                        <iframe class="absolute top-0 left-0 w-full h-full grayscale-[0.2] group-hover:grayscale-0 transition-all duration-700" src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                @endif
                @endforeach
                @endif

                {{-- FOTO GALERI --}}
                @if($invitation->galleries->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5" id="photo-grid">
                    @foreach($invitation->galleries->where('type','photo') as $idx => $photo)
                    <div class="group relative aspect-square rounded-2xl md:rounded-[2rem] overflow-hidden cursor-pointer shadow-xl border border-white/5" onclick="openLightbox({{ $idx }})">
                        <img src="{{ asset('storage/'.$photo->file_path) }}" class="gallery-img w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Gallery">
                        <div class="absolute inset-0 bg-brand-dark/40 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-[2px]">
                            <div class="p-3 rounded-full border border-brand-gold/50 text-brand-gold scale-50 group-hover:scale-100 transition-transform duration-500">
                                <i class="fa-solid fa-expand text-xl"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </section>

        <div id="lightbox" class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-brand-dark/95 backdrop-blur-xl p-4 transition-all duration-500">
            <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white/50 hover:text-brand-gold transition-colors text-3xl z-[110]">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="relative w-full max-w-5xl flex items-center justify-center group">
                <button onclick="prevImg()" class="absolute left-0 md:-left-20 text-white/30 hover:text-brand-gold transition-all text-4xl hidden md:block group-hover:translate-x-2"><i class="fa-solid fa-chevron-left"></i></button>
                <img id="lightbox-img" src="" class="max-h-[85vh] max-w-full rounded-lg shadow-2xl transition-opacity duration-300 ease-in-out border border-white/10" alt="Zoomed Photo">
                <button onclick="nextImg()" class="absolute right-0 md:-right-20 text-white/30 hover:text-brand-gold transition-all text-4xl hidden md:block group-hover:-translate-x-2"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
            <p class="mt-8 text-brand-gold tracking-[0.3em] text-[10px] uppercase font-bold">Image <span id="current-count">1</span> of <span id="total-count">5</span></p>
        </div>
        @endif

        {{-- HADIAH / REKENING (TOGGLE) --}}
        @if(!empty($content['is_gift_active']) && !empty($content['banks']))
        <section id="hadiah" class="py-24 px-6 bg-brand-dark relative overflow-hidden border-t border-brand-gold/20">
            <div class="absolute -bottom-48 -left-48 w-[500px] h-[500px] bg-brand-gold/5 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="max-w-4xl mx-auto text-center relative z-10">
                <div class="mb-16">
                    <span class="text-[9px] tracking-[0.6em] uppercase text-brand-gold font-bold mb-4 block">Wedding Gift</span>
                    <h2 class="text-5xl font-serif italic text-white drop-shadow-md">Tanda Kasih</h2>
                    <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mt-6 mb-8"></div>
                    <p class="text-sm text-white/60 font-light leading-relaxed max-w-lg mx-auto italic">
                        Doa restu Anda adalah karunia terindah bagi kami. Namun jika Anda ingin memberikan tanda kasih, pintu hati kami terbuka untuk menerimanya melalui:
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
                    @foreach($content['banks'] as $idx => $bank)
                    <div class="group relative p-10 bg-white/5 rounded-[3.5rem] border border-white/10 backdrop-blur-md transition-all duration-700 hover:border-brand-gold/40 hover:-translate-y-2">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center shadow-sm border border-brand-gold/30 mb-8 text-brand-gold text-2xl group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <p class="text-sm uppercase tracking-widest text-white mb-6 font-bold">{{ $bank['name'] }}</p>
                            <p class="text-[9px] uppercase tracking-[0.4em] text-brand-gold/60 mb-3 font-bold">Nomor Rekening</p>
                            <h3 id="rek-{{ $idx }}" class="text-3xl font-serif font-bold text-white mb-2 tracking-widest group-hover:text-brand-gold transition-colors duration-500">{{ $bank['account_number'] }}</h3>
                            <p class="text-xs text-white/40 italic mb-10 font-light tracking-wide">a.n {{ $bank['account_name'] }}</p>

                            <button onclick="copyToClipboard('rek-{{ $idx }}', this)"
                                class="w-full py-4 bg-transparent border border-brand-gold/40 text-brand-gold rounded-2xl text-[10px] font-bold uppercase tracking-[0.2em] transition-all duration-500 hover:bg-brand-gold hover:text-brand-dark hover:shadow-[0_10px_30px_rgba(197,160,101,0.2)]">
                                <i class="fa-regular fa-copy mr-2"></i>
                                <span>Salin Nomor</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="copy-toast" class="fixed bottom-28 left-1/2 -translate-x-1/2 z-[300] px-10 py-4 bg-brand-gold text-brand-dark text-[10px] rounded-full tracking-[0.3em] uppercase font-bold opacity-0 translate-y-10 transition-all duration-700 pointer-events-none shadow-[0_20px_40px_rgba(197,160,101,0.3)] border border-white/20">
                    <i class="fa-solid fa-check-circle mr-2"></i> Tersalin ke Clipboard
                </div>
            </div>
        </section>
        @endif

        {{-- UCAPAN & DOA (HASIL RSVP) - TOGGLE SHOW/HIDE --}}
        @if(!empty($content['is_wishes_active']))
        <section id="guest-stats" class="py-24 px-6 bg-brand-dark relative overflow-hidden border-t border-brand-gold/20">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-gold/5 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="max-w-4xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <p class="text-[10px] tracking-[0.5em] uppercase text-brand-gold mb-3 font-bold">Guest Participation</p>
                    <h2 class="text-4xl md:text-5xl font-serif italic text-white drop-shadow-md">Kehadiran & Doa</h2>
                    <div class="h-[1px] w-16 bg-brand-gold/30 mx-auto mt-6"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <div class="glass-stat p-8 rounded-[2.5rem] flex items-center gap-6 transition-all duration-500 hover:border-brand-gold/30 group">
                        <div class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center shrink-0 border border-brand-gold/20 group-hover:bg-brand-gold group-hover:text-brand-dark transition-all duration-500">
                            <i class="fa-solid fa-user-check text-brand-gold text-2xl group-hover:text-brand-dark"></i>
                        </div>
                        <div>
                            <h4 id="total-attendance" class="text-4xl md:text-5xl font-serif font-bold text-white mb-1">0</h4>
                            <p class="text-[9px] uppercase tracking-[0.2em] text-brand-gold font-bold">Tamu Akan Hadir</p>
                        </div>
                    </div>

                    <div class="glass-stat p-8 rounded-[2.5rem] flex items-center gap-6 transition-all duration-500 hover:border-brand-gold/30 group">
                        <div class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center shrink-0 border border-brand-gold/20 group-hover:bg-brand-gold group-hover:text-brand-dark transition-all duration-500">
                            <i class="fa-solid fa-envelope-open-text text-brand-gold text-2xl group-hover:text-brand-dark"></i>
                        </div>
                        <div>
                            <h4 id="total-wishes" class="text-4xl md:text-5xl font-serif font-bold text-white mb-1">0</h4>
                            <p class="text-[9px] uppercase tracking-[0.2em] text-brand-gold font-bold">Ucapan Hangat</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 glass-stat rounded-[3rem] border border-white/10 p-2 overflow-hidden shadow-2xl">
                    <div class="flex items-center justify-between p-6 md:px-10 border-b border-white/5 mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-brand-gold">Wishes Wall</span>
                        <div class="flex gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-500/50"></div>
                            <div class="w-2 h-2 rounded-full bg-yellow-500/50"></div>
                            <div class="w-2 h-2 rounded-full bg-green-500/50"></div>
                        </div>
                    </div>

                    <div id="wishes-container" class="max-h-[500px] overflow-y-auto scroll-custom px-6 md:px-10 py-4 space-y-6">
                        {{-- Diisi Oleh Script JS --}}
                    </div>

                    <div class="p-8 text-center border-t border-white/5">
                        <button id="btn-load-more" onclick="loadMoreWishes()" class="group relative px-10 py-3.5 bg-transparent border border-brand-gold/40 text-brand-gold rounded-full overflow-hidden transition-all duration-500 hover:border-brand-gold">
                            <span class="relative z-10 text-[10px] font-bold uppercase tracking-widest group-hover:text-brand-dark transition-colors">Lihat Semua Pesan</span>
                            <div class="absolute inset-0 bg-brand-gold translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        </button>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- FORM RSVP SELALU AKTIF (TERSEMBUNYI DALAM MODAL) --}}
        <section id="rsvp-modal" class="fixed inset-0 z-[100] invisible transition-all duration-500 overflow-hidden">
            <div onclick="closeRSVP()" class="absolute inset-0 bg-brand-dark/80 backdrop-blur-md opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>

            <div id="rsvp-content" class="absolute bottom-0 left-0 right-0 bg-brand-dark rounded-t-[3.5rem] border-t border-white/10 shadow-[0_-20px_50px_rgba(0,0,0,0.5)] transform translate-y-full transition-transform duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] px-6 pb-12 pt-4 max-w-2xl mx-auto">
                <div class="w-12 h-1 bg-white/10 rounded-full mx-auto mb-10"></div>

                <div class="text-center mb-10">
                    <span class="text-[9px] text-brand-gold tracking-[0.5em] uppercase font-bold mb-2 block">Guest Book</span>
                    <h2 class="text-4xl font-serif text-white italic">RSVP & Ucapan</h2>
                    <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mt-4"></div>
                </div>

                <form id="rsvpForm" class="space-y-4 text-left" onsubmit="submitRSVP(event)">
                    <div class="group">
                        <label class="text-[10px] uppercase tracking-widest text-brand-gold ml-4 mb-2 block opacity-60">Nama Lengkap</label>
                        <input type="text" id="input-nama-rsvp" name="name" placeholder="Tulis nama Anda..." class="input-luxury w-full p-5 rounded-[1.8rem] text-sm placeholder-white/20 outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir" class="py-4 rounded-[1.5rem] border border-white/10 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 bg-white/5 text-white/50 hover:border-brand-gold/50">
                            <i class="fa-solid fa-check mr-2 text-[10px]"></i> Hadir
                        </button>
                        <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen" class="py-4 rounded-[1.5rem] border border-white/10 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 bg-white/5 text-white/50 hover:border-brand-gold/50">
                            <i class="fa-solid fa-xmark mr-2 text-[10px]"></i> Absen
                        </button>
                        <input type="hidden" name="status" id="input-status" required>
                    </div>

                    <div id="guest-selection" class="hidden animate-slide-up bg-white/5 p-6 rounded-[2rem] border border-white/5">
                        <label class="text-[10px] uppercase tracking-widest text-brand-gold mb-4 block font-bold text-center">Berapa orang yang hadir?</label>
                        <div class="flex gap-3">
                            <button type="button" onclick="selectGuestCount(1, this)" class="guest-btn flex-1 py-3 rounded-xl border border-white/10 text-[10px] text-white hover:bg-brand-gold hover:text-brand-dark transition-all">1</button>
                            <button type="button" onclick="selectGuestCount(2, this)" class="guest-btn flex-1 py-3 rounded-xl border border-white/10 text-[10px] text-white hover:bg-brand-gold hover:text-brand-dark transition-all">2</button>
                            <button type="button" onclick="selectGuestCount(3, this)" class="guest-btn flex-1 py-3 rounded-xl border border-white/10 text-[10px] text-white hover:bg-brand-gold hover:text-brand-dark transition-all">3+</button>
                            <input type="hidden" name="guest_count" id="input-guest-count" value="1">
                        </div>
                    </div>

                    <div class="group">
                        <label class="text-[10px] uppercase tracking-widest text-brand-gold ml-4 mb-2 block opacity-60">Doa & Ucapan</label>
                        <textarea id="input-pesan-rsvp" name="message" rows="4" placeholder="Berikan doa terbaik Anda untuk kami..." class="input-luxury w-full p-5 rounded-[1.8rem] text-sm placeholder-white/20 outline-none resize-none" required></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="closeRSVP()" class="flex-1 py-4 bg-white/5 text-white/40 rounded-[1.5rem] font-bold text-[10px] uppercase tracking-widest hover:bg-white/10 transition-all">Batal</button>
                        <button type="submit" id="btnSubmitRsvp" class="flex-[2] py-4 bg-brand-gold hover:bg-brand-charcoal text-white rounded-2xl font-semibold text-sm transition-all shadow-lg shadow-brand-gold/20">
                            Kirim RSVP
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <footer class="py-12 px-6 bg-brand-dark border-t border-white/10 text-center">
            <div class="max-w-md mx-auto">
                <div class="mb-4 opacity-30 italic font-serif text-brand-gold text-lg">
                    {{ substr($pria,0,1) }} & {{ substr($wanita,0,1) }}
                </div>

                <p class="text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-4 font-light">
                    Terima kasih atas doa & restu Anda
                </p>

                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 shadow-sm">
                    <span class="text-[10px] text-gray-500 font-sans uppercase tracking-wider">Created with love by</span>
                    <a href="https://instagram.com/ruangrestu.undangan" target="_blank" rel="noopener noreferrer" class="text-[11px] font-semibold text-brand-gold hover:text-white transition-colors flex items-center gap-1">
                        <i class="fa-brands fa-instagram text-sm"></i> @ruangrestu.undangan
                    </a>
                </div>

                <p class="mt-8 text-[9px] text-gray-500 font-light italic">
                    © 2026 {{ $pria }} & {{ $wanita }} Wedding Invitation.
                </p>
            </div>
        </footer>

    </main>

    <div id="fab-container" class="fixed right-5 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000">
        <div class="relative flex items-center group">
            <div id="music-info" class="absolute right-full mr-3 px-3 py-1 bg-brand-dark/90 backdrop-blur border border-brand-gold/30 rounded-lg text-brand-gold text-xs whitespace-nowrap shadow-md opacity-0 translate-x-4 pointer-events-none transition-all duration-500 group-hover:opacity-100 group-hover:translate-x-0">
                🎵 Memutar Musik
            </div>
            <button id="btn-music" onclick="toggleMusic()" class="w-11 h-11 bg-brand-dark backdrop-blur border border-brand-gold/40 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-brand-dark transition-all">
                <i class="fa-solid fa-music animate-spin-slow" id="icon-music"></i>
            </button>
        </div>

        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-11 h-11 bg-brand-dark backdrop-blur border border-brand-gold/40 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-brand-dark transition-all">
            <i class="fa-solid fa-angles-down" id="icon-scroll"></i>
        </button>
    </div>

    <nav id="bottom-nav" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 floating-nav rounded-[2rem] transition-all duration-1000 translate-y-32">
        <ul class="flex justify-around items-center h-20 w-[340px] md:w-[450px] px-4">
            <li><a href="#home" class="nav-link flex flex-col items-center text-[10px] uppercase tracking-[0.2em] font-bold gap-2 text-white/50 hover:text-brand-gold transition-colors"><i class="fa-solid fa-house-chimney text-lg"></i><span class="hidden md:block">Home</span></a></li>
            @if(!empty($content['is_gallery_active']))<li><a href="#gallery" class="nav-link flex flex-col items-center text-[10px] uppercase tracking-[0.2em] font-bold gap-2 text-white/50 hover:text-brand-gold transition-colors"><i class="fa-solid fa-cloud-sun text-lg"></i><span class="hidden md:block">Gallery</span></a></li>@endif
            @if(!empty($content['is_event_active']))<li><a href="#lokasi" class="nav-link flex flex-col items-center text-[10px] uppercase tracking-[0.2em] font-bold gap-2 text-white/50 hover:text-brand-gold transition-colors"><i class="fa-solid fa-compass text-lg"></i><span class="hidden md:block">Venue</span></a></li>@endif
            <li><a href="javascript:void(0)" onclick="openRSVP()" class="nav-link flex flex-col items-center text-[10px] uppercase tracking-[0.2em] font-bold gap-2 text-white/50 hover:text-brand-gold transition-colors">
                    <div class="relative"><i class="fa-solid fa-paper-plane text-lg"></i><span class="absolute -top-1 -right-1 w-2 h-2 bg-brand-gold rounded-full animate-pulse"></span></div><span class="hidden md:block">RSVP</span>
                </a></li>
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
                icon.classList.remove('fa-music', 'animate-spin');
                icon.classList.add('fa-volume-xmark');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.remove('fa-volume-xmark');
                    icon.classList.add('fa-music', 'animate-spin');
                }).catch(() => { console.log("Autoplay dicegah browser."); });
            }
        }

        function toggleAutoScroll(forceStart = false) {
            const btn = document.getElementById('btn-scroll');
            const icon = document.getElementById('icon-scroll');
            if (isAutoScrolling && !forceStart) {
                clearInterval(scrollInterval);
                isAutoScrolling = false;
                btn.classList.remove('bg-brand-gold', 'text-brand-dark');
                btn.classList.add('bg-brand-dark', 'text-brand-gold');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-angles-down');
            } else {
                isAutoScrolling = true;
                btn.classList.remove('bg-brand-dark', 'text-brand-gold');
                btn.classList.add('bg-brand-gold', 'text-brand-dark');
                icon.classList.remove('fa-angles-down');
                icon.classList.add('fa-pause');
                scrollInterval = setInterval(() => {
                    window.scrollBy({ top: 1, behavior: 'auto' });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) toggleAutoScroll();
                }, 35);
            }
        }

        window.addEventListener('wheel', () => { if(isAutoScrolling) toggleAutoScroll(); }, { passive: true });
        window.addEventListener('touchmove', () => { if(isAutoScrolling) toggleAutoScroll(); }, { passive: true });

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
            btn.querySelector('span').innerText = isHidden ? 'Sembunyikan Cerita' : 'Baca Selengkapnya';
        }

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
                } else { alert('Gagal mengirim RSVP.'); }
            })
            .catch(() => { alert('Terjadi kesalahan server.'); })
            .finally(() => {
                btn.innerHTML = 'Kirim Konfirmasi <i class="fa-solid fa-paper-plane ml-2"></i>';
                btn.disabled = false;
                document.getElementById('rsvpForm').reset();
            });
        }

        function addNewWishCard(nama, pesan, waktu) {
            const container = document.getElementById('wishes-container');
            if (!container) return;
            const card = document.createElement('div');
            card.className = 'wish-card bg-white/5 p-6 rounded-[1.5rem] border border-white/10 animate-fade-in-up transition-all duration-500 hover:bg-white/10 hover:border-brand-gold/30';
            card.innerHTML = `<div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-2"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full bg-brand-gold/20 flex items-center justify-center border border-brand-gold/30"><i class="fa-solid fa-user text-[10px] text-brand-gold"></i></div><h5 class="text-sm font-serif font-bold text-white tracking-wide">${nama}</h5></div><span class="text-[8px] text-brand-gold/60 italic uppercase tracking-[0.2em] flex items-center"><i class="fa-regular fa-clock mr-1.5"></i> ${waktu}</span></div><div class="relative"><i class="fa-solid fa-quote-left absolute -top-2 -left-2 text-brand-gold/10 text-2xl"></i><p class="text-[13px] text-white/70 leading-relaxed font-light italic pl-4">${pesan}</p></div>`;
            container.prepend(card);
        }

        const allWishes = [
            @foreach($invitation->rsvps()->latest()->get() as $wish)
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
            if (displayedCount === 0) container.innerHTML = '';
            let nextLimit = displayedCount + 3;
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
                btn.classList.remove('bg-brand-gold', 'text-brand-dark', 'border-brand-gold', 'shadow-lg');
                btn.classList.add('bg-white/5', 'text-white/50', 'border-white/10');
            });
            if (status === 'Hadir') {
                btnHadir.classList.replace('bg-white/5', 'bg-brand-gold');
                btnHadir.classList.replace('text-white/50', 'text-brand-dark');
                btnHadir.classList.replace('border-white/10', 'border-brand-gold');
                btnHadir.classList.add('shadow-lg');
                guestDiv.classList.remove('hidden');
            } else {
                btnAbsen.classList.replace('bg-white/5', 'bg-brand-gold');
                btnAbsen.classList.replace('text-white/50', 'text-brand-dark');
                btnAbsen.classList.replace('border-white/10', 'border-brand-gold');
                btnAbsen.classList.add('shadow-lg');
                guestDiv.classList.add('hidden');
            }
        }

        function selectGuestCount(count, btnElement) {
            document.getElementById('input-guest-count').value = count;
            document.querySelectorAll('.guest-btn').forEach(btn => {
                btn.classList.remove('bg-brand-gold', 'text-brand-dark', 'font-bold');
                btn.classList.add('text-white');
            });
            btnElement.classList.remove('text-white');
            btnElement.classList.add('bg-brand-gold', 'text-brand-dark', 'font-bold');
        }

        function openRSVP() {
            const modal = document.getElementById('rsvp-modal');
            if(modal) {
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
        
        // Setup Lightbox (Pindahkan inisialisasi ke sini agar aman)
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
        // ==========================================
        // AUTO OPEN RSVP ON SCROLL BOTTOM
        // ==========================================
        let hasShownRSVPAtEnd = false; // Penanda agar modal tidak muncul terus-menerus

        window.addEventListener('scroll', () => {
            // Kita beri toleransi 100px sebelum benar-benar sampai bawah
            const scrollPosition = window.innerHeight + window.scrollY;
            const threshold = document.body.offsetHeight - 100;

            if (scrollPosition >= threshold) {
                if (!hasShownRSVPAtEnd) {
                    openRSVP();
                    hasShownRSVPAtEnd = true; // Set ke true agar hanya muncul 1x otomatis
                    
                    // Jika auto-scroll sedang aktif, matikan agar user bisa mengisi form
                    if (typeof isAutoScrolling !== 'undefined' && isAutoScrolling) {
                        toggleAutoScroll();
                    }
                }
            }
        }, { passive: true });
    </script>
</body>

</html>