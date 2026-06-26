@php
    // Decode data content dari database
    $content = json_decode($invitation->details->content ?? '{}', true);

    // Konfigurasi dasar
    $isGroomFirst = ($content['couple_order'] ?? 'groom_first') === 'groom_first';

    $groom = [
        'name' => $content['groom_name'] ?? 'Romeo Montague',
        'nickname' => $content['groom_nickname'] ?? 'Romeo',
        'father' => $content['groom_father'] ?? 'Bapak Montague',
        'mother' => $content['groom_mother'] ?? 'Ibu Montague',
        'photo' => !empty($content['groom_photo'])
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
        'photo' => !empty($content['bride_photo'])
            ? asset('storage/' . $content['bride_photo'])
            : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg',
        'ig' => $content['bride_ig'] ?? '',
        'label' => 'The Bride',
        'gender_text' => 'Putri',
    ];

    $firstPerson = $isGroomFirst ? $groom : $bride;
    $secondPerson = $isGroomFirst ? $bride : $groom;

    $hasResepsi = false;
    $weddingTimestamp = 0;
    $coverDateDisplay = '- . - . -';
    $coverDateHuman = 'Sabtu, 18 Juli 2026';

    if (!empty($content['events']) && is_array($content['events']) && count($content['events']) > 0) {
        $firstEvent = collect($content['events'])->first();
        if (!empty($firstEvent['date'])) {
            $hasResepsi = true;
            $coverDateDisplay = \Carbon\Carbon::parse($firstEvent['date'])->translatedFormat('d F Y');
            $coverDateHuman = \Carbon\Carbon::parse($firstEvent['date'])->translatedFormat('l, d F Y');
            $eventTime = !empty($firstEvent['time']) ? $firstEvent['time'] : '00:00:00';
            $weddingTimestamp = \Carbon\Carbon::parse($firstEvent['date'] . ' ' . $eventTime)->timestamp * 1000;
        }
    }

    $coverImage = !empty($content['cover_image'])
        ? asset('storage/' . $content['cover_image'])
        : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=2000&q=80';

    $guestSlug = request()->query('to');
    $guest = null;
    if ($guestSlug) {
        $guest = \DB::table('guests')->where('invitation_id', $invitation->id)->where('slug_name', $guestSlug)->first();
    }
    $guestNameDisplay = $guest
        ? $guest->name
        : ($guestSlug
            ? urldecode(str_replace(['+', '-'], ' ', $guestSlug))
            : 'Tamu Undangan');
    $qrData = $guest ? $guest->qr_code ?? $guest->slug_name : $guestNameDisplay;
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=881337&bgcolor=FFFFFF&data=' . urlencode($qrData);

    $dbWishes = \DB::table('wishes_rsvps')
        ->where('invitation_id', $invitation->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $totalAttendance = \DB::table('wishes_rsvps')
        ->where('invitation_id', $invitation->id)
        ->where('status_rsvp', 'hadir')
        ->sum('pax') ?? 0;

    $totalWishes = $dbWishes->count();

    $masterLogos = \DB::table('banks')->pluck('logo', 'name')->toArray();
    $masterLogos = array_change_key_case($masterLogos, CASE_LOWER);
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }} - The Eternal Bloom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#FFF5F8',
                        card: '#FFFFFF',
                        primary: '#881337',
                        secondary: '#BE185D',
                        accent: '#D4AF37',
                        accentHover: '#B5952F',
                        borderLight: '#FCE7F3',
                        dark: '#3E2723',
                        muted: '#F8BBD0'
                    },
                    fontFamily: {
                        sans: ['"Montserrat"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                        cursive: ['"Great Vibes"', 'cursive'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 12s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html,
        body {
            max-width: 100vw;
            overflow-x: hidden;
            background-color: #FFF5F8;
            color: #3E2723;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar {
            width: 8px;
            background: #FFF5F8;
        }

        ::-webkit-scrollbar-thumb {
            background: #BE185D;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 10px;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .img-oval {
            border-radius: 50% 50% 0 0 / 40% 40% 0 0;
        }

        .img-bloom {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }

        .arch-image {
            border-radius: 150px 150px 0 0;
        }

        .arch-bottom {
            border-radius: 0 0 150px 150px;
        }

        #petal-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10;
            overflow: hidden;
        }

        .petal {
            position: absolute;
            background-color: rgba(244, 114, 182, 0.4);
            border-radius: 150% 0 150% 0;
            opacity: 0.6;
            animation: fall linear forwards;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 1.2s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="font-sans selection:bg-accent selection:text-white">

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music_id) && $invitation->music ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3' }}" type="audio/mpeg">
    </audio>

    <div id="petal-container"></div>

    <div id="music-tooltip"
        class="fixed right-20 md:right-24 bottom-28 z-[60] bg-white/90 backdrop-blur-sm border border-borderLight p-3 px-5 rounded-full shadow-lg opacity-0 transition-all duration-500 pointer-events-none transform translate-x-4 flex items-center gap-3">
        <div class="w-6 h-6 bg-secondary rounded-full flex items-center justify-center text-white animate-spin-slow">
            <i class="fa-solid fa-compact-disc text-[10px]"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest leading-none">Now Playing</p>
            <p class="font-serif text-xs italic text-primary leading-tight mt-0.5">{{ !empty($invitation->music_id) && $invitation->music ? $invitation->music->title : 'Beautiful in White' }}</p>
        </div>
    </div>

    <div id="cover-page"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-bg transition-transform duration-1000 ease-in-out overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ $coverImage }}"
                class="w-full h-full object-cover opacity-30 animate-pulse-slow">
            <div class="absolute inset-0 bg-gradient-to-b from-bg/40 via-bg/60 to-bg"></div>
        </div>

        <div class="absolute top-0 w-full p-8 flex justify-center items-center z-10">
            <h1 class="font-serif text-accent text-xl md:text-2xl tracking-[0.2em] uppercase">The Wedding</h1>
        </div>

        <div class="relative z-10 flex flex-col items-center text-center px-6 w-full max-w-2xl">
            <div class="mb-10 relative">
                <div class="absolute -inset-4 border border-accent/30 rounded-full animate-spin-slow"></div>
                <div
                    class="w-48 h-48 md:w-56 md:h-56 rounded-full border-4 border-white shadow-[0_10px_30px_rgba(190,24,93,0.3)] overflow-hidden">
                    <img src="{{ $firstPerson['photo'] }}"
                        class="w-full h-full object-cover">
                </div>
            </div>

            <p class="font-serif italic text-secondary text-lg mb-2">{{ $content['cover_greeting'] ?? 'The Wedding of' }}</p>
            <h1 class="font-cursive text-7xl md:text-8xl text-primary mb-2">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h1>
            <p class="font-sans font-bold text-xs uppercase tracking-[0.5em] text-accent mb-12">{{ $coverDateDisplay }}</p>

            <div
                class="bg-white/80 backdrop-blur-md border border-muted p-8 rounded-[2.5rem] w-full mb-10 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fa-solid fa-quote-right text-4xl text-primary"></i>
                </div>
                <p class="text-[10px] text-secondary uppercase font-bold tracking-[0.3em] mb-3">Spesial Untuk</p>
                <p id="guest-name" class="font-serif text-3xl md:text-4xl text-dark italic font-bold">{{ $guestNameDisplay }}</p>
            </div>

            <button onclick="openInvitation()"
                class="group relative bg-primary text-white px-12 py-4 rounded-full font-bold uppercase tracking-widest text-xs shadow-2xl overflow-hidden hover:scale-105 transition-all active:scale-95">
                <span class="relative z-10">Buka Undangan</span>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-secondary to-primary opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
            </button>
        </div>
    </div>

    <main id="main-content"
        class="min-h-screen opacity-0 transition-opacity duration-1000 pb-20 relative z-10 w-full overflow-hidden">

        <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-md border-b border-borderLight p-5 flex justify-between items-center transition-all duration-300"
            id="main-nav">
            <h1 class="font-serif text-primary text-xl md:text-2xl tracking-[0.2em] uppercase">{{ $firstPerson['nickname'] }} <span
                    class="text-accent">&</span> {{ $secondPerson['nickname'] }}</h1>
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-heart text-secondary text-2xl animate-pulse"></i>
            </div>
        </nav>

        <section id="home"
            class="relative h-screen flex flex-col items-center justify-center px-6 overflow-hidden w-full">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="{{ $coverImage }}"
                    class="w-full h-full object-cover opacity-20">
                <div class="absolute inset-0 bg-gradient-to-t from-bg via-bg/40 to-transparent"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto w-full text-center flex flex-col items-center">
                <div class="mb-12 reveal">
                    <img src="{{ $coverImage }}"
                        class="w-64 h-80 object-cover img-oval border-8 border-white shadow-2xl">
                </div>

                <h2 class="font-cursive text-8xl md:text-9xl text-primary mb-6 reveal">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h2>

                <div class="reveal">
                    <div
                        class="inline-flex items-center gap-4 bg-white/80 border border-muted px-8 py-3 rounded-full shadow-lg mb-10">
                        <span class="w-2 h-2 bg-accent rounded-full"></span>
                        <p class="font-serif italic text-lg text-dark">{{ $coverDateHuman }}</p>
                        <span class="w-2 h-2 bg-accent rounded-full"></span>
                    </div>
                </div>

                <div class="flex gap-4 reveal">
                    <button onclick="openRSVP()"
                        class="bg-primary text-white px-8 py-4 rounded-full font-bold uppercase tracking-widest text-[10px] shadow-lg hover:shadow-secondary/50 transition-all">
                        RSVP Kehadiran
                    </button>
                </div>
            </div>
        </section>

        <section id="cast"
            class="px-6 md:px-20 py-32 relative z-10 bg-white rounded-t-[3rem] shadow-[0_-10px_40px_rgba(157,23,77,0.05)] w-full">
            <div class="absolute top-0 right-0 w-64 opacity-10"><i
                    class="fa-solid fa-leaf text-[200px] text-accent rotate-45"></i></div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-24 reveal">
                    <h3 class="font-cursive text-6xl text-primary mb-4">Mempelai Berbahagia</h3>
                    <div class="h-px w-24 bg-accent mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 lg:gap-32 max-w-5xl mx-auto items-center">
                    <div class="flex flex-col items-center text-center group reveal">
                        <div class="relative mb-10">
                            <div class="absolute inset-0 bg-muted rounded-full blur-3xl opacity-20 scale-125"></div>
                            <img src="{{ $firstPerson['photo'] }}"
                                class="w-72 h-72 md:w-80 md:h-80 object-cover img-bloom border-8 border-white shadow-2xl relative z-10">
                        </div>
                        <h4 class="font-serif text-4xl text-dark mb-4">{{ $firstPerson['name'] }}</h4>
                        <p class="font-sans font-semibold text-xs uppercase tracking-[0.3em] text-accent mb-6">{{ $firstPerson['label'] }}</p>
                        <p class="font-serif italic text-lg text-secondary">{{ $firstPerson['gender_text'] }} tercinta dari Bapak {{ $firstPerson['father'] }} & Ibu {{ $firstPerson['mother'] }}</p>
                        @if(!empty($firstPerson['ig']))
                        <a href="https://instagram.com/{{ str_replace('@', '', $firstPerson['ig']) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-xs text-primary font-bold"><i class="fa-brands fa-instagram"></i> {{ $firstPerson['ig'] }}</a>
                        @endif
                    </div>

                    <div class="flex flex-col items-center text-center group reveal">
                        <div class="relative mb-10">
                            <div class="absolute inset-0 bg-muted rounded-full blur-3xl opacity-20 scale-125"></div>
                            <img src="{{ $secondPerson['photo'] }}"
                                class="w-72 h-72 md:w-80 md:h-80 object-cover img-bloom border-8 border-white shadow-2xl relative z-10"
                                style="border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;">
                        </div>
                        <h4 class="font-serif text-4xl text-dark mb-4">{{ $secondPerson['name'] }}</h4>
                        <p class="font-sans font-semibold text-xs uppercase tracking-[0.3em] text-accent mb-6">{{ $secondPerson['label'] }}</p>
                        <p class="font-serif italic text-lg text-secondary">{{ $secondPerson['gender_text'] }} tercinta dari Bapak {{ $secondPerson['father'] }} & Ibu {{ $secondPerson['mother'] }}</p>
                        @if(!empty($secondPerson['ig']))
                        <a href="https://instagram.com/{{ str_replace('@', '', $secondPerson['ig']) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-xs text-primary font-bold"><i class="fa-brands fa-instagram"></i> {{ $secondPerson['ig'] }}</a>
                        @endif
                    </div>
                </div>

                @if (($content['is_turut_mengundang_active'] ?? false) && !empty($content['turut_mengundang']))
                    <div class="mt-24 max-w-4xl mx-auto p-12 bg-bg rounded-[2.5rem] border border-borderLight text-center reveal">
                        <p class="font-sans font-bold text-[10px] uppercase tracking-[0.3em] text-secondary mb-8">Turut Mengundang</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-dark font-serif italic text-base">
                            @foreach ($content['turut_mengundang'] as $tamu)
                                <p>{{ trim($tamu) }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        @if (($content['is_story_active'] ?? false) && !empty($content['love_stories']))
        <section id="cerita" class="py-32 px-6 bg-bg w-full overflow-hidden">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-24 reveal">
                    <span class="font-sans font-bold text-[10px] uppercase tracking-[0.5em] text-accent block mb-4">Our Love Journal</span>
                    <h3 class="font-serif italic text-5xl text-dark">Kisah Perjalanan Kami</h3>
                </div>

                <div class="space-y-32">
                    @foreach ($content['love_stories'] as $index => $story)
                    @php $isLeft = $index % 2 === 0; @endphp
                    <div class="flex flex-col {{ $isLeft ? 'md:flex-row' : 'md:flex-row-reverse' }} items-center gap-12 reveal">
                        <div class="w-full md:w-1/2">
                            @if(!empty($story['image']))
                            <img src="{{ asset('storage/' . $story['image']) }}"
                                class="w-full h-96 object-cover rounded-[3rem] shadow-2xl border-4 border-white">
                            @endif
                        </div>
                        <div class="w-full md:w-1/2 text-center {{ $isLeft ? 'md:text-left' : 'md:text-right' }}">
                            <span class="font-sans font-bold text-xs text-accent border border-accent/30 px-3 py-1 rounded-full inline-block mb-4">{{ $story['year'] ?? '' }}</span>
                            <h5 class="font-serif text-3xl text-primary mb-6">{{ $story['title'] ?? '' }}</h5>
                            <p class="text-secondary leading-loose italic">"{!! nl2br(e($story['description'] ?? '')) !!}"</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        @if ($content['is_gallery_active'] ?? false)
        <section id="gallery" class="py-24 md:py-32 bg-white w-full relative overflow-hidden">
            <div
                class="absolute top-0 left-0 w-64 h-64 bg-bg rounded-full blur-3xl opacity-50 -translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-muted rounded-full blur-3xl opacity-20 translate-x-1/4 translate-y-1/4">
            </div>

            <div class="px-6 md:px-20 text-center mb-16 relative z-10 reveal">
                <h3 class="text-5xl md:text-7xl font-cursive text-secondary mb-4 drop-shadow-sm">Galeri Kenangan</h3>
                <div class="flex items-center justify-center gap-3">
                    <span class="w-8 h-px bg-accent"></span>
                    <span class="text-primary text-[10px] font-bold uppercase tracking-[0.4em] block">Captured Moments</span>
                    <span class="w-8 h-px bg-accent"></span>
                </div>
            </div>

            @php $youtubeLink = $content['youtube_links'][0] ?? null; @endphp
            @if ($youtubeLink)
            <div class="px-6 md:px-20 mb-20 max-w-5xl mx-auto relative z-10 reveal">
                <div
                    class="relative w-full aspect-video rounded-[2.5rem] overflow-hidden bg-bg shadow-2xl p-2 border border-borderLight group">
                    <div class="w-full h-full rounded-[2rem] overflow-hidden relative">
                        <iframe class="absolute inset-0 w-full h-full"
                            src="{{ str_replace('watch?v=', 'embed/', $youtubeLink) }}"
                            title="Wedding Trailer" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            @endif

            <div class="px-6 md:px-20 max-w-7xl mx-auto relative z-10 reveal">
                <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
                    @foreach ($invitation->galleries ?? [] as $index => $gallery)
                    <div class="break-inside-avoid group cursor-pointer relative overflow-hidden rounded-[2rem] border-4 border-white shadow-lg hover:shadow-2xl transition-all duration-500"
                        onclick="openLightbox({{ $index }})">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}"
                            class="gallery-img w-full h-auto object-cover group-hover:scale-110 transition-transform duration-1000">
                        @if(!empty($gallery->caption))
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-primary/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-8">
                            <p
                                class="text-white font-serif italic text-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                {{ $gallery->caption }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <section id="lokasi" class="py-32 px-6 bg-bg relative overflow-hidden w-full">
            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-24 reveal">
                    <h3 class="font-serif italic text-5xl text-dark mb-4">Waktu & Lokasi</h3>
                    <div class="h-px w-24 bg-accent mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">
                    <div
                        class="bg-white/90 backdrop-blur-sm border border-muted p-12 rounded-[3rem] shadow-2xl relative reveal group">
                        <div
                            class="absolute -top-6 left-12 bg-accent text-white px-8 py-2 rounded-full font-bold text-[10px] uppercase tracking-widest shadow-lg">
                            The Vow</div>
                        <h4 class="font-serif text-3xl text-primary mb-8">Akad Nikah</h4>
                        <div class="space-y-6 mb-10 text-secondary italic">
                            <div class="flex items-center gap-4"><i class="fa-regular fa-calendar text-accent"></i>
                                {{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</div>
                            <div class="flex items-center gap-4"><i class="fa-regular fa-clock text-accent"></i> {{ $content['akad_time'] ?? '08:00' }} - {{ $content['akad_time_end'] ?? 'Selesai' }} WIB</div>
                            <div class="flex items-start gap-4"><i
                                    class="fa-solid fa-location-dot text-accent mt-1"></i>
                                <span>{{ $content['akad_location'] ?? 'Kediaman Mempelai' }}<br><span class="text-xs font-sans not-italic text-muted">{{ $content['akad_address'] ?? '' }}</span></span>
                            </div>
                        </div>
                        <a href="{{ $content['akad_map'] ?? '#' }}" target="_blank"
                            class="block w-full text-center py-4 bg-primary text-white rounded-full font-bold text-xs uppercase tracking-widest hover:bg-secondary transition-all">Lihat Peta Lokasi</a>
                    </div>

                    @if (!empty($content['events']) && is_array($content['events']))
                        @foreach ($content['events'] as $index => $evt)
                        <div
                            class="bg-white/90 backdrop-blur-sm border border-muted p-12 rounded-[3rem] shadow-2xl relative reveal group lg:mt-12">
                            <div
                                class="absolute -top-6 left-12 bg-primary text-white px-8 py-2 rounded-full font-bold text-[10px] uppercase tracking-widest shadow-lg">
                                The Feast</div>
                            <h4 class="font-serif text-3xl text-primary mb-8">{{ $evt['title'] ?? 'Resepsi Pernikahan' }}</h4>
                            <div class="space-y-6 mb-10 text-secondary italic">
                                <div class="flex items-center gap-4"><i class="fa-regular fa-calendar text-accent"></i>
                                    {{ !empty($evt['date']) ? \Carbon\Carbon::parse($evt['date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</div>
                                <div class="flex items-center gap-4"><i class="fa-regular fa-clock text-accent"></i> {{ $evt['time'] ?? '11:00' }} - {{ $evt['time_end'] ?? 'Selesai' }}</div>
                                <div class="flex items-start gap-4"><i
                                        class="fa-solid fa-location-dot text-accent mt-1"></i>
                                    <span>{{ $evt['location'] ?? 'Grand Ballroom Hotel' }}<br><span class="text-xs font-sans not-italic text-muted">{{ $evt['address'] ?? '' }}</span></span>
                                </div>
                            </div>
                            <a href="{{ $evt['map_link'] ?? '#' }}" target="_blank"
                                class="block w-full text-center py-4 bg-dark text-white rounded-full font-bold text-xs uppercase tracking-widest hover:bg-primary transition-all">Lihat Peta Lokasi</a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        @if ($content['is_livestream_active'] ?? false)
        <section id="live-streaming" class="py-32 px-6 bg-white w-full border-y border-borderLight">
            <div class="max-w-4xl mx-auto text-center reveal">
                <div
                    class="w-20 h-20 bg-muted/20 rounded-full flex items-center justify-center mx-auto mb-8 border border-muted">
                    <i class="fa-solid fa-video text-3xl text-primary"></i>
                </div>
                <h2 class="font-serif italic text-4xl text-dark mb-6">Virtual Wedding</h2>
                <p class="text-secondary italic mb-12">"Saksikan momen sakral kami secara virtual melalui platform pilihan Anda."</p>

                <div id="streaming-display"
                    class="bg-bg border border-muted p-4 rounded-[3rem] shadow-2xl max-w-2xl mx-auto transition-transform duration-500 overflow-hidden relative">
                    <div class="p-12 flex flex-col items-center">
                        <i id="platform-icon"
                            class="fa-brands fa-youtube text-7xl text-secondary mb-6 animate-pulse"></i>
                        <h3 id="platform-title" class="font-serif text-3xl text-primary mb-2 italic">YouTube Live</h3>
                        <p id="platform-desc" class="text-secondary font-bold text-xs uppercase tracking-widest mb-10">
                            Mulai Pukul 09.00 WIB</p>
                        <a id="platform-link" href="#"
                            class="bg-primary text-white px-10 py-4 rounded-full font-bold uppercase tracking-widest text-[10px]">Tonton Siaran Langsung</a>
                    </div>
                </div>

                @if(!empty($content['live_streams']) && is_array($content['live_streams']))
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    @foreach ($content['live_streams'] as $stream)
                    @php
                        $platform = strtolower($stream['platform'] ?? 'youtube');
                        $icons = [
                            'youtube' => 'fa-brands fa-youtube',
                            'instagram' => 'fa-brands fa-instagram',
                            'tiktok' => 'fa-brands fa-tiktok',
                            'zoom' => 'fa-solid fa-video'
                        ];
                        $iconClass = $icons[$platform] ?? 'fa-solid fa-video';
                        $title = ucfirst($platform) . ' Live';
                    @endphp
                    <button
                        onclick="switchPlatform('{{ $platform }}', '{{ $title }}', 'Live Broadcast', '{{ $iconClass }}', '{{ $stream['link'] ?? '#' }}')"
                        class="w-12 h-12 bg-bg border border-borderLight rounded-full text-secondary hover:text-primary hover:bg-white flex items-center justify-center text-lg transition-all shadow-sm">
                        <i class="{{ $iconClass }}"></i>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </section>
        @endif

        @if ($content['is_dresscode_active'] ?? true)
        <section id="guest-info" class="py-32 px-6 md:px-16 bg-bg relative overflow-hidden w-full">
            <img src="https://images.unsplash.com/photo-1537274942065-eda9d00a6293?w=400"
                class="absolute -left-20 top-20 opacity-20 rotate-45 w-64 pointer-events-none">
            <img src="https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=400"
                class="absolute -right-20 bottom-20 opacity-20 -rotate-12 w-64 pointer-events-none">

            <div class="max-w-6xl mx-auto relative z-10 w-full">
                <div class="mb-16 text-center reveal">
                    <h2 class="text-4xl md:text-5xl font-serif text-primary mb-6 italic">Informasi Tamu</h2>

                    <div
                        class="flex flex-wrap items-center justify-center gap-3 text-[10px] md:text-xs font-bold font-sans">
                        <span
                            class="text-secondary border border-secondary/30 bg-white/50 px-4 py-1.5 rounded-full shadow-sm backdrop-blur-sm">Love Match 100%</span>
                        <span class="text-dark bg-white px-3 py-1.5 rounded-full shadow-sm">Est. {{ date('Y') }}</span>
                        <span
                            class="px-2 py-1 border border-accent text-accent rounded-sm shadow-sm bg-white/50">VIP</span>
                        <span class="text-dark bg-white px-4 py-1.5 rounded-full shadow-sm">1 Session</span>
                        <span
                            class="px-3 py-1 border border-primary text-primary bg-white/50 rounded-sm uppercase tracking-widest shadow-sm">Exclusive Event</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 w-full">

                    <div class="lg:col-span-2 space-y-12">
                        <div
                            class="bg-white p-10 md:p-12 rounded-[3rem] shadow-xl border border-borderLight reveal relative overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1519741497674-611481863552?w=800')] opacity-5 group-hover:opacity-10 transition-opacity object-cover">
                            </div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <span class="w-1.5 h-6 bg-accent rounded-full"></span>
                                    <h4 class="text-secondary uppercase tracking-[0.3em] text-[10px] font-bold">
                                        Dresscode</h4>
                                </div>
                                <p class="text-dark text-xl md:text-2xl font-serif italic leading-relaxed">
                                    <span
                                        class="font-bold font-sans text-sm uppercase text-primary block mb-3 not-italic tracking-widest">{{ $content['dresscode_title'] ?? 'Formal & Elegant' }}</span>
                                    "{!! nl2br(e($content['dresscode_desc'] ?? 'Your presence is our greatest gift, your elegance completes our joy. Kami memohon kehadiran Anda dengan busana terbaik bernuansa soft/pastel.')) !!}"
                                </p>
                            </div>
                        </div>

                        <div
                            class="reveal bg-white/50 backdrop-blur-md p-8 rounded-[3rem] border border-borderLight shadow-sm">
                            <h5
                                class="text-primary text-[10px] font-bold uppercase tracking-[0.2em] mb-6 text-center border-b border-borderLight pb-4">
                                Protokol Kesehatan</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div id="protokol-cuci-tangan"
                                    class="flex flex-col items-center gap-3 bg-white px-5 py-6 rounded-3xl border border-borderLight hover:shadow-lg hover:-translate-y-1 transition-all group">
                                    <i
                                        class="fa-solid fa-hands-bubbles text-secondary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-dark text-[9px] uppercase tracking-widest font-bold">Cuci Tangan</span>
                                </div>
                                <div id="protokol-pakai-masker"
                                    class="flex flex-col items-center gap-3 bg-white px-5 py-6 rounded-3xl border border-borderLight hover:shadow-lg hover:-translate-y-1 transition-all group">
                                    <i
                                        class="fa-solid fa-head-side-mask text-secondary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-dark text-[9px] uppercase tracking-widest font-bold">Pakai Masker</span>
                                </div>
                                <div id="protokol-jaga-jarak"
                                    class="flex flex-col items-center gap-3 bg-white px-5 py-6 rounded-3xl border border-borderLight hover:shadow-lg hover:-translate-y-1 transition-all group">
                                    <i
                                        class="fa-solid fa-people-arrows text-secondary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-dark text-[9px] uppercase tracking-widest font-bold">Jaga Jarak</span>
                                </div>
                                <div id="protokol-hindari-kerumunan"
                                    class="flex flex-col items-center gap-3 bg-white px-5 py-6 rounded-3xl border border-borderLight hover:shadow-lg hover:-translate-y-1 transition-all group">
                                    <i
                                        class="fa-solid fa-users-slash text-secondary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-dark text-[9px] uppercase tracking-widest font-bold">No Kerumunan</span>
                                </div>
                                <div id="protokol-cek-suhu"
                                    class="flex flex-col items-center gap-3 bg-white px-5 py-6 rounded-3xl border border-borderLight hover:shadow-lg hover:-translate-y-1 transition-all group">
                                    <i
                                        class="fa-solid fa-temperature-high text-secondary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-dark text-[9px] uppercase tracking-widest font-bold">Cek Suhu</span>
                                </div>
                                <div id="protokol-desinfektan"
                                    class="flex flex-col items-center gap-3 bg-white px-5 py-6 rounded-3xl border border-borderLight hover:shadow-lg hover:-translate-y-1 transition-all group">
                                    <i
                                        class="fa-solid fa-spray-can-sparkles text-secondary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span
                                        class="text-dark text-[9px] uppercase tracking-widest font-bold">Desinfektan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white p-8 rounded-[3rem] border border-borderLight shadow-xl h-max reveal relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-accent to-secondary"></div>
                        <h5
                            class="text-primary text-[10px] font-bold uppercase tracking-[0.2em] flex items-center justify-center gap-3 mb-8 border-b border-borderLight pb-4 mt-2">
                            <i class="fa-solid fa-list-check text-accent"></i> Wedding Etiquette
                        </h5>
                        <div class="space-y-6">
                            <div id="adab-sholat" class="flex gap-4 items-start">
                                <div class="text-accent text-lg w-6 shrink-0 text-center"><i
                                        class="fa-solid fa-mosque"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-[10px] uppercase tracking-widest mb-1">Waktu Sholat</p>
                                    <p class="text-gray-500 text-xs font-medium leading-relaxed">Memperhatikan waktu ibadah saat acara.</p>
                                </div>
                            </div>
                            <div id="adab-makan-minum" class="flex gap-4 items-start">
                                <div class="text-accent text-lg w-6 shrink-0 text-center"><i
                                        class="fa-solid fa-utensils"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-[10px] uppercase tracking-widest mb-1">Adab Makan</p>
                                    <p class="text-gray-500 text-xs font-medium leading-relaxed">Makan & minum dengan cara duduk.</p>
                                </div>
                            </div>
                            <div id="adab-mendoakan" class="flex gap-4 items-start">
                                <div class="text-accent text-lg w-6 shrink-0 text-center"><i
                                        class="fa-solid fa-hands-praying"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-[10px] uppercase tracking-widest mb-1">Doa Restu</p>
                                    <p class="text-gray-500 text-xs font-medium leading-relaxed">Memberikan doa keberkahan bagi kami.</p>
                                </div>
                            </div>
                            <div id="adab-jaga-jarak" class="flex gap-4 items-start">
                                <div class="text-accent text-lg w-6 shrink-0 text-center"><i
                                        class="fa-solid fa-restroom"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-[10px] uppercase tracking-widest mb-1">Jaga Jarak</p>
                                    <p class="text-gray-500 text-xs font-medium leading-relaxed">Menjaga batasan antara tamu pria & wanita.</p>
                                </div>
                            </div>
                            <div id="adab-pakaian-sopan" class="flex gap-4 items-start">
                                <div class="text-accent text-lg w-6 shrink-0 text-center"><i
                                        class="fa-solid fa-shirt"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-[10px] uppercase tracking-widest mb-1">Baju Sopan</p>
                                    <p class="text-gray-500 text-xs font-medium leading-relaxed">Berbusana menutup aurat dan rapi.</p>
                                </div>
                            </div>
                            <div id="adab-larangan-foto" class="flex gap-4 items-start">
                                <div class="text-accent text-lg w-6 shrink-0 text-center"><i
                                        class="fa-solid fa-video-slash"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-[10px] uppercase tracking-widest mb-1">Izin Foto</p>
                                    <p class="text-gray-500 text-xs font-medium leading-relaxed">Meminta izin sebelum mendokumentasikan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        @endif

        @if ($content['is_gift_active'] ?? false)
        <section id="hadiah" class="py-32 px-6 bg-white relative overflow-hidden border-t border-borderLight w-full">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center w-full">
                <div class="reveal">
                    <h3 class="font-cursive text-6xl text-primary mb-6">Tanda Kasih</h3>
                    <p class="text-secondary italic mb-10 leading-loose text-lg">Doa restu Anda adalah karunia terindah.
                        Bagi Anda yang ingin memberikan tanda kasih secara digital, pintu hati kami terbuka melalui rekening resmi berikut:</p>

                    <div class="grid grid-cols-1 gap-6">
                        @if (!empty($content['banks']) && is_array($content['banks']))
                            @foreach ($content['banks'] as $index => $bank)
                            @php $logoUrl = $masterLogos[strtolower($bank['name'])] ?? null; @endphp
                            <div
                                class="bg-bg p-8 rounded-[2.5rem] border border-borderLight text-center shadow-md relative group">
                                @if ($logoUrl)
                                <img src="{{ asset('storage/' . $logoUrl) }}" class="h-6 mx-auto mb-4 opacity-80 group-hover:opacity-100 transition-opacity">
                                @else
                                <span class="font-bold text-dark text-lg mb-4 block">{{ strtoupper($bank['name']) }}</span>
                                @endif
                                <h4 id="rek-{{ $index }}" class="font-serif text-3xl text-primary tracking-widest mb-2">{{ $bank['account_number'] }}</h4>
                                <p class="text-[10px] font-bold uppercase text-accent tracking-[0.3em] mb-6">a.n {{ $bank['account_name'] ?? $bank['account_owner'] ?? '' }}</p>
                                <button onclick="copyToClipboard('rek-{{ $index }}', this)"
                                    class="bg-white border border-borderLight text-primary px-8 py-3 rounded-full font-bold text-[10px] uppercase tracking-widest shadow-sm hover:bg-primary hover:text-white transition-colors w-full">Salin Nomor</button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div id="qr-tamu"
                    class="bg-bg p-12 rounded-[3.5rem] text-center border border-borderLight shadow-2xl reveal relative h-max mt-8 md:mt-0">
                    <div class="absolute -top-6 -right-6 text-accent opacity-50"><i
                            class="fa-solid fa-leaf text-6xl"></i></div>
                    <div class="p-4 bg-white rounded-[2rem] border border-borderLight shadow-sm mb-8 inline-block">
                        <img id="qr-image"
                            src="{{ $qrCodeUrl }}"
                            class="w-40 h-40">
                    </div>
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-[0.3em] mb-2">Access Pass</p>
                    <h4 id="guest-name-qr" class="font-serif italic text-3xl text-primary mb-6">{{ $guestNameDisplay }}</h4>
                    <div
                        class="py-3 px-6 bg-white rounded-full text-secondary font-bold text-[10px] uppercase tracking-[0.2em] shadow-sm inline-block">
                        Verify RSVP</div>
                </div>
            </div>
            <div id="copy-toast"
                class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[300] px-8 py-3 bg-secondary text-white rounded-full shadow-xl text-[10px] font-bold uppercase tracking-widest opacity-0 translate-y-4 transition-all duration-500 pointer-events-none flex items-center gap-2">
                <i class="fa-solid fa-check"></i> Tersalin!
            </div>
        </section>
        @endif

        <footer class="py-24 px-6 bg-bg text-center relative overflow-hidden border-t border-borderLight w-full pb-32">
            <div class="max-w-2xl mx-auto relative z-10">
                <h2 class="font-cursive text-7xl md:text-8xl text-primary mb-6 drop-shadow-md">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h2>
                <p class="font-serif italic text-secondary text-lg md:text-xl mb-12 leading-relaxed">"Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir."</p>
                <div class="h-px w-24 bg-accent mx-auto mb-10"></div>
                <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank"
                    class="inline-flex items-center gap-3 text-secondary bg-white px-6 py-2.5 rounded-full font-bold text-[10px] uppercase tracking-[0.2em] shadow-sm hover:text-primary transition-colors">
                    <i class="fa-brands fa-instagram text-lg"></i> @ruangrestu.undangan
                </a>
            </div>
        </footer>

    </main>

    <section id="rsvp-modal"
        class="fixed inset-0 z-[100] invisible transition-all duration-500 flex items-end md:items-center justify-center w-full h-full overflow-hidden">
        <div onclick="closeRSVP()"
            class="absolute inset-0 bg-primary/50 backdrop-blur-md opacity-0 transition-opacity duration-500"
            id="rsvp-overlay"></div>
        <div id="rsvp-content"
            class="relative w-full md:max-w-xl bg-white rounded-t-[3.5rem] md:rounded-[3.5rem] shadow-2xl transform translate-y-full transition-transform duration-700 flex flex-col max-h-[90vh]">
            <div class="overflow-y-auto px-8 md:px-12 pb-12 pt-10 custom-scrollbar relative w-full">
                <div class="w-12 h-1.5 bg-borderLight rounded-full mx-auto mb-8 md:hidden"></div>
                <div class="flex justify-between items-center mb-10 border-b border-borderLight pb-6">
                    <h2 class="text-4xl font-serif text-primary italic">RSVP</h2>
                    <button onclick="closeRSVP()"
                        class="w-12 h-12 bg-bg rounded-full flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-colors border border-borderLight"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <form id="form-rsvp" class="space-y-8 w-full">
                    <input type="hidden" id="input-status" value="Hadir">
                    <input type="hidden" id="input-guest-count" value="1">
                    <div>
                        <label
                            class="font-bold text-[10px] uppercase tracking-[0.3em] text-secondary mb-3 block pl-4">Nama Lengkap</label>
                        <input type="text" id="input-nama-rsvp" value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}"
                            class="w-full bg-bg border border-borderLight focus:border-secondary p-5 rounded-full font-medium outline-none transition-colors shadow-inner text-sm">
                    </div>

                    <div>
                        <label
                            class="font-bold text-[10px] uppercase tracking-[0.3em] text-secondary mb-3 block pl-4">Konfirmasi Kehadiran</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir"
                                class="w-full py-4 border border-borderLight rounded-full bg-primary text-white text-[10px] font-bold uppercase tracking-[0.2em] transition-colors shadow-sm"><i
                                    class="fa-solid fa-check mr-2 text-sm"></i> Hadir</button>
                            <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen"
                                class="w-full py-4 border border-borderLight rounded-full bg-white text-secondary text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-bg transition-colors shadow-sm"><i
                                    class="fa-solid fa-xmark mr-2 text-red-500 text-sm"></i> Absen</button>
                        </div>
                    </div>

                    <div id="guest-selection">
                        <div class="bg-bg border border-borderLight p-6 rounded-3xl">
                            <label
                                class="font-bold text-[10px] uppercase tracking-[0.3em] text-primary mb-4 block text-center">Jumlah Tamu</label>
                            <div class="flex gap-3">
                                <button type="button" onclick="setGuestCount(1)"
                                    class="guest-btn flex-1 py-3 bg-secondary text-white border border-borderLight rounded-full font-bold text-xs shadow-sm transition-colors">1</button>
                                <button type="button" onclick="setGuestCount(2)"
                                    class="guest-btn flex-1 py-3 bg-white border border-borderLight rounded-full font-bold text-xs text-secondary hover:text-primary shadow-sm transition-colors">2</button>
                                <button type="button" onclick="setGuestCount('custom')"
                                    class="guest-btn flex-1 py-3 bg-white border border-borderLight rounded-full font-bold text-xs text-secondary hover:text-primary shadow-sm transition-colors">3+</button>
                            </div>
                            <div id="custom-pax-container" class="hidden mt-4">
                                <input type="number" id="custom-pax-input" placeholder="Masukkan jumlah tamu" class="w-full bg-white border border-borderLight p-4 rounded-full font-medium outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label
                            class="font-bold text-[10px] uppercase tracking-[0.3em] text-secondary mb-3 block pl-4">Doa & Ucapan</label>
                        <textarea id="input-pesan-rsvp" rows="4"
                            class="w-full bg-bg border border-borderLight focus:border-secondary p-5 rounded-[2rem] font-medium outline-none resize-none transition-colors shadow-inner text-sm"></textarea>
                    </div>

                    <div class="pt-4 flex flex-col gap-4">
                        <button type="submit"
                            class="w-full py-5 bg-gradient-to-r from-primary to-secondary text-white rounded-full font-bold text-[10px] uppercase tracking-[0.3em] shadow-xl shadow-secondary/30 hover:-translate-y-1 transition-transform">Kirim Konfirmasi</button>
                        <button type="button" onclick="closeRSVP()"
                            class="w-full py-4 bg-white text-secondary font-bold text-[10px] uppercase tracking-[0.3em] border border-borderLight rounded-full hover:bg-bg transition-colors">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div id="fab-container"
        class="fixed right-4 md:right-6 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000 w-auto">
        <div class="relative flex items-center group">
            <div id="music-info"
                class="absolute right-full mr-3 px-5 py-2.5 bg-white/90 backdrop-blur-sm border border-muted rounded-full font-bold text-[9px] uppercase tracking-widest text-secondary opacity-0 transition-all duration-500 whitespace-nowrap pointer-events-none shadow-lg">
                {{ !empty($invitation->music_id) && $invitation->music ? $invitation->music->title : 'Soundtrack' }}
            </div>
            <button id="btn-music" onclick="toggleMusic()"
                class="w-14 h-14 bg-white/90 backdrop-blur-sm border border-borderLight rounded-full flex items-center justify-center text-secondary shadow-xl hover:scale-105 transition-transform">
                <i class="fa-solid fa-music animate-spin-slow text-xl" id="icon-music"></i>
            </button>
        </div>
        <button id="btn-scroll" onclick="toggleAutoScroll()"
            class="w-14 h-14 bg-primary/90 backdrop-blur-sm border border-white rounded-full flex items-center justify-center text-white shadow-xl hover:scale-105 transition-transform">
            <i class="fa-solid fa-angles-down text-xl" id="icon-scroll"></i>
        </button>
    </div>

    <div id="lightbox"
        class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-primary/95 backdrop-blur-md p-4 w-full h-full">
        <div class="w-full flex justify-between items-center p-6 absolute top-0">
            <span class="bg-white px-5 py-2 rounded-full text-xs font-bold text-primary tracking-[0.2em]"><span
                    id="current-count">1</span> / <span id="total-count">4</span></span>
            <button onclick="closeLightbox()"
                class="w-12 h-12 bg-white rounded-full text-primary flex items-center justify-center hover:bg-bg transition-colors"><i
                    class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="flex items-center justify-center w-full h-[70vh] px-4 mt-8">
            <img id="lightbox-img" src=""
                class="max-h-full max-w-full border-[12px] border-white rounded-[2rem] shadow-2xl object-contain transition-opacity duration-300">
        </div>
        <div class="absolute bottom-8 flex gap-4 bg-white/20 p-2 rounded-full backdrop-blur-md">
            <button onclick="prevImg()"
                class="w-14 h-14 bg-white rounded-full text-primary flex items-center justify-center hover:bg-bg transition-all"><i
                    class="fa-solid fa-arrow-left text-lg"></i></button>
            <button onclick="nextImg()"
                class="w-14 h-14 bg-white rounded-full text-primary flex items-center justify-center hover:bg-bg transition-all"><i
                    class="fa-solid fa-arrow-right text-lg"></i></button>
        </div>
    </div>

    <nav id="bottom-nav"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-white/80 backdrop-blur-lg border border-borderLight rounded-full shadow-2xl p-1.5 flex">
            <ul class="flex justify-around items-center h-14 w-[320px] md:w-[400px]">
                <li class="h-full"><a href="#home"
                        class="flex items-center justify-center w-14 h-full text-secondary hover:text-primary transition-colors"><i
                            class="fa-solid fa-house text-lg"></i></a></li>
                <li class="h-full"><a href="#gallery"
                        class="flex items-center justify-center w-14 h-full text-secondary hover:text-primary transition-colors"><i
                            class="fa-solid fa-image text-lg"></i></a></li>
                <li class="h-full"><a href="#lokasi"
                        class="flex items-center justify-center w-14 h-full text-secondary hover:text-primary transition-colors"><i
                            class="fa-solid fa-location-dot text-lg"></i></a></li>
                <li class="h-full px-2 flex items-center">
                    <a href="javascript:void(0)" onclick="openRSVP()"
                        class="flex items-center justify-center px-8 h-10 bg-primary text-white rounded-full text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-secondary transition-all shadow-md">RSVP</a>
                </li>
            </ul>
        </div>
    </nav>

    <script>
        // Guest Data Setup
        const urlParams = new URLSearchParams(window.location.search);
        let guestName = "{{ $guestNameDisplay }}";

        // Open Invitation & Tooltip Logic
        function openInvitation() {
            document.getElementById('cover-page').classList.add('-translate-y-full');
            document.body.style.overflowY = 'auto';
            document.getElementById('main-content').classList.remove('opacity-0');
            document.getElementById('fab-container').classList.remove('opacity-0');
            document.getElementById('bottom-nav').classList.remove('translate-y-32');
            toggleMusic(true);

            // Tooltip music auto show briefly
            const musicInfo = document.getElementById('music-info');
            setTimeout(() => {
                musicInfo.classList.remove('opacity-0', 'translate-x-4');
                musicInfo.classList.add('opacity-100', 'translate-x-0');
                setTimeout(() => {
                    musicInfo.classList.remove('opacity-100', 'translate-x-0');
                    musicInfo.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => musicInfo.style = '', 500); // clear inline styles to allow hover
                }, 4000);
            }, 1500);
        }

        // Hover Music Tooltip
        document.getElementById('btn-music').addEventListener('mouseenter', () => {
            document.getElementById('music-info').classList.remove('opacity-0', 'translate-x-4');
            document.getElementById('music-info').classList.add('opacity-100', 'translate-x-0');
        });
        document.getElementById('btn-music').addEventListener('mouseleave', () => {
            document.getElementById('music-info').classList.remove('opacity-100', 'translate-x-0');
            document.getElementById('music-info').classList.add('opacity-0', 'translate-x-4');
        });

        // Floating Petals Animation
        function createPetal() {
            const container = document.getElementById('petal-container');
            if(!container) return;
            const petal = document.createElement('div');
            petal.classList.add('petal');
            const size = Math.random() * 15 + 10;
            petal.style.width = `${size}px`; petal.style.height = `${size}px`;
            petal.style.left = `${Math.random() * 100}vw`;
            petal.style.animationDuration = `${Math.random() * 5 + 7}s`;
            container.appendChild(petal);
            setTimeout(() => petal.remove(), 12000);
        }
        setInterval(createPetal, 800);

        // Intersection Observer (Reveal on Scroll)
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
        }, { threshold: 0.15 });
        reveals.forEach(el => observer.observe(el));

        // Music Logic
        const audio = document.getElementById('bg-music');
        let isMusicPlaying = false;
        function toggleMusic(forcePlay = false) {
            const icon = document.getElementById('icon-music');
            if (isMusicPlaying && !forcePlay) {
                audio.pause(); isMusicPlaying = false;
                icon.classList.remove('fa-music', 'animate-spin-slow'); icon.classList.add('fa-volume-xmark');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.remove('fa-volume-xmark'); icon.classList.add('fa-music', 'animate-spin-slow');
                }).catch(() => { });
            }
        }

        // Auto Scroll
        let isAutoScrolling = false; let scrollInterval;
        function toggleAutoScroll(forceStart = false) {
            const icon = document.getElementById('icon-scroll');
            if (isAutoScrolling && !forceStart) {
                clearInterval(scrollInterval); isAutoScrolling = false;
                icon.classList.remove('fa-pause'); icon.classList.add('fa-angles-down');
            } else {
                isAutoScrolling = true;
                icon.classList.remove('fa-angles-down'); icon.classList.add('fa-pause');
                scrollInterval = setInterval(() => {
                    window.scrollBy({ top: 1, behavior: 'auto' });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 5) toggleAutoScroll();
                }, 30);
            }
        }
        window.addEventListener('wheel', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });
        window.addEventListener('touchmove', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });

        // Auto Open RSVP at Bottom
        let hasShownRSVPAtEnd = false;
        window.addEventListener('scroll', () => {
            const coverPage = document.getElementById('cover-page');
            if (coverPage && !coverPage.classList.contains('-translate-y-full')) {
                return;
            }
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 150) {
                if (!hasShownRSVPAtEnd) {
                    openRSVP();
                    hasShownRSVPAtEnd = true;
                    if (isAutoScrolling) toggleAutoScroll();
                }
            }
        }, { passive: true });

        // RSVP Handlers
        function openRSVP() {
            document.getElementById('rsvp-modal').classList.remove('invisible');
            setTimeout(() => {
                document.getElementById('rsvp-overlay').classList.replace('opacity-0', 'opacity-100');
                document.getElementById('rsvp-content').classList.replace('translate-y-full', 'translate-y-0');
            }, 10);
        }
        function closeRSVP() {
            document.getElementById('rsvp-overlay').classList.replace('opacity-100', 'opacity-0');
            document.getElementById('rsvp-content').classList.replace('translate-y-0', 'translate-y-full');
            setTimeout(() => document.getElementById('rsvp-modal').classList.add('invisible'), 500);
        }
        function selectAttendance(status) {
            document.getElementById('input-status').value = status;
            const btnHadir = document.getElementById('btn-hadir'); const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');

            btnHadir.classList.remove('bg-primary', 'text-white', 'border-primary'); btnAbsen.classList.remove('bg-primary', 'text-white', 'border-primary');
            btnHadir.classList.add('bg-white', 'text-secondary', 'border-borderLight'); btnAbsen.classList.add('bg-white', 'text-secondary', 'border-borderLight');

            if (status === 'Hadir') {
                btnHadir.classList.add('bg-primary', 'text-white', 'border-primary'); btnHadir.classList.remove('bg-white', 'text-secondary', 'border-borderLight');
                guestDiv.classList.remove('hidden');
                document.getElementById('input-guest-count').value = 1;
                setGuestCount(1);
            } else {
                btnAbsen.classList.add('bg-primary', 'text-white', 'border-primary'); btnAbsen.classList.remove('bg-white', 'text-secondary', 'border-borderLight');
                guestDiv.classList.add('hidden');
                document.getElementById('input-guest-count').value = 0;
            }
        }
        function setGuestCount(count) {
            const customContainer = document.getElementById('custom-pax-container');
            const customInput = document.getElementById('custom-pax-input');
            const hiddenInputCount = document.getElementById('input-guest-count');

            if (count === 'custom') {
                customContainer.classList.remove('hidden');
                hiddenInputCount.value = customInput.value || 3;
                customInput.focus();
            } else {
                customContainer.classList.add('hidden');
                hiddenInputCount.value = count;
            }

            document.querySelectorAll('.guest-btn').forEach(btn => {
                if (btn.innerText == count || (count === 'custom' && btn.innerText === '3+')) {
                    btn.className = 'guest-btn flex-1 py-3 bg-secondary text-white border border-borderLight rounded-full font-bold text-xs shadow-sm transition-colors';
                } else {
                    btn.className = 'guest-btn flex-1 py-3 bg-white border border-borderLight rounded-full font-bold text-xs text-secondary hover:text-primary shadow-sm transition-colors';
                }
            });
        }
        document.getElementById('custom-pax-input').addEventListener('input', function() {
            document.getElementById('input-guest-count').value = this.value || 3;
        });

        // Copy Clipboard
        function copyToClipboard(id, btn) {
            const text = document.getElementById(id).innerText;
            const toast = document.getElementById('copy-toast');
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = 'Tersalin!';
                toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                }, 2000);
            });
        }

        // Live Streaming Platform Switch
        function switchPlatform(id, title, desc, iconClass, link) {
            document.getElementById('platform-title').innerText = title;
            document.getElementById('platform-desc').innerText = desc;
            document.getElementById('platform-icon').className = iconClass + ' text-7xl text-secondary mb-6 animate-pulse';
            document.getElementById('platform-link').href = link;
        }

        // Gallery Lightbox
        const images = [
            @if (isset($invitation->galleries))
                @foreach ($invitation->galleries as $gallery)
                    "{{ asset('storage/' . $gallery->file_path) }}",
                @endforeach
            @endif
        ];
        let curImg = 0;
        function openLightbox(idx) {
            curImg = idx; updateLB();
            document.getElementById('lightbox').classList.remove('hidden');
            document.getElementById('lightbox').classList.add('flex');
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.getElementById('lightbox').classList.remove('flex');
        }
        function updateLB() {
            const img = document.getElementById('lightbox-img');
            img.style.opacity = '0';
            setTimeout(() => { img.src = images[curImg]; img.style.opacity = '1'; }, 200);
            document.getElementById('current-count').innerText = curImg + 1;
            document.getElementById('total-count').innerText = images.length;
        }
        function nextImg() { curImg = (curImg + 1) % images.length; updateLB(); }
        function prevImg() { curImg = (curImg - 1 + images.length) % images.length; updateLB(); }

        // Wishes API Integration
        let allWishes = [
            @foreach ($dbWishes as $wish)
                {
                    nama: "{{ addslashes($wish->guest_name) }}",
                    pesan: "{{ preg_replace("/\r|\n/", ' ', addslashes($wish->message)) }}",
                    waktu: "{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}",
                    status: "{{ $wish->status_rsvp }}"
                },
            @endforeach
        ];

        let countAttendance = {{ $totalAttendance }};
        let countWishes = {{ $totalWishes }};

        function renderWishes() {
            const container = document.getElementById('wishes-container');
            if (!container) return;

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
                card.className = 'bg-bg border border-borderLight p-6 rounded-3xl flex gap-4 text-left';
                card.innerHTML = `
                    <div class="w-10 h-10 bg-primary/10 border border-primary/20 rounded-full shrink-0 flex items-center justify-center text-primary font-bold">
                        ${wish.nama.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="font-bold text-primary text-sm">${wish.nama}</h5>
                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 border border-borderLight bg-white rounded-full text-secondary">${wish.status}</span>
                        </div>
                        <p class="text-xs text-secondary leading-relaxed font-light">${wish.pesan}</p>
                        <p class="text-[8px] text-gray-400 mt-2"><i class="fa-regular fa-clock mr-1"></i> ${wish.waktu}</p>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        document.getElementById('form-rsvp').onsubmit = function(e) {
            e.preventDefault();
            const statusVal = document.getElementById('input-status').value;
            const statusRsvp = statusVal === 'Hadir' ? 'hadir' : 'tidak_hadir';
            const paxVal = statusRsvp === 'hadir' ? parseInt(document.getElementById('input-guest-count').value) : 0;

            const data = {
                guest_name: document.getElementById('input-nama-rsvp').value || 'Tamu Undangan',
                status_rsvp: statusRsvp,
                pax: paxVal,
                message: document.getElementById('input-pesan-rsvp').value || 'Selamat Berbahagia!'
            };

            sendRsvpData(data);
        };

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
                    allWishes.unshift({
                        nama: data.guest_name,
                        pesan: data.message,
                        waktu: "Baru saja",
                        status: data.status_rsvp
                    });
                    countWishes++;
                    if (data.status_rsvp === 'hadir') countAttendance += parseInt(data.pax);

                    document.getElementById('total-wishes').innerText = countWishes;
                    document.getElementById('total-attendance').innerText = countAttendance;
                    renderWishes();

                    const modal = document.getElementById('rsvp-modal');
                    modal.innerHTML += `
                        <div id="temp-success" class="absolute inset-0 z-50 flex items-center justify-center bg-primary/95 backdrop-blur-sm">
                            <div class="text-center">
                                <i class="fa-solid fa-check-circle text-6xl text-white mb-4 animate-pulse"></i>
                                <h3 class="text-2xl font-serif text-white mb-2 animate-bounce">Terima Kasih</h3>
                                <p class="text-gray-300 text-sm">Konfirmasi Anda telah kami terima.</p>
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

        document.addEventListener('DOMContentLoaded', () => {
            renderWishes();
        });
    </script>
</body>

</html>
