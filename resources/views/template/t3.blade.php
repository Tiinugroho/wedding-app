@php
    // 1. Decode data JSON dari database
    $content = json_decode($invitation->details->content ?? '{}', true);

    // 2. Logika Urutan Mempelai
    $groomFirst = ($content['couple_order'] ?? 'groom_first') === 'groom_first';

    $groom = [
        'name' => $content['groom_name'] ?? 'Romeo Montague',
        'nickname' => $content['groom_nickname'] ?? 'Romeo',
        'father' => $content['groom_father'] ?? 'Bapak Montague',
        'mother' => $content['groom_mother'] ?? 'Ibu Montague',
        'photo' => !empty($content['groom_photo']) ? asset('storage/' . $content['groom_photo']) : 'https://images.soco.id/230-58.jpg.jpeg',
        'ig' => $content['groom_ig'] ?? '',
        'label' => 'The Groom',
        'gender_text' => 'Putra',
    ];

    $bride = [
        'name' => $content['bride_name'] ?? 'Juliet Capulet',
        'nickname' => $content['bride_nickname'] ?? 'Juliet',
        'father' => $content['bride_father'] ?? 'Bapak Capulet',
        'mother' => $content['bride_mother'] ?? 'Ibu Capulet',
        'photo' => !empty($content['bride_photo']) ? asset('storage/' . $content['bride_photo']) : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg',
        'ig' => $content['bride_ig'] ?? '',
        'label' => 'The Bride',
        'gender_text' => 'Putri',
    ];

    $firstPerson = $groomFirst ? $groom : $bride;
    $secondPerson = $groomFirst ? $bride : $groom;

    // 3. Waktu & Countdown
    $akadDate = $content['akad_date'] ?? date('Y-m-d');
    $akadTime = $content['akad_time'] ?? '08:00';
    $weddingTimestamp = 0;
    $coverDateDisplay = '- . - . -';

    if (!empty($content['events']) && is_array($content['events']) && count($content['events']) > 0) {
        $firstEvent = collect($content['events'])->first();
        if (!empty($firstEvent['date'])) {
            $coverDateDisplay = \Carbon\Carbon::parse($firstEvent['date'])->format('d . m . Y');
            $eventTime = !empty($firstEvent['time']) ? $firstEvent['time'] : '00:00:00';
            $weddingTimestamp = \Carbon\Carbon::parse($firstEvent['date'] . ' ' . $eventTime)->timestamp * 1000;
        }
    }

    $coverImage = !empty($content['cover_image']) ? asset('storage/' . $content['cover_image']) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2000&auto=format&fit=crop';

    // 4. Helper Live Streaming
    $platformIcons = [
        'youtube' => ['icon' => 'fa-brands fa-youtube', 'title' => 'YouTube Live'],
        'instagram' => ['icon' => 'fa-brands fa-instagram', 'title' => 'Instagram Live'],
        'tiktok' => ['icon' => 'fa-brands fa-tiktok', 'title' => 'TikTok Live'],
        'zoom' => ['icon' => 'fa-solid fa-video', 'title' => 'Zoom Meeting'],
        'gmeet' => ['icon' => 'fa-solid fa-camera-retro', 'title' => 'Google Meet'],
    ];

    // 5. Ambil data Bank
    $masterLogos = \DB::table('banks')->pluck('logo', 'name')->toArray();
    $masterLogos = array_change_key_case($masterLogos, CASE_LOWER);

    // 6. Data RSVP & Wishes dari Database
    $dbWishes = \DB::table('wishes_rsvps')->where('invitation_id', $invitation->id)->orderBy('created_at', 'desc')->get();
    $totalAttendance = \DB::table('wishes_rsvps')->where('invitation_id', $invitation->id)->where('status_rsvp', 'hadir')->sum('pax') ?? 0;
    $totalWishes = $dbWishes->count();
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }} - Original Series</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Custom Scrollbar for Netflix Feel */
        .scroll-custom::-webkit-scrollbar { width: 5px; }
        .scroll-custom::-webkit-scrollbar-track { background: transparent; }
        .scroll-custom::-webkit-scrollbar-thumb { background: #e50914; border-radius: 10px; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .modal-open { overflow: hidden !important; }
        .cover-locked { overflow-y: hidden !important; } /* PROTEKSI RSVP POPUP */

        @keyframes slide-up {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        .animate-slide-up { animation: slide-up 0.5s ease-out forwards; }
        
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-fade-in { animation: fade-in 1s ease-in-out forwards; }

        @media (max-width: 360px) {
            .text-8xl { font-size: 4rem; }
            .text-5xl { font-size: 2.5rem; }
        }

        body {
            background-color: #141414;
            color: #E5E5E5;
            -webkit-font-smoothing: antialiased;
        }

        .vignette { background: radial-gradient(circle at center, transparent 0%, #141414 100%); }
        .bottom-gradient { background: linear-gradient(to top, #141414 0%, transparent 100%); }
        .text-shadow { text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); }
        .episode-card:hover .episode-img { transform: scale(1.05); }
        
        .input-luxury {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-luxury:focus {
            border-color: #E50914;
            background: rgba(255, 255, 255, 0.1);
            outline: none;
        }

        /* Hilangkan panah spinner input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        netflix: {
                            red: '#E50914',
                            dark: '#141414',
                            gray: '#808080',
                            light: '#E5E5E5',
                            darker: '#000000',
                            hover: '#B81D24'
                        }
                    },
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif'],
                        bebas: ['"Bebas Neue"', 'sans-serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-netflix-dark text-netflix-light font-sans selection:bg-netflix-red selection:text-white relative cover-locked">

    <audio id="bg-music" loop>
        @if ($invitation->music_id && $invitation->music)
            <source src="{{ asset('storage/' . $invitation->music->file_path) }}" type="audio/mpeg">
        @else
            <source src="https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3" type="audio/mpeg">
        @endif
    </audio>

    <div id="cover-page" class="fixed inset-0 z-[100] flex flex-col items-center justify-center w-screen h-screen bg-netflix-dark transition-all duration-700">
        <div class="absolute top-0 w-full p-6 flex justify-between items-center">
            <h1 class="font-bebas text-netflix-red text-4xl md:text-5xl tracking-widest text-shadow">NikahNih</h1>
        </div>

        <div class="flex flex-col items-center justify-center transform transition-transform duration-500">
            <h2 class="text-3xl md:text-5xl font-medium text-white mb-10 text-center px-4">Who's watching?</h2>
            <div class="flex flex-wrap justify-center gap-6 md:gap-10">
                <button onclick="openInvitation()" class="group flex flex-col items-center gap-4 transition-transform hover:scale-105">
                    <div class="w-24 h-24 md:w-36 md:h-36 rounded-md overflow-hidden border-2 border-transparent group-hover:border-white transition-colors relative">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/0/0b/Netflix-avatar.png" class="w-full h-full object-cover" alt="Profile">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                    </div>
                    <span id="guest-name" class="text-netflix-gray group-hover:text-white transition-colors text-sm md:text-lg font-medium">Tamu Undangan</span>
                </button>
            </div>
            <button onclick="openInvitation()" class="mt-16 px-6 py-2 border border-netflix-gray text-netflix-gray hover:text-white hover:border-white uppercase tracking-widest text-xs font-medium transition-colors">
                Buka Undangan
            </button>
        </div>
    </div>

    <main id="main-content" class="min-h-screen opacity-0 transition-opacity duration-1000 pb-20">

        <nav class="fixed top-0 w-full z-50 bg-gradient-to-b from-black/80 to-transparent p-6 flex justify-between items-center transition-all duration-300" id="main-nav">
            <h1 class="font-bebas text-netflix-red text-3xl md:text-4xl tracking-widest drop-shadow-md">NikahNih</h1>
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-magnifying-glass text-white cursor-pointer hover:text-netflix-gray transition-colors"></i>
                <div class="w-8 h-8 rounded-sm overflow-hidden">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/0b/Netflix-avatar.png" alt="User">
                </div>
            </div>
        </nav>

        <section id="home" class="relative h-[85vh] md:h-screen flex items-center justify-start px-6 md:px-16 pt-20">
            <div class="absolute inset-0 z-0">
                <img src="{{ $coverImage }}" class="w-full h-full object-cover" alt="Hero">
                <div class="absolute inset-0 vignette"></div>
                <div class="absolute inset-x-0 bottom-0 h-1/2 bottom-gradient"></div>
                <div class="absolute inset-0 bg-black/30"></div>
            </div>

            <div class="relative z-10 max-w-2xl mt-12 md:mt-0">
                <div class="flex items-center gap-2 mb-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" class="h-4 brightness-0 invert" alt="N">
                    <span class="text-[10px] md:text-xs font-bold tracking-[0.3em] text-white/80 uppercase">Original Event</span>
                </div>

                <h2 class="text-6xl md:text-8xl font-bebas text-white tracking-wider mb-4 text-shadow leading-none">
                    {{ strtoupper($firstPerson['nickname']) }} <span class="text-netflix-red">&</span> {{ strtoupper($secondPerson['nickname']) }}
                </h2>

                <div class="flex items-center gap-3 text-xs md:text-sm font-semibold mb-6">
                    <span class="text-green-500">99% Match</span>
                    <span class="text-white">
                        {{ !empty($content['events'][0]['date']) ? \Carbon\Carbon::parse($content['events'][0]['date'])->format('Y') : \Carbon\Carbon::parse($akadDate)->format('Y') }}
                    </span>
                    <span class="px-2 py-0.5 border border-gray-500 text-white rounded-sm text-[10px]">SU</span>
                    
                    <span class="text-white">
                        {{ !empty($content['events'][0]['date']) ? \Carbon\Carbon::parse($content['events'][0]['date'])->translatedFormat('d F') : \Carbon\Carbon::parse($akadDate)->translatedFormat('d F') }}
                    </span>

                    <span class="px-1.5 py-0.5 border border-gray-500 text-white rounded-sm text-[10px] flex items-center gap-1"><i class="fa-solid fa-closed-captioning"></i> HD</span>
                </div>

                <p class="text-sm md:text-base text-white/90 leading-relaxed mb-8 max-w-lg text-shadow font-light">
                    {!! nl2br(e($content['quotes'] ?? 'Dengan memohon rahmat Tuhan, dua pemeran utama kami bersiap mengikat janji suci. Sebuah perayaan cinta abadi yang tayang perdana secara eksklusif. Jadilah saksi di hari paling bahagia mereka.')) !!}
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="javascript:void(0)" onclick="openRSVP()" class="px-6 py-3 bg-white text-black rounded font-bold flex items-center justify-center gap-3 hover:bg-white/80 transition-colors">
                        <i class="fa-solid fa-play text-xl"></i> RSVP Now
                    </a>
                    <a href="#cerita" class="px-6 py-3 bg-gray-500/50 text-white rounded font-bold flex items-center justify-center gap-3 hover:bg-gray-500/70 transition-colors backdrop-blur-sm">
                        <i class="fa-solid fa-circle-info text-xl"></i> More Info
                    </a>
                </div>
            </div>
        </section>

        <section id="cast" class="px-6 md:px-16 py-12 relative z-10 -mt-20">
            <h3 class="text-2xl font-bold text-white mb-6">Starring Cast</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                
                <div class="flex gap-6 items-center group {{ $groomFirst ? 'order-1' : 'order-2' }}">
                    <div class="w-32 h-44 md:w-40 md:h-56 shrink-0 rounded-md overflow-hidden relative border border-white/10">
                        <img src="{{ $firstPerson['photo'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Groom">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                    </div>
                    <div>
                        <p class="text-netflix-gray text-xs uppercase tracking-widest mb-1 font-bold">{{ $firstPerson['label'] }}</p>
                        <h4 class="text-2xl md:text-3xl font-bebas text-white tracking-wide mb-2">{{ $firstPerson['name'] }}</h4>
                        <p class="text-sm text-netflix-gray font-light">{{ $firstPerson['gender_text'] }} dari Bapak {{ $firstPerson['father'] }} & Ibu {{ $firstPerson['mother'] }}</p>
                       @if (!empty($firstPerson['ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $firstPerson['ig']) }}" target="_blank" class="inline-flex items-center gap-2 mt-2 text-netflix-gray hover:text-white transition-colors">
                                <i class="fa-brands fa-instagram text-lg"></i> <span>{{ $firstPerson['ig'] }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex gap-6 items-center group {{ $groomFirst ? 'order-2' : 'order-1' }}">
                    <div class="w-32 h-44 md:w-40 md:h-56 shrink-0 rounded-md overflow-hidden relative border border-white/10">
                        <img src="{{ $secondPerson['photo'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Bride">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                    </div>
                    <div>
                        <p class="text-netflix-gray text-xs uppercase tracking-widest mb-1 font-bold">{{ $secondPerson['label'] }}</p>
                        <h4 class="text-2xl md:text-3xl font-bebas text-white tracking-wide mb-2">{{ $secondPerson['name'] }}</h4>
                        <p class="text-sm text-netflix-gray font-light">{{ $secondPerson['gender_text'] }} dari Bapak {{ $secondPerson['father'] }} & Ibu {{ $secondPerson['mother'] }}</p>
                        @if (!empty($secondPerson['ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $secondPerson['ig']) }}" target="_blank" class="inline-flex items-center gap-2 mt-2 text-netflix-gray hover:text-white transition-colors">
                                <i class="fa-brands fa-instagram text-lg"></i> <span>{{ $secondPerson['ig'] }}</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </section>

        @if (($content['is_story_active'] ?? false) && !empty($content['love_stories']))
        <section id="cerita" class="px-6 md:px-16 py-12 border-t border-netflix-gray/20 mt-8">
            <div class="flex items-end justify-between mb-8">
                <h3 class="text-2xl font-bold text-white">Episodes</h3>
                <span class="text-netflix-gray text-sm font-medium">Limited Series</span>
            </div>

            <div class="space-y-6">
                @foreach ($content['love_stories'] as $index => $story)
                <div class="episode-card flex flex-col md:flex-row gap-4 md:gap-6 items-start md:items-center p-4 hover:bg-white/5 rounded-lg transition-colors cursor-pointer border-b border-white/5 pb-6">
                    <h4 class="text-4xl font-bebas text-netflix-gray/50 hidden md:block">{{ $index + 1 }}</h4>
                    <div class="w-full md:w-48 h-28 shrink-0 rounded overflow-hidden relative episode-img-container border border-white/10">
                        @if(!empty($story['image']))
                            <img src="{{ asset('storage/' . $story['image']) }}" class="episode-img w-full h-full object-cover transition-transform duration-500" alt="Ep {{ $index + 1 }}">
                        @else
                            <div class="w-full h-full bg-[#333] flex items-center justify-center"><i class="fa-solid fa-heart text-netflix-gray/30 text-3xl"></i></div>
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-black/40 transition-opacity">
                            <i class="fa-regular fa-circle-play text-3xl text-white"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h5 class="font-bold text-white text-base">{{ $story['title'] ?? 'Our Journey' }}</h5>
                            <span class="text-xs text-netflix-gray font-medium">{{ $story['year'] ?? '' }}</span>
                        </div>
                        <p class="text-sm text-netflix-gray font-light leading-relaxed line-clamp-3">
                            {!! nl2br(e($story['description'] ?? '')) !!}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if ($content['is_gallery_active'] ?? false)
        <section id="gallery" class="py-12 border-t border-white/5">
            <div class="px-6 md:px-16 flex items-center gap-8 border-b border-netflix-gray/30 mb-8">
                <h3 class="text-xl md:text-2xl font-bold text-white border-b-4 border-netflix-red pb-4">Trailers & More</h3>
            </div>

            @if (!empty($content['youtube_links'][0]))
                @php
                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $content['youtube_links'][0], $match);
                    $youtube_id = $match[1] ?? '';
                @endphp
                @if ($youtube_id)
                <div class="px-6 md:px-16 mb-12">
                    <p class="text-xs font-bold text-netflix-red uppercase tracking-widest mb-4">Official Trailer</p>
                    <div class="relative w-full aspect-video rounded-md overflow-hidden bg-black shadow-2xl border border-white/10">
                        <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/{{ $youtube_id }}?autoplay=0&controls=1" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                @endif
            @endif

            @if (isset($invitation->galleries) && $invitation->galleries->count() > 0)
            <div class="relative group">
                <div class="flex overflow-x-auto gap-4 px-6 md:px-16 pb-8 snap-x snap-mandatory hide-scrollbar scroll-smooth">
                    @foreach ($invitation->galleries as $index => $gallery)
                    <div class="w-[70vw] md:w-[25vw] shrink-0 snap-start group/card cursor-pointer" onclick="openLightbox({{ $index }})">
                        <div class="relative aspect-video rounded overflow-hidden border border-white/10">
                            <img src="{{ asset('storage/' . $gallery->file_path) }}" class="gallery-img w-full h-full object-cover transition-transform duration-500 group-hover/card:scale-110">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/card:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="fa-solid fa-play text-white text-3xl"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </section>
        @endif

        @if ($content['is_event_active'] ?? false)
        <section id="lokasi" class="px-6 md:px-16 py-12 border-t border-white/5">
            <h3 class="text-2xl font-bold text-white mb-6">Premiere Locations</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-[#181818] p-8 rounded-md border border-white/5 hover:border-netflix-red/50 transition-colors">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="text-netflix-red text-xs font-bold uppercase tracking-widest block mb-1">Part 1</span>
                            <h4 class="text-2xl font-bebas tracking-wide text-white">Akad Nikah</h4>
                        </div>
                        <i class="fa-solid fa-ring text-2xl text-netflix-gray"></i>
                    </div>

                    <ul class="space-y-4 mb-8 text-sm text-netflix-light">
                        <li class="flex items-center gap-3"><i class="fa-regular fa-calendar text-netflix-gray w-5"></i> 
                            {{ \Carbon\Carbon::parse($akadDate)->translatedFormat('l, d F Y') }}</li>
                        <li class="flex items-center gap-3"><i class="fa-regular fa-clock text-netflix-gray w-5"></i> 
                            {{ $akadTime }} - {{ $content['akad_time_end'] ?? 'Selesai' }}</li>
                        <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot text-netflix-gray w-5 mt-1"></i> 
                            <span>{{ $content['akad_location'] ?? 'Lokasi Akad' }}<br><span class="text-netflix-gray text-xs">{{ $content['akad_address'] ?? '' }}</span></span>
                        </li>
                    </ul>

                    @if(!empty($content['akad_map']))
                    <a href="{{ $content['akad_map'] }}" target="_blank" class="block w-full py-3 bg-white/10 hover:bg-white/20 text-white text-center rounded text-sm font-semibold transition-colors">
                        <i class="fa-solid fa-map-location-dot mr-2"></i> Get Directions
                    </a>
                    @endif
                </div>

                @foreach ($content['events'] ?? [] as $idx => $event)
                <div class="bg-[#181818] p-8 rounded-md border border-white/5 hover:border-netflix-red/50 transition-colors">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="text-netflix-red text-xs font-bold uppercase tracking-widest block mb-1">Part {{ $idx + 2 }}</span>
                            <h4 class="text-2xl font-bebas tracking-wide text-white">{{ $event['title'] ?? 'Resepsi' }}</h4>
                        </div>
                        <i class="fa-solid fa-champagne-glasses text-2xl text-netflix-gray"></i>
                    </div>

                    <ul class="space-y-4 mb-8 text-sm text-netflix-light">
                        <li class="flex items-center gap-3"><i class="fa-regular fa-calendar text-netflix-gray w-5"></i> 
                            {{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}</li>
                        <li class="flex items-center gap-3"><i class="fa-regular fa-clock text-netflix-gray w-5"></i> 
                            {{ $event['time'] }} - {{ $event['time_end'] ?? 'Selesai' }}</li>
                        <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot text-netflix-gray w-5 mt-1"></i> 
                            <span>{{ $event['location'] ?? 'Lokasi Resepsi' }}<br><span class="text-netflix-gray text-xs">{{ $event['address'] ?? '' }}</span></span>
                        </li>
                    </ul>

                    @if(!empty($event['map']))
                    <a href="{{ $event['map'] }}" target="_blank" class="block w-full py-3 bg-netflix-red hover:bg-netflix-hover text-white text-center rounded text-sm font-semibold transition-colors">
                        <i class="fa-solid fa-map-location-dot mr-2"></i> Get Directions
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if (($content['is_livestream_active'] ?? false) && !empty($content['live_streams']))
        <section id="live-streaming" class="py-24 px-6 md:px-16 bg-[#141414] relative overflow-hidden border-t border-white/5">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-600/5 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="max-w-5xl mx-auto relative z-10">
                <div class="text-left mb-10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-600 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                        </span>
                        <span class="text-sm font-bold uppercase tracking-[0.3em] text-red-600">Live Virtual Wedding</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Siaran Langsung</h2>
                </div>

                @php $firstStream = $content['live_streams'][0]; @endphp
                <div class="relative group">
                    <div id="streaming-display" class="relative aspect-video w-full rounded-xl bg-black overflow-hidden border border-white/10 shadow-2xl transition-all duration-700">
                        <div class="absolute inset-0 bg-cover bg-center transition-all duration-1000 opacity-40 group-hover:scale-105" style="background-image: url('{{ $coverImage }}');"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>

                        <div class="relative h-full flex flex-col items-center justify-center p-6 text-white text-center">
                            <div class="mb-6 group-hover:scale-110 transition-transform duration-500">
                                <i id="platform-icon" class="{{ $platformIcons[$firstStream['platform']]['icon'] ?? 'fa-solid fa-video' }} text-6xl md:text-8xl text-white drop-shadow-[0_0_20px_rgba(255,255,255,0.3)]"></i>
                            </div>

                            <h3 id="platform-title" class="text-2xl md:text-4xl font-bold mb-2 tracking-tight">{{ $platformIcons[$firstStream['platform']]['title'] ?? ucfirst($firstStream['platform']) }}</h3>
                            <p id="platform-desc" class="text-netflix-gray text-sm md:text-lg mb-8 font-medium">Online Streaming</p>

                            <a id="platform-link" href="{{ $firstStream['link'] }}" target="_blank" class="flex items-center gap-3 px-8 md:px-10 py-3 md:py-4 bg-white text-black rounded font-bold text-sm md:text-lg hover:bg-white/80 transition-all active:scale-95 shadow-lg">
                                <i class="fa-solid fa-play"></i> <span>Putar Sekarang</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-12 {{ count($content['live_streams']) <= 1 ? 'hidden' : '' }}">
                    <h4 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                        <span class="w-1 h-5 bg-red-600"></span> Pilih Platform Lainnya
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($content['live_streams'] as $stream)
                            @php $pData = $platformIcons[$stream['platform']] ?? ['icon' => 'fa-solid fa-video', 'title' => ucfirst($stream['platform'])]; @endphp
                            <button onclick="switchPlatform('{{ $pData['title'] }}', 'Online Streaming', '{{ $pData['icon'] }}', '{{ $stream['link'] }}')"
                                class="platform-btn flex flex-col bg-[#2F2F2F] hover:bg-[#3F3F3F] p-4 rounded transition-all group text-left border-b-4 border-transparent hover:border-red-600">
                                <i class="{{ $pData['icon'] }} text-2xl text-red-600 mb-3"></i>
                                <span class="text-white font-bold text-xs uppercase tracking-wider mb-1">{{ $stream['platform'] }}</span>
                                <span class="text-netflix-gray text-[10px]">Tonton Live</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif

        @if ($content['is_wishes_active'] ?? false)
        <section id="guest-stats" class="py-24 px-6 md:px-16 bg-[#141414] relative overflow-hidden border-t border-white/5">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-red-600/5 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="max-w-5xl mx-auto relative z-10">
                <div class="mb-16 text-center md:text-left">
                    <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Kehadiran & Doa</h2>
                    <div class="flex items-center justify-center md:justify-start gap-2">
                        <span class="w-8 h-1 bg-red-600"></span>
                        <p class="text-netflix-gray text-xs md:text-sm font-bold uppercase tracking-[0.3em]">Trending Now</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-16">
                    <div class="flex items-center gap-4 group">
                        <div class="relative">
                            <span class="text-8xl md:text-9xl font-black text-black leading-none" style="-webkit-text-stroke: 2px #555;">1</span>
                        </div>
                        <div class="bg-netflix-gray/10 p-6 rounded-lg border border-white/5 flex-1 group-hover:bg-netflix-gray/20 transition-all">
                            <h4 id="total-attendance" class="text-4xl md:text-5xl font-bold text-white mb-1">{{ $totalAttendance }}</h4>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-red-600 font-bold">Tamu Akan Hadir</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 group">
                        <div class="relative">
                            <span class="text-8xl md:text-9xl font-black text-black leading-none" style="-webkit-text-stroke: 2px #555;">2</span>
                        </div>
                        <div class="bg-netflix-gray/10 p-6 rounded-lg border border-white/5 flex-1 group-hover:bg-netflix-gray/20 transition-all">
                            <h4 id="total-wishes" class="text-4xl md:text-5xl font-bold text-white mb-1">{{ $totalWishes }}</h4>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-red-600 font-bold">Ucapan Hangat</p>
                        </div>
                    </div>
                </div>

                <div class="bg-black/40 rounded-xl border border-white/10 overflow-hidden shadow-2xl relative">
                    <div class="flex items-center justify-between p-6 border-b border-white/10 bg-netflix-gray/5 relative z-10">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-layer-group text-red-600"></i>
                            <span class="text-sm font-bold uppercase tracking-widest text-white">Wishes Wall</span>
                        </div>
                        <span class="text-[10px] text-netflix-gray uppercase font-bold">Scroll to read</span>
                    </div>

                    <div id="wishes-container" class="max-h-[500px] overflow-y-auto scroll-custom p-4 md:p-8 space-y-4 relative z-10">
                        </div>
                    
                    <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-black to-transparent pointer-events-none z-20"></div>
                </div>
            </div>
        </section>
        @endif

        @if ($content['is_gift_active'] ?? false)
        <section id="hadiah" class="py-24 px-6 md:px-16 bg-[#141414] relative overflow-hidden border-t border-white/5">
            <div class="absolute top-1/2 left-0 w-[500px] h-[500px] bg-red-600/5 blur-[120px] rounded-full pointer-events-none"></div>

            <div class="max-w-5xl mx-auto relative z-10">
                <div class="mb-16 md:text-center text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <span class="w-8 h-[2px] bg-red-600"></span>
                        <span class="text-[10px] tracking-[0.5em] uppercase text-gray-400 font-black">Gift Registry</span>
                        <span class="w-8 h-[2px] bg-red-600"></span>
                    </div>
                    <h2 class="text-4xl md:text-6xl font-black text-white mb-6 uppercase tracking-tighter">Kirim Kado</h2>
                    <p class="text-sm md:text-base text-gray-400 font-medium leading-relaxed max-w-2xl mx-auto">
                        Kehadiran Anda adalah kado terbesar. Namun bagi Anda yang ingin mengirimkan tanda kasih fisik, kami telah menyediakan alamat pengiriman dan daftar kebutuhan kami.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-10">
                    @foreach ($content['banks'] ?? [] as $index => $bank)
                        @php
                            $bNameRaw = trim($bank['name'] ?? '');
                            $bNameLower = strtolower($bNameRaw);
                            $logoPath = $masterLogos[$bNameLower] ?? null;
                        @endphp
                        <div class="group relative bg-[#181818] rounded-xl overflow-hidden border border-white/5 transition-all duration-500 hover:scale-[1.03] hover:border-red-600/50 hover:shadow-[0_20px_40px_rgba(0,0,0,0.6)]">
                            <div class="p-8 md:p-10">
                                <div class="flex justify-between items-center mb-12 h-10">
                                    @if ($logoPath)
                                        @if (str_starts_with($logoPath, 'http'))
                                            <img src="{{ $logoPath }}" class="h-6 md:h-8 object-contain brightness-0 invert opacity-60 group-hover:opacity-100 transition-all" alt="Bank Logo">
                                        @else
                                            <img src="{{ asset('storage/' . $logoPath) }}" class="h-6 md:h-8 object-contain brightness-0 invert opacity-60 group-hover:opacity-100 transition-all" alt="Bank Logo">
                                        @endif
                                    @else
                                        <i class="fa-solid fa-building-columns text-3xl text-gray-500 group-hover:text-white transition-colors"></i>
                                    @endif
                                    <i class="fa-solid fa-circle-check text-red-600 opacity-0 group-hover:opacity-100 transition-all text-xl"></i>
                                </div>

                                <div class="space-y-1 mb-10">
                                    <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Nomor Rekening</p>
                                    <h3 id="rek-{{ $index }}" class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ $bank['account_number'] }}</h3>
                                    <p class="text-sm text-gray-400 font-medium uppercase tracking-wide">a.n {{ $bank['account_name'] }}</p>
                                </div>

                                <button onclick="copyToClipboard('rek-{{ $index }}', this)" class="w-full py-4 bg-white text-black rounded font-black text-[11px] uppercase tracking-[0.2em] transition-all hover:bg-red-600 hover:text-white flex items-center justify-center gap-3">
                                    <i class="fa-regular fa-copy"></i> <span>Salin Nomor</span>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 h-1 w-0 bg-red-600 transition-all duration-500 group-hover:w-full"></div>
                        </div>
                    @endforeach
                </div>

                @if (!empty($content['alamat_kado']))
                <div class="flex flex-col items-center gap-8">
                    <div class="group relative p-10 bg-[#181818] rounded-2xl border border-white/5 max-w-2xl w-full transition-all duration-500 hover:border-red-600/30 hover:scale-[1.01] shadow-2xl">
                        <div class="w-14 h-14 bg-red-600/10 rounded-full flex items-center justify-center mx-auto mb-8 transition-transform group-hover:scale-110">
                            <i class="fa-solid fa-truck-fast text-red-600 text-xl"></i>
                        </div>
                        <p class="text-[10px] text-center uppercase tracking-[0.4em] text-red-600 mb-4 font-black">Alamat Pengiriman</p>
                        <div id="alamat-kado" class="text-xl md:text-2xl text-white font-bold text-center leading-tight mb-10 tracking-tight">
                            {!! nl2br(e($content['alamat_kado'])) !!}
                        </div>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button onclick="copyToClipboardText('alamat-kado', this)" class="px-8 py-4 bg-white text-black rounded font-black text-[11px] uppercase tracking-widest transition-all hover:bg-gray-200 active:scale-95 flex items-center justify-center gap-2">
                                <i class="fa-regular fa-copy"></i> Salin Alamat
                            </button>
                            @if (!empty($content['gifts']))
                            <button onclick="toggleGiftModal(true)" class="px-8 py-4 bg-red-600 text-white rounded font-black text-[11px] uppercase tracking-widest transition-all hover:bg-red-700 active:scale-95 shadow-lg shadow-red-600/20 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-list-ul"></i> Daftar Kebutuhan
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if (!empty($content['gifts']))
                <div id="gift-modal" class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/90 backdrop-blur-md" onclick="toggleGiftModal(false)"></div>
                    <div class="relative bg-[#181818] w-full max-w-lg rounded-xl border border-white/10 overflow-hidden shadow-2xl flex flex-col max-h-[85vh] animate-slide-up">
                        <div class="p-8 border-b border-white/5 bg-[#181818] sticky top-0 z-20">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-2xl font-black text-white uppercase tracking-tighter">Wishlist Kami</h3>
                                    <p class="text-[10px] text-red-600 uppercase tracking-widest mt-1 font-black">Wedding Registry</p>
                                </div>
                                <button onclick="toggleGiftModal(false)" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/5 text-white hover:bg-white/10 transition-all">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar space-y-4">
                            @foreach ($content['gifts'] as $index => $gift)
                            <div id="item-{{ $index }}" class="p-5 rounded-lg border border-white/5 bg-white/5 flex items-center justify-between gap-4 transition-all hover:bg-white/10 group">
                                <div>
                                    <h4 class="text-sm font-black text-white uppercase tracking-wide group-hover:text-red-600 transition-colors">{{ $gift['item_name'] }}</h4>
                                    <p class="text-[10px] text-gray-500 font-bold mt-1 uppercase tracking-widest">{{ $gift['description'] ?? '' }}</p>
                                </div>
                                <button onclick="confirmGift('item-{{ $index }}', '{{ $gift['item_name'] }}')" class="shrink-0 px-5 py-2.5 bg-red-600 text-white rounded text-[9px] font-black uppercase tracking-widest hover:bg-red-700 transition-all">
                                    Pilih
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <div class="p-6 bg-black/40 text-center border-t border-white/5">
                            <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em]">Thank You for Your Kindness</p>
                        </div>
                    </div>
                </div>
                @endif

                <div id="confirm-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-6">
                    <div class="absolute inset-0 bg-black/95 backdrop-blur-xl" onclick="closeConfirmModal()"></div>
                    <div class="relative bg-[#181818] w-full max-w-sm rounded-xl p-8 text-center shadow-2xl border border-white/10 animate-slide-up">
                        <div class="w-16 h-16 bg-red-600/20 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-heart text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-black text-white uppercase mb-2">Niat Baik Anda</h4>
                        <p id="confirm-text" class="text-xs text-gray-400 font-medium leading-relaxed mb-6"></p>

                        <div class="text-left space-y-4 mb-8">
                            <div>
                                <label class="block text-[10px] uppercase tracking-widest text-netflix-gray font-bold mb-2">Nama Anda</label>
                                <input type="text" id="input-gift-name" class="input-luxury w-full p-3 rounded text-sm placeholder-white/20" placeholder="Masukkan nama Anda">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-widest text-netflix-gray font-bold mb-2">Jumlah Hadir (Opsional)</label>
                                <input type="number" id="input-gift-pax" min="0" value="0" class="input-luxury w-full p-3 rounded text-sm placeholder-white/20 text-center" placeholder="0">
                                <p class="text-[9px] text-gray-600 mt-1 italic">*Isi 0 jika hanya mengirim kado</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button id="final-confirm-btn" class="w-full py-4 bg-red-600 text-white rounded font-black text-[11px] uppercase tracking-widest transition-all hover:bg-red-700 shadow-lg shadow-red-600/20">
                                Kirim Konfirmasi
                            </button>
                            <button onclick="closeConfirmModal()" class="w-full py-4 bg-transparent text-gray-500 font-black text-[10px] uppercase tracking-widest hover:text-white transition-all">
                                Batalkan
                            </button>
                        </div>
                    </div>
                </div>

                <div id="gift-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[700] px-10 py-4 bg-red-600 text-white rounded shadow-2xl text-[11px] font-black uppercase tracking-[0.2em] opacity-0 transition-all duration-500 pointer-events-none text-center">
                    Terima kasih!
                </div>
            </div>
        </section>
        @endif

        @if ($content['enable_qr_attendance'] ?? false)
        <section id="qr-tamu" class="py-24 px-6 md:px-16 bg-[#141414] relative overflow-hidden border-t border-white/5">
            <div class="max-w-4xl mx-auto text-center relative z-10">
                <div class="mb-16">
                    <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter mb-4">Akses Undangan</h2>
                    <p class="text-gray-400 text-sm font-medium leading-relaxed max-w-lg mx-auto">
                        Tunjukkan QR Code ini kepada petugas untuk proses verifikasi.
                    </p>
                </div>
                <div class="flex justify-center">
                    <div class="group relative p-10 bg-[#181818] rounded-xl border border-white/5 transition-all duration-700 hover:border-red-600/50 max-w-sm w-full mx-auto">
                        <div class="relative bg-white p-6 rounded-md mb-8 inline-block shadow-[0_0_30px_rgba(229,9,20,0.1)]">
                            <img id="qr-image" src="" class="w-44 h-44 object-contain" alt="QR Code Tamu">
                        </div>
                        <div class="space-y-2 mb-6 text-center">
                            <span class="text-[10px] uppercase tracking-[0.5em] text-red-600 font-black block">Guest Identity</span>
                            <h3 id="guest-name-qr" class="text-2xl font-black text-white tracking-tighter uppercase leading-none">Tamu Undangan</h3>
                        </div>
                        <div class="w-full py-3 bg-white/5 rounded border border-dashed border-white/10 text-center">
                            <p class="text-[9px] uppercase tracking-[0.3em] font-bold text-gray-500">E-Invitation Only</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <footer class="py-20 px-6 bg-[#141414] border-t border-white/10 text-center relative overflow-hidden">
            <div class="max-w-4xl mx-auto relative z-10">
                <div class="mb-12 flex flex-col items-center">
                    <h2 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter select-none">NikahNih</h2>
                </div>
                <div class="mb-16">
                    <p class="text-sm md:text-base font-medium text-netflix-gray leading-relaxed max-w-2xl mx-auto italic">
                        "Merupakan suatu kehormatan apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu."
                    </p>
                </div>
                <div class="mb-16 space-y-2">
                    <p class="text-[10px] text-netflix-gray uppercase font-bold tracking-[0.3em]">Starring</p>
                    <h3 class="text-3xl font-bebas text-white tracking-wide">{{ strtoupper($firstPerson['nickname']) }} & {{ strtoupper($secondPerson['nickname']) }}</h3>
                </div>
                <div class="flex justify-center mb-8">
                    <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank" class="text-[10px] font-bold text-netflix-gray hover:text-white uppercase tracking-widest transition-colors flex items-center gap-2">
                        <i class="fa-brands fa-instagram"></i> @ruangrestu.undangan
                    </a>
                </div>
                <p class="text-[9px] text-netflix-gray font-bold tracking-[0.3em] uppercase opacity-40">
                    &copy; 2026 {{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}. All Rights Reserved.
                </p>
            </div>
        </footer>

    </main>

    <div id="fab-container" class="fixed right-5 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000 pointer-events-none">
        <button id="btn-music" onclick="toggleMusic()" class="w-11 h-11 bg-white text-black rounded-full flex items-center justify-center shadow-xl hover:scale-110 transition-transform pointer-events-auto">
            <i class="fa-solid fa-music animate-pulse-slow" id="icon-music"></i>
        </button>
        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-11 h-11 bg-[#333] text-white rounded-full flex items-center justify-center shadow-xl hover:scale-110 transition-transform pointer-events-auto">
            <i class="fa-solid fa-angles-down" id="icon-scroll"></i>
        </button>
    </div>

    <nav id="bottom-nav" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-black/90 backdrop-blur-xl border border-white/10 rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.8)]">
            <ul class="flex justify-around items-center h-16 w-[320px] md:w-[420px] px-2">
                <li class="relative group">
                    <a href="#home" class="nav-link flex flex-col items-center justify-center w-16 h-full text-netflix-gray hover:text-white transition-all">
                        <i class="fa-solid fa-house text-lg"></i><span class="text-[8px] uppercase font-bold tracking-tighter mt-1">Home</span>
                    </a>
                </li>
                @if ($content['is_gallery_active'] ?? false)
                <li class="relative group">
                    <a href="#gallery" class="nav-link flex flex-col items-center justify-center w-16 h-full text-netflix-gray hover:text-white transition-all">
                        <i class="fa-solid fa-clapperboard text-lg"></i><span class="text-[8px] uppercase font-bold tracking-tighter mt-1">Gallery</span>
                    </a>
                </li>
                @endif
                <li class="relative group">
                    <a href="#lokasi" class="nav-link flex flex-col items-center justify-center w-16 h-full text-netflix-gray hover:text-white transition-all">
                        <i class="fa-solid fa-location-dot text-lg"></i><span class="text-[8px] uppercase font-bold tracking-tighter mt-1">Venue</span>
                    </a>
                </li>
                @if ($content['is_wishes_active'] ?? false)
                <li class="relative">
                    <a href="javascript:void(0)" onclick="openRSVP()" class="flex flex-col items-center justify-center w-20 h-14 bg-netflix-red rounded transition-all duration-300 hover:bg-red-700 active:scale-95 shadow-lg">
                        <i class="fa-solid fa-paper-plane text-lg text-white"></i>
                        <span class="text-[8px] uppercase font-black tracking-tighter mt-1 text-white">RSVP</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </nav>

    <section id="rsvp-modal" class="fixed inset-0 z-[1000] invisible transition-all duration-500 overflow-hidden flex items-end md:items-center justify-center">
        <div onclick="closeRSVP()" class="absolute inset-0 bg-black/95 backdrop-blur-sm opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>

        <div id="rsvp-content" class="relative w-full md:max-w-xl lg:max-w-2xl h-[92vh] md:h-auto max-h-[95vh] bg-[#181818] rounded-t-2xl md:rounded-2xl border-t md:border border-white/10 shadow-2xl transform translate-y-full transition-transform duration-500 ease-out flex flex-col">
            
            <div class="overflow-y-auto px-6 pb-10 pt-4 custom-scrollbar">
                <div class="w-12 h-1 bg-white/10 rounded-full mx-auto mb-8 md:hidden"></div>

                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter mb-2">RSVP & Ucapan</h2>
                    <p class="text-netflix-gray text-xs font-medium">Sampaikan kehadiran dan doa restu Anda.</p>
                </div>

                <form id="form-rsvp" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" id="input-nama-rsvp" placeholder="Masukkan nama Anda" class="input-luxury w-full p-4 rounded text-sm outline-none" required>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Konfirmasi Kehadiran</label>
                        <input type="hidden" id="input-status" value="Hadir">
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir" class="py-3.5 rounded border border-red-600 text-xs font-bold uppercase tracking-widest transition-all bg-red-600 text-white">
                                <i class="fa-solid fa-check mr-2"></i> Hadir
                            </button>
                            <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen" class="py-3.5 rounded border border-white/20 text-xs font-bold uppercase tracking-widest transition-all bg-transparent text-white hover:border-white/50">
                                <i class="fa-solid fa-xmark mr-2"></i> Absen
                            </button>
                        </div>
                    </div>

                    <input type="hidden" id="input-guest-count" value="1">
                    <div id="guest-selection" class="bg-white/5 p-5 rounded-lg border border-white/5 transition-all duration-300">
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 mb-3 block font-bold">Jumlah Tamu</label>
                        <div class="flex gap-2 mb-3">
                            <button type="button" onclick="setGuestCount(1)" class="guest-btn flex-1 py-3 rounded bg-red-600 text-sm text-white font-bold transition-all">1</button>
                            <button type="button" onclick="setGuestCount(2)" class="guest-btn flex-1 py-3 rounded bg-[#333] text-sm text-white font-bold hover:bg-[#444] transition-all">2</button>
                            <button type="button" onclick="setGuestCount(3)" class="guest-btn flex-1 py-3 rounded bg-[#333] text-sm text-white font-bold hover:bg-[#444] transition-all">3</button>
                            <button type="button" onclick="setGuestCount('custom')" class="guest-btn flex-1 py-3 rounded bg-[#333] text-sm text-white font-bold hover:bg-[#444] transition-all">3+</button>
                        </div>
                        <div id="custom-pax-container" class="hidden">
                            <input type="number" id="custom-pax-input" min="4" placeholder="Ketik jumlah spesifik..." class="input-luxury w-full p-3 rounded text-sm text-center outline-none">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Pesan</label>
                        <textarea id="input-pesan-rsvp" rows="3" placeholder="Tulis doa restu Anda..." class="input-luxury w-full p-4 rounded text-sm outline-none resize-none" required></textarea>
                    </div>

                    <div class="flex flex-col gap-3 pt-2">
                        <button type="submit" class="w-full py-4 bg-red-600 text-white rounded font-black text-xs uppercase tracking-widest shadow-lg shadow-red-600/20 active:scale-95 transition-transform">
                            Kirim Konfirmasi
                        </button>
                        <button type="button" onclick="closeRSVP()" class="w-full py-3 bg-transparent text-gray-500 rounded font-bold text-[10px] uppercase tracking-widest hover:text-white transition-colors">
                            Kembali
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <div id="lightbox" class="fixed inset-0 z-[2000] hidden flex-col items-center justify-center bg-[#141414] p-4 transition-all duration-500">
            <div class="w-full flex justify-between items-center p-6 absolute top-0 left-0 z-10">
                <span class="text-white font-bold tracking-widest text-sm"><span id="current-count">1</span> / <span id="total-count">4</span></span>
                <button onclick="closeLightbox()" class="text-white hover:text-netflix-red transition-colors text-3xl"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="relative w-full max-w-5xl flex items-center justify-center h-[80vh]">
                <img id="lightbox-img" src="" class="max-h-full max-w-full object-contain transition-opacity duration-300 shadow-2xl" alt="Zoomed">
            </div>
            <div class="absolute bottom-10 flex gap-6 z-10">
                <button onclick="prevImg()" class="w-12 h-12 rounded-full border border-white text-white hover:bg-white hover:text-black flex items-center justify-center transition-colors"><i class="fa-solid fa-chevron-left"></i></button>
                <button onclick="nextImg()" class="w-12 h-12 rounded-full border border-white text-white hover:bg-white hover:text-black flex items-center justify-center transition-colors"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        @if (session('success'))
            <div id="success-toast" class="fixed top-10 left-1/2 -translate-x-1/2 z-[3000] px-8 py-4 bg-green-600 text-white rounded font-bold shadow-2xl text-sm transition-all duration-500 flex items-center gap-3 animate-slide-up">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('success-toast');
                    if(toast) {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translate(-50%, -20px)';
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 4000);
            </script>
        @endif

    <script>
        // 1. Inisialisasi Nama Tamu
        const urlParams = new URLSearchParams(window.location.search);
        let guestName = urlParams.get('to') ? decodeURIComponent(urlParams.get('to')) : 'Tamu Undangan';
        document.querySelectorAll('#guest-name, #guest-name-qr').forEach(el => el.innerText = guestName);
        
        const qrImage = document.getElementById('qr-image');
        if (qrImage) qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=e50914&bgcolor=ffffff&data=${encodeURIComponent(guestName)}`;
        
        const inputRSVP = document.getElementById('input-nama-rsvp');
        if (inputRSVP && guestName !== 'Tamu Undangan') inputRSVP.value = guestName;

        // 2. Play Music & Buka Cover
        const audio = document.getElementById('bg-music');
        let isMusicPlaying = false;
        let isAutoScrolling = false;
        let scrollInterval;
        let hasShownRSVPAtEnd = false;

        function openInvitation() {
            document.getElementById('cover-page').classList.add('-translate-y-full');
            document.body.classList.remove('cover-locked'); // LEPASKAN KUNCI SCROLL COVER
            document.body.style.overflowY = 'auto';
            document.getElementById('main-content').classList.remove('opacity-0');
            document.getElementById('fab-container').classList.remove('opacity-0', 'pointer-events-none');
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
                icon.classList.remove('animate-pulse-slow');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.replace('fa-volume-xmark', 'fa-music');
                    icon.classList.add('animate-pulse-slow');
                }).catch(() => console.log("Autoplay dicegah browser."));
            }
        }

        function toggleAutoScroll(forceStart = false) {
            const btn = document.getElementById('btn-scroll');
            const icon = document.getElementById('icon-scroll');
            if (isAutoScrolling && !forceStart) {
                clearInterval(scrollInterval);
                isAutoScrolling = false;
                btn.classList.replace('bg-white', 'bg-[#333]');
                btn.classList.replace('text-black', 'text-white');
                icon.classList.replace('fa-pause', 'fa-angles-down');
            } else {
                isAutoScrolling = true;
                btn.classList.replace('bg-[#333]', 'bg-white');
                btn.classList.replace('text-white', 'text-black');
                icon.classList.replace('fa-angles-down', 'fa-pause');
                scrollInterval = setInterval(() => {
                    window.scrollBy({ top: 1, behavior: 'auto' });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) toggleAutoScroll();
                }, 35);
            }
        }

        window.addEventListener('wheel', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });
        window.addEventListener('touchmove', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });

        // 3. Countdown
        const weddingDate = {{ $weddingTimestamp }};
        if (weddingDate > 0) {
            const countdownFunction = setInterval(function () {
                const distance = weddingDate - new Date().getTime();
                if (distance <= 0) { clearInterval(countdownFunction); return; }
                document.getElementById("days").innerText = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                document.getElementById("hours").innerText = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                document.getElementById("minutes").innerText = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            }, 1000);
        }

        // 4. Data Wishes API (Wishes Wall & Stats)
        let allWishes = [
            @foreach ($dbWishes as $wish)
                { nama: "{{ addslashes($wish->guest_name) }}", pesan: "{{ preg_replace("/\r|\n/", ' ', addslashes($wish->message)) }}", waktu: "{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}" },
            @endforeach
        ];
        let countAttendance = {{ $totalAttendance }};
        let countWishes = {{ $totalWishes }};

        function renderWishes() {
            const container = document.getElementById('wishes-container');
            if (!container) return;
            container.innerHTML = ''; 

            if (allWishes.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-10 opacity-50 flex flex-col items-center">
                        <i class="fa-solid fa-comment-slash text-4xl text-netflix-gray mb-4"></i>
                        <p class="text-sm font-bold text-white uppercase tracking-widest">Belum Ada Ucapan</p>
                        <p class="text-[10px] text-netflix-gray mt-2">Jadilah yang pertama memberikan doa restu.</p>
                    </div>`;
                return;
            }

            allWishes.forEach(wish => {
                const card = document.createElement('div');
                card.className = 'flex flex-col md:flex-row gap-4 p-4 rounded-lg bg-netflix-gray/5 border border-transparent hover:bg-netflix-gray/10 hover:border-white/10 transition-all';
                card.innerHTML = `
                    <div class="w-12 h-12 bg-red-600/20 rounded shrink-0 flex items-center justify-center border border-red-600/30">
                        <i class="fa-solid fa-user text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h5 class="font-bold text-white text-sm">${wish.nama}</h5>
                            <span class="text-[9px] text-netflix-gray font-bold uppercase tracking-widest">${wish.waktu}</span>
                        </div>
                        <p class="text-xs text-netflix-gray font-light leading-relaxed">"${wish.pesan}"</p>
                    </div>
                `;
                container.appendChild(card);
            });
        }
        document.addEventListener('DOMContentLoaded', renderWishes);

        async function sendRsvpData(data) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const response = await fetch("{{ route('rsvp.store', $invitation->slug) }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    allWishes.unshift({ nama: data.guest_name, pesan: data.message, waktu: "Baru saja" });
                    countWishes++;
                    if(data.status_rsvp === 'hadir') countAttendance += parseInt(data.pax);
                    
                    const elAtt = document.getElementById('total-attendance');
                    const elWish = document.getElementById('total-wishes');
                    if(elAtt) { elAtt.innerText = countAttendance; elAtt.classList.add('text-red-600'); setTimeout(()=>elAtt.classList.remove('text-red-600'), 500); }
                    if(elWish) { elWish.innerText = countWishes; elWish.classList.add('text-red-600'); setTimeout(()=>elWish.classList.remove('text-red-600'), 500); }
                    
                    renderWishes();
                    showCopyToast("RSVP Berhasil Dikirim!");
                }
            } catch (error) { console.error(error); }
        }

        // 5. Logika RSVP Form
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

            [btnHadir, btnAbsen].forEach(b => b.className = 'py-3.5 rounded border border-white/20 text-xs font-bold uppercase tracking-widest transition-all bg-transparent text-white hover:border-white/50 active:scale-95');

            if (status === 'Hadir') {
                btnHadir.className = 'py-3.5 rounded border border-red-600 text-xs font-bold uppercase tracking-widest transition-all bg-red-600 text-white shadow-lg shadow-red-600/20 active:scale-95';
                guestDiv.classList.remove('hidden');
                document.getElementById('input-guest-count').value = 1;
                setGuestCount(1);
            } else {
                btnAbsen.className = 'py-3.5 rounded border border-red-600 text-xs font-bold uppercase tracking-widest transition-all bg-red-600 text-white shadow-lg shadow-red-600/20 active:scale-95';
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
                hiddenInputCount.value = customInput.value || 4; 
                customInput.focus();
            } else {
                customContainer.classList.add('hidden');
                hiddenInputCount.value = count;
            }

            document.querySelectorAll('.guest-btn').forEach(btn => {
                if (btn.innerText == count || (count === 'custom' && btn.innerText === '3+')) {
                    btn.classList.replace('bg-[#333]', 'bg-red-600');
                } else {
                    btn.classList.replace('bg-red-600', 'bg-[#333]');
                }
            });
        }

        document.getElementById('form-rsvp').onsubmit = function(e) {
            e.preventDefault();
            const statusHadir = document.getElementById('input-status').value === 'Hadir' ? 'hadir' : 'tidak_hadir';
            
            let paxVal = 0;
            if (statusHadir === 'hadir') {
                const customContainer = document.getElementById('custom-pax-container');
                if (!customContainer.classList.contains('hidden')) {
                    const customInputVal = document.getElementById('custom-pax-input').value;
                    paxVal = parseInt(customInputVal) > 0 ? parseInt(customInputVal) : 4;
                } else {
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
            closeRSVP();
        };

        // Scroll to RSVP Auto Popup
        window.addEventListener('scroll', () => {
            // 🔥 PROTEKSI: Jangan jalan jika halaman cover masih ada (dilock)
            if(document.body.classList.contains('cover-locked')) return;

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50) {
                if (!hasShownRSVPAtEnd) {
                    openRSVP();
                    hasShownRSVPAtEnd = true;
                    if (isAutoScrolling) toggleAutoScroll();
                }
            }
        }, { passive: true });

        // 6. Logic Kirim Kado
        let currentGiftId = null;
        let currentGiftName = '';

        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if (show) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; } 
            else { modal.classList.add('hidden'); document.body.style.overflow = 'auto'; }
        }

        function confirmGift(id, name) {
            currentGiftId = id;
            currentGiftName = name;
            document.getElementById('confirm-text').innerHTML = `Silakan isi data diri untuk konfirmasi kado <b>${name}</b>`;
            
            const inputNameEl = document.getElementById('input-gift-name');
            if (inputNameEl && guestName !== 'Tamu Undangan') inputNameEl.value = guestName;
            const inputPaxEl = document.getElementById('input-gift-pax');
            if (inputPaxEl) inputPaxEl.value = 0;

            document.getElementById('confirm-modal').classList.remove('hidden');

            document.getElementById('final-confirm-btn').onclick = function() {
                let finalName = inputNameEl ? inputNameEl.value.trim() : '';
                if (!finalName) finalName = guestName !== 'Tamu Undangan' ? guestName : 'Hamba Allah';
                let finalPax = inputPaxEl ? parseInt(inputPaxEl.value) : 0;
                if (isNaN(finalPax) || finalPax < 0) finalPax = 0;

                processClaimGift(finalName, name, finalPax);
            };
        }

        function closeConfirmModal() { document.getElementById('confirm-modal').classList.add('hidden'); }

        function processClaimGift(senderName, giftName, giftPax) {
            closeConfirmModal();
            const btn = document.getElementById(currentGiftId).querySelector('button');
            if(btn) { btn.outerHTML = `<div class="text-[9px] text-green-500 uppercase tracking-widest font-bold flex items-center gap-2"><i class="fa-solid fa-check-circle"></i> Terpilih</div>`; }

            sendRsvpData({
                guest_name: senderName,
                status_rsvp: giftPax > 0 ? 'hadir' : 'tidak_hadir',
                pax: giftPax,
                message: `Telah memberikan tanda kasih berupa: ${giftName} 🎁`
            });

            const toast = document.getElementById('gift-toast');
            if(toast) {
                toast.innerHTML = `Terima kasih ${senderName}! Kado tercatat.`;
                toast.classList.replace('opacity-0', 'opacity-100');
                setTimeout(() => { 
                    toast.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(()=> toggleGiftModal(false), 500); 
                }, 3000);
            }
        }

        // 7. Utilitas Copy Text
        function showCopyToast(msg = "Tersalin ke Clipboard") {
            const toast = document.getElementById('copy-toast');
            if(toast) {
                toast.innerHTML = `<i class="fa-solid fa-check-circle"></i> ${msg}`;
                toast.classList.remove('opacity-0', 'translate-y-10');
                toast.classList.add('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-10');
                    toast.classList.remove('opacity-100', 'translate-y-0');
                }, 2500);
            }
        }

        function copyToClipboardText(elementOrText, btn) {
            let textToCopy = elementOrText;
            const el = document.getElementById(elementOrText);
            if (el) textToCopy = el.innerText.trim();

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Tersalin';
                btn.classList.add('bg-white', 'text-black');
                showCopyToast();
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-white', 'text-black');
                }, 2000);
            });
        }
        function copyToClipboard(id, btn) { copyToClipboardText(id, btn); }

        // 8. Lightbox Gallery
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
            if(document.getElementById('current-count')) document.getElementById('current-count').innerText = currentIndex + 1;
            if(document.getElementById('total-count')) document.getElementById('total-count').innerText = images.length;
            imgElement.style.opacity = '0';
            setTimeout(() => {
                imgElement.src = images[currentIndex];
                imgElement.style.opacity = '1';
            }, 200);
        }
        function nextImg() { if(images.length > 0) { currentIndex = (currentIndex + 1) % images.length; updateLightbox(); } }
        function prevImg() { if(images.length > 0) { currentIndex = (currentIndex - 1 + images.length) % images.length; updateLightbox(); } }
        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').classList.contains('hidden')) return;
            if (e.key === "ArrowRight") nextImg();
            if (e.key === "ArrowLeft") prevImg();
            if (e.key === "Escape") closeLightbox();
        });

        // 9. Live Streaming Platform Switch
        function switchPlatform(title, desc, iconClass, link) {
            const display = document.getElementById('streaming-display');
            display.style.opacity = '0';
            display.style.transform = 'scale(0.98) translateY(10px)';
            setTimeout(() => {
                document.getElementById('platform-title').innerText = title;
                document.getElementById('platform-icon').className = iconClass + ' text-6xl md:text-8xl text-white drop-shadow-[0_0_20px_rgba(255,255,255,0.3)]';
                document.getElementById('platform-link').href = link;
                display.style.opacity = '1';
                display.style.transform = 'scale(1) translateY(0)';
            }, 400);
        }
    </script>
</body>
</html>