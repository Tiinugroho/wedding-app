<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vibrant Wedding - {{ $content['groom_nickname'] ?? 'Groom' }} & {{ $content['bride_nickname'] ?? 'Bride' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#FFFDF7', // Off-white warm
                        surface: '#FFFFFF',
                        primary: '#FF6B6B', // Vibrant Coral / Peach
                        secondary: '#4ECDC4', // Aqua Teal
                        accent: '#FFE66D', // Sunny Yellow
                        dark: '#292F36', // Charcoal untuk teks
                        light: '#F7F9F9'
                    },
                    fontFamily: {
                        sans: ['"Quicksand"', 'sans-serif'],
                        serif: ['"DM Serif Display"', 'serif'],
                    },
                    animation: {
                        'wobble': 'wobble 3s ease-in-out infinite',
                        'spin-slow': 'spin 12s linear infinite',
                        'float': 'float 4s ease-in-out infinite',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        wobble: {
                            '0%, 100%': { transform: 'rotate(-3deg)' },
                            '50%': { transform: 'rotate(3deg)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar { width: 8px; background: #FFFDF7; }
        ::-webkit-scrollbar-thumb { background: #4ECDC4; border-radius: 10px; }
        body { background-color: #FFFDF7; color: #292F36; overflow-x: hidden; }
        
        /* Pola background ceria */
        .polka-bg {
            background-image: radial-gradient(#FFE66D 2px, transparent 2px);
            background-size: 30px 30px;
        }

        /* Layout Masonry untuk Gallery */
        .masonry {
            column-count: 2;
            column-gap: 1rem;
        }
        @media (min-width: 768px) {
            .masonry { column-count: 3; }
        }
        .masonry-item { break-inside: avoid; margin-bottom: 1rem; }

        /* Custom shapes */
        .shape-blob { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
        
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #292F36; border-radius: 10px; }
    </style>
</head>

<body class="bg-bg text-dark font-sans selection:bg-secondary selection:text-white">

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music->file_path) ? Storage::url($invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3' }}" type="audio/mpeg">
    </audio>

    @php
        $gFullName = $content['groom_name'] ?? 'Romeo Montague';
        $bFullName = $content['bride_name'] ?? 'Juliet Capulet';
        $gNickname = $content['groom_nickname'] ?? explode(' ', $gFullName)[0];
        $bNickname = $content['bride_nickname'] ?? explode(' ', $bFullName)[0];
        $coupleOrder = $content['couple_order'] ?? 'groom_first';
        $displayFirst = $coupleOrder === 'bride_first' ? $bNickname : $gNickname;
        $displaySecond = $coupleOrder === 'bride_first' ? $gNickname : $bNickname;
        $initials = substr($displayFirst, 0, 1) . ' & ' . substr($displaySecond, 0, 1);

        // Penamaan Tamu
        $guestSlug = request()->query('to');
        $guestNameDisplay = $guestData
            ? $guestData->name
            : ($guestSlug
                ? urldecode(str_replace(['+', '-'], ' ', $guestSlug))
                : 'Tamu Spesial');
        $guestNameInput = $guestData
            ? $guestData->name
            : ($guestSlug
                ? urldecode(str_replace(['+', '-'], ' ', $guestSlug))
                : '');
    @endphp

    <!-- COVER PAGE -->
    <div id="cover-page" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-primary transition-transform duration-700 ease-[cubic-bezier(0.87,0,0.13,1)] overflow-hidden">
        
        <div class="absolute top-[-10%] left-[-10%] w-64 h-64 bg-accent shape-blob opacity-80 animate-spin-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-80 h-80 bg-secondary shape-blob opacity-80 animate-float"></div>

        <div class="relative z-10 flex flex-col items-center text-center px-6 w-full max-w-lg">
    
    <div class="relative w-full h-64 mb-8 flex justify-center items-center">
        <!-- Foto Kiri (Cover) -->
        <img src="{{ !empty($content['groom_photo']) ? Storage::url($content['groom_photo']) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=400' }}" 
             class="w-32 h-40 object-cover rounded-2xl shadow-xl -rotate-12 -mr-16 border-4 border-white animate-wobble" 
             style="animation-delay: 0s;">
        
        <!-- Foto Tengah (Groom) -->
        <img src="{{ !empty($content['cover_image']) ? Storage::url($content['cover_image']) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=400' }}" 
             class="w-40 h-48 object-cover rounded-3xl shadow-2xl z-10 border-4 border-white">

        <!-- Foto Kanan (Bride) -->
        <img src="{{ !empty($content['bride_photo']) ? Storage::url($content['bride_photo']) : 'https://images.unsplash.com/photo-1606800052052-a08af7148866?w=400' }}" 
             class="w-32 h-40 object-cover rounded-2xl shadow-xl rotate-12 -ml-16 border-4 border-white animate-wobble" 
             style="animation-delay: 1.5s;">
    </div>

    <h1 class="font-serif text-5xl md:text-6xl text-white mb-2 leading-none">{{ $initials }}</h1>
    <p class="text-white/90 font-medium tracking-widest uppercase text-sm mb-10">{{ $content['cover_greeting'] ?? "We're Getting Married!" }}</p>
    
    <div class="bg-white/20 backdrop-blur-md border border-white/40 p-6 rounded-3xl w-full mb-8 shadow-lg">
        <p class="text-xs text-white uppercase tracking-widest mb-2 font-bold">Hello,</p>
        <p id="guest-name" class="text-2xl font-serif text-accent">{{ $guestNameDisplay }}</p>
    </div>

    <button onclick="openInvitation()" class="bg-accent text-dark px-10 py-4 rounded-full font-bold uppercase tracking-widest text-sm shadow-[4px_4px_0px_rgba(41,47,54,1)] hover:translate-y-1 hover:shadow-[2px_2px_0px_rgba(41,47,54,1)] transition-all active:shadow-none active:translate-y-2">
        Buka Undangan <i class="fa-solid fa-arrow-right ml-2"></i>
    </button>
</div>
    </div>

    <main id="main-content" class="min-h-screen opacity-0 transition-opacity duration-1000 pb-24">

        <nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[90%] max-w-md z-50 bg-white/90 backdrop-blur-md border border-dark p-3 flex justify-between items-center rounded-full shadow-[4px_4px_0px_rgba(41,47,54,1)] transition-all" id="main-nav">
            <h1 class="font-serif text-primary text-xl ml-4">The Wedding</h1>
            <div class="bg-secondary text-white w-10 h-10 rounded-full flex items-center justify-center border-2 border-dark cursor-pointer hover:bg-primary transition-colors" onclick="openRSVP()">
                <i class="fa-solid fa-heart animate-pulse"></i>
            </div>
        </nav>

        <!-- HERO SECTION -->
        <section id="home" class="min-h-screen pt-32 px-6 flex flex-col items-center justify-center relative polka-bg overflow-hidden">
            <div class="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div class="text-left z-10 order-2 lg:order-1">
                    <div class="inline-block bg-accent border-2 border-dark px-4 py-2 rounded-full font-bold text-xs uppercase tracking-widest mb-6 transform -rotate-2">
                        Save The Date
                    </div>
                    <h2 class="text-7xl md:text-8xl lg:text-9xl font-serif text-primary mb-4 leading-[0.9]">
                        {{ $displayFirst }} <br>
                        <span class="text-secondary">& {{ $displaySecond }}</span>
                    </h2>
                    <p class="text-lg md:text-xl font-medium mb-8 max-w-md bg-white border-2 border-dark p-4 rounded-2xl shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                        {{ $content['quotes'] ?? 'Bergabunglah merayakan awal dari petualangan terbesar kami bersama.' }}
                    </p>
                    
                    <div class="flex flex-wrap gap-4">
                        <button onclick="openRSVP()" class="bg-dark text-white px-8 py-4 rounded-full font-bold uppercase tracking-widest text-xs hover:bg-primary transition-colors border-2 border-transparent">
                            RSVP Sekarang
                        </button>
                        <div class="flex items-center gap-2 bg-white border-2 border-dark px-6 py-3 rounded-full font-bold">
                            <i class="fa-regular fa-calendar-check text-secondary text-xl"></i> 
                            {{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->format('d . m . Y') : '2026' }}
                        </div>
                    </div>
                </div>

                <div class="relative h-[50vh] lg:h-[80vh] w-full order-1 lg:order-2">
                    <img src="{{ !empty($content['bride_photo']) ? Storage::url($content['bride_photo']) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600' }}" class="absolute top-0 right-0 w-3/4 h-2/3 object-cover rounded-[3rem] border-4 border-dark shadow-[8px_8px_0px_rgba(255,107,107,1)] z-10">
                    <img src="{{ !empty($content['groom_photo']) ? Storage::url($content['groom_photo']) : 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=400' }}" class="absolute bottom-10 left-0 w-1/2 h-1/2 object-cover rounded-[2rem] border-4 border-dark shadow-[8px_8px_0px_rgba(78,205,196,1)] z-20">
                    <div class="absolute bottom-0 right-10 bg-accent border-4 border-dark w-32 h-32 rounded-full flex items-center justify-center z-30 animate-spin-slow">
                        <svg viewBox="0 0 100 100" class="w-full h-full"><path id="curve" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="transparent"/>
                        <text class="text-sm font-bold uppercase tracking-widest"><textPath href="#curve">Best Day Ever • Best Day Ever • </textPath></text></svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- CAST / MEMPELAI -->
        <section id="cast" class="py-24 px-6 bg-dark text-white">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-end mb-16">
                    <h3 class="text-5xl md:text-7xl font-serif text-accent">Mempelai</h3>
                    <p class="text-sm uppercase tracking-widest font-bold hidden md:block border-b-2 border-secondary pb-2">The Cast</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Groom -->
                    <div class="lg:col-span-7 bg-primary rounded-[3rem] p-8 md:p-12 border-4 border-dark relative overflow-hidden group min-h-[300px]">
                        <div class="absolute top-0 right-0 w-full h-full bg-cover bg-center opacity-40 mix-blend-multiply group-hover:scale-105 transition-transform duration-700" style="background-image: url('{{ !empty($content['groom_photo']) ? Storage::url($content['groom_photo']) : 'https://images.soco.id/230-58.jpg.jpeg' }}');"></div>
                        <div class="relative z-10 flex flex-col h-full justify-end">
                            <span class="bg-dark text-accent px-4 py-2 rounded-full text-xs font-bold uppercase w-max mb-4">The Groom</span>
                            <h4 class="text-5xl font-serif mb-2">{{ $gFullName }}</h4>
                            <p class="font-medium text-lg mb-2">Putra dari {{ $content['groom_father'] ?? 'Bpk.' }} & {{ $content['groom_mother'] ?? 'Ibu' }}</p>
                            @if(!empty($content['groom_ig']))
                                <a href="https://instagram.com/{{ str_replace('@', '', $content['groom_ig']) }}" target="_blank" class="text-white hover:text-accent w-max"><i class="fa-brands fa-instagram text-2xl"></i><span class="mx-2">{{ $content['groom_ig'] ?? '' }}</span> </a>
                            @endif
                        </div>
                    </div>

                    <!-- Bride -->
                    <div class="lg:col-span-5 bg-secondary rounded-[3rem] p-8 border-4 border-dark relative overflow-hidden group min-h-[300px]">
                        <div class="absolute inset-0 w-full h-full bg-cover bg-center opacity-50 mix-blend-multiply group-hover:scale-105 transition-transform duration-700" style="background-image: url('{{ !empty($content['bride_photo']) ? Storage::url($content['bride_photo']) : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg' }}');"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <span class="bg-dark text-secondary px-4 py-2 rounded-full text-xs font-bold uppercase w-max mb-4 md:mb-0">The Bride</span>
                            <div>
                                <h4 class="text-4xl font-serif mb-1">{{ $bFullName }}</h4>
                                <p class="font-medium mb-2">Putri dari {{ $content['bride_father'] ?? 'Bpk.' }} & {{ $content['bride_mother'] ?? 'Ibu' }}</p>
                                @if(!empty($content['bride_ig']))
                                    <a href="https://instagram.com/{{ str_replace('@', '', $content['bride_ig']) }}" target="_blank" class="text-white hover:text-dark w-max"><i class="fa-brands fa-instagram text-2xl"></i><span class="mx-2">{{ $content['bride_ig'] ?? '' }}</span> </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- LOVE STORIES -->
        @if (!empty($content['is_story_active']) && !empty($content['love_stories']))
        <section id="cerita" class="py-24 px-6 bg-surface border-t-4 border-dark">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <span class="bg-primary text-white border-2 border-dark px-4 py-2 rounded-full font-bold text-[10px] uppercase tracking-widest inline-block mb-4">Our Journey</span>
                    <h3 class="text-5xl font-serif text-dark">Kisah Cinta Kami</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($content['love_stories'] as $index => $story)
                        @php
                            $colors = ['text-primary', 'text-secondary', 'text-accent'];
                            $translate = $index % 2 != 0 ? 'md:translate-y-12' : '';
                        @endphp
                        <div class="bg-light border-4 border-dark rounded-[2.5rem] p-6 shadow-[8px_8px_0px_rgba(41,47,54,1)] hover:-translate-y-2 transition-transform {{ $translate }}">
                            @if (!empty($story['image']))
                                <img src="{{ Storage::url($story['image']) }}" class="w-full h-48 object-cover rounded-2xl border-2 border-dark mb-6">
                            @endif
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="font-serif text-2xl {{ $colors[$index % 3] }}">{{ $story['title'] ?? 'Story' }}</h5>
                                @if (!empty($story['year']))
                                    <span class="font-bold text-xs bg-accent px-3 py-1 rounded-full border border-dark">{{ $story['year'] }}</span>
                                @endif
                            </div>
                            <p class="text-sm font-medium leading-relaxed">{{ $story['description'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- GALLERY -->
        @if (!empty($content['is_gallery_active']))
        <section id="gallery" class="py-24 px-6 bg-accent border-y-4 border-dark">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center mb-16 gap-6">
                    <h3 class="text-5xl md:text-6xl font-serif text-dark">Galeri Bahagia</h3>
                    <div class="bg-white px-6 py-3 rounded-full border-2 border-dark font-bold text-sm shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                        Capture Every Moment <i class="fa-solid fa-camera-retro text-primary ml-2"></i>
                    </div>
                </div>

                @if (!empty($content['youtube_links']) && count(array_filter($content['youtube_links'])) > 0)
                    @php
                        $ytLink = array_filter($content['youtube_links'])[0];
                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $ytLink, $match);
                        $ytId = $match[1] ?? null;
                    @endphp
                    @if($ytId)
                    <div class="w-full aspect-video rounded-[2rem] border-4 border-dark overflow-hidden bg-white mb-10 shadow-[8px_8px_0px_rgba(41,47,54,1)]">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=0&mute=1&controls=1&rel=0" frameborder="0" allowfullscreen></iframe>
                    </div>
                    @endif
                @endif

                @if (!empty($invitation->galleries) && count($invitation->galleries) > 0)
                    <div class="masonry">
                        @foreach ($invitation->galleries as $index => $gallery)
                            <div class="masonry-item rounded-2xl overflow-hidden border-4 border-dark cursor-pointer group bg-white" onclick="openLightbox({{ $index }})">
                                <img src="{{ Storage::url($gallery->file_path) }}" class="gallery-img w-full h-auto object-cover group-hover:scale-105 transition-transform" alt="Gallery">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center font-medium mt-10">Galeri foto belum ditambahkan.</p>
                @endif
            </div>
        </section>
        @endif

        <!-- LOKASI / EVENTS -->
        <section id="lokasi" class="py-24 px-6 bg-primary text-white polka-bg border-b-4 border-dark">
            <div class="max-w-6xl mx-auto">
                <h3 class="text-5xl font-serif text-center mb-16 text-dark drop-shadow-md bg-white w-max mx-auto px-8 py-3 rounded-full border-4 border-dark transform -rotate-2">Venue Acara</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Akad -->
                    <div class="bg-surface text-dark rounded-[2.5rem] border-4 border-dark flex flex-col md:flex-row overflow-hidden shadow-[8px_8px_0px_rgba(41,47,54,1)] group">
                        {{-- <div class="md:w-2/5 h-48 md:h-auto border-b-4 md:border-b-0 md:border-r-4 border-dark overflow-hidden bg-light">
                            <img src="https://images.unsplash.com/photo-1542042161784-26ab9e041e89?w=500" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                        </div> --}}
                        <div class="p-8 md:w-3/5 flex flex-col justify-between">
                            <div>
                                <h4 class="text-3xl font-serif mb-4 text-primary">Akad Nikah</h4>
                                <ul class="space-y-3 font-medium text-sm mb-6">
                                    @if (!empty($content['akad_date']))
                                    <li><i class="fa-regular fa-calendar text-primary w-6"></i> {{ \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') }}</li>
                                    @endif
                                    @if (!empty($content['akad_time']))
                                    <li><i class="fa-regular fa-clock text-primary w-6"></i> {{ $content['akad_time'] }} @if (!empty($content['akad_time_end'])) - {{ $content['akad_time_end'] }} @endif WIB</li>
                                    @endif
                                    @if (!empty($content['akad_location']))
                                    <li><i class="fa-solid fa-location-dot text-primary w-6"></i> {{ $content['akad_location'] }}</li>
                                    <li class="pl-6 text-xs text-gray-500">{{ $content['akad_address'] ?? '' }}</li>
                                    @endif
                                </ul>
                            </div>
                            @if (!empty($content['akad_map']))
                            <a href="{{ $content['akad_map'] }}" target="_blank" class="block w-full text-center bg-accent border-2 border-dark py-3 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-dark hover:text-white transition-colors">Lihat Peta</a>
                            @endif
                        </div>
                    </div>

                    <!-- Resepsi / Events -->
                    @if (!empty($content['events']) && is_array($content['events']))
                        @foreach ($content['events'] as $event)
                            <div class="bg-surface text-dark rounded-[2.5rem] border-4 border-dark flex flex-col md:flex-row overflow-hidden shadow-[8px_8px_0px_rgba(41,47,54,1)] group">
                                {{-- <div class="md:w-2/5 h-48 md:h-auto border-b-4 md:border-b-0 md:border-r-4 border-dark overflow-hidden bg-light">
                                    <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=500" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                </div> --}}
                                <div class="p-8 md:w-3/5 flex flex-col justify-between">
                                    <div>
                                        <h4 class="text-3xl font-serif mb-4 text-secondary">{{ $event['title'] ?? 'Resepsi' }}</h4>
                                        <ul class="space-y-3 font-medium text-sm mb-6">
                                            @if (!empty($event['date']))
                                            <li><i class="fa-regular fa-calendar text-secondary w-6"></i> {{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}</li>
                                            @endif
                                            @if (!empty($event['time']))
                                            <li><i class="fa-regular fa-clock text-secondary w-6"></i> {{ $event['time'] }} @if (!empty($event['time_end'])) - {{ $event['time_end'] }} @endif WIB</li>
                                            @endif
                                            @if (!empty($event['location']))
                                            <li><i class="fa-solid fa-location-dot text-secondary w-6"></i> {{ $event['location'] }}</li>
                                            <li class="pl-6 text-xs text-gray-500">{{ $event['address'] ?? '' }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                    @if (!empty($event['map']))
                                    <a href="{{ $event['map'] }}" target="_blank" class="block w-full text-center bg-secondary text-white border-2 border-dark py-3 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-dark transition-colors">Lihat Peta</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        <!-- LIVE STREAMING -->
        @php 
            $activeStreams = collect($content['live_streams'] ?? [])->filter(function($s) {
                return !empty($s['link']) && !empty($s['platform']);
            })->values();
        @endphp
        @if (!empty($content['is_livestream_active']) && $activeStreams->count() > 0)
        <section id="live-streaming" class="py-24 px-6 bg-secondary relative border-b-4 border-dark">
            <div class="max-w-5xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 bg-dark text-white px-5 py-2 rounded-full font-bold text-xs uppercase tracking-widest mb-6">
                    <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span> Live Virtual
                </div>
                <h2 class="text-5xl font-serif text-white mb-10">Join Us Online</h2>

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

                <div id="streaming-display" class="bg-white border-4 border-dark rounded-[2rem] p-4 shadow-[12px_12px_0px_rgba(41,47,54,1)] max-w-3xl mx-auto transition-transform duration-500">
                    <div class="bg-dark rounded-[1.5rem] p-10 flex flex-col items-center justify-center min-h-[300px] text-white">
                        <i id="platform-icon" class="{{ $icons[$firstStream['platform']] ?? 'fa-solid fa-play' }} text-6xl text-primary mb-6 animate-bounce"></i>
                        <h3 id="platform-title" class="text-4xl font-serif mb-2 capitalize">{{ $firstStream['platform'] }} Live</h3>
                        <p id="platform-desc" class="font-medium text-sm text-gray-300 mb-8">Klik tombol di bawah untuk bergabung/menonton</p>
                        <a id="platform-link" href="{{ $firstStream['link'] }}" target="_blank" class="bg-primary text-white border-2 border-primary hover:bg-transparent px-8 py-3 rounded-full font-bold uppercase tracking-widest text-xs transition-colors">Putar Sekarang</a>
                    </div>
                </div>

                @if($activeStreams->count() > 1)
                <div class="flex flex-wrap justify-center gap-4 mt-10">
                    @foreach($activeStreams as $stream)
                        <button onclick="switchPlatform('{{ $stream['platform'] }}', '{{ ucfirst($stream['platform']) }} Live', 'Klik untuk menonton', '{{ $icons[$stream['platform']] ?? 'fa-solid fa-play' }}', '{{ $stream['link'] }}')" class="w-14 h-14 bg-white border-2 border-dark rounded-full text-dark hover:bg-primary hover:text-white flex items-center justify-center text-2xl transition-all shadow-sm transform hover:-translate-y-1"><i class="{{ $icons[$stream['platform']] ?? 'fa-solid fa-play' }}"></i></button>
                    @endforeach
                </div>
                @endif
            </div>
        </section>
        @endif

        <!-- STATS & WISHES -->
        <section id="guest-stats" class="py-24 px-6 bg-bg border-b-4 border-dark">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <div class="lg:col-span-5 flex flex-col justify-center">
                    <h2 class="text-5xl font-serif text-dark mb-10">Kehadiran & Doa</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-accent border-4 border-dark p-6 rounded-3xl text-center shadow-[6px_6px_0px_rgba(41,47,54,1)] hover:-translate-y-1 transition-transform">
                            <h4 id="total-attendance" class="text-5xl font-serif text-dark mb-2">{{ $totalAttendance ?? 0 }}</h4>
                            <p class="font-bold text-[10px] uppercase tracking-widest border-t-2 border-dark pt-2">Tamu Hadir</p>
                        </div>
                        <div class="bg-primary text-white border-4 border-dark p-6 rounded-3xl text-center shadow-[6px_6px_0px_rgba(41,47,54,1)] hover:-translate-y-1 transition-transform">
                            <h4 id="total-wishes" class="text-5xl font-serif mb-2">{{ $totalWishes ?? 0 }}</h4>
                            <p class="font-bold text-[10px] uppercase tracking-widest border-t-2 border-dark pt-2">Ucapan</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 bg-white border-4 border-dark rounded-[2.5rem] shadow-[8px_8px_0px_rgba(41,47,54,1)] flex flex-col h-[500px]">
                    <div class="p-6 border-b-4 border-dark bg-light rounded-t-[2.2rem] flex justify-between items-center">
                        <span class="font-bold uppercase tracking-widest text-sm"><i class="fa-solid fa-message text-secondary mr-2"></i> Wishes Wall</span>
                    </div>
                    <div id="wishes-container" class="flex-1 overflow-y-auto p-6 space-y-4 scroll-custom">
                        @forelse($dbWishes as $wish)
                            <div class="bg-bg border-2 border-dark p-5 rounded-2xl flex gap-4 animate-slide-up">
                                <div class="w-10 h-10 bg-white border-2 border-dark rounded-full flex-shrink-0 flex items-center justify-center text-primary"><i class="fa-solid fa-user"></i></div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <h5 class="font-bold text-sm text-dark">{{ $wish->guest_name }}</h5>
                                        <span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}</span>
                                    </div>
                                    @if($wish->status_rsvp == 'hadir')
                                        <span class="inline-block px-2 py-0.5 bg-secondary text-white rounded text-[9px] font-bold uppercase tracking-widest mb-1"><i class="fa-solid fa-check"></i> Hadir ({{ $wish->pax }} Orang)</span>
                                    @endif
                                    <p class="text-xs text-dark font-medium italic leading-relaxed">"{{ $wish->message }}"</p>
                                </div>
                            </div>
                        @empty
                            <p id="empty-wishes-msg" class="text-center text-gray-500 text-sm mt-10">Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- KADO / GIFTS -->
        @if (!empty($content['is_gift_active']))
        <section id="hadiah" class="py-24 px-6 bg-surface border-b-4 border-dark">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-5xl font-serif text-dark mb-4">Tanda Kasih</h2>
                    <p class="font-medium text-sm text-gray-600 max-w-xl mx-auto">Doa restu Anda adalah hadiah terbaik. Namun jika ingin mengirimkan tanda kasih, silakan melalui rekening berikut.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if (!empty($content['banks']) && is_array($content['banks']))
                        @foreach ($content['banks'] as $index => $bank)
                        <div class="bg-light border-4 border-dark p-8 rounded-[2.5rem] flex flex-col items-center justify-center relative overflow-hidden shadow-[6px_6px_0px_rgba(41,47,54,1)] group">
                            <div class="bg-white px-6 py-2 rounded-full border-2 border-dark mb-6">
                                <span class="font-bold uppercase tracking-widest">{{ $bank['name'] ?? 'Bank' }}</span>
                            </div>
                            <h3 id="rek-{{ $index + 1 }}" class="text-3xl font-bold tracking-widest mb-2">{{ $bank['account_number'] ?? '' }}</h3>
                            <p class="font-bold text-[10px] uppercase tracking-widest text-secondary mb-8">A.n {{ $bank['account_name'] ?? '' }}</p>
                            <button onclick="copyToClipboard('rek-{{ $index + 1 }}', this)" class="bg-dark text-white w-full py-3 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-primary transition-colors">
                                Salin Nomor
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>

                <!-- Alamat Kado Fisik -->
                @if (!empty($content['alamat_kado']) || !empty($content['gifts']))
                <div class="mt-16 bg-accent border-4 border-dark p-10 rounded-[3rem] shadow-[8px_8px_0px_rgba(41,47,54,1)]">
                    <div class="flex flex-col md:flex-row gap-10 items-center bg-white border-4 border-dark p-8 rounded-[2rem]">
                        <div class="w-full md:w-1/2 text-center md:text-left">
                            <div class="bg-bg w-16 h-16 rounded-full border-2 border-dark flex items-center justify-center text-primary text-2xl mb-6 mx-auto md:mx-0"><i class="fa-solid fa-gift"></i></div>
                            <h2 class="text-3xl font-serif text-dark mb-4">Kirim Kado Fisik</h2>
                            <p class="text-sm font-medium mb-6">Untuk pengiriman kado fisik, silakan kirimkan ke alamat di samping atau cek daftar wishlist kami.</p>
                            @if(!empty($content['gifts']))
                            <button onclick="toggleGiftModal(true)" class="bg-dark text-white px-8 py-3 rounded-full font-bold text-[10px] uppercase tracking-widest hover:bg-secondary hover:text-dark transition-colors border-2 border-dark">Lihat Wishlist</button>
                            @endif
                        </div>
                        <div class="w-full md:w-1/2 bg-light border-2 border-dark rounded-[2rem] p-6 text-center relative">
                            <div class="absolute -top-3 -right-3 bg-primary text-white font-bold text-[10px] uppercase px-3 py-1 border-2 border-dark rounded-full transform rotate-12">Alamat</div>
                            <p id="alamat-kado" class="font-serif text-lg mb-6 leading-relaxed">
                                {!! !empty($content['alamat_kado']) ? nl2br(e($content['alamat_kado'])) : "Alamat belum ditambahkan." !!}
                            </p>
                            @if(!empty($content['alamat_kado']))
                            <button onclick="copyToClipboard('alamat-kado', this)" class="bg-white border-2 border-dark px-6 py-2 rounded-full font-bold text-[10px] uppercase hover:bg-dark hover:text-white transition-colors">Salin Alamat</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div id="copy-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[300] bg-dark text-accent border-2 border-accent px-6 py-3 rounded-full font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 opacity-0 translate-y-4 transition-all pointer-events-none shadow-lg">
                    <i class="fa-solid fa-check"></i> Tersalin!
                </div>
            </div>
            
            <!-- MODAL WISHLIST KADO -->
            <div id="gift-modal" class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm" onclick="toggleGiftModal(false)"></div>
                <div class="relative bg-white border-4 border-dark w-full max-w-lg rounded-[2.5rem] flex flex-col max-h-[80vh] shadow-[12px_12px_0px_rgba(255,107,107,1)] animate-slide-up">
                    <div class="p-6 border-b-4 border-dark flex justify-between items-center bg-light rounded-t-[2rem]">
                        <h3 class="text-2xl font-serif">Wedding Wishlist</h3>
                        <button onclick="toggleGiftModal(false)" class="w-10 h-10 bg-dark text-white rounded-full flex items-center justify-center hover:bg-primary transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="overflow-y-auto p-6 space-y-4 custom-scrollbar">
                        @foreach ($content['gifts'] ?? [] as $index => $gift)
                            <div id="item-{{ $index + 1 }}" class="border-2 border-dark rounded-2xl p-4 flex justify-between items-center bg-white hover:bg-bg transition-colors">
                                <div>
                                    <h4 class="font-bold text-sm">{{ $gift['item_name'] ?? 'Gift Item' }}</h4>
                                    <p class="text-[10px] text-gray-500">{{ $gift['description'] ?? '' }}</p>
                                </div>
                                <button onclick="confirmGift('item-{{ $index + 1 }}', '{{ addslashes($gift['item_name'] ?? 'Gift Item') }}')" class="bg-primary text-white px-4 py-2 rounded-full text-[10px] font-bold uppercase border-2 border-dark hover:bg-dark transition-colors">Pilih</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MODAL KONFIRMASI KADO PAX -->
            <div id="confirm-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/60 backdrop-blur-sm"></div>
                <div class="relative bg-white border-4 border-dark w-full max-w-md rounded-[2.5rem] flex flex-col max-h-[90vh] shadow-[8px_8px_0px_rgba(78,205,196,1)] animate-slide-up overflow-hidden">
                    <div class="p-6 border-b-4 border-dark text-center bg-light">
                        <h4 class="text-3xl font-serif text-primary mb-2">Konfirmasi Kirim</h4>
                        <p id="confirm-text" class="text-xs font-medium"></p>
                    </div>
                    <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-4">
                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Nama Pengirim</label>
                            <input id="gift-confirm-name" type="text" value="{{ $guestNameInput }}" class="w-full bg-bg border-2 border-dark focus:bg-white focus:border-primary p-3 rounded-xl font-medium outline-none transition-colors" placeholder="Nama Anda" />
                        </div>
                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Status Kehadiran</label>
                            <div class="flex gap-4">
                                <button type="button" id="gift-btn-hadir" onclick="selectGiftAttendance('hadir')" class="flex-1 py-3 bg-dark text-white border-2 border-dark rounded-xl font-bold text-xs uppercase transition-colors">Hadir</button>
                                <button type="button" id="gift-btn-absen" onclick="selectGiftAttendance('tidak_hadir')" class="flex-1 py-3 bg-white border-2 border-dark text-dark rounded-xl font-bold text-xs uppercase hover:bg-bg transition-colors">Tidak Hadir</button>
                            </div>
                            <input type="hidden" id="gift-confirm-status" value="hadir">
                        </div>

                        <div id="gift-confirm-pax-wrapper" class="space-y-4">
                            <div>
                                <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Jumlah Orang (Hadir)</label>
                                <div class="flex gap-2">
                                    <button type="button" onclick="setGiftPax(1)" class="gift-pax-btn flex-1 py-2 rounded-xl border-2 border-dark bg-dark text-white font-bold text-xs transition-colors">1</button>
                                    <button type="button" onclick="setGiftPax(2)" class="gift-pax-btn flex-1 py-2 rounded-xl border-2 border-dark bg-white text-dark font-bold text-xs transition-colors hover:bg-bg">2</button>
                                    <button type="button" onclick="setGiftPax(3)" class="gift-pax-btn flex-1 py-2 rounded-xl border-2 border-dark bg-white text-dark font-bold text-xs transition-colors hover:bg-bg">3</button>
                                    <button type="button" onclick="setGiftPax('custom')" class="gift-pax-btn flex-1 py-2 rounded-xl border-2 border-dark bg-white text-dark font-bold text-xs transition-colors hover:bg-bg">3+</button>
                                </div>
                                <div id="gift-custom-pax-container" class="hidden mt-3">
                                    <input type="number" id="gift-custom-pax-input" placeholder="Isi jumlah spesifik (Misal: 4)" class="w-full bg-white border-2 border-dark focus:border-primary p-3 rounded-xl text-center text-sm font-bold outline-none" min="4">
                                </div>
                            </div>
                            <div class="bg-light p-4 rounded-xl border-2 border-dark text-center">
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mb-1">Total</p>
                                <p id="gift-pax-display" class="text-lg font-serif text-primary">1 Orang</p>
                            </div>
                            <input type="hidden" id="gift-confirm-pax" value="1">
                        </div>
                    </div>
                    <div class="p-6 border-t-4 border-dark flex gap-3">
                        <button id="final-confirm-btn" class="flex-1 bg-dark text-white py-3 rounded-xl font-bold text-[10px] uppercase border-2 border-dark hover:bg-primary transition-colors">Ya, Kirim</button>
                        <button onclick="closeConfirmModal()" class="flex-1 bg-white border-2 border-dark text-dark py-3 rounded-xl font-bold text-[10px] uppercase hover:bg-light transition-colors">Batal</button>
                    </div>
                </div>
            </div>

            <!-- SUCCESS KADO -->
            <div id="gift-success-modal" class="fixed inset-0 z-[700] hidden items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/60 backdrop-blur-md"></div>
                <div class="relative bg-white w-full max-w-sm border-4 border-dark rounded-[2.5rem] shadow-[8px_8px_0px_rgba(255,230,109,1)] overflow-hidden animate-slide-up">
                    <div class="p-8 text-center">
                        <div class="w-20 h-20 rounded-full bg-accent border-4 border-dark flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-check text-dark text-4xl"></i>
                        </div>
                        <h3 class="text-3xl font-serif text-dark mb-2">Sukses!</h3>
                        <p id="gift-success-message" class="text-gray-600 text-sm font-medium mb-8">Kado Anda telah tercatat.</p>
                        <button onclick="closeGiftSuccessModal()" class="w-full py-3 bg-dark text-white rounded-full font-bold uppercase tracking-widest text-[10px] hover:bg-primary transition-colors border-2 border-dark">Tutup</button>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- GUEST INFO -->
        @if (!empty($content['is_guest_info_active']))
        <section id="guest-info" class="py-24 px-6 bg-surface border-b-4 border-dark relative overflow-hidden">
            <div class="max-w-6xl mx-auto relative z-10">
                
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div>
                        <h2 class="text-5xl font-serif text-dark mb-4">Informasi Tamu</h2>
                    </div>
                    <p class="text-sm font-medium border-l-4 border-primary pl-4 max-w-sm text-gray-600">Perhatikan panduan berikut demi kenyamanan bersama selama acara berlangsung.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        @if (!empty($content['enable_dresscode']))
                        <div class="bg-secondary border-4 border-dark rounded-[2.5rem] p-8 flex flex-col justify-between shadow-[6px_6px_0px_rgba(41,47,54,1)] text-white">
                            <div class="bg-dark w-max px-5 py-1.5 rounded-full text-[10px] font-bold uppercase mb-6 border-2 border-white">Dresscode</div>
                            <div>
                                <h4 class="text-3xl font-serif mb-2">{{ $content['dresscode'] ?? 'Formal & Elegant' }}</h4>
                                <p class="font-medium text-sm leading-relaxed">"Your presence is our greatest gift, your elegance completes our joy." Kami memohon kehadiran Anda dengan busana terbaik.</p>
                            </div>
                        </div>
                        @endif
                        
                        @if (!empty($content['enable_health_protocol']))
                        <div class="bg-light border-4 border-dark rounded-[2.5rem] p-8 shadow-[6px_6px_0px_rgba(41,47,54,1)]">
                            <h5 class="text-primary text-xs font-bold uppercase tracking-widest mb-6 border-b-2 border-dark pb-2 inline-block">Protokol Kesehatan</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-white border-2 border-dark rounded-2xl p-5 flex flex-col items-center text-center gap-3 hover:-translate-y-1 transition-transform shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                                    <i class="fa-solid fa-hands-bubbles text-3xl text-primary"></i>
                                    <span class="font-bold text-[10px] uppercase tracking-widest text-dark">Cuci Tangan</span>
                                </div>
                                <div class="bg-white border-2 border-dark rounded-2xl p-5 flex flex-col items-center text-center gap-3 hover:-translate-y-1 transition-transform shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                                    <i class="fa-solid fa-head-side-mask text-3xl text-primary"></i>
                                    <span class="font-bold text-[10px] uppercase tracking-widest text-dark">Pakai Masker</span>
                                </div>
                                <div class="bg-white border-2 border-dark rounded-2xl p-5 flex flex-col items-center text-center gap-3 hover:-translate-y-1 transition-transform shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                                    <i class="fa-solid fa-people-arrows text-3xl text-primary"></i>
                                    <span class="font-bold text-[10px] uppercase tracking-widest text-dark">Jaga Jarak</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(!empty($content['enable_adab_walimah']))
                    <div class="bg-bg border-4 border-dark rounded-[2.5rem] p-8 shadow-[6px_6px_0px_rgba(41,47,54,1)] flex flex-col">
                        <h5 class="text-primary text-xs font-bold uppercase tracking-widest flex items-center gap-3 border-b-2 border-dark pb-4 mb-6">
                            <i class="fa-solid fa-list-check text-xl"></i> Adab Walimah
                        </h5>
                        <div class="space-y-4 flex-1 overflow-y-auto pr-2 custom-scrollbar max-h-[500px]">
                            <div class="flex gap-4 items-center bg-white p-4 rounded-2xl border-2 border-dark shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                                <div class="text-accent text-2xl w-10 shrink-0 flex justify-center"><i class="fa-solid fa-mosque"></i></div>
                                <div><p class="font-bold text-[11px] uppercase text-dark mb-1">Waktu Sholat</p><p class="text-[10px] text-gray-500 font-medium">Memperhatikan waktu ibadah saat acara.</p></div>
                            </div>
                            <div class="flex gap-4 items-center bg-white p-4 rounded-2xl border-2 border-dark shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                                <div class="text-accent text-2xl w-10 shrink-0 flex justify-center"><i class="fa-solid fa-utensils"></i></div>
                                <div><p class="font-bold text-[11px] uppercase text-dark mb-1">Adab Makan</p><p class="text-[10px] text-gray-500 font-medium">Makan & minum dengan cara duduk.</p></div>
                            </div>
                            <div class="flex gap-4 items-center bg-white p-4 rounded-2xl border-2 border-dark shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                                <div class="text-accent text-2xl w-10 shrink-0 flex justify-center"><i class="fa-solid fa-hands-praying"></i></div>
                                <div><p class="font-bold text-[11px] uppercase text-dark mb-1">Doa Restu</p><p class="text-[10px] text-gray-500 font-medium">Memberikan doa keberkahan bagi kami.</p></div>
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
        <section id="qr-tamu" class="py-24 px-6 bg-dark text-white pb-40">
            <div class="max-w-3xl mx-auto flex flex-col md:flex-row items-center gap-12 bg-white border-4 border-primary rounded-[3rem] p-8 md:p-12 shadow-[12px_12px_0px_rgba(255,107,107,1)]">
                <div class="w-full md:w-1/2 flex flex-col items-center justify-center text-dark">
                    <div class="border-4 border-dark p-4 rounded-[2rem] bg-bg mb-4">
                        <img id="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=292F36&bgcolor=FFFDF7&data={{ $guestData->qr_code ?? $guestData->slug_name }}" class="w-40 h-40 rounded-xl" alt="QR">
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-secondary">Akses Masuk</p>
                </div>
                <div class="w-full md:w-1/2 text-center md:text-left text-dark">
                    <h2 class="text-4xl font-serif mb-2">Scan QR</h2>
                    <p class="font-medium text-sm mb-6">Tunjukkan kode QR ini kepada penerima tamu saat Anda tiba di lokasi acara.</p>
                    <div class="bg-light border-2 border-dark px-6 py-4 rounded-[1.5rem]">
                        <p class="text-[10px] uppercase font-bold text-gray-500 mb-1">Nama Tamu</p>
                        <h3 id="guest-name-qr" class="font-serif text-2xl text-primary">{{ $guestData->name }}</h3>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- RSVP MODAL -->
        <section id="rsvp-modal" class="fixed inset-0 z-[500] invisible transition-all duration-500 overflow-hidden flex items-end md:items-center justify-center">
            <div onclick="closeRSVP()" class="absolute inset-0 bg-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>
            <div id="rsvp-content" class="relative w-full md:max-w-xl bg-white border-4 border-dark rounded-t-[3rem] md:rounded-[3rem] shadow-[0px_-10px_0px_rgba(255,230,109,1)] md:shadow-[12px_12px_0px_rgba(255,230,109,1)] transform translate-y-full transition-transform duration-700 flex flex-col max-h-[90vh]">
                
                <div class="overflow-y-auto p-8 md:p-10 custom-scrollbar">
                    <div class="flex justify-between items-center mb-8 border-b-4 border-dark pb-4">
                        <h2 class="text-4xl font-serif text-primary">RSVP</h2>
                        <button onclick="closeRSVP()" class="w-10 h-10 border-2 border-dark rounded-full flex items-center justify-center hover:bg-dark hover:text-white transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <form id="form-rsvp" class="space-y-6" onsubmit="submitRsvp(event)">
                        @csrf
                        <input type="hidden" id="input-pax-rsvp" value="1">

                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Nama Lengkap</label>
                            <input type="text" id="input-nama-rsvp" value="{{ $guestNameInput }}" required class="w-full bg-light border-2 border-dark focus:bg-white focus:border-primary p-4 rounded-2xl font-medium outline-none transition-colors" placeholder="Masukkan nama Anda">
                        </div>
                        
                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Kehadiran</label>
                            <div class="flex gap-4">
                                <button type="button" onclick="selectAttendance('hadir')" id="btn-hadir" class="flex-1 py-4 border-2 border-dark rounded-2xl bg-white text-dark font-bold text-[10px] uppercase hover:bg-bg transition-colors"><i class="fa-solid fa-check mr-2 text-secondary"></i> Hadir</button>
                                <button type="button" onclick="selectAttendance('tidak_hadir')" id="btn-absen" class="flex-1 py-4 border-2 border-dark rounded-2xl bg-white text-dark font-bold text-[10px] uppercase hover:bg-bg transition-colors"><i class="fa-solid fa-xmark mr-2 text-primary"></i> Absen</button>
                            </div>
                        </div>

                        <!-- Pax 1, 2, 3, 3+ -->
                        <div id="guest-selection" class="hidden">
                            <div class="bg-accent border-2 border-dark p-5 rounded-2xl">
                                <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-3 block text-center">Jumlah Tamu Hadir</label>
                                <div class="flex gap-2">
                                    <button type="button" class="guest-btn flex-1 py-2 bg-dark text-white border-2 border-dark rounded-xl font-bold">1</button>
                                    <button type="button" class="guest-btn flex-1 py-2 bg-white text-dark border-2 border-dark rounded-xl font-bold hover:bg-bg">2</button>
                                    <button type="button" class="guest-btn flex-1 py-2 bg-white text-dark border-2 border-dark rounded-xl font-bold hover:bg-bg">3</button>
                                    <button type="button" class="guest-btn flex-1 py-2 bg-white text-dark border-2 border-dark rounded-xl font-bold hover:bg-bg">3+</button>
                                </div>
                                <div id="custom-pax-container" class="hidden mt-3">
                                    <input type="number" id="custom-pax-input" placeholder="Isi jumlah spesifik (Misal: 4)" class="w-full bg-white border-2 border-dark focus:border-primary px-5 py-3 rounded-xl text-center font-bold text-sm outline-none transition-colors" min="4">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Doa & Ucapan</label>
                            <textarea id="input-pesan-rsvp" rows="4" placeholder="Tuliskan pesan bahagia..." required class="w-full bg-light border-2 border-dark focus:bg-white focus:border-primary p-4 rounded-2xl font-medium outline-none resize-none transition-colors"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-primary text-white border-2 border-dark rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-dark hover:-translate-y-1 shadow-[4px_4px_0px_rgba(41,47,54,1)] transition-all active:shadow-none active:translate-y-0">Kirim Konfirmasi</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- SUCCESS MODAL RSVP -->
        <div id="rsvp-success-modal" class="fixed inset-0 z-[601] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-dark/60 backdrop-blur-md"></div>
            <div class="relative bg-white w-full max-w-sm border-4 border-dark rounded-[2.5rem] shadow-[8px_8px_0px_rgba(78,205,196,1)] overflow-hidden animate-slide-up">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 rounded-full bg-secondary border-4 border-dark flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-check text-dark text-4xl"></i>
                    </div>
                    <h3 class="text-3xl font-serif text-dark mb-2">Sukses!</h3>
                    <p id="rsvp-success-message" class="text-gray-600 text-sm font-medium mb-8">RSVP Anda telah tersimpan.</p>
                    <button onclick="closeRsvpSuccessModal()" class="w-full py-3 bg-dark text-white rounded-full font-bold uppercase tracking-widest text-[10px] hover:bg-primary transition-colors border-2 border-dark">Tutup</button>
                </div>
            </div>
        </div>

        <footer class="py-16 px-6 bg-surface border-t-4 border-dark text-center pb-32">
            <h2 class="text-6xl font-serif text-primary mb-4">{{ substr($gNickname, 0, 1) }} & {{ substr($bNickname, 0, 1) }}</h2>
            <p class="font-bold text-[10px] uppercase tracking-widest mb-10">{{ $gNickname }} & {{ $bNickname }} • {{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->format('Y') : '2026' }}</p>
            
            @if(!$isPreviewMode)
            <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank" class="inline-flex items-center gap-2 bg-bg border-2 border-dark px-6 py-3 rounded-full font-bold text-xs hover:bg-accent transition-colors shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                <i class="fa-brands fa-instagram text-primary text-lg"></i> @ruangrestu.undangan
            </a>
            @endif
        </footer>

    </main>

    <!-- BOTTOM NAV -->
    <nav id="bottom-nav" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-white border-4 border-dark rounded-full shadow-[6px_6px_0px_rgba(41,47,54,1)] p-1.5 backdrop-blur-md">
            <ul class="flex justify-around items-center h-14 w-[300px] md:w-[380px]">
                <li class="relative group h-full">
                    <a href="#home" class="flex items-center justify-center w-12 h-full text-dark hover:bg-accent transition-all rounded-full border-2 border-transparent hover:border-dark">
                        <i class="fa-solid fa-house text-lg"></i>
                    </a>
                </li>
                @if (!empty($content['is_gallery_active']))
                <li class="relative group h-full">
                    <a href="#gallery" class="flex items-center justify-center w-12 h-full text-dark hover:bg-secondary hover:text-white transition-all rounded-full border-2 border-transparent hover:border-dark">
                        <i class="fa-solid fa-image text-lg"></i>
                    </a>
                </li>
                @endif
                <li class="relative group h-full">
                    <a href="#lokasi" class="flex items-center justify-center w-12 h-full text-dark hover:bg-primary hover:text-white transition-all rounded-full border-2 border-transparent hover:border-dark">
                        <i class="fa-solid fa-location-dot text-lg"></i>
                    </a>
                </li>
                <li class="relative h-full px-1 flex items-center">
                    <a href="javascript:void(0)" onclick="openRSVP()" class="flex items-center justify-center px-6 h-10 bg-dark text-white rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-accent hover:text-dark transition-all border-2 border-dark">
                        RSVP
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- FAB -->
    <div id="fab-container" class="fixed right-6 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000">
        <div class="relative flex items-center group">
            <div id="music-info" class="absolute right-full mr-3 px-4 py-2 bg-dark text-accent border-2 border-dark rounded-full font-bold text-[10px] uppercase tracking-widest opacity-0 transition-all pointer-events-none whitespace-nowrap">
                {{ $invitation->music->name ?? $invitation->music->title ?? 'Lagu Pernikahan' }} {{ !empty($invitation->music->artist) ? '- ' . $invitation->music->artist : '' }}
            </div>
            <button id="btn-music" onclick="toggleMusic()" class="w-14 h-14 bg-white border-4 border-dark rounded-full flex items-center justify-center text-primary shadow-[4px_4px_0px_rgba(41,47,54,1)] hover:bg-bg transition-colors"><i class="fa-solid fa-music animate-spin-slow" id="icon-music"></i></button>
        </div>
        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-14 h-14 bg-white border-4 border-dark rounded-full flex items-center justify-center text-secondary shadow-[4px_4px_0px_rgba(41,47,54,1)] hover:bg-bg transition-colors"><i class="fa-solid fa-angles-down" id="icon-scroll"></i></button>
    </div>

    <!-- LIGHTBOX -->
    <div id="lightbox" class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-dark/95 backdrop-blur-sm p-4">
        <div class="w-full flex justify-between items-center p-6 absolute top-0">
            <span class="bg-accent border-2 border-dark px-4 py-1.5 rounded-full font-bold text-xs"><span id="current-count">1</span> / <span id="total-count">4</span></span>
            <button onclick="closeLightbox()" class="w-12 h-12 bg-white border-2 border-dark rounded-full text-dark flex items-center justify-center hover:bg-primary hover:text-white transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <img id="lightbox-img" src="" class="max-h-[75vh] max-w-[90vw] border-8 border-white rounded-[2rem] object-contain transition-opacity duration-300">
        <div class="absolute bottom-10 flex gap-4">
            <button onclick="prevImg()" class="w-14 h-14 bg-white border-4 border-dark rounded-full text-dark flex items-center justify-center hover:bg-secondary transition-colors"><i class="fa-solid fa-arrow-left"></i></button>
            <button onclick="nextImg()" class="w-14 h-14 bg-white border-4 border-dark rounded-full text-dark flex items-center justify-center hover:bg-secondary transition-colors"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        function openInvitation() {
            document.getElementById('cover-page').classList.add('-translate-y-full');
            document.body.style.overflowY = 'auto';
            document.getElementById('main-content').classList.remove('opacity-0');
            document.getElementById('fab-container').classList.remove('opacity-0');
            document.getElementById('bottom-nav').classList.remove('translate-y-32');
            toggleMusic(true);
            toggleAutoScroll(true);

            // Tampilkan info musik 3 detik
            const musicInfo = document.getElementById('music-info');
            if(musicInfo) {
                setTimeout(() => {
                    musicInfo.classList.remove('opacity-0', 'pointer-events-none');
                    musicInfo.classList.add('opacity-100');
                    setTimeout(() => {
                        musicInfo.classList.remove('opacity-100');
                        musicInfo.classList.add('opacity-0', 'pointer-events-none');
                    }, 3000);
                }, 1200);
            }
        }

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
                }).catch(()=>{});
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
        window.addEventListener('wheel', () => { if(isAutoScrolling) toggleAutoScroll(); }, {passive:true});
        window.addEventListener('touchmove', () => { if(isAutoScrolling) toggleAutoScroll(); }, {passive:true});

        // Copy Clipboard
        function copyToClipboard(id, btn) {
            const text = document.getElementById(id).innerText;
            const toast = document.getElementById('copy-toast');
            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerHTML;
                btn.innerHTML = 'Tersalin!';
                btn.classList.add('bg-primary', 'text-white');
                toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-primary', 'text-white');
                    toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                }, 2000);
            });
        }

        // Live Streaming Platform Switch
        function switchPlatform(id, title, desc, iconClass, link) {
            const display = document.getElementById('streaming-display');
            if(!display) return;
            display.style.transform = 'scale(0.95)';
            display.style.opacity = '0.5';
            setTimeout(() => {
                document.getElementById('platform-title').innerText = title;
                document.getElementById('platform-desc').innerText = desc;
                document.getElementById('platform-icon').className = iconClass + ' text-6xl text-primary mb-6 animate-bounce';
                document.getElementById('platform-link').href = link;
                display.style.transform = 'scale(1)';
                display.style.opacity = '1';
            }, 300);
        }

        // Gallery Lightbox
        const images = Array.from(document.querySelectorAll('.gallery-img')).map(img => img.src);
        let curImg = 0;
        function openLightbox(idx) {
            curImg = idx; updateLB();
            document.getElementById('lightbox').classList.remove('hidden');
            document.getElementById('lightbox').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.getElementById('lightbox').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        function updateLB() {
            const img = document.getElementById('lightbox-img');
            img.style.opacity = '0';
            setTimeout(() => { img.src = images[curImg]; img.style.opacity = '1'; }, 200);
            document.getElementById('current-count').innerText = curImg + 1;
            document.getElementById('total-count').innerText = images.length;
        }
        function nextImg() { curImg = (curImg+1)%images.length; updateLB(); }
        function prevImg() { curImg = (curImg-1+images.length)%images.length; updateLB(); }
        
        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').classList.contains('hidden')) return;
            if (e.key === "ArrowRight") nextImg();
            if (e.key === "ArrowLeft") prevImg();
            if (e.key === "Escape") closeLightbox();
        });
    </script>

    <!-- RSVP & GIFT LOGIC JS -->
    <script>
        // === LOGIKA RSVP ===
        let hasShownRSVPAtEnd = false;
        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 150) {
                if (!hasShownRSVPAtEnd && !document.getElementById('cover-page').classList.contains('translate-y-0')) {
                    openRSVP();
                    hasShownRSVPAtEnd = true;
                    if (isAutoScrolling) toggleAutoScroll();
                }
            }
        }, { passive: true });

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
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');
            const paxInput = document.getElementById('input-pax-rsvp');

            [btnHadir, btnAbsen].forEach(btn => {
                btn.classList.remove('bg-dark', 'text-white');
                btn.classList.add('bg-white', 'text-dark');
            });

            if (status === 'hadir') {
                btnHadir.classList.remove('bg-white', 'text-dark');
                btnHadir.classList.add('bg-dark', 'text-white');
                guestDiv.classList.remove('hidden');
                if (!document.querySelector('.guest-btn.bg-dark')) {
                    document.querySelectorAll('.guest-btn')[0].click();
                }
            } else {
                btnAbsen.classList.remove('bg-white', 'text-dark');
                btnAbsen.classList.add('bg-dark', 'text-white');
                guestDiv.classList.add('hidden');
                paxInput.value = 0;
            }
        }

        document.querySelectorAll('.guest-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.guest-btn').forEach(b => {
                    b.classList.remove('bg-dark', 'text-white');
                    b.classList.add('bg-white', 'text-dark');
                });
                this.classList.remove('bg-white', 'text-dark');
                this.classList.add('bg-dark', 'text-white');
                
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

        function appendNewWish(guestName, statusRsvp, pax, message) {
            const container = document.getElementById('wishes-container');
            const emptyMsg = document.getElementById('empty-wishes-msg');
            if (emptyMsg) emptyMsg.remove();

            const badgeHtml = statusRsvp === 'hadir' 
                ? `<span class="inline-block px-2 py-0.5 bg-secondary text-white rounded text-[9px] font-bold uppercase tracking-widest mb-1"><i class="fa-solid fa-check"></i> Hadir (${pax} Orang)</span>` 
                : '';

            const newWishHtml = `
                <div class="bg-bg border-2 border-dark p-5 rounded-2xl flex gap-4 animate-slide-up">
                    <div class="w-10 h-10 bg-white border-2 border-dark rounded-full flex-shrink-0 flex items-center justify-center text-primary"><i class="fa-solid fa-user"></i></div>
                    <div class="flex-1 space-y-1">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="font-bold text-sm text-dark">${guestName}</h5>
                            <span class="text-[10px] text-gray-500">Baru saja</span>
                        </div>
                        ${badgeHtml}
                        <p class="text-xs text-dark font-medium italic leading-relaxed">"${message}"</p>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('afterbegin', newWishHtml);
            
            const totalWishesEl = document.getElementById('total-wishes');
            totalWishesEl.innerText = (parseInt(totalWishesEl.innerText) || 0) + 1;
            
            if (statusRsvp === 'hadir') {
                const totalAttendanceEl = document.getElementById('total-attendance');
                totalAttendanceEl.innerText = (parseInt(totalAttendanceEl.innerText) || 0) + parseInt(pax);
            }
        }

        function submitRsvp(e) {
            e.preventDefault();
            const guestName = document.getElementById('input-nama-rsvp').value.trim();
            const message = document.getElementById('input-pesan-rsvp').value.trim();
            const pax = document.getElementById('input-pax-rsvp').value;
            const btnSubmit = e.target.querySelector('button[type="submit"]');

            const selectedBtn = document.querySelector('#btn-hadir.bg-dark, #btn-absen.bg-dark');
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
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': payload.get('_token') },
                body: payload
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    appendNewWish(guestName, statusRsvp, pax, message);
                    document.getElementById('rsvp-success-message').textContent = data.message || 'RSVP Anda telah tersimpan.';
                    document.getElementById('rsvp-success-modal').classList.remove('hidden');
                    document.getElementById('form-rsvp').reset();
                    setTimeout(() => closeRsvpSuccessModal(), 2500); 
                } else {
                    alert('Gagal menyimpan RSVP.');
                }
            }).catch(() => alert('Terjadi kesalahan koneksi.'))
            .finally(() => { btnSubmit.innerHTML = originalText; btnSubmit.disabled = false; });
        }

        function closeRsvpSuccessModal() {
            document.getElementById('rsvp-success-modal').classList.add('hidden');
            closeRSVP();
        }

        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if (show) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; } 
            else { modal.classList.add('hidden'); document.body.style.overflow = 'auto'; }
        }

        function selectGiftAttendance(status) {
            document.getElementById('gift-confirm-status').value = status;
            const btnHadir = document.getElementById('gift-btn-hadir');
            const btnAbsen = document.getElementById('gift-btn-absen');
            const wrapper = document.getElementById('gift-confirm-pax-wrapper');
            
            btnHadir.className = "flex-1 py-3 bg-white border-2 border-dark text-dark rounded-xl font-bold text-xs uppercase hover:bg-bg transition-colors";
            btnAbsen.className = "flex-1 py-3 bg-white border-2 border-dark text-dark rounded-xl font-bold text-xs uppercase hover:bg-bg transition-colors";
            
            if (status === 'hadir') {
                btnHadir.className = "flex-1 py-3 bg-dark text-white border-2 border-dark rounded-xl font-bold text-xs uppercase transition-colors";
                wrapper.classList.remove('hidden');
                document.getElementById('gift-confirm-pax').value = 1;
                setGiftPax(1);
            } else {
                btnAbsen.className = "flex-1 py-3 bg-dark text-white border-2 border-dark rounded-xl font-bold text-xs uppercase transition-colors";
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
                btn.className = "gift-pax-btn flex-1 py-2 rounded-xl border-2 border-dark bg-white text-dark font-bold text-xs transition-colors hover:bg-bg";
                if (text == count || (count === 'custom' && text === '3+')) {
                    btn.className = "gift-pax-btn flex-1 py-2 rounded-xl border-2 border-dark bg-dark text-white font-bold text-xs transition-colors";
                }
            });
            updateGiftPaxDisplay(hiddenPaxInput.value);
        }

        function updateGiftPaxDisplay(pax) {
            document.getElementById('gift-pax-display').textContent = `${pax} Orang`;
        }

        function confirmGift(id, name) {
            const guestNameInput = document.getElementById('gift-confirm-name');
            const paxCustomInput = document.getElementById('gift-custom-pax-input');
            
            if (paxCustomInput) paxCustomInput.value = '';
            
            selectGiftAttendance('hadir');
            
            document.getElementById('confirm-modal').classList.remove('hidden');
            document.getElementById('final-confirm-btn').onclick = () => {
                const finalName = document.getElementById('gift-confirm-name').value.trim() || 'Hamba Allah';
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

        function closeConfirmModal() { document.getElementById('confirm-modal').classList.add('hidden'); }
        function closeGiftSuccessModal() { document.getElementById('gift-success-modal').classList.add('hidden'); toggleGiftModal(false); }

        function processClaim(guestName, giftName, rsvpStatus, giftPax) {
            if (!guestName) { alert('Silakan isi nama Anda terlebih dahulu.'); return; }

            const confirmButton = document.getElementById('final-confirm-btn');
            const originalText = confirmButton.innerHTML;
            confirmButton.disabled = true;
            confirmButton.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i>';

            const messageValue = `[Tanda Kasih] Telah memberikan kado: ${giftName}`;
            
            const payload = new FormData();
            payload.append('_token', document.querySelector('input[name="_token"]')?.value || '');
            payload.append('guest_name', guestName);
            payload.append('status_rsvp', rsvpStatus);
            payload.append('pax', giftPax);
            payload.append('message', messageValue);

            fetch('{{ route('rsvp.store', $invitation->slug ?? '') }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': payload.get('_token') },
                body: payload
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    appendNewWish(guestName, rsvpStatus, giftPax, messageValue);
                    closeConfirmModal();
                    document.getElementById('gift-success-message').textContent = `Kado "${giftName}" telah tercatat atas nama ${guestName}.`;
                    document.getElementById('gift-success-modal').classList.remove('hidden');
                    setTimeout(() => closeGiftSuccessModal(), 2500); 
                } else {
                    alert('Gagal menyimpan kado.');
                }
            }).catch(() => alert('Gagal terhubung ke server.'))
            .finally(() => {
                confirmButton.disabled = false;
                confirmButton.innerHTML = originalText;
            });
        }
    </script>
</body>
</html>