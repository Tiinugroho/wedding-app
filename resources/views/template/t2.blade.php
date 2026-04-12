@php
    // 1. Decode data JSON dari database
    $content = json_decode($invitation->details->content ?? '{}', true);

    // 2. Logika Urutan Mempelai (Groom First / Bride First)
    $groomFirst = ($content['couple_order'] ?? 'groom_first') === 'groom_first';

    $groom = [
        'name' => $content['groom_name'] ?? 'Romeo Montague',
        'nickname' => $content['groom_nickname'] ?? 'Romeo',
        'father' => $content['groom_father'] ?? 'Bapak Montague',
        'mother' => $content['groom_mother'] ?? 'Ibu Montague',
        'photo' => isset($content['groom_photo'])
            ? asset('storage/' . $content['groom_photo'])
            : 'https://images.soco.id/230-58.jpg.jpeg',
        'ig' => $content['groom_ig'] ?? '',
        'label' => 'The Groom',
        'gender_text' => 'Putra',
    ];

    $bride = [
        'name' => $content['bride_name'] ?? 'Juliet Capulet',
        'nickname' => $content['bride_nickname'] ?? 'Juliet',
        'father' => $content['bride_father'] ?? 'Bapak Capulet',
        'mother' => $content['bride_mother'] ?? 'Ibu Capulet',
        'photo' => isset($content['bride_photo'])
            ? asset('storage/' . $content['bride_photo'])
            : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg',
        'ig' => $content['bride_ig'] ?? '',
        'label' => 'The Bride',
        'gender_text' => 'Putri',
    ];

    $firstPerson = $groomFirst ? $groom : $bride;
    $secondPerson = $groomFirst ? $bride : $groom;

    // 3. Helper Variabel
    $akadDate = $content['akad_date'] ?? date('Y-m-d');
    $akadTime = $content['akad_time'] ?? '08:00';

    // ==========================================
    // LOGIKA COUNTDOWN (DARI RESEPSI PERTAMA)
    // ==========================================
    $hasResepsi = false;
    $weddingTimestamp = 0; // Menggunakan timestamp agar 100% terbaca di semua browser
    $coverDateDisplay = '- . - . -';

    if (!empty($content['events']) && is_array($content['events']) && count($content['events']) > 0) {
        $firstEvent = collect($content['events'])->first();
        if (!empty($firstEvent['date'])) {
            $hasResepsi = true;

            // Format Cover (d . m . Y)
            $coverDateDisplay = \Carbon\Carbon::parse($firstEvent['date'])->format('d . m . Y');

            // Format JS Countdown (Miliseconds)
            $eventTime = !empty($firstEvent['time']) ? $firstEvent['time'] : '00:00:00';
            $weddingTimestamp = \Carbon\Carbon::parse($firstEvent['date'] . ' ' . $eventTime)->timestamp * 1000;
        }
    }

    $coverImage = isset($content['cover_image'])
        ? asset('storage/' . $content['cover_image'])
        : 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop';

    // 4. Helper Live Streaming Platform Icons
    $platformIcons = [
        'youtube' => ['icon' => 'fa-brands fa-youtube', 'title' => 'YouTube Live'],
        'instagram' => ['icon' => 'fa-brands fa-instagram', 'title' => 'Instagram Live'],
        'tiktok' => ['icon' => 'fa-brands fa-tiktok', 'title' => 'TikTok Live'],
        'zoom' => ['icon' => 'fa-solid fa-video', 'title' => 'Zoom Meeting'],
    ];
    $masterLogos = \DB::table('banks')->pluck('logo', 'name')->toArray();
    $masterLogos = array_change_key_case($masterLogos, CASE_LOWER);

    // ==========================================
    // LOGIKA UCAPAN & RSVP (AMBIL DARI DATABASE)
    // ==========================================
    $dbWishes = \DB::table('wishes_rsvps')
        ->where('invitation_id', $invitation->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $totalAttendance =
        \DB::table('wishes_rsvps')
            ->where('invitation_id', $invitation->id)
            ->where('status_rsvp', 'hadir')
            ->sum('pax') ?? 0;

    $totalWishes = $dbWishes->count();
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }} - Wedding Invitation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            overflow-x: hidden;
            background-color: #0F0F0F;
            color: #FFFFFF;
            -webkit-font-smoothing: antialiased;
        }

        .cover-locked {
            overflow-y: hidden !important;
        }

        .floating-nav {
            background: rgba(15, 15, 15, 0.75);
            backdrop-filter: blur(15px);
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
            outline: none;
        }

        .scroll-custom::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-custom::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .scroll-custom::-webkit-scrollbar-thumb {
            background: #C5A065;
            border-radius: 10px;
        }

        .scroll-custom::-webkit-scrollbar-thumb:hover {
            background: #D4AF37;
        }

        .glass-stat {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gift-item-row {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Menghilangkan panah tambah/kurang di input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body
    class="bg-brand-elegant text-brand-charcoal font-sans antialiased relative selection:bg-brand-gold selection:text-white cover-locked">

    <audio id="bg-music" loop>
        @if ($invitation->music_id && $invitation->music)
            <source src="{{ asset('storage/' . $invitation->music->file_path) }}" type="audio/mpeg">
        @else
            <source src="https://cdn.pixabay.com/audio/2021/07/18/audio_c993f91966.mp3" type="audio/mpeg">
        @endif
    </audio>

    <div id="cover-page"
        class="fixed inset-0 z-[200] flex items-center justify-center w-screen h-screen bg-brand-dark overflow-hidden transition-transform duration-1000 ease-in-out">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 opacity-30 animate-slow-zoom bg-cover bg-center"
                style="background-image: url('{{ $coverImage }}')"></div>
            <div class="absolute inset-0 bg-brand-dark/80 backdrop-blur-[2px]"></div>
        </div>

        <div class="hidden lg:block absolute inset-0 z-10 pointer-events-none">
            <div class="absolute inset-12 border border-brand-gold/20"></div>
            <div class="absolute top-20 left-20 w-32 h-32 border-t border-l border-brand-gold/40"></div>
            <div class="absolute bottom-20 right-20 w-32 h-32 border-b border-r border-brand-gold/40"></div>
            <div class="absolute left-20 top-1/2 -translate-y-1/2 -rotate-90 origin-left">
                <p class="text-[10px] tracking-[1em] uppercase text-brand-gold/40 font-light whitespace-nowrap">
                    The Wedding of {{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }} —
                    {{ $coverDateDisplay }}
                </p>
            </div>
        </div>

        <div
            class="relative z-20 w-full max-w-[90%] md:max-w-2xl lg:max-w-3xl px-6 py-12 flex flex-col items-center justify-center min-h-[600px]">
            <div class="mb-8 animate-float">
                <div class="w-12 h-12 border border-brand-gold/40 rotate-45 flex items-center justify-center">
                    <div class="w-8 h-8 border border-brand-gold"></div>
                </div>
            </div>

            <p
                class="text-[10px] md:text-xs tracking-[0.5em] md:tracking-[0.8em] uppercase text-brand-gold/90 mb-6 font-medium text-center">
                The Wedding Celebration of
            </p>

            <div class="text-center mb-8">
                <h1
                    class="text-6xl md:text-8xl lg:text-9xl font-serif text-white tracking-tighter leading-[0.85] flex flex-col md:flex-row items-center justify-center md:gap-8 text-protected">
                    <span>{{ $firstPerson['nickname'] }}</span>
                    <span
                        class="italic font-light text-brand-gold text-4xl md:text-7xl lg:text-8xl my-2 md:my-0">&</span>
                    <span>{{ $secondPerson['nickname'] }}</span>
                </h1>
            </div>

            <div class="flex items-center justify-center gap-6 mb-12 w-full max-w-sm">
                <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent via-brand-gold/50 to-brand-gold/50"></div>
                <p
                    class="text-xs md:text-sm font-sans tracking-[0.4em] text-brand-muted uppercase whitespace-nowrap font-light">
                    {{ $coverDateDisplay }}
                </p>
                <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent via-brand-gold/50 to-brand-gold/50"></div>
            </div>

            <div
                class="glass-card p-8 md:p-12 rounded-3xl mb-12 relative w-full max-w-lg transition-all duration-700 hover:bg-white/5 border border-white/10 group text-center">
                <div
                    class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-brand-gold text-brand-dark text-[10px] uppercase font-bold tracking-widest rounded-full shadow-lg">
                    Special Guest
                </div>
                <p class="text-[11px] text-brand-muted mb-4 tracking-widest uppercase italic font-light">
                    {{ $content['cover_greeting'] ?? 'Kepada Yth.' }}
                </p>
                <h2 id="guest-name"
                    class="text-3xl md:text-5xl font-serif text-white mb-6 leading-tight drop-shadow-lg text-protected">
                    Tamu Undangan
                </h2>
                <div
                    class="flex items-center justify-center gap-3 text-xs text-brand-gold tracking-widest border-t border-white/5 pt-6">
                    <i class="fa-solid fa-location-dot"></i>
                    <span
                        class="uppercase">{{ !empty($content['events'][0]['location']) ? $content['events'][0]['location'] : '-' }}</span>
                </div>
            </div>

            <div class="relative">
                <div
                    class="absolute inset-0 bg-brand-gold blur-xl opacity-20 group-hover:opacity-40 transition-opacity">
                </div>
                <button onclick="openInvitation()"
                    class="shimmer relative px-12 py-4 bg-brand-gold text-brand-dark rounded-full transition-all duration-500 hover:scale-110 hover:shadow-[0_0_40px_rgba(212,175,55,0.4)] active:scale-95 flex items-center justify-center gap-4">
                    <i class="fa-solid fa-envelope-open-text text-sm"></i>
                    <span class="font-bold uppercase tracking-[0.3em] text-[11px]">Buka Undangan</span>
                </button>
            </div>
        </div>
    </div>

    <main id="main-content" class="min-h-screen pb-28 opacity-0 transition-opacity duration-1000 bg-brand-dark">

        <section id="home"
            class="min-h-screen flex flex-col items-center justify-center text-center p-6 md:p-12 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-cover bg-center bg-fixed opacity-20"
                    style="background-image: url('{{ $coverImage }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-brand-dark via-transparent to-brand-dark"></div>
            </div>

            <div class="relative z-10 max-w-4xl w-full">
                <div class="mb-12 md:mb-20 animate-fade-in">
                    <span class="inline-block w-12 h-[1px] bg-brand-gold/50 mb-6"></span>
                    <h3 class="font-serif italic text-2xl md:text-4xl text-white mb-6 leading-relaxed text-protected">
                        {!! nl2br(e($content['quotes'] ?? '"And they lived happily ever after."')) !!}
                    </h3>
                </div>

                <div
                    class="glass-card md:max-w-3xl mx-auto p-8 md:p-16 rounded-[3rem] border border-white/5 relative overflow-hidden group">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 opacity-10">
                        <i class="fa-solid fa-quote-right text-9xl text-brand-gold"></i>
                    </div>

                    <p class="text-[10px] md:text-xs tracking-[0.5em] uppercase text-brand-gold mb-12 font-bold">The
                        Waiting Moment</p>

                    <div class="flex flex-row justify-center items-center gap-4 md:gap-12">
                        <div class="flex flex-col items-center">
                            <div class="relative flex items-center justify-center w-20 h-20 md:w-32 md:h-32">
                                <svg class="absolute inset-0 w-full h-full -rotate-90 opacity-20">
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="2"
                                        fill="none" class="text-brand-gold"></circle>
                                </svg>
                                <span id="days"
                                    class="text-3xl md:text-5xl font-serif text-white leading-none">00</span>
                            </div>
                            <span
                                class="text-[8px] md:text-[10px] uppercase tracking-[0.3em] text-brand-muted mt-4">Days</span>
                        </div>

                        <div class="text-brand-gold/30 font-serif italic text-2xl hidden md:block mt-[-2rem]">:</div>

                        <div class="flex flex-col items-center">
                            <div class="relative flex items-center justify-center w-20 h-20 md:w-32 md:h-32">
                                <svg class="absolute inset-0 w-full h-full -rotate-90 opacity-20">
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor" stroke-width="2"
                                        fill="none" class="text-brand-gold"></circle>
                                </svg>
                                <span id="hours"
                                    class="text-3xl md:text-5xl font-serif text-white leading-none">00</span>
                            </div>
                            <span
                                class="text-[8px] md:text-[10px] uppercase tracking-[0.3em] text-brand-muted mt-4">Hours</span>
                        </div>

                        <div class="text-brand-gold/30 font-serif italic text-2xl hidden md:block mt-[-2rem]">:</div>

                        <div class="flex flex-col items-center">
                            <div class="relative flex items-center justify-center w-20 h-20 md:w-32 md:h-32">
                                <svg class="absolute inset-0 w-full h-full -rotate-90 opacity-20">
                                    <circle cx="50%" cy="50%" r="48%" stroke="currentColor"
                                        stroke-width="2" fill="none" class="text-brand-gold"></circle>
                                </svg>
                                <span id="minutes"
                                    class="text-3xl md:text-5xl font-serif text-white leading-none">00</span>
                            </div>
                            <span
                                class="text-[8px] md:text-[10px] uppercase tracking-[0.3em] text-brand-muted mt-4">Minutes</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50">
                <span class="text-[8px] uppercase tracking-[0.4em] text-brand-gold">Scroll</span>
                <div class="w-[1px] h-12 bg-gradient-to-b from-brand-gold to-transparent"></div>
            </div>
        </section>

        <section id="mempelai" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/black-linen.png')] opacity-30 pointer-events-none">
            </div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="flex flex-col items-center mb-24">
                    <span class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold text-center">The
                        Happy Couple</span>
                    <h2 class="text-4xl md:text-6xl font-serif text-white italic drop-shadow-md">Mempelai</h2>
                    <div class="h-[2px] w-16 bg-brand-gold mt-6"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 lg:gap-12 items-center">

                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 lg:pr-12 group">
                        <div class="relative w-64 h-80 md:w-72 md:h-[28rem] order-1 md:order-2 flex-shrink-0">
                            <div
                                class="absolute inset-0 border-2 border-brand-gold translate-x-4 translate-y-4 rounded-2xl z-0">
                            </div>
                            <div
                                class="absolute inset-0 bg-brand-accent overflow-hidden rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-10">
                                <img src="{{ $firstPerson['photo'] }}"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                    alt="{{ $firstPerson['name'] }}">
                            </div>
                        </div>
                        <div class="text-center md:text-right order-2 md:order-1 flex-1">
                            <p class="text-brand-gold font-serif italic text-2xl mb-2">{{ $firstPerson['label'] }}</p>
                            <h3 class="text-4xl md:text-5xl font-serif text-white mb-6 tracking-wide leading-tight">
                                {{ $firstPerson['name'] }}</h3>
                            <div class="space-y-3">
                                <p class="uppercase text-[10px] tracking-[0.3em] text-brand-gold font-bold">
                                    {{ $firstPerson['gender_text'] }} Tercinta dari</p>
                                <p class="text-white text-lg font-medium">{{ $firstPerson['father'] }}</p>
                                <p class="text-white text-lg font-medium">& {{ $firstPerson['mother'] }}</p>
                            </div>
                            @if (!empty($firstPerson['ig']))
                                <a href="{{ $firstPerson['ig'] }}" target="_blank"
                                    class="inline-block mt-4 text-white/50 hover:text-brand-gold transition-colors">
                                    <i class="fa-brands fa-instagram text-xl"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 lg:pl-12 group mt-0 lg:mt-40">
                        <div class="relative w-64 h-80 md:w-72 md:h-[28rem] flex-shrink-0">
                            <div
                                class="absolute inset-0 border-2 border-brand-gold -translate-x-4 translate-y-4 rounded-2xl z-0">
                            </div>
                            <div
                                class="absolute inset-0 bg-brand-accent overflow-hidden rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] z-10">
                                <img src="{{ $secondPerson['photo'] }}"
                                    class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                    alt="{{ $secondPerson['name'] }}">
                            </div>
                        </div>
                        <div class="text-center md:text-left flex-1">
                            <p class="text-brand-gold font-serif italic text-2xl mb-2">{{ $secondPerson['label'] }}
                            </p>
                            <h3 class="text-4xl md:text-5xl font-serif text-white mb-6 tracking-wide leading-tight">
                                {{ $secondPerson['name'] }}</h3>
                            <div class="space-y-3">
                                <p class="uppercase text-[10px] tracking-[0.3em] text-brand-gold font-bold">
                                    {{ $secondPerson['gender_text'] }} Tercinta dari</p>
                                <p class="text-white text-lg font-medium">{{ $secondPerson['father'] }}</p>
                                <p class="text-white text-lg font-medium">& {{ $secondPerson['mother'] }}</p>
                            </div>
                            @if (!empty($secondPerson['ig']))
                                <a href="{{ $secondPerson['ig'] }}" target="_blank"
                                    class="inline-block mt-4 text-white/50 hover:text-brand-gold transition-colors">
                                    <i class="fa-brands fa-instagram text-xl"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (($content['is_turut_mengundang_active'] ?? false) && !empty($content['turut_mengundang']))
                    <div class="mt-32 p-1 bg-gradient-to-r from-transparent via-brand-gold/30 to-transparent">
                        <div class="bg-brand-dark py-12 px-6">
                            <p
                                class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-10 font-bold text-center">
                                Turut Mengundang</p>
                            <div class="flex flex-wrap justify-center gap-x-10 gap-y-6 max-w-4xl mx-auto text-center">
                                @foreach ($content['turut_mengundang'] as $tamu)
                                    <span
                                        class="text-white/90 text-sm font-light tracking-wide">{{ $tamu }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        @if (($content['is_story_active'] ?? false) && !empty($content['love_stories']))
            <section id="story" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-20 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/black-linen.png')]">
                </div>

                <div class="max-w-6xl mx-auto relative z-10">
                    <div class="text-center mb-24 flex flex-col items-center">
                        <span class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold">Our
                            Journey</span>
                        <h2 class="text-4xl md:text-6xl font-serif italic text-white drop-shadow-md">Cerita Cinta Kami
                        </h2>
                        <div class="h-[2px] w-16 bg-brand-gold mt-8"></div>
                    </div>

                    <div class="relative space-y-20 md:space-y-32">
                        <div
                            class="hidden md:block absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-[1px] bg-gradient-to-b from-brand-gold/50 via-brand-gold/10 to-transparent">
                        </div>

                        @foreach ($content['love_stories'] as $index => $story)
                            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center group">
                                <div
                                    class="hidden md:flex absolute left-1/2 -translate-x-1/2 z-20 w-12 h-12 rounded-full bg-brand-dark border-2 border-brand-gold items-center justify-center group-hover:scale-110 transition-transform duration-500 shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <div class="w-3 h-3 rounded-full bg-brand-gold animate-pulse"></div>
                                </div>

                                <div
                                    class="relative {{ $loop->even ? 'order-1 md:order-2 md:justify-start md:pl-10 lg:pl-16' : 'order-1 md:order-1 md:justify-end md:pr-10 lg:pr-16' }} flex justify-center">
                                    @if (!empty($story['image']))
                                        <div
                                            class="relative w-full max-w-md aspect-[4/3] rounded-3xl overflow-hidden border border-white/5 shadow-2xl transition-all duration-700 hover:border-brand-gold/30">
                                            <img src="{{ asset('storage/' . $story['image']) }}"
                                                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                                alt="{{ $story['title'] }}">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-transparent to-transparent">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div
                                    class="{{ $loop->even ? 'order-2 md:order-1 text-center md:text-right md:pr-10 lg:pr-16 md:items-end' : 'order-2 md:order-2 text-center md:text-left md:pl-10 lg:pl-16 md:items-start' }} flex flex-col items-center">
                                    <span
                                        class="text-[10px] font-bold tracking-[0.3em] text-brand-gold uppercase bg-brand-gold/10 px-3 py-1.5 rounded-full inline-block mb-6">{{ $story['year'] ?? '' }}</span>
                                    <h4 class="text-3xl md:text-4xl font-serif text-white mb-5 leading-snug">
                                        {{ $story['title'] ?? '' }}</h4>
                                    <p
                                        class="text-sm md:text-base text-white/80 leading-relaxed font-light max-w-xl mx-auto md:mx-0">
                                        {!! nl2br(e($story['description'] ?? '')) !!}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_guest_info_active'] ?? false)
            <section id="guest-info" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-brand-gold/5 blur-[120px] rounded-full pointer-events-none">
                </div>

                <div class="max-w-4xl mx-auto relative z-10">
                    <div class="text-center mb-16 animate-fade-in-up">
                        <i class="fa-solid fa-circle-info text-brand-gold/60 text-xl mb-4 gold-glow"></i>
                        <span
                            class="block text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-2 font-bold">Important
                            Notice</span>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-white">Informasi Tamu</h2>
                        <div
                            class="h-[1px] w-24 bg-gradient-to-r from-transparent via-brand-gold to-transparent mx-auto mt-6">
                        </div>
                    </div>

                    @if ($content['enable_dresscode'] ?? false)
                        <div
                            class="glass-card rounded-[2.5rem] p-8 md:p-12 mb-12 text-center border border-white/10 hover:border-brand-gold/30 transition-all duration-700 group">
                            <p
                                class="text-[10px] uppercase tracking-[0.4em] text-brand-gold mb-4 font-bold opacity-80">
                                Dresscode</p>
                            <h4 class="text-2xl md:text-3xl font-serif text-white mb-2">{{ $content['dresscode'] }}
                            </h4>
                            <p class="text-brand-muted text-sm font-light tracking-wide italic">"Your presence is our
                                greatest gift, your elegance completes our joy."</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @if ($content['enable_health_protocol'] ?? false)
                            <div
                                class="glass-card rounded-[2.5rem] p-10 border border-white/5 relative overflow-hidden group">
                                <h5
                                    class="text-xs tracking-[0.3em] uppercase text-brand-gold font-bold mb-10 flex items-center gap-3">
                                    <span class="w-8 h-[1px] bg-brand-gold/30"></span> Protokol Kesehatan
                                </h5>
                                <div class="grid grid-cols-3 gap-y-10 gap-x-4">
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-hands-bubbles text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Cuci
                                            Tangan</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-head-side-mask text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Pakai
                                            Masker</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-people-arrows text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Jaga
                                            Jarak</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-users-slash text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">No
                                            Kerumunan</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-temperature-high text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Cek
                                            Suhu</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-spray-can-sparkles text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Desinfektan</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($content['enable_adab_walimah'] ?? false)
                            <div
                                class="glass-card rounded-[2.5rem] p-10 border border-white/5 relative overflow-hidden group">
                                <h5
                                    class="text-xs tracking-[0.3em] uppercase text-brand-gold font-bold mb-10 flex items-center gap-3">
                                    <span class="w-8 h-[1px] bg-brand-gold/30"></span> Adab Walimah
                                </h5>
                                <div class="grid grid-cols-3 gap-y-10 gap-x-4">
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-mosque text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Waktu
                                            Sholat</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-utensils text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Adab
                                            Makan</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-hands-praying text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Doa
                                            Restu</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-restroom text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Jaga
                                            Jarak</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-shirt text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Baju
                                            Sopan</span>
                                    </div>
                                    <div class="flex flex-col items-center group/icon">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center mb-3 group-hover/icon:bg-brand-gold/20 transition-all">
                                            <i class="fa-solid fa-video-slash text-brand-gold text-lg"></i>
                                        </div>
                                        <span
                                            class="text-[9px] text-brand-muted uppercase tracking-widest text-center leading-tight">Izin
                                            Foto</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_gallery_active'] ?? false)
            <section id="gallery" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-brand-dark/50 to-transparent">
                </div>

                <div class="max-w-6xl mx-auto relative z-10">
                    <div class="text-center mb-16">
                        <p class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold">Captured
                            Moments</p>
                        <h2 class="text-5xl md:text-6xl font-serif italic text-white drop-shadow-md">Our Gallery</h2>
                        <div class="h-[2px] w-12 bg-brand-gold mx-auto mt-6"></div>
                    </div>

                    @if (!empty($content['youtube_links'][0]))
                        @php
                            preg_match(
                                '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
                                $content['youtube_links'][0],
                                $match,
                            );
                            $youtube_id = $match[1] ?? '';
                        @endphp
                        @if ($youtube_id)
                            <div id="video-container" class="mb-16 animate-fade-in group">
                                <div
                                    class="relative w-full max-w-4xl mx-auto pb-[56.25%] rounded-[2.5rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 group-hover:border-brand-gold/30 transition-colors duration-500">
                                    <iframe id="youtube-iframe"
                                        class="absolute top-0 left-0 w-full h-full grayscale-[0.2] group-hover:grayscale-0 transition-all duration-700"
                                        src="https://www.youtube.com/embed/{{ $youtube_id }}" frameborder="0"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if (isset($invitation->galleries) && $invitation->galleries->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5" id="photo-grid">
                            @foreach ($invitation->galleries as $index => $gallery)
                                <div class="group relative {{ $index == 0 ? 'md:row-span-2' : 'aspect-square' }} {{ $index == 4 ? 'md:col-span-2 h-48 md:h-auto' : '' }} rounded-2xl md:rounded-[2rem] overflow-hidden cursor-pointer shadow-xl border border-white/5"
                                    onclick="openLightbox({{ $index }})">
                                    <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                        class="gallery-img w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                        alt="Gallery">
                                    <div
                                        class="absolute inset-0 bg-brand-dark/40 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center backdrop-blur-[2px]">
                                        <div
                                            class="p-3 rounded-full border border-brand-gold/50 text-brand-gold scale-50 group-hover:scale-100 transition-transform duration-500">
                                            <i class="fa-solid fa-expand text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <div id="lightbox"
            class="fixed inset-0 z-[500] hidden flex-col items-center justify-center bg-brand-dark/95 backdrop-blur-xl p-4 transition-all duration-500">
            <button onclick="closeLightbox()"
                class="absolute top-6 right-6 text-white/50 hover:text-brand-gold transition-colors text-3xl z-[510]">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="relative w-full max-w-5xl flex items-center justify-center group">
                <button onclick="prevImg()"
                    class="absolute left-0 md:-left-20 text-white/30 hover:text-brand-gold transition-all text-4xl hidden md:block group-hover:translate-x-2 z-[510]">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <img id="lightbox-img" src=""
                    class="max-h-[85vh] max-w-full rounded-lg shadow-2xl transition-opacity duration-300 ease-in-out border border-white/10"
                    alt="Zoomed Photo">
                <button onclick="nextImg()"
                    class="absolute right-0 md:-right-20 text-white/30 hover:text-brand-gold transition-all text-4xl hidden md:block group-hover:-translate-x-2 z-[510]">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
            <p class="mt-8 text-brand-gold tracking-[0.3em] text-[10px] uppercase font-bold z-[510]">Image <span
                    id="current-count">1</span> of <span id="total-count">0</span></p>
        </div>

        @if ($content['is_event_active'] ?? false)
            <section id="lokasi" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-10 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
                </div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-gold/10 rounded-full blur-[100px]"></div>

                <div class="max-w-6xl mx-auto relative z-10">
                    <div class="text-center mb-16 flex flex-col items-center">
                        <span class="text-[10px] tracking-[0.6em] uppercase text-brand-gold mb-4 font-bold">The
                            Celebration</span>
                        <h2 class="text-5xl md:text-6xl font-serif italic text-white drop-shadow-md">Waktu & Tempat
                        </h2>
                        <div class="h-[2px] w-16 bg-brand-gold mt-8"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-stretch">
                        <div
                            class="glass-card p-10 md:p-14 rounded-[3rem] border border-white/10 relative group hover:border-brand-gold/40 transition-all duration-500 flex flex-col items-center text-center">
                            <div
                                class="w-20 h-20 rounded-full bg-brand-gold/10 flex items-center justify-center mb-8 border border-brand-gold/20 group-hover:scale-110 transition-transform duration-500">
                                <i class="fa-solid fa-ring text-3xl text-brand-gold"></i>
                            </div>
                            <h3 class="text-3xl font-serif text-white mb-2 italic">Akad Nikah</h3>
                            <p class="text-brand-gold text-[10px] tracking-[0.4em] uppercase font-bold mb-8">Sacred
                                Union</p>

                            <div class="space-y-6 mb-10 flex-1">
                                <div class="flex flex-col items-center">
                                    <i class="fa-regular fa-calendar-check text-brand-gold/60 mb-2"></i>
                                    <p class="text-white font-medium">
                                        {{ \Carbon\Carbon::parse($akadDate)->translatedFormat('l, d F Y') }}</p>
                                </div>
                                <div class="flex flex-col items-center">
                                    <i class="fa-regular fa-clock text-brand-gold/60 mb-2"></i>
                                    <p class="text-white font-medium">{{ $akadTime }} -
                                        {{ $content['akad_time_end'] ?? 'Selesai' }}</p>
                                </div>
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-location-dot text-brand-gold/60 mb-2"></i>
                                    <p class="text-white/80 text-sm leading-relaxed max-w-[200px]">
                                        {{ $content['akad_location'] ?? '' }}<br>
                                        {{ $content['akad_address'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                            @if (!empty($content['akad_map']))
                                <a href="{{ $content['akad_map'] }}" target="_blank"
                                    class="w-full py-4 bg-transparent border border-brand-gold/50 text-brand-gold rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-brand-dark transition-all duration-500 flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-map-location-dot text-sm"></i> Petunjuk Lokasi
                                </a>
                            @endif
                        </div>

                        @foreach ($content['events'] ?? [] as $event)
                            <div
                                class="glass-card p-10 md:p-14 rounded-[3rem] border border-white/10 relative group hover:border-brand-gold/40 transition-all duration-500 flex flex-col items-center text-center">
                                <div
                                    class="w-20 h-20 rounded-full bg-brand-gold/10 flex items-center justify-center mb-8 border border-brand-gold/20 group-hover:scale-110 transition-transform duration-500">
                                    <i class="fa-solid fa-champagne-glasses text-3xl text-brand-gold"></i>
                                </div>
                                <h3 class="text-3xl font-serif text-white mb-2 italic">
                                    {{ $event['title'] ?? 'Resepsi' }}</h3>
                                <p class="text-brand-gold text-[10px] tracking-[0.4em] uppercase font-bold mb-8">Grand
                                    Celebration</p>

                                <div class="space-y-6 mb-10 flex-1">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-regular fa-calendar-check text-brand-gold/60 mb-2"></i>
                                        <p class="text-white font-medium">
                                            {{ \Carbon\Carbon::parse($event['date'] ?? now())->translatedFormat('l, d F Y') }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <i class="fa-regular fa-clock text-brand-gold/60 mb-2"></i>
                                        <p class="text-white font-medium">{{ $event['time'] ?? '' }} -
                                            {{ $event['time_end'] ?? 'Selesai' }}</p>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-hotel text-brand-gold/60 mb-2"></i>
                                        <p class="text-white/80 text-sm leading-relaxed max-w-[200px]">
                                            {{ $event['location'] ?? '' }}<br>
                                            {{ $event['address'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                                @if (!empty($event['map']))
                                    <a href="{{ $event['map'] }}" target="_blank"
                                        class="w-full py-4 bg-transparent border border-brand-gold/50 text-brand-gold rounded-full text-[11px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-brand-dark transition-all duration-500 flex items-center justify-center gap-3">
                                        <i class="fa-solid fa-map-location-dot text-sm"></i> Petunjuk Lokasi
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if (($content['is_livestream_active'] ?? false) && !empty($content['live_streams']))
            <section id="live-streaming" class="py-24 px-6 bg-brand-dark relative overflow-hidden font-sans">
                <div
                    class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-gold/5 blur-[120px] rounded-full pointer-events-none">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-brand-gold/5 blur-[100px] rounded-full pointer-events-none">
                </div>

                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <div
                            class="inline-flex items-center gap-3 px-5 py-2 bg-red-500/10 border border-red-500/20 rounded-full mb-8 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-red-500">Live Virtual
                                Wedding</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-white mb-6">Siaran Langsung</h2>
                        <p class="text-brand-muted text-sm font-light max-w-md mx-auto leading-relaxed">
                            Bergabunglah bersama kami secara virtual untuk menyaksikan momen sakral penyatuan janji suci
                            kami.
                        </p>
                    </div>

                    @php $firstStream = $content['live_streams'][0]; @endphp
                    <div
                        class="relative max-w-3xl mx-auto p-3 md:p-6 glass-card rounded-[3rem] border border-white/10 shadow-2xl">
                        <div id="streaming-display"
                            class="relative aspect-video rounded-[2.2rem] bg-brand-charcoal overflow-hidden flex items-center justify-center shadow-2xl transition-all duration-700">
                            <div class="absolute inset-0 opacity-20 grayscale transition-all duration-1000 bg-cover bg-center"
                                style="background-image: url('{{ $coverImage }}');"></div>
                            <div class="relative z-10 flex flex-col items-center p-6 text-white text-center">
                                <div
                                    class="mb-6 w-20 h-20 bg-white/5 backdrop-blur-2xl rounded-3xl border border-white/10 flex items-center justify-center shadow-2xl gold-glow group-hover:scale-110 transition-transform duration-500">
                                    <i id="platform-icon"
                                        class="{{ $platformIcons[$firstStream['platform']]['icon'] ?? 'fa-solid fa-video' }} text-4xl text-brand-gold"></i>
                                </div>
                                <h3 id="platform-title" class="text-2xl font-serif italic mb-2 tracking-wide">
                                    {{ $platformIcons[$firstStream['platform']]['title'] ?? ucfirst($firstStream['platform']) }}
                                </h3>
                                <p id="platform-desc"
                                    class="text-[10px] uppercase tracking-[0.3em] text-brand-gold mb-8 font-bold opacity-80">
                                    Online Streaming</p>
                                <a id="platform-link" href="{{ $firstStream['link'] }}" target="_blank"
                                    class="group/btn relative px-12 py-4 bg-brand-gold text-white rounded-full text-[11px] font-bold uppercase tracking-[0.3em] overflow-hidden transition-all duration-500 active:scale-95">
                                    <span class="relative z-10">Gabung Sekarang</span>
                                    <div
                                        class="absolute inset-0 bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300">
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-8 px-2 md:px-6 {{ count($content['live_streams']) <= 1 ? 'hidden' : '' }}">
                            @foreach ($content['live_streams'] as $stream)
                                @php $pData = $platformIcons[$stream['platform']] ?? ['icon' => 'fa-solid fa-video', 'title' => ucfirst($stream['platform'])]; @endphp
                                <button
                                    onclick="switchPlatform('{{ $stream['platform'] }}', '{{ $pData['title'] }}', 'Online Streaming', '{{ $pData['icon'] }}', '{{ $stream['link'] }}')"
                                    class="platform-btn flex flex-col items-center justify-center gap-3 p-4 rounded-2xl hover:bg-white/5 transition-all duration-500 group">
                                    <i
                                        class="{{ $pData['icon'] }} text-xl text-brand-gold opacity-40 group-hover:opacity-100 group-hover:scale-110 transition-all"></i>
                                    <span
                                        class="text-[8px] uppercase tracking-widest text-brand-muted font-bold group-hover:text-white">{{ $stream['platform'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_wishes_active'] ?? false)
            <section id="guest-stats" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-gold/5 rounded-full blur-[120px] pointer-events-none">
                </div>

                <div class="max-w-4xl mx-auto relative z-10">
                    <div class="text-center mb-16">
                        <p class="text-[10px] tracking-[0.5em] uppercase text-brand-gold mb-3 font-bold">Guest
                            Participation</p>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-white drop-shadow-md">Kehadiran & Doa
                        </h2>
                        <div class="h-[1px] w-16 bg-brand-gold/30 mx-auto mt-6"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <div
                            class="glass-stat p-8 rounded-[2.5rem] flex items-center gap-6 hover:border-brand-gold/30 group">
                            <div
                                class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center border border-brand-gold/20 group-hover:bg-brand-gold group-hover:text-brand-dark transition-all">
                                <i
                                    class="fa-solid fa-user-check text-brand-gold text-2xl group-hover:text-brand-dark"></i>
                            </div>
                            <div>
                                <h4 id="total-attendance"
                                    class="text-4xl md:text-5xl font-serif font-bold text-white mb-1">
                                    {{ $totalAttendance }}</h4>
                                <p class="text-[9px] uppercase tracking-[0.2em] text-brand-gold font-bold">Tamu Hadir
                                </p>
                            </div>
                        </div>

                        <div
                            class="glass-stat p-8 rounded-[2.5rem] flex items-center gap-6 hover:border-brand-gold/30 group">
                            <div
                                class="w-16 h-16 bg-brand-gold/10 rounded-2xl flex items-center justify-center border border-brand-gold/20 group-hover:bg-brand-gold group-hover:text-brand-dark transition-all">
                                <i
                                    class="fa-solid fa-envelope-open-text text-brand-gold text-2xl group-hover:text-brand-dark"></i>
                            </div>
                            <div>
                                <h4 id="total-wishes"
                                    class="text-4xl md:text-5xl font-serif font-bold text-white mb-1">
                                    {{ $totalWishes }}</h4>
                                <p class="text-[9px] uppercase tracking-[0.2em] text-brand-gold font-bold">Ucapan
                                    Hangat</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-12 glass-stat rounded-[3rem] border border-white/10 p-2 overflow-hidden shadow-2xl relative">
                        <div
                            class="flex items-center justify-between p-6 md:px-10 border-b border-white/5 mb-2 relative z-10">
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-brand-gold">Wishes
                                Wall</span>
                            <div class="flex gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-500/50"></div>
                                <div class="w-2 h-2 rounded-full bg-yellow-500/50"></div>
                                <div class="w-2 h-2 rounded-full bg-green-500/50"></div>
                            </div>
                        </div>

                        <div id="wishes-container"
                            class="max-h-[500px] overflow-y-auto scroll-custom px-6 md:px-10 py-4 space-y-6 relative z-10">
                        </div>

                        <div
                            class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-[#1a2233] to-transparent pointer-events-none rounded-b-[3rem] z-20">
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_gift_active'] ?? false)
            <section id="hadiah" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
                <div
                    class="absolute -bottom-48 -left-48 w-[500px] h-[500px] bg-brand-gold/5 rounded-full blur-[120px] pointer-events-none">
                </div>

                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <span
                            class="text-[9px] tracking-[0.6em] uppercase text-brand-gold font-bold mb-4 block">Wedding
                            Gift</span>
                        <h2 class="text-5xl font-serif italic text-white drop-shadow-md">Tanda Kasih</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mt-6 mb-8"></div>
                        <p class="text-sm text-white/60 font-light leading-relaxed max-w-lg mx-auto italic">
                            Doa restu Anda adalah karunia terindah bagi kami. Namun jika Anda ingin memberikan tanda
                            kasih, pintu hati kami terbuka untuk menerimanya melalui:
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
                        @foreach ($content['banks'] ?? [] as $index => $bank)
                            @php
                                $bNameRaw = trim($bank['name'] ?? '');
                                $bNameLower = strtolower($bNameRaw);
                                $logoPath = $masterLogos[$bNameLower] ?? null;
                            @endphp
                            <div
                                class="group relative p-10 bg-white/5 rounded-[3.5rem] border border-white/10 backdrop-blur-md transition-all duration-700 hover:border-brand-gold/40 hover:-translate-y-2">
                                <div class="flex flex-col items-center">
                                    <div class="h-8 md:h-10 mb-8 flex items-center justify-center w-full">
                                        @if ($logoPath)
                                            @if (str_starts_with($logoPath, 'http'))
                                                <img src="{{ $logoPath }}" alt="{{ $bNameRaw }}"
                                                    class="max-h-full max-w-full object-contain brightness-0 invert opacity-60 group-hover:opacity-100 transition-all duration-500">
                                            @else
                                                <img src="{{ asset('storage/' . $logoPath) }}"
                                                    alt="{{ $bNameRaw }}"
                                                    class="max-h-full max-w-full object-contain brightness-0 invert opacity-60 group-hover:opacity-100 transition-all duration-500">
                                            @endif
                                        @else
                                            <i
                                                class="fa-solid fa-building-columns text-brand-gold text-3xl opacity-60 group-hover:opacity-100 transition-all duration-500"></i>
                                        @endif
                                    </div>
                                    <p class="text-[9px] uppercase tracking-[0.4em] text-brand-gold/60 mb-3 font-bold">
                                        Nomor Rekening / HP</p>
                                    <h3 id="rek-{{ $index }}"
                                        class="text-2xl md:text-3xl font-serif font-bold text-white mb-2 tracking-widest group-hover:text-brand-gold transition-colors duration-500">
                                        {{ $bank['account_number'] }}</h3>
                                    <p class="text-xs text-white/40 italic mb-10 font-light tracking-wide">a.n
                                        {{ $bank['account_name'] ?? 'Mempelai' }}</p>

                                    <button onclick="copyToClipboard('rek-{{ $index }}', this)"
                                        class="w-full py-4 bg-transparent border border-brand-gold/40 text-brand-gold rounded-2xl text-[10px] font-bold uppercase tracking-[0.2em] transition-all duration-500 hover:bg-brand-gold hover:text-brand-dark hover:shadow-[0_10px_30px_rgba(197,160,101,0.2)]">
                                        <i class="fa-regular fa-copy mr-2"></i> <span>Salin Nomor</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (!empty($content['alamat_kado']))
                        <div class="flex flex-col items-center gap-8 mt-12">
                            <div
                                class="group relative p-10 bg-white/5 rounded-[3rem] border border-white/10 backdrop-blur-md max-w-xl w-full hover:border-brand-gold/30 transition-all duration-700 shadow-2xl">
                                <div
                                    class="w-12 h-12 bg-brand-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-location-dot text-brand-gold"></i>
                                </div>
                                <p class="text-[10px] uppercase tracking-[0.4em] text-brand-gold mb-4 font-bold">Alamat
                                    Pengiriman Kado</p>
                                <div id="alamat-kado"
                                    class="text-lg text-white font-serif italic leading-relaxed mb-10">
                                    {!! nl2br(e($content['alamat_kado'])) !!}
                                </div>

                                <div class="flex flex-col sm:flex-row justify-center gap-4">
                                    <button onclick="copyToClipboardText('alamat-kado', this)"
                                        class="px-8 py-4 bg-white/5 text-white border border-white/10 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-white hover:text-brand-dark active:scale-95">
                                        Salin Alamat
                                    </button>

                                    @if (!empty($content['gifts']))
                                        <button onclick="toggleGiftModal(true)"
                                            class="px-8 py-4 bg-brand-gold text-brand-dark rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all shadow-lg shadow-brand-gold/20 hover:scale-105 active:scale-95">
                                            <i class="fa-solid fa-list-check mr-2"></i> Daftar Kebutuhan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if (!empty($content['gifts']))
                    <div id="gift-modal" class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-brand-dark/90 backdrop-blur-md"
                            onclick="toggleGiftModal(false)"></div>
                        <div
                            class="relative bg-brand-charcoal w-full max-w-lg rounded-[3.5rem] border border-white/10 overflow-hidden shadow-2xl flex flex-col max-h-[85vh] animate-fade-in-up">
                            <div class="p-8 md:p-10 border-b border-white/5 bg-brand-charcoal shrink-0">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h3 class="text-2xl font-serif italic text-white">Daftar Kebutuhan</h3>
                                        <p
                                            class="text-[10px] text-brand-gold uppercase tracking-[0.3em] mt-1 font-bold">
                                            Wedding Registry</p>
                                    </div>
                                    <button onclick="toggleGiftModal(false)"
                                        class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white/5 text-white hover:bg-red-500/20 hover:text-red-500 transition-all">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div class="p-5 bg-brand-gold/10 rounded-[1.5rem] border border-brand-gold/20">
                                    <p class="text-[11px] text-brand-gold italic leading-relaxed text-center">
                                        "Terima kasih atas kebaikan hati Anda untuk membantu kami membangun rumah tangga
                                        baru."
                                    </p>
                                </div>
                            </div>
                            <div class="flex-1 overflow-y-auto p-8 pt-4 custom-scrollbar bg-brand-charcoal"
                                id="gift-list-container">
                                <div class="space-y-4">
                                    @foreach ($content['gifts'] as $index => $gift)
                                        <div id="item-{{ $index }}"
                                            class="gift-item-row p-6 rounded-[2rem] border border-white/5 bg-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 transition-all hover:bg-white/10 hover:border-brand-gold/30 group">
                                            <div class="text-center sm:text-left flex-1">
                                                <h4
                                                    class="text-sm font-bold text-white uppercase tracking-wider group-hover:text-brand-gold transition-colors">
                                                    {{ $gift['item_name'] }}</h4>
                                                <p
                                                    class="text-[10px] text-brand-muted font-light mt-1 uppercase tracking-widest">
                                                    {{ $gift['description'] ?? '' }}</p>
                                            </div>
                                            <button
                                                onclick="confirmGift('item-{{ $index }}', '{{ $gift['item_name'] }}')"
                                                class="shrink-0 px-6 py-3 bg-brand-gold/10 text-brand-gold border border-brand-gold/30 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-brand-dark transition-all">
                                                Pilih Kado
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div id="confirm-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-6">
                    <div class="absolute inset-0 bg-brand-dark/95 backdrop-blur-xl" onclick="closeConfirmModal()">
                    </div>
                    <div
                        class="relative bg-brand-charcoal w-full max-w-sm rounded-[3rem] p-8 text-center shadow-2xl border border-white/10">
                        <div
                            class="w-16 h-16 bg-brand-gold/10 text-brand-gold rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_20px_rgba(180,150,90,0.2)]">
                            <i class="fa-solid fa-heart text-3xl"></i>
                        </div>
                        <h4 class="text-2xl font-serif italic text-white mb-2">Niat Baik Anda</h4>
                        <p id="confirm-text" class="text-xs text-brand-muted font-light leading-relaxed mb-6"></p>

                        <div class="text-left space-y-4 mb-8">
                            <div>
                                <label
                                    class="block text-[10px] uppercase tracking-widest text-brand-gold font-bold mb-2 opacity-80">Nama
                                    Anda</label>
                                <input type="text" id="input-gift-name"
                                    class="input-luxury w-full p-4 rounded-2xl text-sm placeholder-white/20 outline-none"
                                    placeholder="Masukkan nama Anda">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] uppercase tracking-widest text-brand-gold font-bold mb-2 opacity-80">Jumlah
                                    Kehadiran (Opsional)</label>
                                <input type="number" id="input-gift-pax" min="0" value="0"
                                    class="input-luxury w-full p-4 rounded-2xl text-sm placeholder-white/20 outline-none text-center"
                                    placeholder="0 jika hanya kirim kado">
                                <p class="text-[9px] text-white/30 mt-2 italic">*Isi 0 jika Anda tidak hadir.</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button id="final-confirm-btn"
                                class="w-full py-4 bg-brand-gold text-brand-dark rounded-2xl text-[11px] font-bold uppercase tracking-widest shadow-lg shadow-brand-gold/20 active:scale-95 transition-all">
                                Kirim Konfirmasi
                            </button>
                            <button onclick="closeConfirmModal()"
                                class="w-full py-4 bg-transparent text-brand-muted text-[10px] font-bold uppercase tracking-widest hover:text-white transition-all">
                                Batalkan
                            </button>
                        </div>
                    </div>
                </div>

                <div id="copy-toast"
                    class="fixed bottom-28 left-1/2 -translate-x-1/2 z-[300] px-10 py-4 bg-brand-gold text-brand-dark text-[10px] rounded-full tracking-[0.3em] uppercase font-bold opacity-0 translate-y-10 transition-all duration-700 pointer-events-none shadow-[0_20px_40px_rgba(197,160,101,0.3)] border border-white/20">
                    <i class="fa-solid fa-check-circle mr-2"></i> Tersalin
                </div>
                <div id="gift-toast"
                    class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[700] px-10 py-5 bg-brand-gold text-brand-dark rounded-full text-[11px] font-bold uppercase tracking-[0.3em] shadow-2xl opacity-0 transition-all duration-500 pointer-events-none text-center min-w-[320px]">
                </div>
            </section>
        @endif

        @if ($content['enable_qr_attendance'] ?? false)
            <section id="qr-tamu" class="py-24 px-6 bg-brand-dark relative overflow-hidden">
                <div
                    class="absolute -bottom-20 -right-20 w-64 h-64 bg-brand-gold/10 blur-[80px] rounded-full pointer-events-none">
                </div>

                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/5 border border-white/10 mb-6">
                            <i class="fa-solid fa-qrcode text-brand-gold text-2xl gold-glow"></i>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-serif italic text-white mb-6">QR Code Tamu</h2>
                        <div
                            class="h-[1px] w-24 bg-gradient-to-r from-transparent via-brand-gold/40 to-transparent mx-auto mb-8">
                        </div>
                        <p class="text-brand-muted text-sm font-light leading-relaxed max-w-lg mx-auto">
                            Silakan tunjukkan kode unik Anda di bawah ini kepada petugas penerima tamu untuk memudahkan
                            proses absensi kehadiran.
                        </p>
                    </div>

                    <div class="flex justify-center">
                        <div
                            class="group relative p-12 bg-white/5 rounded-[4rem] border border-white/10 backdrop-blur-xl transition-all duration-700 hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)] hover:border-brand-gold/30 max-w-sm w-full mx-auto overflow-hidden">
                            <div
                                class="absolute -inset-1 bg-gradient-to-b from-brand-gold/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 blur-xl">
                            </div>
                            <div class="relative flex flex-col items-center z-10">
                                <div
                                    class="relative p-6 bg-white rounded-[2.5rem] mb-10 shadow-2xl transition-transform duration-700 group-hover:scale-105">
                                    <img id="qr-image" src=""
                                        class="w-44 h-44 object-contain grayscale-[0.2] hover:grayscale-0 transition-all"
                                        alt="QR Code Tamu">
                                    <div
                                        class="absolute top-4 left-4 w-6 h-6 border-t-2 border-l-2 border-brand-gold/30">
                                    </div>
                                    <div
                                        class="absolute bottom-4 right-4 w-6 h-6 border-b-2 border-r-2 border-brand-gold/30">
                                    </div>
                                </div>
                                <div class="space-y-2 mb-10 text-center">
                                    <span
                                        class="text-[10px] uppercase tracking-[0.5em] text-brand-gold font-bold block mb-2">Guest
                                        Identity</span>
                                    <h3 id="guest-name-qr"
                                        class="text-2xl font-serif font-medium text-white tracking-wide uppercase leading-tight">
                                        Tamu Undangan</h3>
                                    <p class="text-xs text-brand-muted italic font-light tracking-widest uppercase">
                                        E-Invitation Only</p>
                                </div>
                                <div
                                    class="w-full py-5 bg-white/5 rounded-3xl border border-dashed border-white/10 group-hover:border-brand-gold/30 transition-colors">
                                    <p class="text-[9px] uppercase tracking-[0.3em] font-bold text-brand-gold/70">Scan
                                        to RSVP Verification</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if (session('success'))
            <div id="success-toast"
                class="fixed top-10 left-1/2 -translate-x-1/2 z-[1000] px-8 py-4 bg-green-500 text-white rounded-full font-bold shadow-2xl text-sm transition-all duration-500 flex items-center gap-3">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('success-toast');
                    toast.style.opacity = '0';
                    toast.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            </script>
        @endif

        <section id="rsvp-modal" class="fixed inset-0 z-[1000] invisible transition-all duration-500 overflow-hidden">
            <div onclick="closeRSVP()"
                class="absolute inset-0 bg-brand-dark/80 backdrop-blur-md opacity-0 transition-opacity duration-500"
                id="rsvp-overlay"></div>
            <div id="rsvp-content"
                class="absolute bottom-0 left-0 right-0 bg-brand-charcoal rounded-t-[3.5rem] border-t border-white/10 shadow-[0_-20px_50px_rgba(0,0,0,0.5)] transform translate-y-full transition-transform duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] px-6 pb-12 pt-4 max-w-2xl mx-auto">
                <div class="w-12 h-1 bg-white/10 rounded-full mx-auto mb-10"></div>
                <div class="text-center mb-10">
                    <span class="text-[9px] text-brand-gold tracking-[0.5em] uppercase font-bold mb-2 block">Guest
                        Book</span>
                    <h2 class="text-4xl font-serif text-white italic">RSVP & Ucapan</h2>
                    <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mt-4"></div>
                </div>

                <form id="form-rsvp" class="space-y-5 text-left">
                    <div class="group">
                        <label
                            class="text-[10px] uppercase tracking-widest text-brand-gold ml-4 mb-2 block opacity-60">Nama
                            Lengkap</label>
                        <input type="text" id="input-nama-rsvp" placeholder="Tulis nama Anda..."
                            class="input-luxury w-full p-5 rounded-[1.8rem] text-sm placeholder-white/20 outline-none"
                            required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir"
                            class="py-4 rounded-[1.5rem] border border-white/10 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 bg-white/5 text-white/50">Hadir</button>
                        <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen"
                            class="py-4 rounded-[1.5rem] border border-white/10 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 bg-white/5 text-white/50">Absen</button>
                    </div>
                    <input type="hidden" id="input-status" value="Hadir">
                    <input type="hidden" id="input-guest-count" value="1">

                    <div id="guest-selection"
                        class="hidden animate-slide-up bg-white/5 p-6 rounded-[2rem] border border-white/5">
                        <label
                            class="text-[10px] uppercase tracking-widest text-brand-gold mb-4 block font-bold text-center">Berapa
                            orang yang hadir?</label>

                        <div class="flex gap-2">
                            <button type="button" onclick="setGuestCount(1)"
                                class="guest-btn flex-1 py-3 rounded-xl border border-white/10 text-[10px] text-white hover:bg-brand-gold hover:text-brand-dark transition-all">1</button>
                            <button type="button" onclick="setGuestCount(2)"
                                class="guest-btn flex-1 py-3 rounded-xl border border-white/10 text-[10px] text-white hover:bg-brand-gold hover:text-brand-dark transition-all">2</button>
                            <button type="button" onclick="setGuestCount(3)"
                                class="guest-btn flex-1 py-3 rounded-xl border border-white/10 text-[10px] text-white hover:bg-brand-gold hover:text-brand-dark transition-all">3</button>
                            <button type="button" onclick="setGuestCount('custom')"
                                class="guest-btn flex-1 py-3 rounded-xl border border-white/10 text-[10px] text-white hover:bg-brand-gold hover:text-brand-dark transition-all">3+</button>
                        </div>

                        <div id="custom-pax-container" class="hidden mt-4">
                            <input type="number" id="custom-pax-input" min="4"
                                placeholder="Ketik jumlah orang (misal: 4)"
                                class="input-luxury w-full p-4 rounded-xl text-sm text-center placeholder-white/40 outline-none">
                            <style>
                                /* Sembunyikan panah atas-bawah di input number */
                                #custom-pax-input::-webkit-outer-spin-button,
                                #custom-pax-input::-webkit-inner-spin-button {
                                    -webkit-appearance: none;
                                    margin: 0;
                                }

                                #custom-pax-input[type=number] {
                                    -moz-appearance: textfield;
                                }
                            </style>
                        </div>
                    </div>

                    <div class="group">
                        <label
                            class="text-[10px] uppercase tracking-widest text-brand-gold ml-4 mb-2 block opacity-60">Doa
                            & Ucapan</label>
                        <textarea id="input-pesan-rsvp" rows="4" placeholder="Berikan doa terbaik Anda untuk kami..."
                            class="input-luxury w-full p-5 rounded-[1.8rem] text-sm placeholder-white/20 outline-none resize-none" required></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="closeRSVP()"
                            class="flex-1 py-4 bg-white/5 text-white/40 rounded-[1.5rem] font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-white/10">Batal</button>
                        <button type="submit"
                            class="flex-[2] py-4 bg-brand-gold text-brand-dark rounded-[1.5rem] font-bold text-[10px] uppercase tracking-widest shadow-[0_10px_30px_rgba(197,160,101,0.3)] hover:scale-[1.02] active:scale-95 transition-all">
                            Kirim Konfirmasi <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <footer class="py-24 px-6 bg-brand-dark border-t border-white/5 text-center relative overflow-hidden">
            <div
                class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/natural-paper.png')]">
            </div>
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-[300px] h-[300px] bg-brand-gold/5 blur-[120px] rounded-full">
            </div>

            <div class="max-w-xl mx-auto relative z-10">
                <div class="mb-12 flex flex-col items-center">
                    <div class="text-4xl font-serif italic text-brand-gold/30 mb-4 gold-glow">
                        {{ substr($firstPerson['nickname'], 0, 1) }} & {{ substr($secondPerson['nickname'], 0, 1) }}
                    </div>
                    <div class="h-[1px] w-12 bg-gradient-to-r from-transparent via-brand-gold/40 to-transparent"></div>
                </div>

                <div class="mb-16">
                    <p class="text-lg font-serif italic text-white/80 mb-6 leading-relaxed px-4">
                        "Merupakan suatu kehormatan dan kebahagiaan bagi kami <br class="hidden md:block">
                        apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu."
                    </p>
                    <p class="text-[10px] tracking-[0.4em] uppercase text-brand-gold font-bold opacity-80">
                        Sampai jumpa di hari bahagia kami
                    </p>
                </div>

                <div class="mb-16">
                    <p class="text-xs text-brand-muted font-light tracking-widest uppercase mb-3">Kami yang berbahagia,
                    </p>
                    <h3 class="text-3xl font-serif italic text-brand-gold mb-2">{{ $firstPerson['nickname'] }} &
                        {{ $secondPerson['nickname'] }}</h3>
                    <p class="text-[10px] text-brand-muted uppercase tracking-[0.3em] font-light italic">Beserta
                        Keluarga Besar</p>
                </div>

                <div
                    class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl transition-all hover:border-brand-gold/30 group">
                    <span class="text-[9px] text-brand-muted font-sans uppercase tracking-[0.2em]">Digital Invitation
                        by</span>
                    <a href="https://instagram.com/ruangrestu.undangan" target="_blank" rel="noopener noreferrer"
                        class="text-[10px] font-bold text-white group-hover:text-brand-gold transition-colors flex items-center gap-2">
                        <i class="fa-brands fa-instagram text-xs"></i>
                        @ruangrestu.undangan
                    </a>
                </div>

                <div class="mt-12 pt-8 border-t border-white/5">
                    <p class="text-[9px] text-brand-muted font-light tracking-[0.2em] uppercase">
                        © {{ date('Y') }} Wedding Invitation. <br class="md:hidden"> All Rights Reserved.
                    </p>
                </div>
            </div>
        </footer>

    </main>

    <div id="fab-container"
        class="fixed right-5 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000 pointer-events-none">
        <button id="btn-music" onclick="toggleMusic()"
            class="w-11 h-11 bg-brand-charcoal backdrop-blur border border-brand-gold/30 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-brand-dark transition-all pointer-events-auto">
            <i class="fa-solid fa-music animate-spin-slow" id="icon-music"></i>
        </button>
        <button id="btn-scroll" onclick="toggleAutoScroll()"
            class="w-11 h-11 bg-brand-charcoal backdrop-blur border border-brand-gold/30 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-brand-dark transition-all pointer-events-auto">
            <i class="fa-solid fa-angles-down" id="icon-scroll"></i>
        </button>
    </div>

    <nav id="bottom-nav"
        class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 floating-nav rounded-[2rem] transition-all duration-1000 translate-y-32">
        <ul class="flex justify-around items-center h-20 w-[340px] md:w-[450px] px-4">
            <li><a href="#home"
                    class="nav-link flex flex-col items-center text-[10px] text-white/70 hover:text-brand-gold uppercase tracking-[0.2em] font-bold gap-2 transition-colors"><i
                        class="fa-solid fa-house-chimney text-lg"></i><span class="hidden md:block">Home</span></a>
            </li>
            @if ($content['is_gallery_active'] ?? false)
                <li><a href="#gallery"
                        class="nav-link flex flex-col items-center text-[10px] text-white/70 hover:text-brand-gold uppercase tracking-[0.2em] font-bold gap-2 transition-colors"><i
                            class="fa-solid fa-images text-lg"></i><span class="hidden md:block">Gallery</span></a>
                </li>
            @endif
            <li><a href="#lokasi"
                    class="nav-link flex flex-col items-center text-[10px] text-white/70 hover:text-brand-gold uppercase tracking-[0.2em] font-bold gap-2 transition-colors"><i
                        class="fa-solid fa-map-location-dot text-lg"></i><span
                        class="hidden md:block">Venue</span></a></li>
            @if ($content['is_wishes_active'] ?? false)
                <li><a href="javascript:void(0)" onclick="openRSVP()"
                        class="nav-link flex flex-col items-center text-[10px] text-white/70 hover:text-brand-gold uppercase tracking-[0.2em] font-bold gap-2 transition-colors"><i
                            class="fa-solid fa-paper-plane text-lg"></i><span class="hidden md:block">RSVP</span></a>
                </li>
            @endif
        </ul>
    </nav>

    <script>
        // 1. URL Parameter untuk Tamu
        const urlParams = new URLSearchParams(window.location.search);
        let guestName = urlParams.get('to') ? decodeURIComponent(urlParams.get('to')) : 'Tamu Undangan';

        document.querySelectorAll('#guest-name, #guest-name-qr').forEach(el => el.innerText = guestName);

        const qrImage = document.getElementById('qr-image');
        if (qrImage) {
            qrImage.src =
                `https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=d4af37&bgcolor=0f172a&data=${encodeURIComponent(guestName)}`;
        }

        const inputRSVP = document.getElementById('input-nama-rsvp');
        if (inputRSVP && guestName !== 'Tamu Undangan') {
            inputRSVP.value = guestName;
        }

        // 2. Kontrol Umum (Audio & Scroll)
        const audio = document.getElementById('bg-music');
        let isMusicPlaying = false;
        let isAutoScrolling = false;
        let scrollInterval;
        let hasShownRSVPAtEnd = false;

        function openInvitation() {
            document.getElementById('cover-page').classList.add('-translate-y-full');
            document.body.classList.remove('cover-locked');
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
                icon.classList.replace('fa-music', 'fa-volume-xmark');
                icon.classList.remove('animate-spin-slow');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.replace('fa-volume-xmark', 'fa-music');
                    icon.classList.add('animate-spin-slow');
                }).catch(() => console.log("Autoplay dicegah browser."));
            }
        }

        function toggleAutoScroll(forceStart = false) {
            const btn = document.getElementById('btn-scroll');
            const icon = document.getElementById('icon-scroll');

            if (isAutoScrolling && !forceStart) {
                clearInterval(scrollInterval);
                isAutoScrolling = false;
                btn.classList.remove('bg-brand-gold', 'text-brand-dark');
                icon.classList.replace('fa-pause', 'fa-angles-down');
            } else {
                isAutoScrolling = true;
                btn.classList.add('bg-brand-gold', 'text-brand-dark');
                icon.classList.replace('fa-angles-down', 'fa-pause');

                scrollInterval = setInterval(() => {
                    window.scrollBy({
                        top: 1,
                        behavior: 'auto'
                    });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                        toggleAutoScroll();
                    }
                }, 35);
            }
        }

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

        // 3. Countdown
        const weddingDate = {{ $weddingTimestamp }};
        if (weddingDate > 0) {
            const countdownFunction = setInterval(function() {
                const now = new Date().getTime();
                const distance = weddingDate - now;

                if (distance <= 0) {
                    clearInterval(countdownFunction);
                    return;
                }

                document.getElementById("days").innerText = String(Math.floor(distance / (1000 * 60 * 60 * 24)))
                    .padStart(2, '0');
                document.getElementById("hours").innerText = String(Math.floor((distance % (1000 * 60 * 60 * 24)) /
                    (1000 * 60 * 60))).padStart(2, '0');
                document.getElementById("minutes").innerText = String(Math.floor((distance % (1000 * 60 * 60)) / (
                    1000 * 60))).padStart(2, '0');
            }, 1000);
        }

        // 4. Copy to Clipboard
        function copyToClipboardText(text, btn) {
            // Ambil text asli atau element
            let textToCopy = text;
            if (document.getElementById(text)) {
                textToCopy = document.getElementById(text).innerText.trim();
            }

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Tersalin';
                btn.classList.add('bg-brand-gold', 'text-brand-dark');

                const toast = document.getElementById('copy-toast');
                if (toast) {
                    toast.classList.remove('opacity-0', 'translate-y-10');
                    toast.classList.add('opacity-100', 'translate-y-0');
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-y-10');
                        toast.classList.remove('opacity-100', 'translate-y-0');
                    }, 2500);
                }

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-brand-gold', 'text-brand-dark');
                }, 2000);
            });
        }

        function copyToClipboard(id, btn) {
            copyToClipboardText(id, btn);
        }

        // 5. Lightbox Gallery
        const images = [
            @if (isset($invitation->galleries))
                @foreach ($invitation->galleries as $gallery)
                    "{{ asset('storage/' . $gallery->file_path) }}",
                @endforeach
            @endif
        ];
        let currentIndex = 0;

        function openLightbox(index) {
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
            document.getElementById('current-count').innerText = currentIndex + 1;
            document.getElementById('total-count').innerText = images.length;
            imgElement.style.opacity = '0';
            setTimeout(() => {
                imgElement.src = images[currentIndex];
                imgElement.style.opacity = '1';
            }, 200);
        }

        function nextImg() {
            if (images.length > 0) {
                currentIndex = (currentIndex + 1) % images.length;
                updateLightbox();
            }
        }

        function prevImg() {
            if (images.length > 0) {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                updateLightbox();
            }
        }

        // 6. Live Streaming Switch
        function switchPlatform(id, title, desc, iconClass, link) {
            const display = document.getElementById('streaming-display');
            display.style.opacity = '0';
            display.style.transform = 'scale(0.98) translateY(10px)';
            setTimeout(() => {
                document.getElementById('platform-title').innerText = title;
                document.getElementById('platform-icon').className = iconClass + ' text-4xl text-brand-gold';
                document.getElementById('platform-link').href = link;
                display.style.opacity = '1';
                display.style.transform = 'scale(1) translateY(0)';
            }, 400);
        }

        // 7. Data RSVP & Wishes (API Integration)
        let allWishes = [
            @foreach ($dbWishes as $wish)
                {
                    nama: "{{ addslashes($wish->guest_name) }}",
                    pesan: "{{ preg_replace("/\r|\n/", ' ', addslashes($wish->message)) }}",
                    waktu: "{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}"
                },
            @endforeach
        ];

        let countAttendance = {{ $totalAttendance }};
        let countWishes = {{ $totalWishes }};

        function renderWishes() {
            const container = document.getElementById('wishes-container');
            if (!container) return;

            // 🔥 JIKA DATA KOSONG 🔥
            if (allWishes.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-10 opacity-40">
                        <i class="fa-regular fa-comment-dots text-4xl mb-3 block"></i>
                        <p class="text-xs italic">Belum ada ucapan.<br>Jadilah yang pertama mendoakan kami!</p>
                    </div>`;
                return;
            }

            container.innerHTML = '';
            allWishes.forEach(wish => {
                const card = document.createElement('div');
                card.className = 'wish-card bg-white/5 p-6 rounded-[1.5rem] border border-white/10 mb-4';
                card.innerHTML = `
                    <div class="flex justify-between mb-2">
                        <h5 class="text-sm font-bold text-white">${wish.nama}</h5>
                        <span class="text-[8px] text-brand-gold/60 uppercase tracking-widest">${wish.waktu}</span>
                    </div>
                    <p class="text-xs text-white/70 italic">"${wish.pesan}"</p>
                `;
                container.appendChild(card);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderWishes();
        });

        async function sendRsvpData(data) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const response = await fetch("{{ route('rsvp.store', $invitation->slug) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    // Update Local Array & Re-render
                    allWishes.unshift({
                        nama: data.guest_name,
                        pesan: data.message,
                        waktu: "Baru saja"
                    });
                    countWishes++;
                    if (data.status_rsvp === 'hadir') countAttendance += parseInt(data.pax);

                    document.getElementById('total-wishes').innerText = countWishes;
                    document.getElementById('total-attendance').innerText = countAttendance;
                    renderWishes();

                    // Animasi sukses
                    const modal = document.getElementById('rsvp-modal');
                    modal.innerHTML += `
                        <div id="temp-success" class="absolute inset-0 z-50 flex items-center justify-center bg-brand-dark/95 backdrop-blur-md">
                            <div class="text-center animate-fade-in-up">
                                <i class="fa-solid fa-check-circle text-6xl text-brand-gold mb-4"></i>
                                <h3 class="text-2xl font-serif text-white mb-2">Terima Kasih</h3>
                                <p class="text-brand-muted text-sm">Konfirmasi Anda telah kami terima.</p>
                            </div>
                        </div>
                    `;
                    setTimeout(() => {
                        closeRSVP();
                        setTimeout(() => document.getElementById('temp-success')?.remove(), 1000);
                    }, 2000);
                }
            } catch (error) {
                console.error(error);
            }
        }

        // 8. Logika Form RSVP
        function openRSVP() {
            const modal = document.getElementById('rsvp-modal');
            const overlay = document.getElementById('rsvp-overlay');
            const content = document.getElementById('rsvp-content');
            modal.classList.remove('invisible');
            document.body.style.overflow = 'hidden';
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
            document.body.style.overflow = 'auto';
            setTimeout(() => modal.classList.add('invisible'), 500);
        }

        function selectAttendance(status) {
            document.getElementById('input-status').value = status;
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');

            [btnHadir, btnAbsen].forEach(b => {
                b.className =
                    'py-4 rounded-[1.5rem] border border-white/10 text-[11px] font-bold uppercase tracking-widest transition-all duration-300 bg-white/5 text-white/50';
            });

            if (status === 'Hadir') {
                btnHadir.className =
                    'py-4 rounded-[1.5rem] border border-brand-gold text-[11px] font-bold uppercase tracking-widest transition-all duration-300 bg-brand-gold text-brand-dark shadow-[0_0_15px_rgba(212,175,55,0.3)]';
                guestDiv.classList.remove('hidden');
                document.getElementById('input-guest-count').value = 1;
                setGuestCount(1); // Reset visual ke 1
            } else {
                btnAbsen.className =
                    'py-4 rounded-[1.5rem] border border-brand-gold text-[11px] font-bold uppercase tracking-widest transition-all duration-300 bg-brand-gold text-brand-dark shadow-[0_0_15px_rgba(212,175,55,0.3)]';
                guestDiv.classList.add('hidden');
                document.getElementById('input-guest-count').value = 0;
            }
        }

        // Fungsi mengatur pilihan jumlah tamu
        function setGuestCount(count) {
            const customContainer = document.getElementById('custom-pax-container');
            const customInput = document.getElementById('custom-pax-input');
            const hiddenInputCount = document.getElementById('input-guest-count');

            if (count === 'custom') {
                // Tampilkan input manual jika klik 3+
                customContainer.classList.remove('hidden');
                hiddenInputCount.value = customInput.value || 4;
                customInput.focus();
            } else {
                // Sembunyikan input manual jika klik 1, 2, atau 3
                customContainer.classList.add('hidden');
                hiddenInputCount.value = count;
            }

            // Ubah warna tombol aktif (Gold)
            document.querySelectorAll('.guest-btn').forEach(btn => {
                if (btn.innerText == count || (count === 'custom' && btn.innerText === '3+')) {
                    btn.classList.add('bg-brand-gold', 'text-brand-dark');
                    btn.classList.remove('text-white');
                } else {
                    btn.classList.remove('bg-brand-gold', 'text-brand-dark');
                    btn.classList.add('text-white');
                }
            });
        }

        // Fungsi saat tombol Kirim RSVP ditekan
        document.getElementById('form-rsvp').onsubmit = function(e) {
            e.preventDefault();

            const statusHadir = document.getElementById('input-status').value === 'Hadir' ? 'hadir' : 'tidak_hadir';

            // Ambil jumlah PAX dengan akurat
            let paxVal = 0;
            if (statusHadir === 'hadir') {
                const customContainer = document.getElementById('custom-pax-container');
                if (!customContainer.classList.contains('hidden')) {
                    // Ambil dari input ketik jika tombol 3+ aktif
                    const customInputVal = document.getElementById('custom-pax-input').value;
                    paxVal = parseInt(customInputVal) > 0 ? parseInt(customInputVal) : 4;
                } else {
                    // Ambil dari tombol 1, 2, atau 3
                    paxVal = parseInt(document.getElementById('input-guest-count').value);
                }
            }

            const data = {
                guest_name: document.getElementById('input-nama-rsvp').value || 'Hamba Allah',
                status_rsvp: statusHadir,
                pax: paxVal,
                message: document.getElementById('input-pesan-rsvp').value || 'Selamat Berbahagia!'
            };

            sendRsvpData(data);
        };

        window.addEventListener('scroll', () => {
            // 🔥 PROTEKSI: Jangan jalan jika halaman cover masih ada/belum dibuka 🔥
            const coverPage = document.getElementById('cover-page');
            if (coverPage && !coverPage.classList.contains('-translate-y-full')) {
                return;
            }

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

        // 9. Gift Logic
        let currentGiftId = null;

        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Fungsi Membuka Modal Konfirmasi Kado
        function confirmGift(id, name) {
            currentSelectedItemId = id;
            currentSelectedItemName = name;

            const confirmText = document.getElementById('confirm-text');
            if (confirmText) {
                confirmText.innerHTML =
                    `Apakah Anda bersedia memberikan <b>${name}</b> untuk mempelai? Silakan isi data berikut.`;
            }

            // Isi otomatis nama jika dari URL params ada (dan bukan 'Tamu Undangan')
            const inputNameEl = document.getElementById('input-gift-name');
            if (inputNameEl && guestName !== 'Tamu Undangan') {
                inputNameEl.value = guestName;
            }

            // Reset jumlah orang jadi 0 setiap modal dibuka
            const inputPaxEl = document.getElementById('input-gift-pax');
            if (inputPaxEl) inputPaxEl.value = 0;

            document.getElementById('confirm-modal').classList.remove('hidden');

            // 🔥 EKSEKUSI SAAT TOMBOL KIRIM KONFIRMASI DIKLIK 🔥
            document.getElementById('final-confirm-btn').onclick = function() {
                let finalName = inputNameEl ? inputNameEl.value.trim() : '';
                if (!finalName) finalName = guestName !== 'Tamu Undangan' ? guestName : 'Hamba Allah';

                let finalPax = inputPaxEl ? parseInt(inputPaxEl.value) : 0;
                if (isNaN(finalPax) || finalPax < 0) finalPax = 0;

                processClaim(finalName, name, finalPax);
            };
        }

        // Fungsi Final Kirim Data ke Database
        function processClaim(senderName, giftName, giftPax) {
            // Ubah tombol di list kado jadi hijau "Terpilih"
            const itemElement = document.getElementById(currentSelectedItemId);
            if (itemElement) {
                const actionArea = itemElement.querySelector('button');
                if (actionArea) {
                    actionArea.outerHTML = `
                        <div class="flex items-center gap-2 text-green-400 animate-pulse">
                            <span class="text-[9px] font-bold uppercase tracking-widest">Tercatat</span>
                            <i class="fa-solid fa-circle-check text-lg"></i>
                        </div>
                    `;
                }
                itemElement.classList.add('border-green-400/30', 'bg-green-400/5');
            }

            closeConfirmModal();

            // 🔥 KIRIM KE DATABASE API RSVP 🔥
            const giftMessage = `Telah memberikan tanda kasih berupa: ${giftName} 🎁`;
            const statusHadir = giftPax > 0 ? 'hadir' : 'tidak_hadir';

            sendRsvpData({
                guest_name: senderName,
                status_rsvp: statusHadir,
                pax: giftPax,
                message: giftMessage
            });

            // Tampilkan Toast Notifikasi
            const toast = document.getElementById('gift-toast');
            if (toast) {
                toast.innerHTML = `Terima kasih ${senderName}! Kado "${giftName}" telah tercatat.`;
                toast.style.opacity = '1';
                toast.style.bottom = '40px';

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.bottom = '10px';
                    setTimeout(() => {
                        toggleGiftModal(false);
                    }, 500);
                }, 4000);
            }
        }

        function closeConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
        }
    </script>
</body>

</html>
