<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invitation->slug }} - The Elegant Wedding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scroll-custom::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-custom::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-custom::-webkit-scrollbar-thumb {
            background: #C5A880;
            border-radius: 10px;
        }

        .modal-open {
            overflow: hidden !important;
        }

        @keyframes slide-up {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            animation: slide-up 0.5s ease-out forwards;
        }

        @media (max-width: 360px) {
            .text-8xl {
                font-size: 3.5rem;
            }

            .text-5xl {
                font-size: 2.25rem;
            }
        }

        ::-webkit-scrollbar {
            width: 6px;
            background: #FDFBF7;
        }

        ::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #C5A880;
        }

        body {
            background-color: #FDFBF7;
            color: #2D3748;
            overflow-y: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .vignette {
            background: radial-gradient(circle at center, transparent 0%, rgba(253, 251, 247, 0.4) 100%);
        }

        .bottom-gradient {
            background: linear-gradient(to top, #FDFBF7 0%, transparent 100%);
        }

        .text-shadow {
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
        }

        .episode-card:hover .episode-img {
            transform: scale(1.03);
        }

        .arch-image {
            border-radius: 100px 100px 0 0;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#FDFBF7',
                        card: '#FFFFFF',
                        primary: '#2D3748',
                        secondary: '#718096',
                        accent: '#C5A880',
                        accentHover: '#B0946D',
                        borderLight: '#E2E8F0'
                    },
                    fontFamily: {
                        sans: ['"Montserrat"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-bg text-primary font-sans selection:bg-accent selection:text-white relative">

    @php
        // LOGIKA PENYESUAIAN DATA DARI CONTROLLER
        $order = $content['couple_order'] ?? 'groom_first';
        $gNickname = $content['groom_nickname'] ?? 'Romeo';
        $bNickname = $content['bride_nickname'] ?? 'Juliet';
        $gFullName = $content['groom_name'] ?? 'Romeo Montague';
        $bFullName = $content['bride_name'] ?? 'Juliet Capulet';

        $name1 = $order === 'groom_first' ? $gNickname : $bNickname;
        $name2 = $order === 'groom_first' ? $bNickname : $gNickname;
        $titleNames = $name1 . ' & ' . $name2;
        $initials = substr($name1, 0, 1) . ' & ' . substr($name2, 0, 1);

        $coverImage = !empty($content['cover_image'])
            ? Storage::url($content['cover_image'])
            : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2000&auto=format&fit=crop';
        $groomPhoto = !empty($content['groom_photo'])
            ? Storage::url($content['groom_photo'])
            : 'https://images.soco.id/230-58.jpg.jpeg';
        $bridePhoto = !empty($content['bride_photo'])
            ? Storage::url($content['bride_photo'])
            : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg';

        // Penamaan Tamu
        $guestSlug = request()->query('to');
        $tamuName = $guestData->name ?? ($guestSlug ? urldecode(str_replace(['+', '-'], ' ', $guestSlug)) : 'Tamu Undangan');
    @endphp

    @if (isset($invitation->music) && $invitation->music)
        <audio id="bg-music" loop>
            <source src="{{ Storage::url($invitation->music->file_path) }}" type="audio/mpeg">
        </audio>
    @else
        <audio id="bg-music" loop>
            <source src="https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3" type="audio/mpeg">
        </audio>
    @endif

    {{-- COVER PAGE --}}
    <div id="cover-page"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center w-screen h-screen bg-bg transition-all duration-700">
        <div class="absolute inset-0 z-0">
            <img src="{{ $coverImage }}" class="w-full h-full object-cover opacity-20" alt="Cover">
            <div class="absolute inset-0 bg-gradient-to-t from-bg via-bg/80 to-transparent"></div>
        </div>

        <div class="absolute top-0 w-full p-8 flex justify-center md:justify-between items-center z-10">
            <h1 class="font-serif text-accent text-2xl md:text-3xl tracking-[0.2em] uppercase">{{ $titleNames }}</h1>
        </div>

        <div
            class="flex flex-col items-center justify-center transform transition-transform duration-500 w-full max-w-md px-6 z-10">
            <h2 class="text-2xl md:text-3xl font-serif text-primary mb-10 text-center italic">
                {{ $content['cover_greeting'] ?? 'Welcome to the Celebration' }}</h2>

            <div class="flex flex-wrap justify-center gap-6 md:gap-10">
                <button onclick="openInvitation()"
                    class="group flex flex-col items-center gap-5 transition-transform hover:scale-105">
                    <div
                        class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden border border-borderLight group-hover:border-accent group-hover:shadow-xl transition-all relative p-1 bg-white">
                        <div
                            class="w-full h-full rounded-full flex items-center justify-center bg-bg text-accent font-serif text-3xl">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                    </div>
                    <span id="guest-name"
                        class="text-secondary group-hover:text-accent transition-colors text-sm md:text-base font-bold tracking-wide uppercase">{{ $tamuName }}</span>
                </button>
            </div>

            <button onclick="openInvitation()"
                class="mt-16 px-8 py-3 bg-white/50 backdrop-blur border border-accent text-accent hover:bg-accent hover:text-white uppercase tracking-[0.2em] text-xs font-semibold transition-all rounded-sm shadow-sm">
                Buka Undangan
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main id="main-content" class="min-h-screen opacity-0 transition-opacity duration-1000 pb-20">

        <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-borderLight p-5 flex justify-between items-center transition-all duration-300"
            id="main-nav">
            <h1 class="font-serif text-accent text-xl md:text-2xl tracking-[0.2em] uppercase">{{ $initials }}</h1>
            <div class="flex items-center gap-4">
                <a href="javascript:void(0)" onclick="openRSVP()"><i
                        class="fa-solid fa-envelope text-accent cursor-pointer hover:text-accentHover transition-colors text-lg"></i></a>
            </div>
        </nav>

        {{-- HERO SECTION --}}
        <section id="home"
            class="relative h-[85vh] md:h-screen flex items-center justify-center md:justify-start px-6 md:px-20 pt-20">
            <div class="absolute inset-0 z-0">
                <img src="{{ $coverImage }}" class="w-full h-full object-cover opacity-60" alt="Hero">
                <div class="absolute inset-0 bg-white/40"></div>
                <div class="absolute inset-x-0 bottom-0 h-2/3 bottom-gradient"></div>
            </div>

            <div class="relative z-10 max-w-2xl mt-12 md:mt-0 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-6">
                    <span class="w-10 h-px bg-accent"></span>
                    <span class="text-xs font-semibold tracking-[0.3em] text-accent uppercase">Exclusive
                        Celebration</span>
                </div>

                <h2 class="text-5xl md:text-7xl font-serif text-primary mb-6 leading-tight">
                    {{ $name1 }} <span class="text-accent italic font-light">&</span> {{ $name2 }}
                </h2>

                @if (!empty($content['akad_date']))
                    <div
                        class="flex items-center justify-center md:justify-start gap-4 text-xs md:text-sm font-medium mb-8 text-secondary">
                        <span class="tracking-widest uppercase">Save The Date</span>
                        <span class="w-1 h-1 rounded-full bg-accent"></span>
                        <span
                            class="tracking-widest">{{ \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('d M Y') }}</span>
                    </div>
                @endif

                <p
                    class="text-sm md:text-base text-secondary leading-relaxed mb-10 max-w-lg mx-auto md:mx-0 font-light">
                    {{ $content['quotes'] ?? 'Dengan memohon rahmat Tuhan, dua pemeran utama kami bersiap mengikat janji suci. Jadilah saksi di hari paling bahagia mereka.' }}
                </p>
            </div>
        </section>

        {{-- COUPLE SECTION --}}
        <section id="cast" class="px-6 md:px-20 py-16 relative z-10 bg-bg">
            <div class="text-center mb-12">
                <h3 class="text-3xl md:text-4xl font-serif text-primary mb-3">The Couple</h3>
                <div class="w-16 h-px bg-accent mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20 max-w-5xl mx-auto">
                @php
                    $p1Type = $order === 'groom_first' ? 'The Groom' : 'The Bride';
                    $p1Name = $order === 'groom_first' ? $gFullName : $bFullName;
                    $p1Father =
                        $order === 'groom_first' ? $content['groom_father'] ?? '' : $content['bride_father'] ?? '';
                    $p1Mother =
                        $order === 'groom_first' ? $content['groom_mother'] ?? '' : $content['bride_mother'] ?? '';
                    $p1Ig = $order === 'groom_first' ? $content['groom_ig'] ?? '' : $content['bride_ig'] ?? '';
                    $p1Photo = $order === 'groom_first' ? $groomPhoto : $bridePhoto;

                    $p2Type = $order === 'groom_first' ? 'The Bride' : 'The Groom';
                    $p2Name = $order === 'groom_first' ? $bFullName : $gFullName;
                    $p2Father =
                        $order === 'groom_first' ? $content['bride_father'] ?? '' : $content['groom_father'] ?? '';
                    $p2Mother =
                        $order === 'groom_first' ? $content['bride_mother'] ?? '' : $content['groom_mother'] ?? '';
                    $p2Ig = $order === 'groom_first' ? $content['bride_ig'] ?? '' : $content['groom_ig'] ?? '';
                    $p2Photo = $order === 'groom_first' ? $bridePhoto : $groomPhoto;
                @endphp

                {{-- Person 1 --}}
                <div class="flex flex-col md:flex-row gap-8 items-center text-center md:text-left group">
                    <div
                        class="w-48 h-64 md:w-44 md:h-60 shrink-0 arch-image overflow-hidden relative border-4 border-white shadow-xl">
                        <img src="{{ $p1Photo }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>
                    <div>
                        <p class="text-accent text-xs uppercase tracking-[0.2em] mb-2 font-bold">{{ $p1Type }}
                        </p>
                        <h4 class="text-3xl font-serif text-primary mb-3">{{ $p1Name }}</h4>
                        @if ($p1Father || $p1Mother)
                            <p class="text-sm text-secondary font-light leading-relaxed mb-3">Putra/i dari<br>Bapak
                                {{ $p1Father }} & Ibu {{ $p1Mother }}</p>
                        @endif
                        @if ($p1Ig)
                            <a href="https://instagram.com/{{ str_replace('@', '', $p1Ig) }}" target="_blank"
                                class="inline-flex items-center gap-2 text-xs font-semibold text-accent hover:text-primary transition-colors"><i
                                    class="fa-brands fa-instagram"></i> {{ $p1Ig }}</a>
                        @endif
                    </div>
                </div>

                {{-- Person 2 --}}
                <div class="flex flex-col md:flex-row-reverse gap-8 items-center text-center md:text-right group">
                    <div
                        class="w-48 h-64 md:w-44 md:h-60 shrink-0 arch-image overflow-hidden relative border-4 border-white shadow-xl">
                        <img src="{{ $p2Photo }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>
                    <div>
                        <p class="text-accent text-xs uppercase tracking-[0.2em] mb-2 font-bold">{{ $p2Type }}
                        </p>
                        <h4 class="text-3xl font-serif text-primary mb-3">{{ $p2Name }}</h4>
                        @if ($p2Father || $p2Mother)
                            <p class="text-sm text-secondary font-light leading-relaxed mb-3">Putra/i dari<br>Bapak
                                {{ $p2Father }} & Ibu {{ $p2Mother }}</p>
                        @endif
                        @if ($p2Ig)
                            <a href="https://instagram.com/{{ str_replace('@', '', $p2Ig) }}" target="_blank"
                                class="inline-flex items-center gap-2 text-xs font-semibold text-accent hover:text-primary transition-colors justify-end"><i
                                    class="fa-brands fa-instagram"></i> {{ $p2Ig }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- OUR STORY --}}
        @if (!empty($content['is_story_active']) && !empty($content['love_stories']))
            <section id="cerita" class="px-6 md:px-20 py-20 bg-card border-y border-borderLight mt-8">
                <div class="text-center mb-16">
                    <h3 class="text-3xl md:text-4xl font-serif text-primary mb-3">The Journey</h3>
                    <span class="text-accent text-xs uppercase tracking-[0.2em] font-medium block">Our Story</span>
                    <div class="w-16 h-px bg-accent mx-auto mt-4"></div>
                </div>

                <div class="space-y-8 max-w-4xl mx-auto">
                    @foreach ($content['love_stories'] as $index => $story)
                        <div
                            class="episode-card flex flex-col md:flex-row gap-6 md:gap-10 items-start md:items-center p-6 bg-bg hover:bg-white rounded-xl border border-borderLight hover:shadow-xl transition-all cursor-pointer">
                            <h4 class="text-5xl font-serif text-borderLight hidden md:block italic">
                                0{{ $index + 1 }}</h4>
                            @if (!empty($story['image']))
                                <div
                                    class="w-full md:w-56 h-36 shrink-0 rounded-lg overflow-hidden relative episode-img-container shadow-md">
                                    <img src="{{ Storage::url($story['image']) }}"
                                        class="episode-img w-full h-full object-cover transition-transform duration-700">
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-3">
                                    <h5 class="font-serif text-xl text-primary">{{ $story['title'] }}</h5>
                                    <span
                                        class="text-xs text-accent font-semibold tracking-widest uppercase">{{ $story['year'] }}</span>
                                </div>
                                <p class="text-sm text-secondary font-light leading-relaxed">
                                    {{ $story['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- GALLERY SECTION --}}
        @if (!empty($content['is_gallery_active']))
            <section id="gallery" class="py-20 bg-bg">
                <div class="px-6 md:px-20 text-center mb-12">
                    <h3 class="text-3xl md:text-4xl font-serif text-primary mb-3">Gallery</h3>
                    <div class="w-16 h-px bg-accent mx-auto"></div>
                </div>

                @if (!empty($content['youtube_links'][0]))
                    @php
                        // Convert normal YT link to embed format
                        $ytLink = $content['youtube_links'][0];
                        preg_match(
                            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i',
                            $ytLink,
                            $match,
                        );
                        $embedId = $match[1] ?? '';
                    @endphp
                    @if ($embedId)
                        <div class="px-6 md:px-20 mb-16 max-w-5xl mx-auto">
                            <p class="text-xs font-semibold text-accent uppercase tracking-[0.2em] mb-4 text-center">
                                Our Moments</p>
                            <div
                                class="relative w-full aspect-video rounded-xl overflow-hidden bg-card shadow-xl border border-borderLight p-2 group">
                                <div class="w-full h-full rounded-lg overflow-hidden relative">
                                    <iframe class="absolute inset-0 w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $embedId }}?rel=0" frameborder="0"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if ($invitation->galleries->count() > 0)
                    <div class="relative group">
                        <div
                            class="flex overflow-x-auto gap-6 px-6 md:px-20 pb-12 snap-x snap-mandatory hide-scrollbar scroll-smooth">
                            @foreach ($invitation->galleries as $index => $gallery)
                                <div class="w-[75vw] md:w-[30vw] shrink-0 snap-start group/card cursor-pointer"
                                    onclick="openLightbox({{ $index }})">
                                    <div
                                        class="relative aspect-[4/5] rounded-xl overflow-hidden shadow-md border border-borderLight">
                                        <img src="{{ Storage::url($gallery->file_path) }}"
                                            class="gallery-img w-full h-full object-cover transition-transform duration-700 group-hover/card:scale-105">
                                        <div
                                            class="absolute inset-0 bg-white/20 opacity-0 group-hover/card:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                            <i
                                                class="fa-solid fa-expand text-primary text-3xl bg-white/80 w-12 h-12 rounded-full flex items-center justify-center shadow-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
        @endif

        {{-- EVENTS SECTION --}}
        @if (!empty($content['is_event_active']))
            <section id="lokasi" class="px-6 md:px-20 py-24 bg-card border-y border-borderLight">
                <div class="text-center mb-16">
                    <h3 class="text-3xl md:text-4xl font-serif text-primary mb-3">Event Venue</h3>
                    <div class="w-16 h-px bg-accent mx-auto"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    {{-- Akad --}}
                    @if (!empty($content['akad_date']))
                        <div
                            class="bg-bg p-10 rounded-2xl border border-borderLight hover:border-accent transition-all shadow-sm hover:shadow-xl group">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <span
                                        class="text-accent text-xs font-semibold uppercase tracking-[0.2em] block mb-2">The
                                        Vow</span>
                                    <h4 class="text-3xl font-serif text-primary">Akad Nikah</h4>
                                </div>
                                <div
                                    class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-accent group-hover:bg-accent group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-ring text-xl"></i>
                                </div>
                            </div>

                            <ul class="space-y-5 mb-10 text-sm text-secondary font-medium">
                                <li class="flex items-center gap-4"><i
                                        class="fa-regular fa-calendar w-5 text-accent"></i>
                                    {{ \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') }}
                                </li>
                                <li class="flex items-center gap-4"><i
                                        class="fa-regular fa-clock w-5 text-accent"></i>
                                    {{ $content['akad_time'] }} - {{ $content['akad_time_end'] ?? 'Selesai' }}</li>
                                <li class="flex items-start gap-4"><i
                                        class="fa-solid fa-location-dot w-5 mt-1 text-accent"></i>
                                    <span>{{ $content['akad_location'] ?? 'Lokasi Akad' }}<br><span
                                            class="text-gray-400 text-xs font-light mt-1 block">{{ $content['akad_address'] ?? '' }}</span></span>
                                </li>
                            </ul>

                            @if (!empty($content['akad_map']))
                                <a href="{{ $content['akad_map'] }}" target="_blank"
                                    class="block w-full py-4 bg-white border border-borderLight hover:border-accent text-primary hover:text-accent text-center rounded-sm text-xs font-bold tracking-[0.1em] uppercase transition-all shadow-sm hover:shadow-md">
                                    <i class="fa-solid fa-map-location-dot mr-2"></i> Get Directions
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Resepsi Dinamis --}}
                    @if (!empty($content['events']))
                        @foreach ($content['events'] as $event)
                            <div
                                class="bg-bg p-10 rounded-2xl border border-borderLight hover:border-accent transition-all shadow-sm hover:shadow-xl group">
                                <div class="flex justify-between items-start mb-8">
                                    <div>
                                        <span
                                            class="text-accent text-xs font-semibold uppercase tracking-[0.2em] block mb-2">The
                                            Celebration</span>
                                        <h4 class="text-3xl font-serif text-primary">
                                            {{ $event['title'] ?? 'Resepsi' }}</h4>
                                    </div>
                                    <div
                                        class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-accent group-hover:bg-accent group-hover:text-white transition-colors">
                                        <i class="fa-solid fa-champagne-glasses text-xl"></i>
                                    </div>
                                </div>

                                <ul class="space-y-5 mb-10 text-sm text-secondary font-medium">
                                    <li class="flex items-center gap-4"><i
                                            class="fa-regular fa-calendar w-5 text-accent"></i>
                                        {{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}</li>
                                    <li class="flex items-center gap-4"><i
                                            class="fa-regular fa-clock w-5 text-accent"></i>
                                        {{ $event['time'] }} - {{ $event['time_end'] ?? 'Selesai' }}</li>
                                    <li class="flex items-start gap-4"><i
                                            class="fa-solid fa-location-dot w-5 mt-1 text-accent"></i>
                                        <span>{{ $event['location'] ?? '' }}<br><span
                                                class="text-gray-400 text-xs font-light mt-1 block">{{ $event['address'] ?? '' }}</span></span>
                                    </li>
                                </ul>

                                @if (!empty($event['map']))
                                    <a href="{{ $event['map'] }}" target="_blank"
                                        class="block w-full py-4 bg-accent hover:bg-accentHover text-white text-center rounded-sm text-xs font-bold tracking-[0.1em] uppercase transition-all shadow-md hover:shadow-lg">
                                        <i class="fa-solid fa-map-location-dot mr-2"></i> Get Directions
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
        @endif

        {{-- LIVE STREAMING --}}
        @if (!empty($content['is_livestream_active']) && !empty($content['live_streams']))
            <section id="live-streaming"
                class="py-24 px-6 bg-bg relative overflow-hidden border-b border-borderLight">
                <div class="max-w-5xl mx-auto relative z-10">
                    <div class="text-center mb-16">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
                            </span>
                            <span class="text-xs font-semibold uppercase tracking-[0.2em] text-accent">Virtual
                                Wedding</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-serif text-primary mb-4">Siaran Langsung</h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                        @foreach ($content['live_streams'] as $stream)
                            @php
                                $icon = 'fa-link';
                                $label = 'Streaming';
                                if ($stream['platform'] == 'youtube') {
                                    $icon = 'fa-youtube';
                                    $label = 'YouTube Live';
                                }
                                if ($stream['platform'] == 'instagram') {
                                    $icon = 'fa-instagram';
                                    $label = 'IG Live';
                                }
                                if ($stream['platform'] == 'tiktok') {
                                    $icon = 'fa-tiktok';
                                    $label = 'TikTok Live';
                                }
                                if ($stream['platform'] == 'zoom') {
                                    $icon = 'fa-video';
                                    $label = 'Zoom Meeting';
                                }
                            @endphp
                            <a href="{{ $stream['link'] }}" target="_blank"
                                class="platform-btn flex flex-col items-center justify-center bg-card border border-borderLight hover:border-accent p-6 rounded-xl transition-all group text-center shadow-sm hover:shadow-md">
                                <i
                                    class="fa-brands {{ $icon }} text-4xl text-secondary group-hover:text-accent mb-4 transition-colors"></i>
                                <span
                                    class="text-primary font-bold text-xs uppercase tracking-wider">{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- WISHES & RSVP SECTION --}}
        @if (!empty($content['is_wishes_active']))
            <section id="guest-stats" class="py-24 px-6 bg-card border-y border-borderLight relative overflow-hidden">
                <div class="max-w-5xl mx-auto relative z-10">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-serif text-primary mb-4">Kehadiran & Doa</h2>
                        <div class="flex items-center justify-center gap-3">
                            <span class="w-8 h-px bg-accent"></span>
                            <p class="text-accent text-xs font-semibold uppercase tracking-[0.2em]">Guest Registry</p>
                            <span class="w-8 h-px bg-accent"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-16 max-w-3xl mx-auto">
                        <div
                            class="flex flex-col items-center p-8 bg-bg border border-borderLight rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-xs uppercase tracking-[0.2em] text-secondary font-semibold mb-4">Tamu Hadir
                            </p>
                            <h4 id="total-attendance" class="text-5xl md:text-6xl font-serif text-primary mb-2">
                                {{ $totalAttendance ?? 0 }}</h4>
                            <div class="w-12 h-1 bg-accent rounded-full mt-2"></div>
                        </div>
                        <div
                            class="flex flex-col items-center p-8 bg-bg border border-borderLight rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <p class="text-xs uppercase tracking-[0.2em] text-secondary font-semibold mb-4">Ucapan Doa
                            </p>
                            <h4 id="total-wishes" class="text-5xl md:text-6xl font-serif text-primary mb-2">
                                {{ $totalWishes ?? 0 }}</h4>
                            <div class="w-12 h-1 bg-accent rounded-full mt-2"></div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl border border-borderLight shadow-xl max-w-4xl mx-auto overflow-hidden">
                        <div class="flex items-center justify-between p-6 border-b border-borderLight bg-bg">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-envelope-open-text text-accent text-xl"></i>
                                <span class="text-sm font-semibold uppercase tracking-[0.1em] text-primary">Wishes
                                    Wall</span>
                            </div>
                        </div>
                        <div id="wishes-container" class="max-h-[500px] overflow-y-auto scroll-custom p-6 space-y-4">
                            @if (isset($dbWishes) && $dbWishes->count())
                                @foreach ($dbWishes as $wish)
                                    <div
                                        class="flex flex-col md:flex-row gap-4 p-5 rounded-2xl bg-white/70 backdrop-blur-sm border border-borderLight hover:border-accent/40 hover:shadow-lg transition-all duration-300">

                                        {{-- Avatar --}}
                                        <div
                                            class="w-14 h-14 md:w-16 md:h-16 rounded-full shrink-0 flex items-center justify-center bg-gradient-to-br from-pink-100 to-rose-100 border border-pink-200 shadow-sm">
                                            <i class="fa-solid fa-heart text-accent text-xl"></i>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1">
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                                <div>
                                                    <h5 class="text-sm md:text-base font-semibold text-primary">
                                                        {{ $wish->guest_name }}
                                                    </h5>

                                                    <p class="text-[11px] text-secondary mt-1">
                                                        {{ \Carbon\Carbon::parse($wish->created_at)->translatedFormat('d F Y • H:i') }}
                                                        WIB
                                                    </p>
                                                </div>

                                                {{-- RSVP Badge --}}
                                                @if ($wish->status_rsvp === 'hadir')
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-semibold uppercase tracking-wider w-fit">
                                                        <i class="fa-solid fa-circle-check mr-1"></i>
                                                        Hadir • {{ $wish->pax ?? 1 }} Orang
                                                    </span>
                                                @elseif($wish->status_rsvp === 'tidak_hadir')
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-semibold uppercase tracking-wider w-fit">
                                                        <i class="fa-solid fa-circle-xmark mr-1"></i>
                                                        Tidak Hadir
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Message --}}
                                            <p class="text-sm text-secondary leading-relaxed break-words">
                                                {{ $wish->message }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center py-14 text-center">
                                    <div
                                        class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class="fa-regular fa-comment-dots text-3xl text-gray-400"></i>
                                    </div>

                                    <h4 class="text-sm font-semibold text-primary mb-1">
                                        Belum Ada Ucapan
                                    </h4>

                                    <p class="text-xs text-secondary max-w-xs leading-relaxed">
                                        Belum ada doa & ucapan yang masuk. Jadilah yang pertama memberikan ucapan
                                        terbaik ✨
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- GIFTS SECTION --}}
        @if (!empty($content['is_gift_active']))
            <section id="hadiah" class="py-24 px-6 bg-bg relative overflow-hidden">
                <div class="max-w-5xl mx-auto relative z-10">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-serif text-primary mb-6">Wedding Gift</h2>
                        <div class="w-16 h-px bg-accent mx-auto mb-6"></div>
                        <p class="text-sm md:text-base text-secondary font-light leading-relaxed max-w-2xl mx-auto">
                            Doa restu Anda adalah karunia terindah bagi kami. Namun jika Anda ingin memberikan tanda
                            kasih, pintu hati kami terbuka melalui jalur resmi berikut:
                        </p>
                    </div>

                    {{-- BANKS --}}
                    @if (!empty($content['banks']))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-12">
                            @foreach ($content['banks'] as $index => $bank)
                                <div
                                    class="group relative bg-white rounded-2xl overflow-hidden border border-borderLight transition-all duration-500 hover:border-accent hover:shadow-xl p-8 text-center">
                                    <div class="space-y-2 mb-8">
                                        <h4 class="text-lg font-bold text-accent uppercase tracking-widest">
                                            {{ $bank['name'] }}</h4>
                                        <h3 id="rek-{{ $index }}"
                                            class="text-2xl md:text-3xl font-sans font-bold text-primary tracking-widest">
                                            {{ $bank['account_number'] }}</h3>
                                        <p class="text-xs text-secondary font-medium uppercase tracking-widest">a.n
                                            {{ $bank['account_name'] ?? $name1 }}</p>
                                    </div>
                                    <button onclick="copyToClipboard('rek-{{ $index }}', this)"
                                        class="w-full py-3.5 bg-bg border border-borderLight text-primary rounded-sm font-semibold text-xs uppercase tracking-[0.1em] transition-all hover:bg-accent hover:text-white flex items-center justify-center gap-2">
                                        <i class="fa-regular fa-copy"></i> Salin Nomor
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- ALAMAT & BARANG --}}
                    @if (!empty($content['alamat_kado']) || (!empty($content['gifts']) && count($content['gifts']) > 0))
                        <div class="flex flex-col items-center gap-8 border-t border-borderLight pt-16">
                            <div
                                class="group relative p-10 bg-white rounded-2xl border border-borderLight max-w-2xl w-full shadow-sm text-center">
                                <div
                                    class="w-16 h-16 bg-bg rounded-full flex items-center justify-center mx-auto mb-6 border border-borderLight text-accent">
                                    <i class="fa-solid fa-box-open text-2xl"></i>
                                </div>
                                <p
                                    class="text-[10px] text-center uppercase tracking-[0.3em] text-secondary mb-4 font-semibold">
                                    Alamat Pengiriman / Kado Fisik</p>

                                @if (!empty($content['alamat_kado']))
                                    <div id="alamat-kado"
                                        class="text-sm md:text-base text-primary font-serif italic text-center leading-relaxed mb-10">
                                        {{ $content['alamat_kado'] }}
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row justify-center gap-4">
                                    @if (!empty($content['alamat_kado']))
                                        <button onclick="copyToClipboard('alamat-kado', this)"
                                            class="px-8 py-3.5 bg-bg text-primary border border-borderLight rounded-sm font-semibold text-[10px] uppercase tracking-widest transition-all hover:bg-slate-100 flex items-center justify-center gap-2">
                                            <i class="fa-regular fa-copy"></i> Salin Alamat
                                        </button>
                                    @endif

                                    @if (!empty($content['gifts']) && count($content['gifts']) > 0)
                                        <button onclick="toggleGiftModal(true)"
                                            class="px-8 py-3.5 bg-accent text-white rounded-sm font-semibold text-[10px] uppercase tracking-widest transition-all hover:bg-accentHover shadow-md flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-list"></i> Daftar Kebutuhan (Wishlist)
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- MODAL WISHLIST KADO --}}
                        @if (!empty($content['gifts']) && count($content['gifts']) > 0)
                            <div id="gift-modal"
                                class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                                <div class="absolute inset-0 bg-primary/40 backdrop-blur-sm"
                                    onclick="toggleGiftModal(false)"></div>
                                <div
                                    class="relative bg-white w-full max-w-lg rounded-2xl border border-borderLight overflow-hidden shadow-2xl flex flex-col max-h-[85vh] animate-slide-up">
                                    <div
                                        class="p-8 border-b border-borderLight bg-bg sticky top-0 z-20 flex justify-between items-center">
                                        <div>
                                            <h3 class="text-2xl font-serif text-primary">Wishlist Kami</h3>
                                            <p
                                                class="text-[10px] text-accent uppercase tracking-widest mt-1 font-semibold">
                                                Wedding Registry</p>
                                        </div>
                                        <button onclick="toggleGiftModal(false)"
                                            class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-borderLight text-secondary hover:text-accent shadow-sm">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div class="flex-1 overflow-y-auto p-6 custom-scrollbar space-y-4">
                                        @foreach ($content['gifts'] as $idx => $gift)
                                            <div id="item-{{ $idx }}"
                                                class="p-5 rounded-xl border border-borderLight bg-white flex flex-col sm:flex-row items-center justify-between gap-4 transition-all hover:shadow-md group">
                                                <div class="text-center sm:text-left w-full sm:w-auto">
                                                    <h4 class="text-sm font-bold text-primary tracking-wide">
                                                        {{ $gift['item_name'] }}</h4>
                                                    <p class="text-[10px] text-secondary font-medium mt-1">
                                                        {{ $gift['description'] ?? '-' }}</p>
                                                </div>
                                                <button
                                                    onclick="confirmGift('item-{{ $idx }}', '{{ addslashes($gift['item_name']) }}')"
                                                    class="w-full sm:w-auto shrink-0 px-5 py-2.5 bg-white border border-accent text-accent rounded-sm text-[10px] font-semibold uppercase tracking-widest hover:bg-accent hover:text-white transition-all">
                                                    Kirim Ini
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div id="gift-confirm-modal"
                                class="fixed inset-0 z-[550] hidden items-center justify-center p-4">
                                <div class="absolute inset-0 bg-primary/40 backdrop-blur-sm"
                                    onclick="closeGiftConfirmModal()"></div>
                                <div
                                    class="relative bg-white w-full max-w-md rounded-2xl border border-borderLight shadow-2xl overflow-hidden">
                                    <div
                                        class="p-6 border-b border-borderLight bg-bg flex justify-between items-center">
                                        <div>
                                            <h3 class="text-xl font-serif text-primary">Konfirmasi Kado</h3>
                                            <p class="text-[10px] text-secondary uppercase tracking-[0.2em] mt-1">
                                                Masukkan detail tamu untuk RSVP</p>
                                        </div>
                                        <button onclick="closeGiftConfirmModal()"
                                            class="w-10 h-10 rounded-full bg-white border border-borderLight text-secondary hover:text-accent transition-all">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <label
                                                class="text-[10px] uppercase tracking-widest text-secondary font-semibold">Nama
                                                Pengirim</label>
                                            <input id="gift-confirm-name" type="text"
                                                class="mt-2 w-full bg-bg border border-borderLight rounded-xl px-4 py-3 text-sm text-primary outline-none focus:border-accent"
                                                value="{{ $tamuName != 'Tamu Undangan' ? $tamuName : '' }}" />
                                        </div>
                                        
                                        <div>
                                            <label class="text-[10px] uppercase tracking-widest text-secondary font-semibold block mb-2">Status Kehadiran</label>
                                            <div class="grid grid-cols-2 gap-4">
                                                <button type="button" id="gift-btn-hadir" onclick="selectGiftAttendance('hadir')" class="py-3 rounded-sm border border-accent bg-accent text-white text-xs font-semibold uppercase tracking-widest transition-all">Hadir</button>
                                                <button type="button" id="gift-btn-absen" onclick="selectGiftAttendance('tidak_hadir')" class="py-3 rounded-sm border border-borderLight text-secondary bg-transparent text-xs font-semibold uppercase tracking-widest transition-all">Tidak Hadir</button>
                                            </div>
                                            <input type="hidden" id="gift-confirm-status" value="hadir">
                                        </div>

                                        <div id="gift-confirm-pax-wrapper" class="space-y-4">
                                            <div>
                                                <label class="text-[10px] uppercase tracking-widest text-secondary font-semibold block mb-2">Jumlah Orang (Hadir)</label>
                                                <div class="flex gap-2">
                                                    <button type="button" onclick="setGiftPax(1)" class="gift-pax-btn flex-1 py-2 rounded border border-accent bg-accent text-white text-sm font-semibold transition-all">1</button>
                                                    <button type="button" onclick="setGiftPax(2)" class="gift-pax-btn flex-1 py-2 rounded border border-borderLight bg-white text-secondary text-sm font-semibold transition-all">2</button>
                                                    <button type="button" onclick="setGiftPax(3)" class="gift-pax-btn flex-1 py-2 rounded border border-borderLight bg-white text-secondary text-sm font-semibold transition-all">3</button>
                                                    <button type="button" onclick="setGiftPax('custom')" class="gift-pax-btn flex-1 py-2 rounded border border-borderLight bg-white text-secondary text-sm font-semibold transition-all">3+</button>
                                                </div>
                                                <div id="gift-custom-pax-container" class="hidden mt-3">
                                                    <input type="number" id="gift-confirm-custom-pax-input" placeholder="Masukkan jumlah spesifik (Misal: 4)" class="w-full bg-white border border-borderLight focus:border-accent p-3 rounded-xl text-center text-sm font-bold outline-none" min="4">
                                                </div>
                                            </div>

                                            <div class="bg-bg p-4 rounded-xl border border-borderLight text-center">
                                                <p class="text-[10px] text-secondary font-semibold uppercase tracking-widest mb-1">Total</p>
                                                <p id="gift-pax-display" class="text-xl font-serif text-primary">1 Orang</p>
                                            </div>
                                            <input type="hidden" id="gift-confirm-pax" value="1">
                                        </div>

                                        <div>
                                            <label
                                                class="text-[10px] uppercase tracking-widest text-secondary font-semibold">Kado</label>
                                            <p id="gift-confirm-title"
                                                class="mt-2 text-sm text-primary font-semibold"></p>
                                        </div>
                                        <button onclick="submitGiftConfirm()"
                                            class="w-full py-4 bg-accent text-white rounded-xl font-semibold uppercase tracking-[0.2em] text-xs hover:bg-accentHover transition-all">Kirim
                                            RSVP Kado</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <div id="copy-toast"
                    class="fixed bottom-12 left-1/2 -translate-x-1/2 z-[300] px-8 py-3 bg-accent text-white text-xs rounded-sm shadow-xl font-semibold uppercase tracking-widest opacity-0 translate-y-10 transition-all duration-500 pointer-events-none flex items-center gap-3">
                    <i class="fa-solid fa-check"></i> Tersalin ke Clipboard
                </div>
            </section>
        @endif

        {{-- SUCCESS MODAL --}}
        <div id="success-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-primary/40 backdrop-blur-sm"></div>
            <div
                class="relative bg-white w-full max-w-md rounded-2xl border border-borderLight shadow-2xl overflow-hidden animate-slide-up">
                <div class="p-8 text-center">
                    <div class="flex justify-center mb-6">
                        <div
                            class="w-16 h-16 rounded-full bg-accent/10 border border-accent flex items-center justify-center">
                            <i class="fa-solid fa-check text-accent text-3xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-serif text-primary mb-3">Sukses!</h3>
                    <p id="success-message" class="text-secondary text-sm leading-relaxed mb-8">RSVP Anda telah
                        tersimpan.</p>
                    <button onclick="closeSuccessModal()"
                        class="w-full py-4 bg-accent text-white rounded-xl font-semibold uppercase tracking-[0.2em] text-xs hover:bg-accentHover transition-all">Tutup</button>
                </div>
            </div>
        </div>

        {{-- GUEST INFO / PROTOCOL --}}
        @if (!empty($content['is_guest_info_active']))
            <section id="guest-info"
                class="py-24 px-6 md:px-20 bg-bg relative overflow-hidden border-t border-borderLight">
                <div class="max-w-6xl mx-auto relative z-10">
                    <div class="mb-16 text-center md:text-left">
                        <h2 class="text-3xl md:text-4xl font-serif text-primary mb-4">Informasi Tamu</h2>
                        <div class="w-16 h-px bg-accent mx-auto md:mx-0 mb-6"></div>
                        <p class="text-secondary font-light max-w-xl mx-auto md:mx-0">Hal-hal yang perlu diperhatikan
                            demi kenyamanan dan kekhidmatan acara.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <div class="lg:col-span-2 space-y-12">
                            @if (!empty($content['enable_dresscode']))
                                <div class="bg-white p-8 rounded-2xl border border-borderLight shadow-sm">
                                    <div class="flex items-center gap-3 mb-4">
                                        <i class="fa-solid fa-shirt text-accent text-xl"></i>
                                        <h4 class="text-primary uppercase tracking-[0.1em] text-sm font-semibold">
                                            Dresscode</h4>
                                    </div>
                                    <p class="text-secondary text-base font-light leading-relaxed">
                                        <span
                                            class="font-bold text-primary">{{ $content['dresscode'] ?? 'Formal & Elegant.' }}</span>
                                    </p>
                                </div>
                            @endif

                            @if (!empty($content['enable_health_protocol']) || !empty($content['enable_adab_walimah']))
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    @if (!empty($content['enable_health_protocol']))
                                        <div class="bg-white p-8 rounded-2xl border border-borderLight shadow-sm">
                                            <h5
                                                class="text-primary text-sm font-semibold uppercase tracking-widest mb-6 border-b border-borderLight pb-2">
                                                Protokol Kesehatan</h5>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                <div
                                                    class="flex flex-col items-center text-center gap-3 bg-white p-6 rounded-xl border border-borderLight shadow-sm">
                                                    <i class="fa-solid fa-hands-bubbles text-accent text-2xl"></i>
                                                    <span class="text-primary text-[10px] uppercase font-semibold">Cuci
                                                        Tangan</span>
                                                </div>
                                                <div
                                                    class="flex flex-col items-center text-center gap-3 bg-white p-6 rounded-xl border border-borderLight shadow-sm">
                                                    <i class="fa-solid fa-head-side-mask text-accent text-2xl"></i>
                                                    <span
                                                        class="text-primary text-[10px] uppercase font-semibold">Pakai
                                                        Masker</span>
                                                </div>
                                                <div
                                                    class="flex flex-col items-center text-center gap-3 bg-white p-6 rounded-xl border border-borderLight shadow-sm">
                                                    <i class="fa-solid fa-people-arrows text-accent text-2xl"></i>
                                                    <span class="text-primary text-[10px] uppercase font-semibold">Jaga
                                                        Jarak</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($content['enable_adab_walimah']))
                                        <div
                                            class="space-y-8 bg-card p-8 rounded-2xl border border-borderLight shadow-sm">
                                            <h5
                                                class="text-primary text-sm font-semibold uppercase tracking-widest flex items-center gap-3 border-b border-borderLight pb-4">
                                                <i class="fa-solid fa-list-check text-accent"></i> Adab Walimah
                                            </h5>
                                            <div class="space-y-6">
                                                <div class="flex gap-4 items-start">
                                                    <div class="text-accent text-lg w-6 shrink-0 mt-0.5"><i
                                                            class="fa-solid fa-utensils"></i></div>
                                                    <div>
                                                        <p class="text-primary font-bold text-xs uppercase">Adab Makan
                                                        </p>
                                                        <p
                                                            class="text-secondary text-[11px] leading-relaxed mt-1 font-light">
                                                            Makan & minum dengan cara duduk sopan.</p>
                                                    </div>
                                                </div>
                                                <div class="flex gap-4 items-start">
                                                    <div class="text-accent text-lg w-6 shrink-0 mt-0.5"><i
                                                            class="fa-solid fa-mosque"></i></div>
                                                    <div>
                                                        <p class="text-primary font-bold text-xs uppercase">Waktu
                                                            Ibadah</p>
                                                        <p
                                                            class="text-secondary text-[11px] leading-relaxed mt-1 font-light">
                                                            Memperhatikan waktu sholat saat acara berlangsung.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- QR CODE ATTENDANCE --}}
        @if (!empty($content['enable_qr_attendance']))
            <section id="qr-tamu" class="py-24 px-6 bg-card border-y border-borderLight relative overflow-hidden">
                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-bg border border-borderLight mb-6 shadow-sm">
                            <i class="fa-solid fa-qrcode text-accent text-2xl"></i>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-serif text-primary mb-4">Akses Undangan</h2>
                        <div class="h-px w-16 bg-accent mx-auto mb-6"></div>
                        <p class="text-secondary text-sm font-light leading-relaxed max-w-md mx-auto">Tunjukkan kode
                            akses unik Anda di bawah ini kepada petugas verifikasi.</p>
                    </div>

                    <div class="flex justify-center">
                        <div
                            class="bg-white p-10 rounded-2xl border border-borderLight shadow-xl max-w-sm w-full mx-auto">
                            <div class="relative flex flex-col items-center">
                                <div class="p-4 border border-borderLight rounded-xl mb-8 shadow-inner bg-bg">
                                    {{-- Menggunakan URL parameter 'to' untuk QR Data. Jika tidak ada, default "Tamu" --}}
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($tamuName) }}"
                                        class="w-40 h-40 object-contain" alt="QR Code Tamu">
                                </div>
                                <div class="space-y-2 mb-8 text-center">
                                    <span
                                        class="text-[10px] uppercase tracking-[0.3em] text-accent font-semibold block">Guest
                                        Identity</span>
                                    <h3 class="text-2xl font-serif text-primary italic">{{ $tamuName }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- FOOTER --}}
        <footer class="py-20 px-6 bg-white border-t border-borderLight text-center relative overflow-hidden">
            <div class="max-w-4xl mx-auto relative z-10">
                <div class="mb-12 flex flex-col items-center">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="w-10 h-px bg-accent"></span>
                        <h2 class="text-4xl md:text-5xl font-serif text-primary uppercase tracking-widest">
                            {{ $initials }}</h2>
                        <span class="w-10 h-px bg-accent"></span>
                    </div>
                </div>

                <div class="mb-16 space-y-3">
                    <h3 class="text-2xl md:text-3xl font-serif text-primary">{{ $titleNames }}</h3>
                    <p class="text-[10px] text-secondary uppercase tracking-[0.2em] font-medium">Beserta Keluarga Besar
                    </p>
                </div>

                {{-- Turut Mengundang --}}
                @if (!empty($content['is_turut_mengundang_active']) && !empty($content['turut_mengundang']))
                    <div class="mb-16">
                        <p class="text-xs uppercase tracking-[0.2em] text-accent font-semibold mb-4">Turut Mengundang
                        </p>
                        <div
                            class="flex flex-wrap justify-center gap-2 max-w-2xl mx-auto text-sm text-secondary font-medium">
                            @foreach ($content['turut_mengundang'] as $person)
                                <span
                                    class="bg-bg border border-borderLight px-4 py-1.5 rounded-full">{{ $person }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-col items-center">
                    <div class="p-6 rounded-xl bg-bg border border-borderLight w-full max-w-xs shadow-sm">
                        <p class="text-[9px] text-secondary font-semibold uppercase tracking-widest mb-3">Created By
                        </p>
                        <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank"
                            rel="noopener noreferrer" class="block group">
                            <div
                                class="flex items-center justify-center gap-2 text-xs font-medium text-primary group-hover:text-accent transition-colors">
                                <i class="fa-brands fa-instagram"></i>
                                <span class="tracking-wide">@ruangrestu.undangan</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </footer>

    </main>

    {{-- FLOATING BUTTONS --}}
    <div id="fab-container"
        class="fixed right-6 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000">
        <div class="relative flex items-center group">
            <button id="btn-music" onclick="toggleMusic()"
                class="w-12 h-12 bg-white border border-borderLight rounded-full flex items-center justify-center text-accent shadow-md hover:bg-bg transition-all">
                <i class="fa-solid fa-music animate-spin-slow" id="icon-music"></i>
            </button>
        </div>
        <button id="btn-scroll" onclick="toggleAutoScroll()"
            class="w-12 h-12 bg-white border border-borderLight rounded-full flex items-center justify-center text-accent shadow-md hover:bg-bg transition-all">
            <i class="fa-solid fa-angles-down" id="icon-scroll"></i>
        </button>
    </div>

    {{-- BOTTOM NAVIGATION --}}
    <nav id="bottom-nav"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-white/90 backdrop-blur-md border border-borderLight rounded-full shadow-lg p-1">
            <ul class="flex justify-around items-center h-14 w-[300px] md:w-[380px]">
                <li class="relative group h-full">
                    <a href="#home"
                        class="nav-link flex items-center justify-center w-14 h-full text-secondary hover:text-accent transition-all rounded-full hover:bg-bg">
                        <i class="fa-solid fa-house text-lg"></i>
                    </a>
                </li>
                @if (!empty($content['is_gallery_active']))
                    <li class="relative group h-full">
                        <a href="#gallery"
                            class="nav-link flex items-center justify-center w-14 h-full text-secondary hover:text-accent transition-all rounded-full hover:bg-bg">
                            <i class="fa-solid fa-image text-lg"></i>
                        </a>
                    </li>
                @endif
                @if (!empty($content['is_event_active']))
                    <li class="relative group h-full">
                        <a href="#lokasi"
                            class="nav-link flex items-center justify-center w-14 h-full text-secondary hover:text-accent transition-all rounded-full hover:bg-bg">
                            <i class="fa-solid fa-location-dot text-lg"></i>
                        </a>
                    </li>
                @endif
                <li class="relative h-full px-1 flex items-center">
                    <a href="javascript:void(0)" onclick="openRSVP()"
                        class="flex items-center justify-center px-6 h-10 bg-accent text-white rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-accentHover shadow-md transition-all active:scale-95">
                        RSVP
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- LIGHTBOX GALLERY --}}
    <div id="lightbox"
        class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-white/95 backdrop-blur-md p-4 transition-all duration-500">
        <div class="w-full flex justify-between items-center p-6 absolute top-0 left-0">
            <span class="text-primary font-bold tracking-widest text-sm"><span id="current-count">1</span> / <span
                    id="total-count">1</span></span>
            <button onclick="closeLightbox()" class="text-primary hover:text-accent transition-colors text-3xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="relative w-full max-w-5xl flex items-center justify-center h-[80vh]">
            <img id="lightbox-img" src=""
                class="max-h-full max-w-full object-contain transition-opacity duration-300 shadow-2xl rounded">
        </div>
        <div class="absolute bottom-10 flex gap-6">
            <button onclick="prevImg()"
                class="w-12 h-12 rounded-full border border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-colors shadow-sm"><i
                    class="fa-solid fa-chevron-left"></i></button>
            <button onclick="nextImg()"
                class="w-12 h-12 rounded-full border border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-colors shadow-sm"><i
                    class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    {{-- RSVP MODAL FORM --}}
    <section id="rsvp-modal"
        class="fixed inset-0 z-[100] invisible transition-all duration-500 overflow-hidden flex items-end md:items-center justify-center">
        <div onclick="closeRSVP()"
            class="absolute inset-0 bg-primary/60 backdrop-blur-sm opacity-0 transition-opacity duration-500"
            id="rsvp-overlay"></div>

        <div id="rsvp-content"
            class="relative w-full md:max-w-xl lg:max-w-2xl h-[92vh] md:h-auto max-h-[95vh] bg-white rounded-t-[2rem] md:rounded-2xl border border-borderLight shadow-2xl transform translate-y-full transition-transform duration-700 flex flex-col">
            <div class="overflow-y-auto px-8 pb-10 pt-6 custom-scrollbar">
                <div class="w-12 h-1.5 bg-borderLight rounded-full mx-auto mb-8 md:hidden"></div>
                <div class="text-center mb-10 border-b border-borderLight pb-6">
                    <h2 class="text-3xl font-serif text-primary mb-2">RSVP</h2>
                    <p class="text-secondary text-xs uppercase tracking-widest">Confirmation & Wishes</p>
                </div>

                {{-- Target form to Route storeRsvp --}}
                <form id="form-rsvp" class="space-y-8" onsubmit="submitRsvp(event)">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-semibold text-secondary uppercase tracking-widest">Nama
                            Lengkap</label>
                        <input type="text" id="guest_name" name="guest_name"
                            value="{{ $tamuName != 'Tamu Undangan' ? $tamuName : '' }}"
                            placeholder="Masukkan nama Anda" required
                            class="w-full bg-bg border border-borderLight focus:border-accent p-4 text-primary text-sm outline-none transition-all rounded-sm shadow-inner">
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-semibold text-secondary uppercase tracking-widest">Konfirmasi
                            Kehadiran</label>
                        <input type="hidden" name="status_rsvp" id="status_rsvp" value="hadir">
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" onclick="selectAttendance('hadir')" id="btn-hadir"
                                class="py-3.5 rounded-sm border border-accent bg-accent text-white text-xs font-semibold uppercase tracking-widest transition-all">
                                <i class="fa-solid fa-check mr-2"></i> Hadir
                            </button>
                            <button type="button" onclick="selectAttendance('tidak_hadir')" id="btn-absen"
                                class="py-3.5 rounded-sm border border-borderLight text-secondary bg-transparent text-xs font-semibold uppercase tracking-widest transition-all">
                                <i class="fa-solid fa-xmark mr-2"></i> Absen
                            </button>
                        </div>
                    </div>

                    <div id="guest-selection" class="transition-all duration-500 overflow-hidden">
                        <div class="bg-bg p-5 rounded-xl border border-borderLight space-y-4">
                            <p class="text-[10px] uppercase tracking-widest text-primary text-center font-semibold">
                                Jumlah Tamu</p>
                            <input type="hidden" name="pax" id="pax" value="1">
                            <div class="flex gap-3 flex-wrap">
                                <button type="button" onclick="selectPax(1, this)"
                                    class="guest-btn flex-1 min-w-[60px] py-2 rounded border border-accent bg-accent text-white text-sm font-semibold transition-all">1</button>
                                <button type="button" onclick="selectPax(2, this)"
                                    class="guest-btn flex-1 min-w-[60px] py-2 rounded border border-borderLight bg-white text-secondary text-sm font-semibold transition-all">2</button>
                                <button type="button" onclick="selectPax(3, this)"
                                    class="guest-btn flex-1 min-w-[60px] py-2 rounded border border-borderLight bg-white text-secondary text-sm font-semibold transition-all">3</button>
                                <button type="button" onclick="selectPax('custom', this)"
                                    class="guest-btn flex-1 min-w-[60px] py-2 rounded border border-borderLight bg-white text-secondary text-sm font-semibold transition-all">3+</button>
                            </div>
                            <div id="custom-pax-container" class="hidden space-y-3">
                                <label
                                    class="text-[10px] uppercase tracking-widest text-secondary font-semibold">Masukkan
                                    jumlah tamu (4+)</label>
                                <input type="number" id="custom-pax-input" min="4"
                                    placeholder="Ketik jumlah tamu"
                                    class="w-full bg-white border border-borderLight p-3 rounded-xl text-sm text-primary outline-none focus:border-accent transition-all" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-semibold text-secondary uppercase tracking-widest">Doa &
                            Ucapan</label>
                        <textarea id="message" name="message" rows="3" placeholder="Tuliskan pesan bahagia..." required
                            class="w-full bg-bg border border-borderLight focus:border-accent p-4 text-primary text-sm outline-none transition-all rounded-sm resize-none shadow-inner"></textarea>
                    </div>

                    <div class="flex flex-col gap-3 pt-4 border-t border-borderLight">
                        <button type="submit" id="btn-submit-rsvp"
                            class="w-full py-4 bg-accent text-white rounded-sm font-semibold text-xs uppercase tracking-[0.2em] shadow-md hover:bg-accentHover active:scale-95 transition-all">
                            Kirim Konfirmasi
                        </button>
                        <button type="button" onclick="closeRSVP()"
                            class="w-full py-3 bg-transparent text-secondary border border-transparent hover:border-borderLight rounded-sm font-semibold text-[10px] uppercase tracking-widest hover:text-primary transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- SCRIPTS --}}
    <script>
        // Utilities
        function copyToClipboard(elementId, btn) {
            const text = document.getElementById(elementId).innerText;
            const toast = document.getElementById('copy-toast');
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Tersalin!</span>';
                toast.classList.remove('opacity-0', 'translate-y-10');
                toast.classList.add('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    toast.classList.add('opacity-0', 'translate-y-10');
                    toast.classList.remove('opacity-100', 'translate-y-0');
                }, 2500);
            });
        }

        function showToast(msg) {
            const toast = document.getElementById('copy-toast');
            if (!toast) {
                alert(msg);
                return;
            }
            toast.innerHTML = `<i class="fa-solid fa-check"></i> ${msg}`;
            toast.classList.remove('opacity-0', 'translate-y-10');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-10');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2800);
        }

        // Music & Scroll
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
            const btn = document.getElementById('btn-music');
            const icon = document.getElementById('icon-music');
            if (isMusicPlaying && !forcePlay) {
                audio.pause();
                isMusicPlaying = false;
                icon.className = 'fa-solid fa-volume-xmark';
                btn.classList.remove('bg-accent', 'text-white');
                btn.classList.add('bg-white', 'text-accent');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.className = 'fa-solid fa-music animate-spin-slow';
                    btn.classList.add('bg-accent', 'text-white');
                    btn.classList.remove('bg-white', 'text-accent');
                }).catch(() => console.log("Autoplay blocked"));
            }
        }

        function toggleAutoScroll(forceStart = false) {
            const btn = document.getElementById('btn-scroll');
            const icon = document.getElementById('icon-scroll');
            if (isAutoScrolling && !forceStart) {
                clearInterval(scrollInterval);
                isAutoScrolling = false;
                btn.classList.remove('bg-accent', 'text-white');
                btn.classList.add('bg-white', 'text-accent');
                icon.className = 'fa-solid fa-angles-down';
            } else {
                isAutoScrolling = true;
                btn.classList.add('bg-accent', 'text-white');
                btn.classList.remove('bg-white', 'text-accent');
                icon.className = 'fa-solid fa-pause';
                scrollInterval = setInterval(() => {
                    window.scrollBy({
                        top: 1,
                        behavior: 'auto'
                    });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) toggleAutoScroll();
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

        // Lightbox
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
            document.getElementById('current-count').innerText = currentIndex + 1;
            document.getElementById('total-count').innerText = images.length;
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

        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').classList.contains('hidden')) return;
            if (e.key === "ArrowRight") nextImg();
            if (e.key === "ArrowLeft") prevImg();
            if (e.key === "Escape") closeLightbox();
        });

        // RSVP Control
        function openRSVP() {
            const modal = document.getElementById('rsvp-modal');
            const overlay = document.getElementById('rsvp-overlay');
            const content = document.getElementById('rsvp-content');
            modal.classList.remove('invisible');
            setTimeout(() => {
                overlay.classList.replace('opacity-0', 'opacity-100');
                content.classList.replace('translate-y-full', 'translate-y-0');
            }, 10);
            if (isAutoScrolling) toggleAutoScroll();
        }

        function closeRSVP() {
            const overlay = document.getElementById('rsvp-overlay');
            const content = document.getElementById('rsvp-content');
            const modal = document.getElementById('rsvp-modal');
            overlay.classList.replace('opacity-100', 'opacity-0');
            content.classList.replace('translate-y-0', 'translate-y-full');
            setTimeout(() => modal.classList.add('invisible'), 500);
        }

        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 120) {
                if (!window.hasOpenedRsvpOnScroll) {
                    openRSVP();
                    window.hasOpenedRsvpOnScroll = true;
                    if (isAutoScrolling) toggleAutoScroll();
                }
            }
        }, {
            passive: true
        });

        function selectAttendance(status) {
            document.getElementById('status_rsvp').value = status;
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');
            const customContainer = document.getElementById('custom-pax-container');
            const customInput = document.getElementById('custom-pax-input');

            [btnHadir, btnAbsen].forEach(btn => {
                btn.classList.remove('bg-accent', 'text-white', 'border-accent');
                btn.classList.add('bg-transparent', 'text-secondary', 'border-borderLight');
            });

            if (status === 'hadir') {
                btnHadir.classList.add('bg-accent', 'text-white', 'border-accent');
                btnHadir.classList.remove('bg-transparent', 'text-secondary');
                guestDiv.classList.remove('hidden');
                customContainer.classList.add('hidden');
                customInput.value = '';
                document.getElementById('pax').value = "1";
                selectPax(1, document.querySelectorAll('.guest-btn')[0]);
            } else {
                btnAbsen.classList.add('bg-accent', 'text-white', 'border-accent');
                btnAbsen.classList.remove('bg-transparent', 'text-secondary');
                guestDiv.classList.add('hidden');
                customContainer.classList.add('hidden');
                customInput.value = '';
                document.getElementById('pax').value = "0";
            }
        }

        function selectPax(value, btnElement) {
            const paxInput = document.getElementById('pax');
            const customContainer = document.getElementById('custom-pax-container');
            const customInput = document.getElementById('custom-pax-input');

            document.querySelectorAll('.guest-btn').forEach(b => {
                b.classList.remove('bg-accent', 'text-white', 'border-accent');
                b.classList.add('bg-white', 'text-secondary', 'border-borderLight');
            });

            if (value === 'custom') {
                customContainer.classList.remove('hidden');
                paxInput.value = customInput.value && parseInt(customInput.value) >= 4 ? customInput.value : '4';
                btnElement.classList.add('bg-accent', 'text-white', 'border-accent');
                btnElement.classList.remove('bg-white', 'text-secondary');
                customInput.focus();
                return;
            }

            customContainer.classList.add('hidden');
            customInput.value = '';
            paxInput.value = value;
            btnElement.classList.add('bg-accent', 'text-white', 'border-accent');
            btnElement.classList.remove('bg-white', 'text-secondary');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const customInput = document.getElementById('custom-pax-input');
            if (customInput) {
                customInput.addEventListener('input', function() {
                    const value = parseInt(this.value, 10);
                    if (!isNaN(value) && value >= 4) {
                        document.getElementById('pax').value = value;
                    }
                });
            }
        });

        // AJAX RSVP Submit
        function submitRsvp(e) {
            e.preventDefault();
            const form = document.getElementById('form-rsvp');
            const formData = new FormData(form);
            const btnSubmit = document.getElementById('btn-submit-rsvp');
            const originalText = 'Kirim Konfirmasi';

            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            btnSubmit.disabled = true;

            fetch('{{ route('rsvp.store', $invitation->slug) }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                    if (data.status === 'success') {
                        showSuccessModal(data.message || 'RSVP Anda telah tersimpan.');
                        form.reset();
                        document.getElementById('pax').value = '1';
                        selectAttendance('hadir');
                        updateRsvpStats();
                    } else {
                        showToast('Terjadi kesalahan, silakan coba lagi.');
                    }
                })
                .catch(err => {
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                    showToast('Gagal terhubung ke server.');
                });
        }

        function showSuccessModal(msg) {
            document.getElementById('success-message').textContent = msg;
            document.getElementById('success-modal').classList.remove('hidden');
        }

        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
            closeRSVP();
        }

        function updateRsvpStats() {
            fetch('{{ route('rsvp.stats', $invitation->slug) }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('total-attendance').textContent = data.totalAttendance || 0;
                        document.getElementById('total-wishes').textContent = data.totalWishes || 0;
                    }
                })
                .catch(err => console.log('Stats update failed:', err));
        }

        // Gifts Modal
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

        function selectGiftAttendance(status) {
            document.getElementById('gift-confirm-status').value = status;
            const btnHadir = document.getElementById('gift-btn-hadir');
            const btnAbsen = document.getElementById('gift-btn-absen');
            const wrapper = document.getElementById('gift-confirm-pax-wrapper');
            
            [btnHadir, btnAbsen].forEach(btn => {
                btn.className = "py-3 rounded-sm border border-borderLight text-secondary bg-transparent text-xs font-semibold uppercase tracking-widest transition-all";
            });
            
            if (status === 'hadir') {
                btnHadir.className = "py-3 rounded-sm border border-accent bg-accent text-white text-xs font-semibold uppercase tracking-widest transition-all";
                wrapper.classList.remove('hidden');
                document.getElementById('gift-confirm-pax').value = 1;
                setGiftPax(1);
            } else {
                btnAbsen.className = "py-3 rounded-sm border border-accent bg-accent text-white text-xs font-semibold uppercase tracking-widest transition-all";
                wrapper.classList.add('hidden');
                document.getElementById('gift-confirm-pax').value = 0;
            }
        }

        function setGiftPax(count) {
            const customContainer = document.getElementById('gift-custom-pax-container');
            const customInput = document.getElementById('gift-confirm-custom-pax-input');
            const hiddenPaxInput = document.getElementById('gift-confirm-pax');

            if (count === 'custom') {
                customContainer.classList.remove('hidden');
                hiddenPaxInput.value = customInput.value || 4;
                customInput.focus();
            } else {
                customContainer.classList.add('hidden');
                hiddenPaxInput.value = count;
            }

            document.querySelectorAll('.gift-pax-btn').forEach(btn => {
                const text = btn.innerText.trim();
                btn.className = "gift-pax-btn flex-1 py-2 rounded border border-borderLight bg-white text-secondary text-sm font-semibold transition-all";
                if (text == count || (count === 'custom' && text === '3+')) {
                    btn.className = "gift-pax-btn flex-1 py-2 rounded border border-accent bg-accent text-white text-sm font-semibold transition-all";
                }
            });
            updateGiftPaxDisplay(hiddenPaxInput.value);
        }

        function updateGiftPaxDisplay(pax) {
            document.getElementById('gift-pax-display').textContent = `${pax} Orang`;
        }

        function confirmGift(id, name) {
            document.getElementById('gift-confirm-title').textContent = name;
            
            const nameInput = document.getElementById('gift-confirm-name');
            const paxCustomInput = document.getElementById('gift-confirm-custom-pax-input');
            
            if (nameInput) nameInput.value = document.getElementById('guest_name').value || '{{ $tamuName }}';
            if (paxCustomInput) paxCustomInput.value = '';
            
            selectGiftAttendance('hadir');
            
            document.getElementById('gift-confirm-modal').classList.remove('hidden');
            document.getElementById('gift-confirm-modal').classList.add('flex');
            document.getElementById('gift-confirm-modal').dataset.selectedGift = name;
        }

        function closeGiftConfirmModal() {
            const modal = document.getElementById('gift-confirm-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.dataset.selectedGift = '';
        }

        document.getElementById('gift-confirm-custom-pax-input')?.addEventListener('input', function() {
            const count = parseInt(this.value) || 4;
            document.getElementById('gift-confirm-pax').value = count;
            updateGiftPaxDisplay(count);
        });

        function submitGiftConfirm() {
            const selectedGift = document.getElementById('gift-confirm-modal').dataset.selectedGift;
            if (!selectedGift) return;

            const guestName = document.getElementById('gift-confirm-name').value || '{{ $tamuName }}';
            const rsvpStatus = document.getElementById('gift-confirm-status').value || 'hadir';
            let pax = 0;
            if (rsvpStatus === 'hadir') {
                pax = parseInt(document.getElementById('gift-confirm-pax').value, 10) || 1;
            }
            if (isNaN(pax) || pax < 0) pax = 0;

            const message = `Telah memberikan kado: ${selectedGift}. Jumlah tamu: ${pax}`;
            const payload = new FormData();
            payload.append('_token', document.querySelector('input[name="_token"]').value);
            payload.append('guest_name', guestName);
            payload.append('status_rsvp', rsvpStatus);
            payload.append('pax', pax);
            payload.append('message', message);

            fetch('{{ route('rsvp.store', $invitation->slug) }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: payload
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showSuccessModal(`Kado "${selectedGift}" telah tercatat.`);
                        toggleGiftModal(false);
                        closeGiftConfirmModal();
                        selectAttendance(rsvpStatus);
                        document.getElementById('pax').value = pax.toString();
                        updateRsvpStats();
                    } else {
                        showToast('Gagal menyimpan kado. Silakan coba lagi.');
                    }
                })
                .catch(() => {
                    showToast('Gagal terhubung ke server.');
                });
        }
    </script>

    {{-- PROTEKSI DRAFT / EXPIRED - Hanya disable copy jika pending/canceled --}}
    @if (isset($isPreviewMode) && $isPreviewMode)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const copyButtons = document.querySelectorAll('[onclick*="copyToClipboard"]');
                copyButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.title = 'Aktifkan paket untuk membuka fitur bagikan';
                    btn.onclick = function() {
                        showToast('Aktifkan paket untuk membuka fitur bagikan.');
                        return false;
                    };
                });
            });
        </script>
    @endif
</body>

</html>
