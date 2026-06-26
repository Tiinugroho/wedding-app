@php
    $guestSlug = request()->query('to');
    $guestNameDisplay = $guestData
        ? $guestData->name
        : ($guestSlug
            ? urldecode(str_replace(['+', '-'], ' ', $guestSlug))
            : 'Tamu Undangan');
    $guestNameInput = $guestData
        ? $guestData->name
        : ($guestSlug
            ? urldecode(str_replace(['+', '-'], ' ', $guestSlug))
            : '');
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Elegant Wedding - {{ $content['groom_nickname'] ?? 'Groom' }} & {{ $content['bride_nickname'] ?? 'Bride' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom Scrollbar for Minimalist Feel */
        .scroll-custom::-webkit-scrollbar { width: 4px; }
        .scroll-custom::-webkit-scrollbar-track { background: transparent; }
        .scroll-custom::-webkit-scrollbar-thumb { background: #6B8E7B; border-radius: 10px; }
        .modal-open { overflow: hidden !important; }

        @keyframes slide-up {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        .animate-slide-up { animation: slide-up 0.5s ease-out forwards; }

        @media (max-width: 360px) {
            .text-8xl { font-size: 3.5rem; }
            .text-5xl { font-size: 2.25rem; }
        }

        ::-webkit-scrollbar { width: 8px; background: #F4F6F4; }
        ::-webkit-scrollbar-thumb { background: #DDE5E0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #6B8E7B; }

        body {
            background-color: #F4F6F4;
            color: #1E3329;
            overflow-y: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .vignette { background: radial-gradient(circle at center, transparent 0%, rgba(244,246,244, 0.5) 100%); }
        .bottom-gradient { background: linear-gradient(to top, #F4F6F4 0%, transparent 100%); }
        .text-shadow { text-shadow: 1px 1px 4px rgba(255, 255, 255, 0.9); }
        .episode-card:hover .episode-img { transform: scale(1.05); }
        .arch-image { border-radius: 120px; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#F4F6F4',
                        card: '#FFFFFF',
                        primary: '#1E3329',
                        secondary: '#52665B',
                        accent: '#6B8E7B',
                        accentHover: '#547563',
                        borderLight: '#DDE5E0'
                    },
                    fontFamily: {
                        sans: ['"Outfit"', 'sans-serif'],
                        serif: ['"Lora"', 'serif'],
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

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music->file_path) ? Storage::url($invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3' }}" type="audio/mpeg">
    </audio>

    <!-- COVER PAGE -->
    <div id="cover-page" class="fixed inset-0 z-[100] flex flex-col items-center justify-center w-screen h-screen bg-bg transition-all duration-700">
        <div class="absolute top-0 w-full p-8 flex justify-center md:justify-between items-center">
            <h1 class="font-serif text-primary text-2xl md:text-3xl tracking-[0.3em] uppercase">The Wedding</h1>
        </div>
        <div class="flex flex-col items-center justify-center transform transition-transform duration-500 w-full max-w-md px-6">
            <h2 class="text-2xl md:text-3xl font-serif text-accent mb-10 text-center italic">{{ $content['cover_greeting'] ?? 'Welcome to the Celebration' }}</h2>
            <div class="flex flex-wrap justify-center gap-6 md:gap-10">
                <button onclick="openInvitation()" class="group flex flex-col items-center gap-5 transition-transform hover:-translate-y-2 duration-300">
                    <div class="w-32 h-40 md:w-36 md:h-48 rounded-[3rem] overflow-hidden border-2 border-borderLight group-hover:border-accent group-hover:shadow-2xl group-hover:shadow-accent/30 transition-all relative p-1.5 bg-white">
                        <div class="w-full h-full rounded-[2.5rem] overflow-hidden">
                            <img src="{{ !empty($content['cover_image']) ? Storage::url($content['cover_image']) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=500&auto=format&fit=crop' }}"
                                class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700" alt="Cover">
                        </div>
                        <div class="absolute inset-0 bg-accent/5 group-hover:bg-transparent transition-colors rounded-[3rem]"></div>
                    </div>
                    <span id="guest-name" class="text-primary group-hover:text-accent transition-colors text-sm md:text-base font-semibold tracking-wide">
                        {{ $guestNameDisplay }}
                    </span>
                </button>
            </div>
            <button onclick="openInvitation()" class="mt-16 px-10 py-4 bg-primary text-white shadow-xl shadow-primary/20 hover:bg-accent hover:shadow-accent/40 uppercase tracking-[0.2em] text-xs font-bold transition-all rounded-full hover:-translate-y-1">
                Open Invitation
            </button>
        </div>
    </div>

    <main id="main-content" class="min-h-screen opacity-0 transition-opacity duration-1000 pb-20">

        <!-- NAV -->
        <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl border-b border-white p-5 flex justify-between items-center transition-all duration-300 shadow-sm" id="main-nav">
            <h1 class="font-serif text-primary text-xl md:text-2xl tracking-[0.2em] uppercase">The Wedding</h1>
            <div class="flex items-center gap-4 bg-bg px-4 py-2 rounded-full border border-borderLight cursor-pointer hover:border-accent transition-colors" onclick="openRSVP()">
                <i class="fa-solid fa-envelope text-accent text-lg"></i>
            </div>
        </nav>

        @php
            $gFullName = $content['groom_name'] ?? 'Romeo Montague';
            $bFullName = $content['bride_name'] ?? 'Juliet Capulet';
            $gNickname = $content['groom_nickname'] ?? explode(' ', $gFullName)[0];
            $bNickname = $content['bride_nickname'] ?? explode(' ', $bFullName)[0];
            $coupleOrder = $content['couple_order'] ?? 'groom_first';
            $displayFirst = $coupleOrder === 'bride_first' ? $bNickname : $gNickname;
            $displaySecond = $coupleOrder === 'bride_first' ? $gNickname : $bNickname;
        @endphp

        <!-- HERO SECTION -->
        <section id="home" class="relative h-[85vh] md:h-screen flex items-center justify-center md:justify-start px-6 md:px-20 pt-20">
            <div class="absolute inset-0 z-0">
                <img src="{{ !empty($content['cover_image']) ? Storage::url($content['cover_image']) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2000&auto=format&fit=crop' }}" class="w-full h-full object-cover opacity-30" alt="Hero">
                <div class="absolute inset-0 bg-gradient-to-r from-bg via-bg/80 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 h-2/3 bottom-gradient"></div>
            </div>

            <div class="relative z-10 max-w-2xl mt-12 md:mt-0 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-6">
                    <span class="w-12 h-px bg-accent"></span>
                    <span class="text-xs font-bold tracking-[0.4em] text-accent uppercase">Exclusive Celebration</span>
                </div>

                <h2 class="text-6xl md:text-8xl font-serif text-primary mb-6 leading-none">
                    {{ $displayFirst }} <span class="text-accent italic font-light text-5xl md:text-7xl">&</span> {{ $displaySecond }}
                </h2>

                <div class="inline-flex items-center justify-center md:justify-start gap-5 text-xs md:text-sm font-semibold mb-8 text-primary bg-white/50 backdrop-blur-md px-6 py-3 rounded-full border border-borderLight">
                    <span class="tracking-widest uppercase text-accent">Save The Date</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    <span class="tracking-widest">{{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->format('Y') : '2026' }}</span>
                </div>

                <p class="text-sm md:text-base text-secondary leading-relaxed mb-10 max-w-lg mx-auto md:mx-0 font-light">
                    {{ $content['quotes'] ?? 'Dengan memohon rahmat Tuhan, dua pemeran utama kami bersiap mengikat janji suci. Sebuah perayaan cinta abadi yang tayang perdana secara eksklusif.' }}
                </p>

                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                    <a href="javascript:void(0)" onclick="openRSVP()" class="px-8 py-4 bg-primary text-white rounded-full text-xs uppercase tracking-[0.2em] font-bold flex items-center justify-center gap-3 hover:bg-accent transition-colors shadow-xl shadow-primary/20">
                        <i class="fa-solid fa-envelope-open-text text-lg"></i> RSVP Now
                    </a>
                </div>
            </div>
        </section>

        <!-- COUPLE SECTION -->
        <section id="cast" class="px-6 md:px-20 py-24 relative z-10 bg-bg">
            <div class="text-center mb-16">
                <span class="text-accent text-xs font-bold uppercase tracking-[0.3em] mb-2 block">Our Cast</span>
                <h3 class="text-4xl md:text-5xl font-serif text-primary mb-5">The Couple</h3>
                <div class="w-20 h-0.5 bg-accent mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 lg:gap-24 max-w-5xl mx-auto">
                <!-- Groom -->
                <div class="flex flex-col md:flex-row gap-8 items-center text-center md:text-left group">
                    <div class="w-56 h-72 md:w-52 md:h-64 shrink-0 arch-image overflow-hidden relative border-8 border-white shadow-2xl shadow-primary/10">
                        <img src="{{ !empty($content['groom_photo']) ? Storage::url($content['groom_photo']) : 'https://images.soco.id/230-58.jpg.jpeg' }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Groom">
                    </div>
                    <div>
                        <p class="text-accent bg-white border border-borderLight px-4 py-1.5 rounded-full inline-block text-[10px] uppercase tracking-[0.2em] mb-4 font-bold shadow-sm">The Groom</p>
                        <h4 class="text-3xl font-serif text-primary mb-3">{{ $gFullName }}</h4>
                        <p class="text-sm text-secondary font-light leading-relaxed">Putra dari {{ $content['groom_father'] ?? 'Bapak' }}<br>& {{ $content['groom_mother'] ?? 'Ibu' }}</p>
                        @if(!empty($content['groom_ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $content['groom_ig']) }}" target="_blank" class="mt-4 inline-block text-accent hover:text-primary transition-colors"><i class="fa-brands fa-instagram text-xl"></i></a>
                        @endif
                    </div>
                </div>

                <!-- Bride -->
                <div class="flex flex-col md:flex-row-reverse gap-8 items-center text-center md:text-right group">
                    <div class="w-56 h-72 md:w-52 md:h-64 shrink-0 arch-image overflow-hidden relative border-8 border-white shadow-2xl shadow-primary/10">
                        <img src="{{ !empty($content['bride_photo']) ? Storage::url($content['bride_photo']) : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg' }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Bride">
                    </div>
                    <div>
                        <p class="text-accent bg-white border border-borderLight px-4 py-1.5 rounded-full inline-block text-[10px] uppercase tracking-[0.2em] mb-4 font-bold shadow-sm">The Bride</p>
                        <h4 class="text-3xl font-serif text-primary mb-3">{{ $bFullName }}</h4>
                        <p class="text-sm text-secondary font-light leading-relaxed">Putri dari {{ $content['bride_father'] ?? 'Bapak' }}<br>& {{ $content['bride_mother'] ?? 'Ibu' }}</p>
                        @if(!empty($content['bride_ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $content['bride_ig']) }}" target="_blank" class="mt-4 inline-block text-accent hover:text-primary transition-colors"><i class="fa-brands fa-instagram text-xl"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- LOVE STORIES -->
        @if (!empty($content['is_story_active']) && !empty($content['love_stories']))
            <section id="cerita" class="px-6 md:px-20 py-24 bg-card rounded-t-[3rem] shadow-[0_-10px_40px_rgba(0,0,0,0.03)] mt-8 relative z-20">
                <div class="text-center mb-20">
                    <span class="text-accent text-xs uppercase tracking-[0.3em] font-bold block mb-2">Our Story</span>
                    <h3 class="text-4xl md:text-5xl font-serif text-primary mb-5">The Journey</h3>
                    <div class="w-20 h-0.5 bg-accent mx-auto rounded-full"></div>
                </div>

                <div class="space-y-12 max-w-4xl mx-auto relative before:absolute before:inset-0 before:ml-5 md:before:ml-[11rem] before:-translate-x-px md:before:mx-auto before:h-full before:w-0.5 before:bg-gradient-to-b before:from-accent before:via-borderLight before:to-transparent">
                    @foreach ($content['love_stories'] as $index => $story)
                        <div class="episode-card relative flex flex-col md:flex-row gap-6 md:gap-12 items-start md:items-center p-8 bg-bg rounded-[2rem] border-none shadow-[0_10px_30px_-15px_rgba(30,51,41,0.1)] hover:shadow-[0_20px_40px_-15px_rgba(107,142,123,0.2)] transition-all duration-500 cursor-pointer group">
                            <h4 class="text-6xl font-serif text-accent/20 hidden md:block italic group-hover:text-accent/40 transition-colors">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</h4>
                            @if (!empty($story['image']))
                                <div class="w-full md:w-60 h-40 shrink-0 rounded-[1.5rem] overflow-hidden relative episode-img-container shadow-lg border-4 border-white">
                                    <img src="{{ Storage::url($story['image']) }}" class="episode-img w-full h-full object-cover transition-transform duration-1000" alt="{{ $story['title'] }}">
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-4">
                                    <h5 class="font-serif text-2xl text-primary">{{ $story['title'] ?? 'Story' }}</h5>
                                    @if (!empty($story['year']))
                                        <span class="bg-white px-3 py-1 rounded-full text-[10px] text-accent font-bold tracking-widest uppercase shadow-sm border border-borderLight">{{ $story['year'] }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-secondary font-light leading-relaxed line-clamp-3">{{ $story['description'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- GALLERY -->
        @if (!empty($content['is_gallery_active']))
            <section id="gallery" class="py-24 bg-card">
                <div class="px-6 md:px-20 text-center mb-16">
                    <span class="text-accent text-xs font-bold uppercase tracking-[0.3em] mb-2 block">Captured Memories</span>
                    <h3 class="text-4xl md:text-5xl font-serif text-primary mb-5">Gallery</h3>
                    <div class="w-20 h-0.5 bg-accent mx-auto rounded-full"></div>
                </div>

                @if (!empty($invitation->galleries) && count($invitation->galleries) > 0)
                    <div class="relative group">
                        <div class="flex overflow-x-auto gap-8 px-6 md:px-20 pb-12 snap-x snap-mandatory hide-scrollbar scroll-smooth">
                            @foreach ($invitation->galleries as $index => $gallery)
                                <div class="w-[75vw] md:w-[28vw] shrink-0 snap-center group/card cursor-pointer" onclick="openLightbox({{ $index }})">
                                    <div class="relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-lg border-[6px] border-bg bg-white">
                                        <img src="{{ Storage::url($gallery->file_path) }}" alt="Gallery {{ $index + 1 }}" class="gallery-img w-full h-full object-cover transition-transform duration-1000 group-hover/card:scale-110">
                                        <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover/card:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                            <i class="fa-solid fa-expand text-accent text-2xl bg-white w-14 h-14 rounded-full flex items-center justify-center shadow-xl transform scale-50 group-hover/card:scale-100 transition-transform duration-300"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-center text-secondary text-sm font-light px-6">Galeri belum tersedia.</p>
                @endif
            </section>
        @endif

        <!-- EVENTS / VENUE -->
        <section id="lokasi" class="px-6 md:px-20 py-24 bg-bg border-y border-borderLight rounded-b-[3rem] shadow-[0_10px_40px_rgba(0,0,0,0.02)] relative z-10">
            <div class="text-center mb-16">
                <span class="text-accent text-xs font-bold uppercase tracking-[0.3em] mb-2 block">Where & When</span>
                <h3 class="text-4xl md:text-5xl font-serif text-primary mb-5">Event Venue</h3>
                <div class="w-20 h-0.5 bg-accent mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl mx-auto">
                <!-- Akad -->
                <div class="bg-white p-12 rounded-[2.5rem] border border-borderLight hover:border-accent transition-all shadow-lg hover:shadow-2xl hover:-translate-y-2 group">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <span class="bg-bg text-accent px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] block mb-4 w-max">The Vow</span>
                            <h4 class="text-3xl md:text-4xl font-serif text-primary">Akad Nikah</h4>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-bg flex items-center justify-center shadow-inner border border-borderLight text-accent group-hover:bg-primary group-hover:text-white transition-all duration-500">
                            <i class="fa-solid fa-ring text-2xl"></i>
                        </div>
                    </div>
                    <ul class="space-y-6 mb-12 text-sm text-secondary font-medium">
                        @if (!empty($content['akad_date']))
                            <li class="flex items-center gap-5"><div class="w-10 h-10 rounded-full bg-bg flex items-center justify-center text-accent"><i class="fa-regular fa-calendar text-lg"></i></div>
                                {{ \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') }}</li>
                        @endif
                        @if (!empty($content['akad_time']))
                            <li class="flex items-center gap-5"><div class="w-10 h-10 rounded-full bg-bg flex items-center justify-center text-accent"><i class="fa-regular fa-clock text-lg"></i></div>
                                {{ $content['akad_time'] }} @if (!empty($content['akad_time_end'])) - {{ $content['akad_time_end'] }} @endif WIB</li>
                        @endif
                        @if (!empty($content['akad_location']))
                            <li class="flex items-start gap-5"><div class="w-10 h-10 rounded-full bg-bg flex items-center justify-center text-accent shrink-0"><i class="fa-solid fa-location-dot text-lg"></i></div>
                                <span class="mt-2.5">{{ $content['akad_location'] }}<br><span class="text-gray-400 text-xs font-light mt-1.5 block leading-relaxed">{{ $content['akad_address'] ?? '' }}</span></span></li>
                        @endif
                    </ul>
                    @if (!empty($content['akad_map']))
                        <a href="{{ $content['akad_map'] }}" target="_blank" class="block w-full py-4 bg-bg border border-borderLight hover:border-accent text-primary hover:text-white hover:bg-accent text-center rounded-full text-xs font-bold tracking-[0.1em] uppercase transition-all shadow-sm">
                            <i class="fa-solid fa-map-location-dot mr-2"></i> Get Directions
                        </a>
                    @endif
                </div>

                <!-- Resepsi / Events -->
                @if (!empty($content['events']) && is_array($content['events']))
                    @foreach ($content['events'] as $event)
                        <div class="bg-white p-12 rounded-[2.5rem] border border-borderLight hover:border-accent transition-all shadow-lg hover:shadow-2xl hover:-translate-y-2 group">
                            <div class="flex justify-between items-start mb-10">
                                <div>
                                    <span class="bg-bg text-accent px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] block mb-4 w-max">The Celebration</span>
                                    <h4 class="text-3xl md:text-4xl font-serif text-primary">{{ $event['title'] ?? 'Resepsi' }}</h4>
                                </div>
                                <div class="w-16 h-16 rounded-full bg-bg flex items-center justify-center shadow-inner border border-borderLight text-accent group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                    <i class="fa-solid fa-champagne-glasses text-2xl"></i>
                                </div>
                            </div>
                            <ul class="space-y-6 mb-12 text-sm text-secondary font-medium">
                                @if (!empty($event['date']))
                                    <li class="flex items-center gap-5"><div class="w-10 h-10 rounded-full bg-bg flex items-center justify-center text-accent"><i class="fa-regular fa-calendar text-lg"></i></div>
                                        {{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}</li>
                                @endif
                                @if (!empty($event['time']))
                                    <li class="flex items-center gap-5"><div class="w-10 h-10 rounded-full bg-bg flex items-center justify-center text-accent"><i class="fa-regular fa-clock text-lg"></i></div>
                                        {{ $event['time'] }} @if (!empty($event['time_end'])) - {{ $event['time_end'] }} @endif WIB</li>
                                @endif
                                @if (!empty($event['location']))
                                    <li class="flex items-start gap-5"><div class="w-10 h-10 rounded-full bg-bg flex items-center justify-center text-accent shrink-0"><i class="fa-solid fa-location-dot text-lg"></i></div>
                                        <span class="mt-2.5">{{ $event['location'] }}<br><span class="text-gray-400 text-xs font-light mt-1.5 block leading-relaxed">{{ $event['address'] ?? '' }}</span></span></li>
                                @endif
                            </ul>
                            @if (!empty($event['map']))
                                <a href="{{ $event['map'] }}" target="_blank" class="block w-full py-4 bg-primary hover:bg-accent text-white text-center rounded-full text-xs font-bold tracking-[0.1em] uppercase transition-all shadow-lg shadow-primary/20">
                                    <i class="fa-solid fa-map-location-dot mr-2"></i> Get Directions
                                </a>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- LIVE STREAMING -->
        @php 
            $activeStreams = collect($content['live_streams'] ?? [])->filter(function($s) {
                return !empty($s['link']) && !empty($s['platform']);
            })->values();
        @endphp

        @if (!empty($content['is_livestream_active']) && $activeStreams->count() > 0)
            <section id="live-streaming" class="py-24 px-6 bg-card relative overflow-hidden">
                <div class="max-w-5xl mx-auto relative z-10">
                    <div class="text-center mb-16">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-accent"></span>
                            </span>
                            <span class="text-xs font-bold uppercase tracking-[0.3em] text-accent">Virtual Wedding</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-serif text-primary mb-5">Siaran Langsung</h2>
                        <p class="text-secondary text-sm md:text-base max-w-2xl mx-auto leading-relaxed font-light">
                            Saksikan momen sakral kami secara virtual melalui platform pilihan Anda. Tekan tombol putar untuk bergabung dalam kebahagiaan kami.
                        </p>
                    </div>

                    @php 
                        $icons = [
                            'youtube' => 'fa-brands fa-youtube',
                            'instagram' => 'fa-brands fa-instagram',
                            'tiktok' => 'fa-brands fa-tiktok',
                            'zoom' => 'fa-solid fa-video',
                            'gmeet' => 'fa-solid fa-camera-retro',
                        ];
                        $firstStream = $activeStreams[0]; 
                    @endphp

                    <div class="relative group max-w-4xl mx-auto">
                        <div id="streaming-display" class="relative aspect-video w-full rounded-[2.5rem] bg-bg overflow-hidden border-[8px] border-bg shadow-2xl transition-all duration-700">
                            <div class="w-full h-full rounded-[2rem] overflow-hidden relative">
                                <div class="absolute inset-0 bg-cover bg-center transition-all duration-1000 opacity-40 group-hover:scale-110 group-hover:opacity-50" style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1000&q=80');"></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/30 to-transparent"></div>
                                <div class="relative h-full flex flex-col items-center justify-center p-6 text-white text-center">
                                    <div class="mb-6 group-hover:scale-110 transition-transform duration-500 bg-white/20 backdrop-blur-md border border-white/30 w-24 h-24 rounded-full flex items-center justify-center shadow-xl">
                                        <i id="platform-icon" class="{{ $icons[$firstStream['platform']] ?? 'fa-solid fa-play' }} text-5xl text-white"></i>
                                    </div>
                                    <h3 id="platform-title" class="text-3xl md:text-4xl font-serif mb-2 text-white capitalize">{{ $firstStream['platform'] }} Live</h3>
                                    <p id="platform-desc" class="text-white/80 text-sm md:text-base mb-8 font-medium tracking-wide">Klik tombol di bawah untuk menonton</p>
                                    <a id="platform-link" href="{{ $firstStream['link'] }}" target="_blank" class="flex items-center gap-3 px-10 py-4 bg-white text-primary rounded-full font-bold text-xs uppercase tracking-[0.1em] hover:bg-accent hover:text-white transition-all active:scale-95 shadow-xl">
                                        <i class="fa-solid fa-play"></i> <span>Putar Sekarang</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($activeStreams->count() > 1)
                    <div class="mt-20 max-w-4xl mx-auto">
                        <h4 class="text-secondary font-sans font-medium text-sm mb-6 text-center uppercase tracking-[0.2em]">Pilih Platform Lainnya</h4>
                        <div class="flex flex-wrap justify-center gap-5">
                            @foreach($activeStreams as $stream)
                                <button onclick="switchPlatform('{{ $stream['platform'] }}', '{{ ucfirst($stream['platform']) }} Live', 'Klik untuk menonton via {{ ucfirst($stream['platform']) }}', '{{ $icons[$stream['platform']] ?? 'fa-solid fa-play' }}', '{{ $stream['link'] }}')"
                                    class="platform-btn flex flex-col items-center bg-bg border border-borderLight hover:border-accent p-6 w-32 rounded-[2rem] transition-all group text-center shadow-sm hover:shadow-lg hover:-translate-y-1">
                                    <i class="{{ $icons[$stream['platform']] ?? 'fa-solid fa-play' }} text-3xl text-secondary group-hover:text-accent mb-4 transition-colors"></i>
                                    <span class="text-primary font-bold text-[10px] uppercase tracking-widest mb-1">{{ $stream['platform'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- GUEST STATS & WISHES -->
        <section id="guest-stats" class="py-24 px-6 bg-bg relative overflow-hidden">
            <div class="max-w-5xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <span class="text-accent text-xs font-bold uppercase tracking-[0.3em] mb-2 block">Guest Registry</span>
                    <h2 class="text-4xl md:text-5xl font-serif text-primary mb-4">Kehadiran & Doa</h2>
                    <div class="w-20 h-0.5 bg-accent mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-20 max-w-3xl mx-auto">
                    <div class="flex flex-col items-center p-10 bg-white border-2 border-borderLight rounded-[2.5rem] shadow-lg hover:shadow-xl transition-shadow hover:-translate-y-1 duration-300">
                        <div class="w-14 h-14 bg-bg rounded-full flex items-center justify-center mb-6 text-accent"><i class="fa-solid fa-user-check text-2xl"></i></div>
                        <h4 id="total-attendance" class="text-6xl md:text-7xl font-serif text-primary mb-3">{{ $totalAttendance ?? 0 }}</h4>
                        <p class="text-xs uppercase tracking-[0.3em] text-secondary font-bold">Tamu Hadir</p>
                    </div>

                    <div class="flex flex-col items-center p-10 bg-white border-2 border-borderLight rounded-[2.5rem] shadow-lg hover:shadow-xl transition-shadow hover:-translate-y-1 duration-300">
                        <div class="w-14 h-14 bg-bg rounded-full flex items-center justify-center mb-6 text-accent"><i class="fa-solid fa-envelope-open-text text-2xl"></i></div>
                        <h4 id="total-wishes" class="text-6xl md:text-7xl font-serif text-primary mb-3">{{ $totalWishes ?? 0 }}</h4>
                        <p class="text-xs uppercase tracking-[0.3em] text-secondary font-bold">Ucapan Hangat</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-borderLight shadow-2xl max-w-4xl mx-auto overflow-hidden">
                    <div class="flex items-center justify-between p-8 border-b border-borderLight bg-card">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-bg rounded-full flex items-center justify-center text-accent"><i class="fa-solid fa-heart"></i></div>
                            <span class="text-sm font-bold uppercase tracking-[0.2em] text-primary">Wishes Wall</span>
                        </div>
                        <span class="bg-bg px-4 py-1.5 rounded-full text-[10px] text-secondary uppercase font-bold tracking-widest border border-borderLight">Recent</span>
                    </div>

                    <div id="wishes-container" class="max-h-[500px] overflow-y-auto scroll-custom p-8 space-y-5 bg-bg/30">
                        @forelse($dbWishes as $wish)
                            <div class="flex flex-col md:flex-row gap-6 p-6 rounded-[1.5rem] bg-white border border-borderLight hover:border-accent hover:shadow-lg transition-all group">
                                <div class="w-14 h-14 md:w-16 md:h-16 bg-bg border border-borderLight rounded-full shrink-0 flex items-center justify-center shadow-inner group-hover:bg-accent group-hover:text-white transition-colors">
                                    <i class="fa-regular fa-comment-dots text-xl text-accent group-hover:text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <h5 class="font-bold text-primary font-serif text-lg">{{ $wish->guest_name }}</h5>
                                        <span class="text-[10px] text-secondary">{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}</span>
                                    </div>
                                    @if($wish->status_rsvp == 'hadir')
                                        <span class="inline-block mb-3 px-3 py-1 bg-accent/10 text-accent rounded-full text-[10px] font-bold tracking-widest uppercase"><i class="fa-solid fa-check mr-1"></i> Hadir ({{ $wish->pax }} Orang)</span>
                                    @endif
                                    <p class="text-sm text-secondary font-light leading-relaxed italic">"{{ $wish->message }}"</p>
                                </div>
                            </div>
                        @empty
                            <p id="empty-wishes-msg" class="text-center text-secondary text-sm">Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- KIRIM KADO -->
        @if (!empty($content['is_gift_active']))
        <section id="hadiah" class="py-24 px-6 bg-card relative overflow-hidden">
            <div class="max-w-5xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <span class="text-[10px] tracking-[0.4em] uppercase text-accent font-bold block mb-3">Tanda Kasih</span>
                    <h2 class="text-4xl md:text-5xl font-serif text-primary mb-6">Wedding Gift</h2>
                    <div class="w-20 h-0.5 bg-accent mx-auto mb-6 rounded-full"></div>
                    <p class="text-sm md:text-base text-secondary font-light leading-relaxed max-w-2xl mx-auto">
                        Doa restu Anda adalah karunia terindah bagi kami. Namun jika Anda ingin memberikan tanda kasih,
                        pintu hati kami terbuka melalui jalur resmi berikut:
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-4xl mx-auto">
                    @if (!empty($content['banks']) && is_array($content['banks']))
                        @foreach ($content['banks'] as $index => $bank)
                            <div class="group relative bg-bg rounded-[2.5rem] overflow-hidden border border-borderLight transition-all duration-500 hover:border-accent hover:shadow-2xl p-10 md:p-12 text-center">
                                <div class="space-y-3 mb-10">
                                    <h3 id="rek-{{ $index + 1 }}" class="text-3xl md:text-4xl font-sans font-medium text-primary tracking-widest">
                                        {{ $bank['account_number'] ?? '' }}</h3>
                                    <p class="text-[11px] text-secondary font-bold uppercase tracking-[0.2em] bg-white inline-block px-4 py-1.5 rounded-full border border-borderLight">
                                        {{ $bank['name'] ?? 'Bank' }} - a.n {{ $bank['account_name'] ?? '' }}
                                    </p>
                                </div>
                                <button onclick="copyToClipboard('rek-{{ $index + 1 }}', this)"
                                    class="w-full py-4 bg-white shadow-md border border-borderLight text-primary rounded-full font-bold text-xs uppercase tracking-[0.2em] transition-all hover:bg-primary hover:text-white flex items-center justify-center gap-3">
                                    <i class="fa-regular fa-copy"></i>
                                    <span>Salin Nomor</span>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- ALAMAT KADO FISIK -->
                @if (!empty($content['alamat_kado']) || !empty($content['gifts']))
                <div class="mt-20 flex flex-col items-center gap-8">
                    <div class="group relative p-12 bg-white rounded-[3rem] border border-borderLight max-w-2xl w-full transition-all duration-500 hover:border-accent hover:shadow-2xl shadow-lg">
                        <div class="w-20 h-20 bg-bg rounded-full flex items-center justify-center mx-auto mb-8 border border-borderLight group-hover:bg-accent group-hover:text-white transition-colors text-accent shadow-inner">
                            <i class="fa-solid fa-box-open text-3xl"></i>
                        </div>
                        <p class="text-[10px] text-center uppercase tracking-[0.4em] text-accent mb-4 font-bold">Alamat Pengiriman</p>
                        <div id="alamat-kado" class="text-lg md:text-xl text-primary font-serif italic text-center leading-relaxed mb-12">
                            {!! !empty($content['alamat_kado']) ? nl2br(e($content['alamat_kado'])) : "Alamat belum ditambahkan." !!}
                        </div>
                        <div class="flex flex-col sm:flex-row justify-center gap-5">
                            @if(!empty($content['alamat_kado']))
                            <button onclick="copyToClipboard('alamat-kado', this)" class="px-8 py-4 bg-bg text-primary border border-borderLight rounded-full font-bold text-[10px] uppercase tracking-[0.2em] transition-all hover:bg-borderLight active:scale-95 flex items-center justify-center gap-3 shadow-sm">
                                <i class="fa-regular fa-copy text-lg"></i> Salin Alamat
                            </button>
                            @endif
                            
                            @if(!empty($content['gifts']))
                            <button onclick="toggleGiftModal(true)" class="px-8 py-4 bg-primary text-white rounded-full font-bold text-[10px] uppercase tracking-[0.2em] transition-all hover:bg-accent active:scale-95 shadow-xl shadow-primary/20 flex items-center justify-center gap-3">
                                <i class="fa-solid fa-list text-lg"></i> Daftar Kebutuhan
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- MODAL WISHLIST KADO -->
            <div id="gift-modal" class="fixed inset-0 z-[500] hidden items-center justify-center p-4">
                <div class="absolute inset-0 bg-primary/40 backdrop-blur-md" onclick="toggleGiftModal(false)"></div>
                <div class="relative bg-bg w-full max-w-lg rounded-[2.5rem] border border-borderLight overflow-hidden shadow-2xl flex flex-col max-h-[85vh] animate-slide-up">
                    <div class="p-8 border-b border-borderLight bg-white sticky top-0 z-20">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-3xl font-serif text-primary">Wishlist Kami</h3>
                                <p class="text-[10px] text-accent uppercase tracking-[0.3em] mt-2 font-bold">Wedding Registry</p>
                            </div>
                            <button onclick="toggleGiftModal(false)" class="w-12 h-12 flex items-center justify-center rounded-full bg-bg border border-borderLight text-secondary hover:text-white hover:bg-accent transition-all shadow-sm">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-5">
                        @foreach ($content['gifts'] ?? [] as $index => $gift)
                            <div id="item-{{ $index + 1 }}" class="p-6 rounded-[1.5rem] border border-borderLight bg-white flex items-center justify-between gap-4 transition-all hover:shadow-lg hover:-translate-y-1 group">
                                <div>
                                    <h4 class="text-base font-bold text-primary tracking-wide group-hover:text-accent transition-colors">{{ $gift['item_name'] ?? 'Gift Item' }}</h4>
                                    <p class="text-[11px] text-secondary font-medium mt-2">{{ $gift['description'] ?? '' }}</p>
                                </div>
                                <button onclick="confirmGift('item-{{ $index + 1 }}', '{{ addslashes($gift['item_name'] ?? 'Gift Item') }}')" class="shrink-0 px-6 py-3 bg-bg border border-borderLight text-primary rounded-full text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                                    Pilih
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MODAL KONFIRMASI KADO -->
            <div id="confirm-modal" class="fixed inset-0 z-[600] hidden items-center justify-center p-6">
                <div class="absolute inset-0 bg-primary/60 backdrop-blur-xl"></div>
                <div class="relative bg-white w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl border border-borderLight animate-slide-up max-h-[90vh] flex flex-col">
                    <div class="p-8 text-center border-b border-borderLight shrink-0">
                        <div class="w-16 h-16 bg-bg border border-borderLight shadow-sm text-accent rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-heart text-2xl"></i>
                        </div>
                        <h4 class="text-2xl font-serif text-primary mb-2">Konfirmasi Kirim</h4>
                        <p id="confirm-text" class="text-xs text-secondary font-light leading-relaxed">
                            Apakah Anda yakin ingin mengirimkan kado ini?
                        </p>
                    </div>
                    <div class="p-8 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-secondary font-semibold block mb-2">Nama Pengirim</label>
                            <input id="gift-confirm-name" type="text" value="{{ $guestNameInput }}" class="w-full bg-bg border border-borderLight rounded-full px-5 py-3 text-sm text-primary outline-none focus:border-accent transition-all" placeholder="Nama Anda" />
                        </div>
                        
                        <div>
                            <label class="text-[10px] uppercase tracking-widest text-secondary font-semibold block mb-2">Status Kehadiran</label>
                            <div class="flex gap-4">
                                <button type="button" id="gift-btn-hadir" onclick="selectGiftAttendance('hadir')" class="flex-1 py-3 rounded-full border border-primary bg-primary text-white text-sm font-bold transition-all active:scale-95">Hadir</button>
                                <button type="button" id="gift-btn-absen" onclick="selectGiftAttendance('tidak_hadir')" class="flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95">Tidak Hadir</button>
                            </div>
                            <input type="hidden" id="gift-confirm-status" value="hadir">
                        </div>

                        <div id="gift-confirm-pax-wrapper" class="space-y-5">
                            <div>
                                <label class="text-[10px] uppercase tracking-widest text-secondary font-semibold block mb-2">Jumlah Orang (Hadir)</label>
                                <div class="flex gap-2">
                                    <button type="button" onclick="setGiftPax(1)" class="gift-pax-btn flex-1 py-3 rounded-full border border-primary bg-primary text-white text-sm font-bold transition-all active:scale-95">1</button>
                                    <button type="button" onclick="setGiftPax(2)" class="gift-pax-btn flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95">2</button>
                                    <button type="button" onclick="setGiftPax(3)" class="gift-pax-btn flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95">3</button>
                                    <button type="button" onclick="setGiftPax('custom')" class="gift-pax-btn flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95">3+</button>
                                </div>
                                <div id="gift-custom-pax-container" class="hidden mt-3">
                                    <input type="number" id="gift-custom-pax-input" placeholder="Masukkan jumlah spesifik (Misal: 4)" class="w-full bg-white border border-borderLight focus:border-accent px-5 py-3 rounded-full text-primary text-sm outline-none transition-all text-center" min="4">
                                </div>
                            </div>

                            <div class="bg-bg p-4 rounded-2xl border border-borderLight text-center">
                                <p class="text-[10px] text-secondary font-semibold uppercase tracking-widest mb-1">Total</p>
                                <p id="gift-pax-display" class="text-xl font-serif text-primary">1 Orang</p>
                            </div>
                            <input type="hidden" id="gift-confirm-pax" value="1">
                        </div>
                        
                        <div class="flex flex-col gap-3 mt-4">
                            <button id="final-confirm-btn" class="w-full py-4 bg-primary text-white rounded-full font-bold text-[10px] uppercase tracking-[0.2em] transition-all hover:bg-accent shadow-xl active:scale-95">
                                Ya, Saya Bersedia
                            </button>
                            <button onclick="closeConfirmModal()" class="w-full py-4 bg-white text-secondary font-bold text-[10px] uppercase tracking-[0.2em] hover:text-primary transition-all border border-borderLight hover:border-primary rounded-full shadow-sm active:scale-95">
                                Batalkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUCCESS KADO -->
            <div id="gift-success-modal" class="fixed inset-0 z-[700] hidden items-center justify-center p-4">
                <div class="absolute inset-0 bg-primary/40 backdrop-blur-md"></div>
                <div class="relative bg-white w-full max-w-md rounded-[2.5rem] border border-borderLight shadow-2xl overflow-hidden animate-slide-up">
                    <div class="p-10 text-center">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 h-20 rounded-full bg-accent/10 border-2 border-accent flex items-center justify-center">
                                <i class="fa-solid fa-check text-accent text-4xl"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-serif text-primary mb-3">Sukses!</h3>
                        <p id="gift-success-message" class="text-secondary text-sm leading-relaxed mb-8">Kado Anda telah tercatat.</p>
                        <button onclick="closeGiftSuccessModal()" class="w-full py-4 bg-accent text-white rounded-full font-semibold uppercase tracking-[0.2em] text-xs hover:bg-primary transition-all active:scale-95">Tutup</button>
                    </div>
                </div>
            </div>
        </section>
        @endif


        <!-- GUEST INFO / ADAB WALIMAH -->
        @if (!empty($content['is_guest_info_active']))
        <section id="guest-info" class="py-24 px-6 md:px-20 bg-card relative overflow-hidden">
            <div class="max-w-6xl mx-auto relative z-10">
                <div class="mb-16 text-center md:text-left">
                    <span class="text-accent text-xs font-bold uppercase tracking-[0.3em] mb-2 block">Guidelines</span>
                    <h2 class="text-4xl md:text-5xl font-serif text-primary mb-4">Informasi Tamu</h2>
                    <div class="w-20 h-0.5 bg-accent mx-auto md:mx-0 mb-6 rounded-full"></div>
                    <p class="text-secondary font-light max-w-xl">Hal-hal yang perlu diperhatikan demi kenyamanan dan kekhidmatan acara.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="lg:col-span-2 space-y-12">
                        @if (!empty($content['enable_dresscode']))
                            <div class="bg-bg p-10 rounded-[2.5rem] border border-borderLight shadow-sm hover:shadow-lg transition-shadow">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-accent shadow-sm border border-borderLight"><i class="fa-solid fa-shirt text-xl"></i></div>
                                    <h4 class="text-primary uppercase tracking-[0.2em] text-sm font-bold">Dresscode</h4>
                                </div>
                                <p class="text-secondary text-lg font-light leading-relaxed">
                                    <span class="font-bold text-primary">{{ $content['dresscode'] ?? 'Formal & Elegant.' }}</span>
                                </p>
                            </div>
                        @endif

                        @if (!empty($content['enable_health_protocol']))
                            <div>
                                <h5 class="text-primary text-sm font-bold uppercase tracking-[0.2em] mb-8 border-b border-borderLight pb-3">Protokol Kesehatan</h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                    <div class="flex flex-col items-center text-center gap-4 bg-bg p-8 rounded-[2rem] border border-borderLight shadow-sm hover:border-accent transition-colors">
                                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-accent shadow-inner"><i class="fa-solid fa-hands-bubbles text-2xl"></i></div>
                                        <span class="text-primary text-[10px] uppercase font-bold tracking-widest">Cuci Tangan</span>
                                    </div>
                                    <div class="flex flex-col items-center text-center gap-4 bg-bg p-8 rounded-[2rem] border border-borderLight shadow-sm hover:border-accent transition-colors">
                                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-accent shadow-inner"><i class="fa-solid fa-head-side-mask text-2xl"></i></div>
                                        <span class="text-primary text-[10px] uppercase font-bold tracking-widest">Pakai Masker</span>
                                    </div>
                                    <div class="flex flex-col items-center text-center gap-4 bg-bg p-8 rounded-[2rem] border border-borderLight shadow-sm hover:border-accent transition-colors">
                                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-accent shadow-inner"><i class="fa-solid fa-people-arrows text-2xl"></i></div>
                                        <span class="text-primary text-[10px] uppercase font-bold tracking-widest">Jaga Jarak</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!empty($content['enable_adab_walimah']))
                    <div class="space-y-10 bg-bg p-10 rounded-[2.5rem] border border-borderLight shadow-sm">
                        <h5 class="text-primary text-sm font-bold uppercase tracking-[0.2em] flex items-center gap-4 border-b border-borderLight pb-4">
                            <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-accent"><i class="fa-solid fa-list-check text-sm"></i></div> Adab Walimah
                        </h5>
                        <div class="space-y-8">
                            <div class="flex gap-5 items-start bg-white p-5 rounded-[1.5rem] shadow-sm border border-borderLight">
                                <div class="text-accent text-xl w-8 shrink-0 mt-1 flex justify-center"><i class="fa-solid fa-mosque"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-xs uppercase tracking-widest">Waktu Sholat</p>
                                    <p class="text-secondary text-xs leading-relaxed mt-2 font-light">Memperhatikan waktu ibadah saat acara.</p>
                                </div>
                            </div>
                            <div class="flex gap-5 items-start bg-white p-5 rounded-[1.5rem] shadow-sm border border-borderLight">
                                <div class="text-accent text-xl w-8 shrink-0 mt-1 flex justify-center"><i class="fa-solid fa-utensils"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-xs uppercase tracking-widest">Adab Makan</p>
                                    <p class="text-secondary text-xs leading-relaxed mt-2 font-light">Makan & minum dengan cara duduk sopan.</p>
                                </div>
                            </div>
                            <div class="flex gap-5 items-start bg-white p-5 rounded-[1.5rem] shadow-sm border border-borderLight">
                                <div class="text-accent text-xl w-8 shrink-0 mt-1 flex justify-center"><i class="fa-solid fa-hands-praying"></i></div>
                                <div>
                                    <p class="text-primary font-bold text-xs uppercase tracking-widest">Doa Restu</p>
                                    <p class="text-secondary text-xs leading-relaxed mt-2 font-light">Memberikan doa keberkahan bagi kami.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        <!-- QR CODE -->
        @if (!empty($content['enable_qr_attendance']) && $guestData)
        <section id="qr-tamu" class="py-24 px-6 bg-bg border-y border-borderLight rounded-t-[3rem] shadow-[0_-10px_40px_rgba(0,0,0,0.02)] relative overflow-hidden z-20">
            <div class="max-w-4xl mx-auto text-center relative z-10">
                <div class="mb-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white border-2 border-borderLight mb-8 shadow-lg text-accent">
                        <i class="fa-solid fa-qrcode text-3xl"></i>
                    </div>
                    <span class="text-accent text-xs font-bold uppercase tracking-[0.3em] mb-2 block">Your Pass</span>
                    <h2 class="text-4xl md:text-5xl font-serif text-primary mb-5">Akses Undangan</h2>
                    <div class="h-0.5 w-20 rounded-full bg-accent mx-auto mb-6"></div>
                    <p class="text-secondary text-sm font-light leading-relaxed max-w-md mx-auto">
                        Tunjukkan kode QR di bawah ini kepada penerima tamu untuk verifikasi kehadiran.
                    </p>
                </div>
                <div class="flex justify-center">
                    <div class="bg-white p-12 rounded-[3rem] border border-borderLight shadow-2xl max-w-sm w-full mx-auto relative before:absolute before:-inset-2 before:bg-white/50 before:rounded-[3.5rem] before:-z-10 before:border before:border-borderLight">
                        <div class="relative flex flex-col items-center">
                            <div class="p-5 border-2 border-borderLight rounded-[2rem] mb-10 shadow-inner bg-bg">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $guestData->qr_code ?? $guestData->slug_name }}" class="w-48 h-48 object-contain rounded-xl" alt="QR Code Tamu">
                            </div>
                            <div class="space-y-3 mb-10 text-center bg-bg w-full py-4 rounded-[1.5rem] border border-borderLight">
                                <span class="text-[10px] uppercase tracking-[0.4em] text-accent font-bold block">Guest Identity</span>
                                <h3 class="text-2xl font-serif text-primary italic">{{ $guestData->name }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif


        <!-- RSVP MODAL & FORM -->
        <section id="rsvp-modal" class="fixed inset-0 z-[500] invisible transition-all duration-500 overflow-hidden flex items-end md:items-center justify-center">
            <div onclick="closeRSVP()" class="absolute inset-0 bg-primary/80 backdrop-blur-md opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>

            <div id="rsvp-content" class="relative w-full md:max-w-xl lg:max-w-2xl h-[92vh] md:h-auto max-h-[95vh] bg-bg rounded-t-[3rem] md:rounded-[3rem] border border-borderLight shadow-2xl transform translate-y-full transition-transform duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] flex flex-col">
                <div class="overflow-y-auto px-8 md:px-10 pb-12 pt-8 custom-scrollbar">
                    <div class="w-16 h-1.5 bg-borderLight rounded-full mx-auto mb-10 md:hidden"></div>
                    <div class="text-center mb-10 border-b border-borderLight pb-8">
                        <h2 class="text-4xl font-serif text-primary mb-3">RSVP</h2>
                        <p class="text-accent text-xs uppercase tracking-[0.3em] font-bold">Confirmation & Wishes</p>
                    </div>

                    <form id="form-rsvp" class="space-y-8" onsubmit="submitRsvp(event)">
                        @csrf
                        <input type="hidden" id="input-pax-rsvp" value="1">
                        
                        <div>
                            <label class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] ml-2 block mb-3">Nama Lengkap</label>
                            <input type="text" id="input-nama-rsvp" value="{{ $guestNameInput }}" placeholder="Masukkan nama Anda" required
                                class="w-full bg-white border border-borderLight focus:border-accent px-5 py-4 rounded-full text-primary text-sm outline-none transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] ml-2 block mb-3">Konfirmasi Kehadiran</label>
                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" onclick="selectAttendance('hadir')" id="btn-hadir" class="py-4 rounded-full border-2 border-borderLight text-xs font-bold uppercase tracking-[0.2em] transition-all bg-white text-secondary active:scale-95 hover:border-accent shadow-sm">
                                    <i class="fa-solid fa-check mr-2"></i> Hadir
                                </button>
                                <button type="button" onclick="selectAttendance('tidak_hadir')" id="btn-absen" class="py-4 rounded-full border-2 border-borderLight text-xs font-bold uppercase tracking-[0.2em] transition-all bg-white text-secondary active:scale-95 hover:border-accent shadow-sm">
                                    <i class="fa-solid fa-xmark mr-2"></i> Absen
                                </button>
                            </div>
                        </div>

                        <!-- Logic 1,2,3,3+ RSVP -->
                        <div id="guest-selection" class="hidden transition-all duration-500 overflow-hidden">
                            <div class="bg-white p-6 rounded-[2rem] border border-borderLight shadow-sm">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-primary text-center font-bold mb-4">Jumlah Tamu Hadir</p>
                                <div class="flex gap-2">
                                    <button type="button" class="guest-btn flex-1 py-3 rounded-full border border-primary bg-primary text-white text-sm font-bold transition-all active:scale-95">1</button>
                                    <button type="button" class="guest-btn flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95">2</button>
                                    <button type="button" class="guest-btn flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95">3</button>
                                    <button type="button" class="guest-btn flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95">3+</button>
                                </div>
                                <div id="custom-pax-container" class="hidden mt-3">
                                    <input type="number" id="custom-pax-input" placeholder="Ketik jumlah spesifik (misal: 4)" class="w-full bg-bg border border-borderLight focus:border-accent px-5 py-3 rounded-full text-primary text-sm outline-none transition-all text-center" min="4">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] ml-2 block mb-3">Doa & Ucapan</label>
                            <textarea id="input-pesan-rsvp" rows="4" placeholder="Tuliskan pesan bahagia..." required
                                class="w-full bg-white border border-borderLight focus:border-accent p-6 rounded-[2rem] text-primary text-sm outline-none transition-all resize-none shadow-sm"></textarea>
                        </div>

                        <div class="flex flex-col gap-3 pt-6 border-t border-borderLight">
                            <button type="submit" class="w-full py-5 bg-primary text-white rounded-full font-bold text-xs uppercase tracking-[0.3em] shadow-xl hover:bg-accent active:scale-95 transition-all">
                                Kirim Konfirmasi
                            </button>
                            <button type="button" onclick="closeRSVP()" class="w-full py-4 bg-white text-secondary border border-borderLight hover:border-primary rounded-full font-bold text-[10px] uppercase tracking-[0.2em] hover:text-primary transition-all shadow-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- SUCCESS MODAL RSVP -->
        <div id="rsvp-success-modal" class="fixed inset-0 z-[601] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-primary/40 backdrop-blur-md"></div>
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] border border-borderLight shadow-2xl overflow-hidden animate-slide-up">
                <div class="p-10 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-accent/10 border-2 border-accent flex items-center justify-center">
                            <i class="fa-solid fa-check text-accent text-4xl"></i>
                        </div>
                    </div>
                    <h3 class="text-3xl font-serif text-primary mb-3">Sukses!</h3>
                    <p id="rsvp-success-message" class="text-secondary text-sm leading-relaxed mb-8">RSVP Anda telah tersimpan.</p>
                    <button onclick="closeRsvpSuccessModal()" class="w-full py-4 bg-accent text-white rounded-full font-semibold uppercase tracking-[0.2em] text-xs hover:bg-primary transition-all active:scale-95">Tutup</button>
                </div>
            </div>
        </div>

        <footer class="py-24 px-6 bg-card border-t-0 text-center relative overflow-hidden">
            <div class="max-w-4xl mx-auto relative z-10">
                <div class="mb-14 flex flex-col items-center">
                    <div class="flex items-center gap-6 mb-5">
                        <span class="w-16 h-0.5 rounded-full bg-accent"></span>
                        <h2 class="text-5xl md:text-6xl font-serif text-primary uppercase tracking-[0.3em]">{{ substr($gNickname, 0, 1) }} & {{ substr($bNickname, 0, 1) }}</h2>
                        <span class="w-16 h-0.5 rounded-full bg-accent"></span>
                    </div>
                </div>
                <div class="mb-20">
                    <p class="text-lg md:text-xl font-serif italic text-secondary mb-10 leading-relaxed max-w-xl mx-auto px-4 bg-bg py-8 rounded-[2rem] border border-borderLight shadow-sm">
                        "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu."
                    </p>
                </div>
                <div class="mb-20 space-y-4">
                    <h3 class="text-3xl md:text-4xl font-serif text-primary">{{ $gNickname }} & {{ $bNickname }}</h3>
                    <p class="text-[11px] text-accent uppercase tracking-[0.3em] font-bold">Beserta Keluarga Besar</p>
                </div>
                
                @if(!$isPreviewMode)
                <div class="flex flex-col items-center">
                    <div class="p-8 rounded-[2rem] bg-white border border-borderLight w-full max-w-xs shadow-lg relative before:absolute before:-inset-1 before:border before:border-borderLight before:rounded-[2.2rem] before:-z-10">
                        <p class="text-[10px] text-secondary font-bold uppercase tracking-[0.3em] mb-4">Created By</p>
                        <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank" rel="noopener noreferrer" class="block group">
                            <div class="flex items-center justify-center gap-3 text-sm font-bold text-primary group-hover:text-accent transition-colors bg-bg py-2.5 rounded-full">
                                <i class="fa-brands fa-instagram text-xl"></i>
                                <span class="tracking-widest">@ruangrestu.undangan</span>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </footer>
    </main>

    <!-- FAB & BOTTOM NAV -->
    <div id="fab-container" class="fixed right-6 bottom-28 flex flex-col gap-5 z-40 opacity-0 transition-opacity duration-1000">
        <div class="relative flex items-center group">
            <div id="music-info" class="absolute right-full mr-4 px-5 py-2.5 bg-white/90 backdrop-blur-md border border-borderLight rounded-full shadow-lg text-primary text-xs whitespace-nowrap opacity-0 translate-x-4 pointer-events-none transition-all duration-500 group-hover:opacity-100 group-hover:translate-x-0 font-bold tracking-wide">
                {{ $invitation->music->name ?? $invitation->music->title ?? 'Lagu Pernikahan' }} {{ !empty($invitation->music->artist) ? '- ' . $invitation->music->artist : '' }}
            </div>
            <button id="btn-music" onclick="toggleMusic()" class="w-14 h-14 bg-white border border-borderLight rounded-full flex items-center justify-center text-accent shadow-xl hover:bg-bg hover:scale-105 transition-all">
                <i class="fa-solid fa-music animate-spin-slow text-lg" id="icon-music"></i>
            </button>
        </div>
        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-14 h-14 bg-white border border-borderLight rounded-full flex items-center justify-center text-accent shadow-xl hover:bg-bg hover:scale-105 transition-all">
            <i class="fa-solid fa-angles-down text-lg" id="icon-scroll"></i>
        </button>
    </div>

    <!-- LIGHTBOX -->
    <div id="lightbox" class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-white/95 backdrop-blur-xl p-4 transition-all duration-500">
        <div class="w-full flex justify-between items-center p-8 absolute top-0 left-0">
            <span class="text-primary font-bold tracking-[0.2em] text-sm bg-bg px-5 py-2 rounded-full border border-borderLight shadow-sm"><span id="current-count">1</span> / <span id="total-count">4</span></span>
            <button onclick="closeLightbox()" class="w-12 h-12 bg-bg border border-borderLight rounded-full text-primary hover:text-white hover:bg-accent flex items-center justify-center transition-all shadow-sm">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="relative w-full max-w-5xl flex items-center justify-center h-[80vh] mt-10">
            <img id="lightbox-img" src="" class="max-h-full max-w-full object-contain transition-opacity duration-300 shadow-2xl rounded-2xl" alt="Zoomed Photo">
        </div>
        <div class="absolute bottom-10 flex gap-6">
            <button onclick="prevImg()" class="w-14 h-14 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                <i class="fa-solid fa-chevron-left text-xl"></i>
            </button>
            <button onclick="nextImg()" class="w-14 h-14 rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                <i class="fa-solid fa-chevron-right text-xl"></i>
            </button>
        </div>
    </div>

    <nav id="bottom-nav" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-white/90 backdrop-blur-xl border border-borderLight rounded-full shadow-2xl p-1.5">
            <ul class="flex justify-around items-center h-16 w-[320px] md:w-[400px]">
                <li class="relative group h-full">
                    <a href="#home" class="nav-link flex items-center justify-center w-16 h-full text-secondary hover:text-primary transition-all rounded-full hover:bg-bg hover:shadow-inner"><i class="fa-solid fa-house text-xl"></i></a>
                </li>
                @if (!empty($content['is_gallery_active']))
                <li class="relative group h-full">
                    <a href="#gallery" class="nav-link flex items-center justify-center w-16 h-full text-secondary hover:text-primary transition-all rounded-full hover:bg-bg hover:shadow-inner"><i class="fa-solid fa-image text-xl"></i></a>
                </li>
                @endif
                <li class="relative group h-full">
                    <a href="#lokasi" class="nav-link flex items-center justify-center w-16 h-full text-secondary hover:text-primary transition-all rounded-full hover:bg-bg hover:shadow-inner"><i class="fa-solid fa-location-dot text-xl"></i></a>
                </li>
                <li class="relative h-full px-2 flex items-center">
                    <a href="javascript:void(0)" onclick="openRSVP()" class="flex items-center justify-center px-8 h-12 bg-primary text-white rounded-full text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-accent shadow-lg transition-all active:scale-95">RSVP</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="copy-toast" class="fixed bottom-28 left-1/2 -translate-x-1/2 z-[300] px-8 py-4 bg-primary text-white text-xs rounded-full shadow-2xl font-bold uppercase tracking-[0.2em] opacity-0 translate-y-10 transition-all duration-500 pointer-events-none flex items-center gap-3">
        <i class="fa-solid fa-check-circle"></i> Tersalin!
    </div>

    <!-- SCRIPTS -->
    <script>
        function copyToClipboard(elementId, btn) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                const originalContent = btn.innerHTML;
                btn.classList.replace('bg-white', 'bg-accent');
                btn.classList.replace('text-primary', 'text-white');
                btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Tersalin!</span>';
                showToast('Tersalin ke Clipboard!');
                setTimeout(() => {
                    btn.classList.replace('bg-accent', 'bg-white');
                    btn.classList.replace('text-white', 'text-primary');
                    btn.innerHTML = originalContent;
                }, 2500);
            });
        }

        function showToast(message) {
            const toast = document.getElementById('copy-toast');
            if (toast) {
                toast.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + message;
                toast.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
                toast.classList.add('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
                    toast.classList.remove('opacity-100', 'translate-y-0');
                }, 2600);
            }
        }
    </script>
    
    <script>
        // Media & Scroll Controls
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
            
            // Tampilkan info musik selama 3 detik lalu sembunyikan lagi saat awal buka
            const musicInfo = document.getElementById('music-info');
            if(musicInfo) {
                setTimeout(() => {
                    musicInfo.classList.remove('opacity-0', 'translate-x-4', 'pointer-events-none');
                    musicInfo.classList.add('opacity-100', 'translate-x-0');
                    setTimeout(() => {
                        musicInfo.classList.remove('opacity-100', 'translate-x-0');
                        musicInfo.classList.add('opacity-0', 'translate-x-4', 'pointer-events-none');
                        setTimeout(() => musicInfo.classList.remove('pointer-events-none'), 500);
                    }, 3000);
                }, 1200);
            }
        }

        function toggleMusic(forcePlay = false) {
            const btn = document.getElementById('btn-music');
            const icon = document.getElementById('icon-music');
            if (isMusicPlaying && !forcePlay) {
                audio.pause();
                isMusicPlaying = false;
                icon.classList.remove('fa-music', 'animate-spin');
                icon.classList.add('fa-volume-xmark');
                btn.classList.remove('bg-primary', 'text-white');
                btn.classList.add('bg-white', 'text-accent');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.remove('fa-volume-xmark');
                    icon.classList.add('fa-music', 'animate-spin');
                    btn.classList.add('bg-primary', 'text-white');
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
                btn.classList.remove('bg-primary', 'text-white');
                btn.classList.add('bg-white', 'text-accent');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-angles-down');
            } else {
                isAutoScrolling = true;
                btn.classList.add('bg-primary', 'text-white');
                btn.classList.remove('bg-white', 'text-accent');
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

        // Lightbox
        const images = Array.from(document.querySelectorAll('.gallery-img')).map(img => img.src);
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

        function nextImg() { currentIndex = (currentIndex + 1) % images.length; updateLightbox(); }
        function prevImg() { currentIndex = (currentIndex - 1 + images.length) % images.length; updateLightbox(); }

        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').classList.contains('hidden')) return;
            if (e.key === "ArrowRight") nextImg();
            if (e.key === "ArrowLeft") prevImg();
            if (e.key === "Escape") closeLightbox();
        });
    </script>
    
    <script>
        // RSVP & LOGIKA PAX 1,2,3,3+ DENGAN AUTO APPEND WISHES
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
            const paxInput = document.getElementById('input-pax-rsvp');

            [btnHadir, btnAbsen].forEach(btn => {
                btn.classList.remove('bg-primary', 'text-white', 'border-primary');
                btn.classList.add('bg-white', 'text-secondary', 'border-borderLight');
            });

            if (status === 'hadir') {
                btnHadir.classList.remove('bg-white', 'text-secondary', 'border-borderLight');
                btnHadir.classList.add('bg-primary', 'text-white', 'border-primary');
                guestDiv.classList.remove('hidden');
                
                // Trigger 1 pax as default if none selected
                if (!document.querySelector('.guest-btn.bg-primary')) {
                    document.querySelectorAll('.guest-btn')[0].click();
                }
            } else {
                btnAbsen.classList.remove('bg-white', 'text-secondary', 'border-borderLight');
                btnAbsen.classList.add('bg-primary', 'text-white', 'border-primary');
                guestDiv.classList.add('hidden');
                paxInput.value = 0;
            }
        }

        // Logic Pax RSVP (1, 2, 3, 3+)
        document.querySelectorAll('.guest-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.guest-btn').forEach(b => {
                    b.classList.remove('bg-primary', 'text-white', 'border-primary');
                    b.classList.add('bg-bg', 'text-secondary', 'border-borderLight');
                });
                this.classList.remove('bg-bg', 'text-secondary', 'border-borderLight');
                this.classList.add('bg-primary', 'text-white', 'border-primary');
                
                const paxValue = this.textContent.trim();
                const customContainer = document.getElementById('custom-pax-container');
                const paxInput = document.getElementById('input-pax-rsvp');
                
                if (paxValue === '3+') {
                    customContainer.classList.remove('hidden');
                    paxInput.value = document.getElementById('custom-pax-input').value || 4; 
                } else {
                    customContainer.classList.add('hidden');
                    paxInput.value = parseInt(paxValue);
                }
            });
        });

        document.getElementById('custom-pax-input')?.addEventListener('input', function() {
            document.getElementById('input-pax-rsvp').value = this.value || 4;
        });

        // FUNGSI INJEK ELEMEN BARU SECARA DINAMIS
        function appendNewWish(guestName, statusRsvp, pax, message) {
            const container = document.getElementById('wishes-container');
            
            // Hapus tulisan "Belum ada ucapan" jika masih ada
            const emptyMsg = document.getElementById('empty-wishes-msg');
            if (emptyMsg) emptyMsg.remove();

            const badgeHtml = statusRsvp === 'hadir' 
                ? `<span class="inline-block mb-3 px-3 py-1 bg-accent/10 text-accent rounded-full text-[10px] font-bold tracking-widest uppercase"><i class="fa-solid fa-check mr-1"></i> Hadir (${pax} Orang)</span>` 
                : '';

            const newWishHtml = `
                <div class="flex flex-col md:flex-row gap-6 p-6 rounded-[1.5rem] bg-white border border-borderLight hover:border-accent hover:shadow-lg transition-all group animate-slide-up">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-bg border border-borderLight rounded-full shrink-0 flex items-center justify-center shadow-inner group-hover:bg-accent group-hover:text-white transition-colors">
                        <i class="fa-regular fa-comment-dots text-xl text-accent group-hover:text-white"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-2">
                            <h5 class="font-bold text-primary font-serif text-lg">${guestName}</h5>
                            <span class="text-[10px] text-secondary">Baru saja</span>
                        </div>
                        ${badgeHtml}
                        <p class="text-sm text-secondary font-light leading-relaxed italic">"${message}"</p>
                    </div>
                </div>
            `;

            // Insert di urutan pertama
            container.insertAdjacentHTML('afterbegin', newWishHtml);
            
            // Update Angka Statistik
            const totalWishesEl = document.getElementById('total-wishes');
            let totalWishes = parseInt(totalWishesEl.innerText) || 0;
            totalWishesEl.innerText = totalWishes + 1;
            
            if (statusRsvp === 'hadir') {
                const totalAttendanceEl = document.getElementById('total-attendance');
                let totalAttendance = parseInt(totalAttendanceEl.innerText) || 0;
                totalAttendanceEl.innerText = totalAttendance + parseInt(pax);
            }
        }

        function submitRsvp(e) {
            e.preventDefault();
            const guestName = document.getElementById('input-nama-rsvp').value.trim();
            const message = document.getElementById('input-pesan-rsvp').value.trim();
            const pax = document.getElementById('input-pax-rsvp').value;
            const btnSubmit = e.target.querySelector('button[type="submit"]');

            const selectedBtn = document.querySelector('#btn-hadir.bg-primary, #btn-absen.bg-primary');
            if(!selectedBtn) { alert('Silakan pilih konfirmasi kehadiran.'); return; }
            const statusRsvp = selectedBtn.id === 'btn-hadir' ? 'hadir' : 'tidak_hadir';

            const originalText = btnSubmit.innerHTML;
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i>Mengirim...';

            const payload = new FormData();
            payload.append('_token', document.querySelector('input[name="_token"]')?.value || '');
            payload.append('guest_name', guestName);
            payload.append('status_rsvp', statusRsvp);
            payload.append('pax', pax);
            payload.append('message', message);

            fetch('{{ route('rsvp.store', $invitation->slug ?? '') }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': payload.get('_token')
                },
                body: payload
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update DOM (Auto inject ke daftar wishes)
                    appendNewWish(guestName, statusRsvp, pax, message);
                    
                    document.getElementById('rsvp-success-message').textContent = data.message || 'RSVP Anda telah tersimpan.';
                    document.getElementById('rsvp-success-modal').classList.remove('hidden');
                    document.getElementById('rsvp-success-modal').classList.add('flex');
                    
                    document.getElementById('form-rsvp').reset();
                    
                    // Tutup modal secara perlahan TANPA RELOAD
                    setTimeout(() => {
                        closeRsvpSuccessModal();
                    }, 2500); 
                } else {
                    showToast('Gagal menyimpan RSVP.');
                }
            })
            .catch(() => showToast('Terjadi kesalahan koneksi.'))
            .finally(() => {
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
            });
        }

        function closeRsvpSuccessModal() {
            document.getElementById('rsvp-success-modal').classList.add('hidden');
            document.getElementById('rsvp-success-modal').classList.remove('flex');
            closeRSVP(); // Tutup form modal utamanya juga
        }
    </script>
    
    <script>
        // GIFT MODAL & PAX 1,2,3,3+
        function selectGiftAttendance(status) {
            document.getElementById('gift-confirm-status').value = status;
            const btnHadir = document.getElementById('gift-btn-hadir');
            const btnAbsen = document.getElementById('gift-btn-absen');
            const wrapper = document.getElementById('gift-confirm-pax-wrapper');
            
            btnHadir.className = "flex-1 py-3 rounded-full border border-primary bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95";
            btnAbsen.className = "flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95";
            
            if (status === 'hadir') {
                btnHadir.className = "flex-1 py-3 bg-primary text-white rounded-full font-bold border border-primary text-sm transition-all active:scale-95";
                wrapper.classList.remove('hidden');
                document.getElementById('gift-confirm-pax').value = 1;
                setGiftPax(1);
            } else {
                btnAbsen.className = "flex-1 py-3 bg-primary text-white rounded-full font-bold border border-primary text-sm transition-all active:scale-95";
                wrapper.classList.add('hidden');
                document.getElementById('gift-confirm-pax').value = 0;
            }
        }

        function setGiftPax(count) {
            const customContainer = document.getElementById('gift-custom-pax-container');
            const customInput = document.getElementById('gift-custom-pax-input');
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
                btn.className = "gift-pax-btn flex-1 py-3 rounded-full border border-borderLight bg-bg text-secondary text-sm font-bold hover:border-accent hover:text-accent transition-all active:scale-95";
                if (text == count || (count === 'custom' && text === '3+')) {
                    btn.className = "gift-pax-btn flex-1 py-3 bg-primary text-white border border-primary rounded-full font-bold text-sm transition-all active:scale-95";
                }
            });
            updateGiftPaxDisplay(hiddenPaxInput.value);
        }

        function updateGiftPaxDisplay(pax) {
            document.getElementById('gift-pax-display').textContent = `${pax} Orang`;
        }

        let currentSelectedItemId = null;
        let currentSelectedItemName = '';

        function confirmGift(id, name) {
            currentSelectedItemId = id;
            currentSelectedItemName = name;
            
            const nameInput = document.getElementById('gift-confirm-name');
            const paxCustomInput = document.getElementById('gift-custom-pax-input');
            const confirmText = document.getElementById('confirm-text');
            confirmText.innerHTML = `Terima kasih atas niat baiknya. Apakah Anda yakin ingin mengirimkan <b>${name}</b> sebagai tanda kasih?`;

            if (paxCustomInput) paxCustomInput.value = '';
            
            selectGiftAttendance('hadir');
            
            document.getElementById('confirm-modal').classList.remove('hidden');
            document.getElementById('confirm-modal').classList.add('flex');
            document.getElementById('final-confirm-btn').onclick = () => {
                const finalName = nameInput ? nameInput.value.trim() : 'Hamba Allah';
                const finalStatus = document.getElementById('gift-confirm-status').value || 'hadir';
                let finalPax = 0;
                if (finalStatus === 'hadir') {
                    finalPax = parseInt(document.getElementById('gift-confirm-pax').value) || 1;
                }
                if (isNaN(finalPax) || finalPax < 0) finalPax = 0;

                processClaim(finalName, name, finalStatus, finalPax);
            };
        }

        document.getElementById('gift-custom-pax-input')?.addEventListener('input', function() {
            const count = parseInt(this.value) || 4;
            document.getElementById('gift-confirm-pax').value = count;
            updateGiftPaxDisplay(count);
        });

        function closeConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
            document.getElementById('confirm-modal').classList.remove('flex');
        }

        function closeGiftSuccessModal() {
            document.getElementById('gift-success-modal').classList.add('hidden');
            document.getElementById('gift-success-modal').classList.remove('flex');
            toggleGiftModal(false);
        }

        function processClaim(guestName, giftName, rsvpStatus, giftPax) {
            if (!guestName) {
                alert('Silakan isi nama Anda terlebih dahulu.');
                return;
            }

            const confirmButton = document.getElementById('final-confirm-btn');
            const originalText = confirmButton.innerHTML;
            confirmButton.disabled = true;
            confirmButton.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i>Mengirim...';

            const messageValue = `[Tanda Kasih] Telah memberikan kado: ${giftName}`;
            
            const payload = new FormData();
            payload.append('_token', document.querySelector('input[name="_token"]')?.value || '');
            payload.append('guest_name', guestName);
            payload.append('status_rsvp', rsvpStatus);
            payload.append('pax', giftPax);
            payload.append('message', messageValue);

            fetch('{{ route('rsvp.store', $invitation->slug ?? '') }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': payload.get('_token')
                },
                body: payload
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    appendNewWish(guestName, rsvpStatus, giftPax, messageValue);
                    closeConfirmModal();
                    
                    document.getElementById('gift-success-message').textContent = `Kado "${giftName}" telah tercatat atas nama ${guestName}.`;
                    document.getElementById('gift-success-modal').classList.remove('hidden');
                    document.getElementById('gift-success-modal').classList.add('flex');
                    setTimeout(() => {
                        closeGiftSuccessModal();
                    }, 2500);
                } else {
                    alert('Gagal menyimpan kado.');
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan saat menghubungi server.');
            })
            .finally(() => {
                confirmButton.disabled = false;
                confirmButton.innerHTML = originalText;
            });
        }

        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        function switchPlatform(id, title, desc, iconClass, link) {
            const display = document.getElementById('streaming-display');
            if(!display) return;
            display.style.opacity = '0';
            display.style.transform = 'scale(0.95)';
            setTimeout(() => {
                document.getElementById('platform-title').innerText = title;
                document.getElementById('platform-desc').innerText = desc;
                document.getElementById('platform-icon').className = iconClass + ' text-5xl text-white';
                document.getElementById('platform-link').href = link;
                display.style.opacity = '1';
                display.style.transform = 'scale(1)';
            }, 400);
        }
    </script>
</body>
</html>