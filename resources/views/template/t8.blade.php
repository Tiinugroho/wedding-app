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

    if (!empty($content['events']) && is_array($content['events']) && count($content['events']) > 0) {
        $firstEvent = collect($content['events'])->first();
        if (!empty($firstEvent['date'])) {
            $hasResepsi = true;
            $coverDateDisplay = \Carbon\Carbon::parse($firstEvent['date'])->format('d . m . Y');
            $eventTime = !empty($firstEvent['time']) ? $firstEvent['time'] : '00:00:00';
            $weddingTimestamp = \Carbon\Carbon::parse($firstEvent['date'] . ' ' . $eventTime)->timestamp * 1000;
        }
    }

    $coverImage = !empty($content['cover_image'])
        ? asset('storage/' . $content['cover_image'])
        : 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1000&auto=format&fit=crop';

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
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData);

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
    <title>{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }} - Wedding Invitation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#FCFBF4', // Off-white warm/kertas
                        primary: '#FF8A5B', // Vibrant Peach
                        secondary: '#4ECDC4', // Teal/Aqua
                        accent: '#FFE66D', // Sunny Yellow
                        funky: '#C8A2C8', // Lavender
                        dark: '#292F36', // Charcoal
                        paper: '#FFFFFF', // Kertas putih
                    },
                    fontFamily: {
                        sans: ['"Poppins"', 'sans-serif'],
                        serif: ['"Pacifico"', 'cursive'], // Font judul utama
                    },
                    animation: {
                        'wobble-slow': 'wobble 4s ease-in-out infinite',
                        'float': 'float 5s ease-in-out infinite',
                        'float-delayed': 'float 5s ease-in-out 2.5s infinite',
                        'pulse-soft': 'pulseSoft 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        wobble: {
                            '0%, 100%': { transform: 'rotate(-2deg)' },
                            '50%': { transform: 'rotate(2deg)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                            '50%': { transform: 'translateY(-15px) rotate(1deg)' },
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: 1, transform: 'scale(1)' },
                            '50%': { opacity: 0.95, transform: 'scale(1.02)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Global Reset untuk mencegah horizontal scroll */
        html, body { 
            max-width: 100vw;
            overflow-x: hidden; 
            background-color: #FCFBF4; 
            color: #292F36; 
            font-family: 'Poppins', sans-serif;
        }

        ::-webkit-scrollbar { width: 8px; background: #FCFBF4; }
        ::-webkit-scrollbar-thumb { background: #FF8A5B; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #4ECDC4; }
        
        /* Gaya Kertas Tempel */
        .paper-card {
            background-color: white;
            border: 2px solid #292F36;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 6px 6px 0px rgba(41, 47, 54, 1);
            position: relative;
        }

        /* Gaya Polaroid */
        .polaroid-frame {
            background-color: white;
            padding: 10px 10px 30px 10px;
            border: 2px solid #292F36;
            border-radius: 4px;
            box-shadow: 4px 4px 0px rgba(41, 47, 54, 0.8);
            transform: rotate(-3deg);
        }

        /* Ornamen Washi Tape */
        .washi-tape {
            position: absolute;
            background-color: #4ECDC4; /* Teal */
            height: 20px;
            width: 80px;
            top: -10px;
            left: 50%;
            transform: translateX(-50%) rotate(-5deg);
            border: 1px solid #292F36;
            opacity: 0.8;
            border-radius: 4px;
            z-index: 10;
        }

        /* Pola background ceria */
        .collage-bg {
            background-image: radial-gradient(#FFE66D 2px, transparent 2px);
            background-size: 30px 30px;
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #292F36; border-radius: 10px; }
        .scroll-custom::-webkit-scrollbar { width: 4px; }
        .scroll-custom::-webkit-scrollbar-track { background: transparent; }
        .scroll-custom::-webkit-scrollbar-thumb { background: #FF8A5B; border-radius: 10px; }
    </style>
</head>

<body class="selection:bg-secondary selection:text-dark">

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music_id) && $invitation->music ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2021/07/18/audio_c993f91966.mp3' }}" type="audio/mpeg">
    </audio>

    <div id="cover-page" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-funky transition-transform duration-700 ease-[cubic-bezier(0.87,0,0.13,1)] overflow-hidden polka-bg">
        
        <div class="absolute inset-0 z-0 opacity-20 transform scale-110">
            <img src="{{ $coverImage }}" class="w-full h-full object-cover">
        </div>

        <div class="relative z-10 flex flex-col items-center text-center px-6 w-full max-w-lg">
            
            <div class="flex items-center gap-4 mb-8">
                <img src="{{ $firstPerson['photo'] }}" class="w-24 h-24 object-cover polaroid-frame transform rotate-12 border-2 border-dark" alt="Couple 1">
                <img src="{{ $secondPerson['photo'] }}" class="w-24 h-24 object-cover polaroid-frame transform -rotate-12 border-2 border-dark" alt="Couple 2">
            </div>

            <p class="text-white font-bold text-xs uppercase tracking-[0.4em] mb-3">Save The Date For</p>
            <h1 class="font-serif text-6xl md:text-7xl text-accent mb-1 leading-none drop-shadow-md">{{ $firstPerson['nickname'] }} <span class="font-sans text-white text-5xl">&</span> {{ $secondPerson['nickname'] }}</h1>
            <p class="text-white/90 font-semibold uppercase text-xs tracking-widest mb-12">The Wedding Celebration</p>
            
            <div class="paper-card w-full mb-10 transform rotate-2">
                <div class="washi-tape"></div>
                <p class="text-xs text-dark uppercase tracking-widest mb-2 font-bold flex items-center justify-center gap-2">
                    <i class="fa-solid fa-star text-accent"></i> {{ $content['cover_greeting'] ?? 'Kepada Yth.' }} <i class="fa-solid fa-star text-accent"></i>
                </p>
                <p id="guest-name" class="text-2xl font-semibold text-primary">{{ $guestNameDisplay }}</p>
            </div>

            <button onclick="openInvitation()" class="bg-primary text-white px-10 py-4 rounded-full font-bold uppercase tracking-widest text-sm shadow-[4px_4px_0px_rgba(41,47,54,1)] hover:scale-105 hover:bg-secondary transition-all">
                Buka Undangan
            </button>
        </div>
    </div>

    <main id="main-content" class="min-h-screen opacity-0 transition-opacity duration-1000 pb-24 w-full overflow-hidden">

        <nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[90%] max-w-md z-50 bg-white/90 backdrop-blur-md border border-dark p-3 flex justify-between items-center rounded-full shadow-[4px_4px_0px_rgba(41,47,54,1)]" id="main-nav">
            <h1 class="font-serif text-primary text-xl ml-4">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h1>
            <div class="bg-secondary text-white w-10 h-10 rounded-full flex items-center justify-center border-2 border-dark">
                <i class="fa-solid fa-heart animate-pulse"></i>
            </div>
        </nav>

        <section id="home" class="min-h-screen pt-32 px-6 flex flex-col items-center justify-center relative overflow-hidden w-full">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="{{ $coverImage }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-dark/30 mix-blend-multiply"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto w-full text-center flex flex-col items-center">
                <div class="inline-flex items-center gap-2 bg-accent border-2 border-dark px-4 py-2 rounded-full font-bold text-xs uppercase tracking-widest mb-6 transform -rotate-3 shadow-lg">
                    A Joyful Event
                </div>
                
                <h2 class="text-7xl md:text-9xl font-serif text-white mb-6 leading-tight drop-shadow-[0_4px_8px_rgba(0,0,0,0.5)] flex flex-col items-center animate-pulse-soft">
                    {{ $firstPerson['nickname'] }} <span class="text-accent text-5xl font-sans font-bold -mt-2 -mb-2 transform -rotate-12">&</span> {{ $secondPerson['nickname'] }}
                </h2>
                
                <div class="paper-card transform rotate-2 mb-10 max-w-sm w-full p-6 text-center">
                    <div class="washi-tape" style="background-color: #FF8A5B;"></div>
                    <p class="text-xs uppercase font-bold text-dark tracking-widest mb-1.5">You're Invited!</p>
                    <p class="text-sm font-semibold text-secondary">
                        {!! nl2br(e($content['quotes'] ?? 'Bergabunglah merayakan awal dari petualangan terbesar kami bersama.')) !!}
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-4 justify-center">
                    <button onclick="openRSVP()" class="bg-dark text-white px-8 py-4 rounded-full font-bold uppercase tracking-widest text-xs hover:bg-primary transition-colors border-2 border-transparent">
                        RSVP Sekarang
                    </button>
                    <div class="flex items-center gap-2 bg-white border-2 border-dark px-6 py-3 rounded-full font-bold shadow-md">
                        <i class="fa-regular fa-calendar-check text-secondary text-xl"></i> {{ $coverDateDisplay }}
                    </div>
                </div>

                <div class="mt-12 flex flex-wrap justify-center items-center gap-4 text-white">
                    <div class="flex flex-col items-center bg-dark/50 px-4 py-2 rounded-xl">
                        <span id="days" class="text-2xl font-bold">00</span>
                        <span class="text-[9px] uppercase tracking-wider">Days</span>
                    </div>
                    <div class="flex flex-col items-center bg-dark/50 px-4 py-2 rounded-xl">
                        <span id="hours" class="text-2xl font-bold">00</span>
                        <span class="text-[9px] uppercase tracking-wider">Hours</span>
                    </div>
                    <div class="flex flex-col items-center bg-dark/50 px-4 py-2 rounded-xl">
                        <span id="minutes" class="text-2xl font-bold">00</span>
                        <span class="text-[9px] uppercase tracking-wider">Mins</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="cast" class="py-24 px-6 bg-paper relative overflow-hidden collage-bg w-full">
            <div class="absolute inset-0 z-0 opacity-10 scale-110">
                <img src="https://images.unsplash.com/photo-1510154221590-ff63e90a136f?q=80&w=2000" class="w-full h-full object-cover">
            </div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <h3 class="text-5xl md:text-7xl font-serif text-dark drop-shadow-md">The Happy Couple</h3>
                    <div class="h-1.5 w-16 bg-primary mx-auto rounded-full mt-4 transform rotate-2"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-16 md:gap-20 max-w-5xl mx-auto items-center">
                    <div class="flex flex-col items-center md:items-start group">
                        <div class="polaroid-frame group-hover:rotate-0 transition-transform duration-500 mb-8 border-4 border-dark shadow-2xl">
                            <img src="{{ $firstPerson['photo'] }}" class="w-64 h-64 md:w-80 md:h-80 object-cover rounded-xl" alt="{{ $firstPerson['name'] }}">
                            <p class="font-serif text-2xl text-dark text-center pt-5">{{ $firstPerson['name'] }}</p>
                        </div>
                        <div class="paper-card w-max max-w-xs transform rotate-2 border-2 border-dark p-6">
                            <div class="washi-tape" style="background-color: #FF8A5B;"></div>
                            <span class="bg-dark text-accent px-3 py-1 rounded-full text-xs font-bold uppercase w-max mb-3 inline-block">{{ $firstPerson['label'] }}</span>
                            <p class="font-medium text-sm leading-relaxed text-dark">{{ $firstPerson['gender_text'] }} tercinta dari Bapak {{ $firstPerson['father'] }} & Ibu {{ $firstPerson['mother'] }}</p>
                            @if(!empty($firstPerson['ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $firstPerson['ig']) }}" target="_blank" class="block mt-3 text-xs text-primary font-bold"><i class="fa-brands fa-instagram mr-1"></i> {{ $firstPerson['ig'] }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-center md:items-end group">
                        <div class="polaroid-frame transform rotate-[3deg] group-hover:rotate-0 transition-transform duration-500 mb-8 border-4 border-dark shadow-2xl">
                            <img src="{{ $secondPerson['photo'] }}" class="w-64 h-64 md:w-80 md:h-80 object-cover rounded-xl" alt="{{ $secondPerson['name'] }}">
                            <p class="font-serif text-2xl text-dark text-center pt-5">{{ $secondPerson['name'] }}</p>
                        </div>
                        <div class="paper-card w-max max-w-xs transform -rotate-2 border-2 border-dark p-6">
                            <div class="washi-tape" style="background-color: #4ECDC4;"></div>
                            <span class="bg-dark text-secondary px-3 py-1 rounded-full text-xs font-bold uppercase w-max mb-3 inline-block">{{ $secondPerson['label'] }}</span>
                            <p class="font-medium text-sm leading-relaxed text-dark">{{ $secondPerson['gender_text'] }} tercinta dari Bapak {{ $secondPerson['father'] }} & Ibu {{ $secondPerson['mother'] }}</p>
                            @if(!empty($secondPerson['ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $secondPerson['ig']) }}" target="_blank" class="block mt-3 text-xs text-secondary font-bold"><i class="fa-brands fa-instagram mr-1"></i> {{ $secondPerson['ig'] }}</a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (($content['is_turut_mengundang_active'] ?? false) && !empty($content['turut_mengundang']))
                    <div class="mt-20 paper-card max-w-4xl mx-auto border-2 border-dark">
                        <div class="washi-tape" style="background-color: #C8A2C8;"></div>
                        <p class="text-xs text-center uppercase tracking-widest font-bold mb-6 text-gray-500">Turut Mengundang</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center text-xs text-dark font-medium leading-relaxed">
                            @foreach ($content['turut_mengundang'] as $tamu)
                                <p>{{ trim($tamu) }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        @if (($content['is_story_active'] ?? false) && !empty($content['love_stories']))
        <section id="cerita" class="py-24 px-6 bg-light relative overflow-hidden w-full">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?q=80&w=2000" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-dark/60"></div>
            </div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <span class="bg-primary text-white border-2 border-dark px-4 py-2 rounded-full font-bold text-[10px] uppercase tracking-widest inline-block mb-4 transform -rotate-2">Our Story</span>
                    <h3 class="text-5xl md:text-6xl font-serif text-accent drop-shadow-lg">Love Story Gallery</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach ($content['love_stories'] as $index => $story)
                    @php
                        $rotations = ['-rotate-2', 'rotate-2', '-rotate-3'];
                        $rot = $rotations[$index % 3];
                    @endphp
                    <div class="polaroid-frame transform {{ $rot }} border-4 border-dark group hover:-translate-y-2 transition-transform shadow-2xl">
                        @if(!empty($story['image']))
                        <img src="{{ asset('storage/' . $story['image']) }}" class="w-full h-48 object-cover rounded-xl border-2 border-dark mb-4">
                        @endif
                        <div class="text-left px-2">
                            <span class="font-bold text-xs bg-accent px-3 py-1 rounded-full border border-dark inline-block mb-2">{{ $story['year'] ?? '' }}</span>
                            <h5 class="font-serif text-2xl text-primary mb-3">{{ $story['title'] ?? '' }}</h5>
                            <p class="text-xs font-medium leading-relaxed text-dark">{!! nl2br(e($story['description'] ?? '')) !!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        @if ($content['is_gallery_active'] ?? false)
        <section id="gallery" class="py-24 px-6 bg-accent/10 relative overflow-hidden w-full">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?q=80&w=2000" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-funky/40 mix-blend-multiply"></div>
            </div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-center mb-16 gap-6">
                    <h3 class="text-5xl md:text-6xl font-serif text-white drop-shadow-md">Gallery Bahagia</h3>
                    <div class="bg-white px-6 py-3 rounded-full border-2 border-dark font-bold text-sm shadow-[4px_4px_0px_rgba(41,47,54,1)]">
                        Capture Every Moment <i class="fa-solid fa-camera-retro text-primary ml-2"></i>
                    </div>
                </div>

                @php $youtubeLink = $content['youtube_links'][0] ?? null; @endphp
                @if ($youtubeLink)
                <div class="w-full aspect-video rounded-[2rem] border-8 border-white overflow-hidden bg-white mb-10 shadow-2xl">
                    <iframe class="w-full h-full" src="{{ str_replace('watch?v=', 'embed/', $youtubeLink) }}" frameborder="0" allowfullscreen></iframe>
                </div>
                @endif

                <div class="masonry max-w-6xl mx-auto">
                    @foreach ($invitation->galleries ?? [] as $index => $gallery)
                    @php
                        $rotations = ['rotate-0', 'rotate-2', '-rotate-3', 'rotate-0', 'rotate-2', '-rotate-2'];
                        $rot = $rotations[$index % 6];
                    @endphp
                    <div class="masonry-item polaroid-frame transform {{ $rot }} group cursor-pointer border-4 border-dark hover:-rotate-0 hover:scale-105 transition-all" onclick="openLightbox({{ $index }})">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" class="gallery-img w-full h-auto object-cover rounded-lg group-hover:scale-110 transition-transform">
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <section id="lokasi" class="py-24 px-6 bg-funky relative overflow-hidden polka-bg border-t-4 border-dark shadow-inner w-full">
            <div class="absolute inset-0 z-0 opacity-15 scale-110">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2000" class="w-full h-full object-cover">
            </div>

            <div class="max-w-6xl mx-auto relative z-10 flex flex-col items-center">
                <div class="paper-card transform rotate-2 max-w-sm w-full p-8 text-center mb-16 border-2 border-dark shadow-2xl">
                    <div class="washi-tape" style="background-color: #FFE66D;"></div>
                    <h3 class="text-4xl font-serif text-dark mb-4">Venue & Time</h3>
                    <div class="w-12 h-1 bg-accent mx-auto rounded-full mt-3"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto items-start w-full">
                    <div class="paper-card rounded-[2.5rem] border-4 border-dark flex flex-col md:flex-row overflow-hidden shadow-2xl group transition-all hover:border-secondary hover:shadow-[12px_12px_0px_rgba(41,47,54,1)] p-0 w-full">
                        <div class="w-full h-48 md:h-auto md:w-2/5 md:border-r-4 border-b-4 md:border-b-0 border-dark overflow-hidden bg-bg">
                            <img src="https://images.unsplash.com/photo-1542042161784-26ab9e041e89?w=500" class="w-full h-full object-cover group-hover:scale-110 transition-transform animate-pulse-soft">
                        </div>
                        <div class="p-8 md:p-10 md:w-3/5 flex flex-col justify-between">
                            <div class="text-left">
                                <span class="bg-secondary text-white px-3 py-1 rounded-full text-xs font-bold uppercase mb-4 inline-block">The Knot Tying</span>
                                <h4 class="text-3xl font-serif mb-6 text-dark">Akad Nikah</h4>
                                <ul class="space-y-4 font-medium text-sm text-dark mb-8">
                                    <li><i class="fa-regular fa-calendar text-primary w-6"></i> {{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</li>
                                    <li><i class="fa-regular fa-clock text-primary w-6"></i> {{ $content['akad_time'] ?? '08:00' }} - {{ $content['akad_time_end'] ?? 'Selesai' }}</li>
                                    <li><i class="fa-solid fa-location-dot text-primary w-6"></i> {{ $content['akad_location'] ?? 'Kediaman Mempelai' }}<br><span class="text-xs text-gray-500">{{ $content['akad_address'] ?? '' }}</span></li>
                                </ul>
                            </div>
                            <a href="{{ $content['akad_map'] ?? '#' }}" target="_blank" class="block w-full text-center bg-dark text-white py-3.5 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-secondary Transition-all shadow-md">
                                <i class="fa-solid fa-map-location-dot mr-2"></i> Lihat Peta
                            </a>
                        </div>
                    </div>

                    @if (!empty($content['events']) && is_array($content['events']))
                        @foreach ($content['events'] as $index => $evt)
                        <div class="paper-card rounded-[2.5rem] border-4 border-dark flex flex-col md:flex-row overflow-hidden shadow-2xl group transition-all md:translate-y-12 hover:border-primary hover:shadow-[12px_12px_0px_rgba(41,47,54,1)] p-0 w-full mt-4 md:mt-0">
                            <div class="w-full h-48 md:h-auto md:w-2/5 md:border-r-4 border-b-4 md:border-b-0 border-dark overflow-hidden bg-bg">
                                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=500" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                            </div>
                            <div class="p-8 md:p-10 md:w-3/5 flex flex-col justify-between">
                                <div class="text-left">
                                    <span class="bg-primary text-white px-3 py-1 rounded-full text-xs font-bold uppercase mb-4 inline-block">{{ $evt['title'] ?? 'Resepsi' }}</span>
                                    <h4 class="text-3xl font-serif mb-6 text-dark">{{ $evt['title'] ?? 'Resepsi' }}</h4>
                                    <ul class="space-y-4 font-medium text-sm text-dark mb-8">
                                        <li><i class="fa-regular fa-calendar text-secondary w-6"></i> {{ !empty($evt['date']) ? \Carbon\Carbon::parse($evt['date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</li>
                                        <li><i class="fa-regular fa-clock text-secondary w-6"></i> {{ $evt['time'] ?? '11:00' }} - {{ $evt['time_end'] ?? 'Selesai' }}</li>
                                        <li><i class="fa-solid fa-location-dot text-secondary w-6"></i> {{ $evt['location'] ?? 'Grand Ballroom Hotel' }}<br><span class="text-xs text-gray-500">{{ $evt['address'] ?? '' }}</span></li>
                                    </ul>
                                </div>
                                <a href="{{ $evt['map_link'] ?? '#' }}" target="_blank" class="block w-full text-center bg-dark text-white py-3.5 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-primary Transition-all shadow-md">
                                    <i class="fa-solid fa-map-location-dot mr-2"></i> Lihat Peta
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        @if ($content['is_livestream_active'] ?? false)
        <section id="live-streaming" class="py-24 px-6 bg-light relative border-y-4 border-dark overflow-hidden w-full">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?q=80&w=2000" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-dark/60"></div>
            </div>

            <div class="max-w-5xl mx-auto text-center relative z-10 flex flex-col items-center">
                <div class="inline-flex items-center gap-2 bg-dark text-white px-5 py-2 rounded-full font-bold text-xs uppercase tracking-widest mb-6">
                    <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span> Live Virtual
                </div>
                <h2 class="text-5xl md:text-6xl font-serif text-accent mb-10 drop-shadow-lg">Join Us Online</h2>

                <div id="streaming-display" class="bg-white border-4 border-dark rounded-[2.5rem] p-4 shadow-[12px_12px_0px_rgba(41,47,54,1)] max-w-3xl mx-auto transition-transform duration-500 hover:scale-105 w-full">
                    <div class="bg-dark rounded-[2rem] p-8 md:p-10 flex flex-col items-center justify-center min-h-[300px] text-white w-full">
                        <i id="platform-icon" class="fa-brands fa-youtube text-6xl text-primary mb-6 animate-wobble-slow"></i>
                        <h3 id="platform-title" class="text-3xl md:text-4xl font-serif mb-2 text-accent">YouTube Live</h3>
                        <p id="platform-desc" class="font-medium text-sm text-gray-300 mb-8 uppercase tracking-widest">Mulai Pukul 09.00 WIB</p>
                        <a id="platform-link" href="#" class="bg-primary text-white border-2 border-primary hover:bg-transparent px-10 py-3.5 rounded-full font-bold uppercase tracking-widest text-xs transition-colors shadow-lg">Putar Sekarang</a>
                    </div>
                </div>

                @if(!empty($content['live_streams']) && is_array($content['live_streams']))
                <div class="flex flex-wrap justify-center gap-4 mt-12 bg-white/10 backdrop-blur-sm p-4 md:p-5 rounded-full border-2 border-dark">
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
                    <button onclick="switchPlatform('{{ $platform }}', '{{ $title }}', 'Live Wedding', '{{ $iconClass }}', '{{ $stream['link'] ?? '#' }}')" class="w-12 h-12 md:w-14 md:h-14 bg-white border-2 border-dark rounded-full text-dark hover:bg-primary hover:text-white flex items-center justify-center text-xl md:text-2xl transition-all shadow-md transform hover:-translate-y-1">
                        <i class="{{ $iconClass }}"></i>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </section>
        @endif

        <section id="guest-stats" class="py-24 px-6 bg-paper relative overflow-hidden collage-bg w-full">
            <div class="absolute inset-0 z-0 opacity-15 scale-110">
                <img src="https://images.unsplash.com/photo-1591604466107-ec97de577aff?q=80&w=2000" class="w-full h-full object-cover">
            </div>

            <div class="max-w-6xl mx-auto relative z-10 flex flex-col items-center">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16 w-full max-w-2xl mx-auto">
                    <div class="paper-card border-4 border-dark p-6 rounded-3xl text-center shadow-[8px_8px_0px_rgba(41,47,54,1)] hover:-translate-y-1 transition-transform rotate-2 w-full">
                        <div class="washi-tape" style="background-color: #FF8A5B;"></div>
                        <h4 id="total-attendance" class="text-5xl font-semibold text-primary mb-2">{{ $totalAttendance }}</h4>
                        <p class="font-bold text-[10px] uppercase tracking-widest text-dark border-t-2 border-dark pt-2">Tamu Akan Hadir</p>
                    </div>
                    <div class="paper-card border-4 border-dark p-6 rounded-3xl text-center shadow-[8px_8px_0px_rgba(41,47,54,1)] hover:-translate-y-1 transition-transform -rotate-2 w-full mt-4 md:mt-0">
                         <div class="washi-tape" style="background-color: #4ECDC4;"></div>
                        <h4 id="total-wishes" class="text-5xl font-semibold text-secondary mb-2">{{ $totalWishes }}</h4>
                        <p class="font-bold text-[10px] uppercase tracking-widest text-dark border-t-2 border-dark pt-2">Ucapan Hangat</p>
                    </div>
                </div>

                <div class="bg-white/70 backdrop-blur-sm border-4 border-dark rounded-[2.5rem] shadow-2xl p-4 md:p-6 w-full max-w-5xl h-[600px] flex flex-col relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-center justify-between p-4 md:p-6 border-b-4 border-dark bg-white rounded-t-[2.2rem] gap-4">
                        <span class="font-bold uppercase tracking-widest text-sm text-dark"><i class="fa-solid fa-message text-secondary mr-2"></i> Wishes Wall</span>
                        <button id="btn-load-more" onclick="renderWishes()" class="bg-dark text-white px-5 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-secondary Transition-all shadow-md">Refresh</button>
                    </div>
                    
                    <div id="wishes-container" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 scroll-custom relative hide-scrollbar">
                        <!-- Wishes list rendered via JS -->
                    </div>

                    <div class="absolute bottom-[-50px] right-[-50px] w-64 h-64 z-[-1] opacity-50 transform rotate-12 pointer-events-none">
                         <img src="https://images.unsplash.com/photo-1537274942065-eda9d00a6293?q=80&w=600" class="w-full h-full object-cover polaroid-frame">
                    </div>
                </div>
            </div>
        </section>

        @if ($content['is_gift_active'] ?? false)
        <section id="hadiah" class="py-24 px-6 bg-light relative overflow-hidden w-full border-t-4 border-dark">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="https://images.unsplash.com/photo-1591604466107-ec97de577aff?q=80&w=2000" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-secondary/40 mix-blend-multiply opacity-80"></div>
            </div>

            <div class="max-w-5xl mx-auto relative z-10 flex flex-col items-center">
                <div class="paper-card transform rotate-2 max-w-sm w-full p-8 text-center mb-16 border-2 border-dark shadow-2xl">
                    <div class="washi-tape" style="background-color: #C8A2C8;"></div>
                    <h3 class="text-4xl font-serif text-dark mb-4">Tanda Kasih</h3>
                    <div class="w-12 h-1 bg-funky mx-auto rounded-full mt-3"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 w-full">
                    @if (!empty($content['banks']) && is_array($content['banks']))
                        @foreach ($content['banks'] as $index => $bank)
                        @php $logoUrl = $masterLogos[strtolower($bank['name'])] ?? null; @endphp
                        <div class="paper-card border-4 border-dark p-8 rounded-3xl flex flex-col items-center justify-center relative shadow-[10px_10px_0px_rgba(41,47,54,1)] group hover:-translate-y-1 transition-transform {{ $index % 2 == 0 ? 'rotate-1' : '-rotate-1 md:translate-y-8' }} w-full">
                            <div class="h-14 bg-white rounded-full inline-flex items-center justify-center px-10 shadow-sm mb-8 border border-gray-200">
                                @if ($logoUrl)
                                <img src="{{ asset('storage/' . $logoUrl) }}" class="h-6 object-contain" alt="{{ $bank['name'] }}">
                                @else
                                <span class="font-bold text-dark text-lg">{{ strtoupper($bank['name']) }}</span>
                                @endif
                            </div>
                            <h3 id="rek-{{ $index }}" class="text-3xl font-semibold text-primary tracking-widest mb-2">{{ $bank['account_number'] }}</h3>
                            <p class="font-bold text-[11px] uppercase tracking-widest text-dark mb-10">A.n {{ $bank['account_name'] ?? $bank['account_owner'] ?? '' }}</p>
                            <button onclick="copyToClipboard('rek-{{ $index }}', this)" class="bg-dark text-white w-full py-3.5 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-funky transition-all shadow-md">
                                Salin Nomor
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>

                <div id="copy-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[300] px-8 py-3.5 bg-funky text-white border-2 border-dark rounded-full font-bold text-[11px] uppercase tracking-widest flex items-center gap-2 opacity-0 translate-y-4 transition-all pointer-events-none shadow-2xl">
                    <i class="fa-solid fa-check"></i> Tersalin!
                </div>
            </div>
        </section>

        <section id="kirim-kado" class="py-24 px-6 bg-funky relative border-t-4 border-dark shadow-inner polka-bg w-full">
            <div class="absolute inset-0 z-0 scale-105 opacity-15">
                <img src="https://images.unsplash.com/photo-1510154221590-ff63e90a136f?q=80&w=2000" class="w-full h-full object-cover">
            </div>

            <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-10 items-center bg-white/70 backdrop-blur-md border-4 border-dark p-8 md:p-12 rounded-[3rem] shadow-2xl relative z-10 w-full">
                <div class="w-full md:w-1/2 text-center md:text-left flex flex-col items-center md:items-start">
                    <div class="bg-white/80 w-16 h-16 rounded-full border-2 border-dark flex items-center justify-center text-secondary text-3xl mb-6 shadow-md"><i class="fa-solid fa-gift"></i></div>
                    <h2 class="text-4xl font-serif text-dark mb-4">Physical Gift</h2>
                    <p class="text-sm font-medium mb-6 text-gray-700 leading-relaxed">Merupakan kehormatan bagi kami apabila Anda berkenan hadir. Namun jika Anda ingin mengirim kado fisik, silakan kirimkan ke alamat atau cek daftar wishlist kami.</p>
                    <button onclick="toggleGiftModal(true)" class="bg-dark text-white px-10 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest hover:bg-accent hover:text-dark Transition-all border-2 border-transparent">Lihat Wishlist</button>
                </div>
                <div class="w-full md:w-1/2 bg-white border-4 border-dark rounded-[2rem] p-6 md:p-8 text-center relative shadow-[8px_8px_0px_rgba(41,47,54,0.7)] group">
                    <div class="absolute -top-3 -right-3 bg-secondary text-white font-bold text-[10px] uppercase px-4 py-1.5 border-2 border-dark rounded-full transform rotate-12">Alamat</div>
                    <p id="alamat-kado" class="font-serif text-base md:text-lg mb-8 leading-relaxed text-dark italic">{!! nl2br(e($content['alamat_kado'] ?? '')) !!}</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="copyToClipboard('alamat-kado', this)" class="px-6 py-3 rounded-full bg-light border-2 border-dark text-dark text-[10px] font-bold uppercase tracking-widest hover:bg-white transition-all shadow-md w-full sm:w-auto">
                            <i class="fa-regular fa-copy mr-2"></i> Salin Alamat
                        </button>
                    </div>
                </div>
            </div>

            <div id="gift-modal" class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/70 backdrop-blur-sm" onclick="toggleGiftModal(false)"></div>
                <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] border-4 border-dark flex flex-col max-h-[80vh] shadow-[12px_12px_0px_rgba(255,107,107,1)] animate-pulseSoft">
                    <div class="p-6 border-b-4 border-dark flex justify-between items-center bg-light rounded-t-[2.2rem]">
                        <div>
                            <h3 class="text-2xl font-serif text-primary mb-1">Wedding Wishlist</h3>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Your thoughtful gestures</p>
                        </div>
                        <button onclick="toggleGiftModal(false)" class="w-10 h-10 bg-dark text-white rounded-full flex items-center justify-center hover:bg-primary transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    
                    <div class="overflow-y-auto p-6 space-y-4 custom-scrollbar scroll-custom hide-scrollbar">
                        @if (!empty($content['gifts']) && is_array($content['gifts']))
                            @foreach ($content['gifts'] as $idx => $gift)
                            <div id="item-{{ $idx }}" class="p-5 rounded-[2rem] border-2 border-dark flex flex-col sm:flex-row justify-between gap-4 items-center group hover:bg-accent transition-all text-center sm:text-left">
                                <div><h4 class="font-bold text-dark text-sm group-hover:text-dark transition-colors">{{ $gift['item_name'] ?? '' }}</h4><p class="text-[10px] text-gray-500 group-hover:text-dark">{{ $gift['description'] ?? '' }}</p></div>
                                <button onclick="confirmGift('item-{{ $idx }}', '{{ addslashes($gift['item_name'] ?? '') }}')" class="px-5 py-2.5 rounded-full bg-light text-primary text-[10px] font-bold uppercase tracking-widest hover:bg-dark hover:text-white transition-all border border-dark/10 shadow-inner shrink-0 w-full sm:w-auto">Pilih</button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div id="confirm-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/80 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
                <div class="relative bg-white w-full max-w-md rounded-[2.5rem] p-8 md:p-10 text-center shadow-2xl border-4 border-dark max-h-[90vh] overflow-y-auto custom-scrollbar">
                    <div class="w-16 h-16 rounded-full bg-accent text-primary flex items-center justify-center mx-auto mb-6 shadow-inner border-2 border-dark"><i class="fa-solid fa-heart text-2xl"></i></div>
                    <h4 class="text-3xl font-serif text-dark mb-4">Konfirmasi Niat</h4>
                    <p id="confirm-text" class="text-sm text-gray-600 mb-6 leading-relaxed"></p>
                    
                    <div class="space-y-6 text-left mb-6 font-sans">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-secondary mb-2">Nama Pengirim</label>
                            <input type="text" id="gift-confirm-name" class="w-full py-2.5 px-4 bg-bg border-2 border-dark rounded-xl focus:outline-none text-sm font-medium text-dark" value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}" placeholder="Nama Anda">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-secondary mb-2">Status Kehadiran</label>
                            <div class="flex gap-4">
                                <button type="button" id="gift-btn-hadir" onclick="selectGiftAttendance('hadir')" class="flex-1 py-3 bg-primary text-white border-2 border-dark rounded-full font-bold text-xs uppercase tracking-widest transition-colors">Hadir</button>
                                <button type="button" id="gift-btn-absen" onclick="selectGiftAttendance('tidak_hadir')" class="flex-1 py-3 bg-white border-2 border-dark text-primary rounded-full font-bold text-xs uppercase tracking-widest hover:bg-bg transition-colors">Tidak Hadir</button>
                            </div>
                            <input type="hidden" id="gift-confirm-status" value="hadir">
                        </div>

                        <div id="gift-confirm-pax-wrapper" class="border-2 border-dark p-4 bg-bg rounded-2xl">
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-secondary mb-2 font-bold">Jumlah Orang</label>
                            <div class="flex gap-2">
                                <button type="button" onclick="setGiftPax(1)" class="gift-pax-btn flex-1 py-2 bg-primary text-white border-2 border-dark rounded-full font-bold text-xs">1</button>
                                <button type="button" onclick="setGiftPax(2)" class="gift-pax-btn flex-1 py-2 bg-white border-2 border-dark rounded-full font-bold text-xs text-secondary hover:bg-bg transition-colors">2</button>
                                <button type="button" onclick="setGiftPax('custom')" class="gift-pax-btn flex-1 py-2 bg-white border-2 border-dark rounded-full font-bold text-xs text-secondary hover:bg-bg transition-colors">3+</button>
                            </div>
                            
                            <div id="gift-confirm-custom-pax-container" class="hidden mt-3">
                                <input type="number" id="gift-confirm-custom-pax-input" placeholder="Masukkan jumlah tamu" class="w-full bg-white border-2 border-dark p-3 font-medium outline-none text-sm rounded-xl">
                            </div>
                            <input type="hidden" id="gift-confirm-pax" value="1">
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                        <button id="final-confirm-btn" class="w-full py-4 rounded-full bg-primary text-white text-[10px] font-bold uppercase tracking-widest border-2 border-dark shadow-[4px_4px_0px_rgba(41,47,54,1)]">Ya, Saya Bersedia</button>
                        <button onclick="closeConfirmModal()" class="w-full py-4 rounded-full bg-light text-dark text-[10px] font-bold uppercase tracking-widest border border-dark/10 shadow-inner">Batal</button>
                    </div>
                </div>
            </div>

            <div id="gift-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[700] px-8 py-4 bg-paper text-primary border-4 border-primary rounded-full shadow-2xl text-[10px] font-bold uppercase tracking-widest opacity-0 transition-all duration-500 text-center">
                Terima kasih!
            </div>
        </section>
        @endif

        @if ($content['is_guest_info_active'] ?? false)
        <section id="guest-info" class="py-24 px-6 md:px-20 bg-paper relative overflow-hidden border-t-4 border-dark w-full">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="https://images.unsplash.com/photo-1542042161784-26ab9e041e89?w=2000" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-paper/80"></div>
            </div>

            <div class="max-w-6xl mx-auto relative z-10 flex flex-col items-center">
                
                <div class="paper-card transform rotate-2 max-w-lg w-full p-8 text-center mb-12 border-2 border-dark shadow-2xl">
                    <div class="washi-tape" style="background-color: #4ECDC4;"></div>
                    <h3 class="text-4xl font-serif text-dark mb-4">Informasi Tamu</h3>
                    
                    <div class="flex flex-wrap items-center justify-center gap-3 text-xs font-bold mt-4">
                        <span class="bg-secondary text-white border-2 border-dark px-4 py-1.5 rounded-full shadow-sm">98% Match</span>
                        <span class="bg-white border-2 border-dark px-4 py-1.5 rounded-full shadow-sm">2026</span>
                        <span class="bg-white border-2 border-dark px-3 py-1.5 rounded-md shadow-sm">17+</span>
                        <span class="bg-white border-2 border-dark px-4 py-1.5 rounded-full shadow-sm">1 Season</span>
                        <span class="bg-primary text-white border-2 border-dark px-4 py-1.5 rounded-full uppercase tracking-widest text-[10px] shadow-sm">Ultra HD 4K</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 w-full">
                    
                    <div class="lg:col-span-2 space-y-10">
                        @if ($content['enable_dresscode'] ?? false)
                        <div class="polaroid-frame transform rotate(-1deg) border-4 border-dark group shadow-xl bg-paper p-4">
                            <div class="border-2 border-dashed border-gray-300 p-2 rounded-lg mb-4 bg-light text-left">
                                <div class="flex items-center gap-2 mb-3 px-2 pt-2">
                                    <span class="w-1.5 h-5 bg-primary"></span>
                                    <h4 class="text-dark uppercase tracking-widest text-[10px] font-bold">Dresscode</h4>
                                </div>
                                <p class="text-sm md:text-base font-medium leading-relaxed text-dark px-2 pb-2">
                                    <span class="font-bold text-primary">{{ $content['dresscode'] ?? '' }}</span>
                                </p>
                            </div>
                        </div>
                        @endif

                        @if ($content['enable_health_protocol'] ?? false)
                        <div class="paper-card border-4 border-dark p-6 md:p-8 transform rotate-1 bg-white">
                            <h5 class="text-dark text-[10px] font-bold uppercase tracking-widest mb-6 border-b-2 border-dark pb-2 inline-block">Protokol Kesehatan</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div id="protokol-cuci-tangan" class="bg-light border-2 border-dark p-4 rounded-xl flex flex-col items-center text-center gap-2 hover:-translate-y-1 transition-transform shadow-sm">
                                    <i class="fa-solid fa-hands-bubbles text-secondary text-2xl"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-dark">Cuci Tangan</span>
                                </div>
                                <div id="protokol-pakai-masker" class="bg-light border-2 border-dark p-4 rounded-xl flex flex-col items-center text-center gap-2 hover:-translate-y-1 transition-transform shadow-sm">
                                    <i class="fa-solid fa-head-side-mask text-secondary text-2xl"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-dark">Pakai Masker</span>
                                </div>
                                <div id="protokol-jaga-jarak-1" class="bg-light border-2 border-dark p-4 rounded-xl flex flex-col items-center text-center gap-2 hover:-translate-y-1 transition-transform shadow-sm">
                                    <i class="fa-solid fa-people-arrows text-secondary text-2xl"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-dark">Jaga Jarak</span>
                                </div>
                                <div id="protokol-hindari-kerumunan" class="bg-light border-2 border-dark p-4 rounded-xl flex flex-col items-center text-center gap-2 hover:-translate-y-1 transition-transform shadow-sm">
                                    <i class="fa-solid fa-users-slash text-primary text-2xl"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-dark">No Kerumunan</span>
                                </div>
                                <div id="protokol-cek-suhu" class="bg-light border-2 border-dark p-4 rounded-xl flex flex-col items-center text-center gap-2 hover:-translate-y-1 transition-transform shadow-sm">
                                    <i class="fa-solid fa-temperature-high text-primary text-2xl"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-dark">Cek Suhu</span>
                                </div>
                                <div id="protokol-desinfektan" class="bg-light border-2 border-dark p-4 rounded-xl flex flex-col items-center text-center gap-2 hover:-translate-y-1 transition-transform shadow-sm">
                                    <i class="fa-solid fa-spray-can-sparkles text-primary text-2xl"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-dark">Desinfektan</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if ($content['enable_adab_walimah'] ?? false)
                    <div class="polaroid-frame transform rotate(-2deg) border-4 border-dark bg-paper h-full flex flex-col p-6 shadow-xl">
                        <h5 class="text-dark text-[11px] font-bold uppercase tracking-widest flex items-center gap-2 mb-6 border-b-2 border-dark pb-3">
                            <i class="fa-solid fa-list-check text-accent text-xl"></i> Adab Walimah
                        </h5>
                        <div class="space-y-4 overflow-y-auto pr-2 custom-scrollbar flex-1 max-h-[500px]">
                            <div id="adab-sholat" class="flex gap-4 bg-white border-2 border-dark p-3 rounded-xl shadow-sm items-center">
                                <div class="text-primary text-2xl w-8 shrink-0 flex justify-center"><i class="fa-solid fa-mosque"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Waktu Sholat</p><p class="text-[9px] text-gray-500 font-medium">Memperhatikan waktu ibadah saat acara.</p></div>
                            </div>
                            <div id="adab-makan-minum" class="flex gap-4 bg-white border-2 border-dark p-3 rounded-xl shadow-sm items-center">
                                <div class="text-primary text-2xl w-8 shrink-0 flex justify-center"><i class="fa-solid fa-utensils"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Adab Makan</p><p class="text-[9px] text-gray-500 font-medium">Makan & minum dengan cara duduk.</p></div>
                            </div>
                            <div id="adab-mendoakan" class="flex gap-4 bg-white border-2 border-dark p-3 rounded-xl shadow-sm items-center">
                                <div class="text-primary text-2xl w-8 shrink-0 flex justify-center"><i class="fa-solid fa-hands-praying"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Doa Restu</p><p class="text-[9px] text-gray-500 font-medium">Memberikan doa keberkahan bagi kami.</p></div>
                            </div>
                            <div id="adab-jaga-jarak-2" class="flex gap-4 bg-white border-2 border-dark p-3 rounded-xl shadow-sm items-center">
                                <div class="text-primary text-2xl w-8 shrink-0 flex justify-center"><i class="fa-solid fa-restroom"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Jaga Jarak</p><p class="text-[9px] text-gray-500 font-medium">Menjaga batasan antara tamu pria & wanita.</p></div>
                            </div>
                            <div id="adab-pakaian-sopan" class="flex gap-4 bg-white border-2 border-dark p-3 rounded-xl shadow-sm items-center">
                                <div class="text-primary text-2xl w-8 shrink-0 flex justify-center"><i class="fa-solid fa-shirt"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Baju Sopan</p><p class="text-[9px] text-gray-500 font-medium">Berbusana menutup aurat dan rapi.</p></div>
                            </div>
                            <div id="adab-larangan-foto" class="flex gap-4 bg-white border-2 border-dark p-3 rounded-xl shadow-sm items-center">
                                <div class="text-primary text-2xl w-8 shrink-0 flex justify-center"><i class="fa-solid fa-video-slash"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Izin Foto</p><p class="text-[9px] text-gray-500 font-medium">Meminta izin sebelum mendokumentasikan.</p></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        <section id="qr-tamu" class="py-24 px-6 md:px-20 bg-paper relative overflow-hidden border-t-4 border-dark w-full pb-40">
            <div class="absolute inset-0 z-0 scale-105">
                <img src="https://images.unsplash.com/photo-1510154221590-ff63e90a136f?w=2000" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-paper/80"></div>
            </div>

            <div class="max-w-xl mx-auto flex flex-col items-center bg-white border-4 border-dark rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative z-10 rotate-1 w-full">
                <div class="text-center">
                    <h2 class="text-4xl font-serif text-dark mb-4 mt-4">Akses Undangan</h2>
                    <p class="text-sm font-medium mb-10 text-gray-700 leading-relaxed max-w-sm">Tunjukkan kode QR ini kepada penerima tamu di pintu masuk untuk verifikasi RSVP Anda.</p>
                </div>

                <div class="polaroid-frame transform -rotate-2 border-4 border-dark group w-full max-w-[250px]">
                    <div class="border-2 border-dashed border-gray-300 p-2 rounded-xl mb-4 bg-bg">
                        <img id="qr-image" src="{{ $qrCodeUrl }}" class="w-full h-auto aspect-square rounded-xl" alt="QR">
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-primary mb-2 text-center">GUEST CODE</p>
                    <h3 id="guest-name-qr" class="text-xl font-serif text-dark italic mb-6 text-center">{{ $guestNameDisplay }}</h3>
                    <div class="py-2.5 px-4 bg-light rounded-full text-center text-[9px] font-bold uppercase tracking-widest text-gray-500 border border-gray-200 shadow-inner w-full break-words">Verify RSVP Status</div>
                </div>
            </div>
        </section>

        <section id="rsvp-modal" class="fixed inset-0 z-[100] invisible transition-all duration-500 overflow-hidden flex items-end md:items-center justify-center">
            <div onclick="closeRSVP()" class="absolute inset-0 bg-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>
            <div id="rsvp-content" class="relative w-full md:max-w-xl bg-white border-4 border-dark rounded-t-[3rem] md:rounded-[3rem] shadow-[0px_-10px_0px_rgba(255,138,91,0.5)] transform translate-y-full transition-transform duration-700 flex flex-col max-h-[90vh]">
                
                <div class="overflow-y-auto p-8 md:p-10 custom-scrollbar relative w-full">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 washi-tape hidden md:block" style="background-color: #C8A2C8;"></div>
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6 md:hidden"></div>
                    <div class="w-full flex justify-end mb-2 md:mb-4">
                        <button onclick="closeRSVP()" class="w-10 h-10 border-2 border-dark rounded-full flex items-center justify-center hover:bg-funky transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="text-center mb-8 md:mb-10">
                        <h2 class="text-4xl font-serif text-primary mb-2">RSVP</h2>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold border-b-2 border-dark pb-1 inline-block">Confirmation</p>
                    </div>

                    <form id="form-rsvp" class="space-y-6 text-left">
                        <input type="hidden" id="input-status" value="Hadir">
                        <input type="hidden" id="input-guest-count" value="1">
                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-gray-500 mb-2 block">Nama Lengkap</label>
                            <input type="text" id="input-nama-rsvp" value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}" class="w-full bg-light border-2 border-dark focus:bg-white focus:border-funky p-4 rounded-xl font-medium outline-none transition-all">
                        </div>
                        
                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-gray-500 mb-2 block">Kehadiran</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir" class="w-full py-4 border-2 border-brand-gold rounded-xl bg-brand-gold text-brand-dark text-xs font-bold uppercase tracking-widest shadow-[0_0_15px_rgba(212,175,55,0.3)]"><i class="fa-solid fa-check mr-2"></i> Hadir</button>
                                <button type="button" onclick="selectAttendance('Absen')" id="btn-absen" class="w-full py-4 border-2 border-dark rounded-xl bg-white text-primary text-xs font-bold uppercase tracking-widest hover:bg-light Transition-all"><i class="fa-solid fa-xmark mr-2"></i> Absen</button>
                            </div>
                        </div>

                        <div id="guest-selection">
                            <div class="bg-accent/20 border-2 border-accent p-5 rounded-2xl">
                                <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-3 block text-center">Jumlah Tamu</label>
                                <div class="flex gap-2">
                                    <button type="button" onclick="setGuestCount(1)" class="guest-btn flex-1 py-2.5 bg-brand-gold text-brand-dark border-2 border-dark rounded-xl font-bold">1</button>
                                    <button type="button" onclick="setGuestCount(2)" class="guest-btn flex-1 py-2.5 bg-white border-2 border-dark rounded-xl font-bold hover:bg-light">2</button>
                                    <button type="button" onclick="setGuestCount('custom')" class="guest-btn flex-1 py-2.5 bg-white border-2 border-dark rounded-xl font-bold hover:bg-light">3+</button>
                                </div>
                                <div id="custom-pax-container" class="hidden mt-4">
                                    <input type="number" id="custom-pax-input" placeholder="Masukkan jumlah tamu" class="w-full bg-white border-2 border-dark p-3 rounded-xl font-medium outline-none">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="font-bold text-[10px] uppercase tracking-widest text-gray-500 mb-2 block">Doa & Ucapan</label>
                            <textarea id="input-pesan-rsvp" rows="3" class="w-full bg-light border-2 border-dark focus:bg-white focus:border-funky p-4 rounded-2xl font-medium outline-none resize-none transition-all"></textarea>
                        </div>

                        <div class="pt-4 space-y-3">
                            <button type="submit" class="w-full py-4 rounded-full bg-dark text-accent font-bold text-xs uppercase tracking-widest hover:scale-105 transition-transform shadow-lg">Kirim Konfirmasi</button>
                            <button type="button" onclick="closeRSVP()" class="w-full py-3.5 rounded-full bg-white text-gray-500 font-bold text-[10px] uppercase tracking-widest border-2 border-dark/20 shadow-inner">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <footer class="py-20 px-6 bg-funky/10 text-center relative overflow-hidden collage-bg border-t-4 border-dark w-full">
            <div class="absolute inset-0 z-0 scale-105 opacity-15">
                <img src="https://images.unsplash.com/photo-1542042161784-26ab9e041e89?w=2000" class="w-full h-full object-cover">
            </div>

            <div class="max-w-xl mx-auto flex flex-col items-center relative z-10">
                <h2 class="text-6xl font-serif text-dark drop-shadow-md mb-8">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h2>
                
                <div class="paper-card border-4 border-dark p-6 md:p-8 transform rotate-1 mb-12 shadow-2xl w-full">
                    <div class="absolute top-[-20px] left-[10px] md:left-[-30px] washi-tape" style="background-color: #FF8A5B;"></div>
                    <div class="absolute top-[-10px] right-[10px] md:right-[-30px] washi-tape" style="background-color: #4ECDC4; transform: rotate(10deg);"></div>
                    
                    <p class="font-serif italic text-funky text-base md:text-lg mb-8 leading-relaxed">"Merupakan kehormatan bagi kami apabila Anda berkenan hadir dan memberikan doa restu."</p>
                    
                    <div class="flex flex-col items-center justify-center gap-2 text-center border-t-2 border-dashed border-gray-300 pt-6 mt-6">
                        <p class="text-sm uppercase font-bold text-dark tracking-widest">{{ $firstPerson['name'] }} & {{ $secondPerson['name'] }}</p>
                        <p class="font-bold text-[10px] text-gray-500 uppercase tracking-widest">& Keluarga Besar</p>
                    </div>
                </div>

                <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank" class="bg-dark text-funky px-6 py-3 rounded-full border-2 border-funky flex items-center justify-center gap-2 group hover:scale-105 transition-transform w-full sm:w-auto">
                    <i class="fa-brands fa-instagram text-lg"></i> <span class="font-bold text-xs">@ruangrestu.undangan</span>
                </a>
                
                <p class="text-[9px] font-bold uppercase text-dark tracking-[0.4em] mt-10">2026. CREATED WITH LOVE.</p>
            </div>
        </footer>

    </main>

    <div id="fab-container" class="fixed right-4 md:right-6 bottom-24 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000">
        
        <div class="relative flex items-center group">
            <div id="music-info" class="absolute right-full mr-3 px-4 py-2 bg-dark text-accent border-2 border-dark rounded-full font-bold text-[10px] uppercase tracking-widest transition-all duration-500 whitespace-nowrap opacity-0 translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 pointer-events-none">
                {{ !empty($invitation->music_id) && $invitation->music ? $invitation->music->title : 'Soundtrack' }}
            </div>
            
            <button id="btn-music" onclick="toggleMusic()" class="w-12 h-12 md:w-14 md:h-14 bg-white border-4 border-dark rounded-full flex items-center justify-center text-primary shadow-[4px_4px_0px_rgba(41,47,54,1)] hover:bg-bg transition-colors">
                <i class="fa-solid fa-music animate-spin-slow text-lg md:text-xl" id="icon-music"></i>
            </button>
        </div>

        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-12 h-12 md:w-14 md:h-14 bg-white border-4 border-dark rounded-full flex items-center justify-center text-secondary shadow-[4px_4px_0px_rgba(41,47,54,1)] hover:bg-bg transition-colors">
            <i class="fa-solid fa-angles-down text-lg md:text-xl" id="icon-scroll"></i>
        </button>
    </div>

    <div id="lightbox" class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-dark/95 backdrop-blur-md p-4">
        <div class="w-full flex justify-between items-center p-6 absolute top-0">
            <span class="bg-paper border border-dark px-4 py-1.5 rounded-full text-xs font-bold text-dark"><span id="current-count">1</span> / <span id="total-count">4</span></span>
            <button onclick="closeLightbox()" class="w-12 h-12 bg-white rounded-full text-primary flex justify-center items-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="flex items-center justify-center w-full h-[70vh] px-4 mt-8">
            <img id="lightbox-img" src="" class="max-h-full max-w-full border-[10px] border-white rounded-[2rem] shadow-2xl object-contain transition-opacity duration-300">
        </div>
        <div class="absolute bottom-8 flex gap-4 bg-white/10 p-3 rounded-full border-2 border-dark backdrop-blur-sm">
            <button onclick="prevImg()" class="w-12 h-12 md:w-14 md:h-14 bg-white border-4 border-dark rounded-full text-dark flex items-center justify-center hover:bg-accentTransition-all"><i class="fa-solid fa-arrow-left"></i></button>
            <button onclick="nextImg()" class="w-12 h-12 md:w-14 md:h-14 bg-white border-4 border-dark rounded-full text-dark flex items-center justify-center hover:bg-accentTransition-all"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>

    <nav id="bottom-nav" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-white border-4 border-dark rounded-full shadow-[6px_6px_0px_rgba(41,47,54,1)] p-1.5 backdrop-blur-md">
            <ul class="flex justify-around items-center h-14 w-[320px] md:w-[380px]">
                <li class="relative group h-full">
                    <a href="#home" class="flex items-center justify-center w-12 h-full text-dark hover:bg-accent transition-all rounded-full border-2 border-transparent hover:border-dark">
                        <i class="fa-solid fa-house text-lg"></i>
                    </a>
                </li>
                <li class="relative group h-full">
                    <a href="#gallery" class="flex items-center justify-center w-12 h-full text-dark hover:bg-secondary hover:text-white transition-all rounded-full border-2 border-transparent hover:border-dark">
                        <i class="fa-solid fa-image text-lg"></i>
                    </a>
                </li>
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

    <script>
        // Guest Data Setup
        const urlParams = new URLSearchParams(window.location.search);
        let guestName = "{{ $guestNameDisplay }}";
        
        // Countdown
        const weddingDate = {{ $weddingTimestamp }};
        if (weddingDate > 0) {
            const countdownFunction = setInterval(function() {
                const now = new Date().getTime();
                const distance = weddingDate - now;

                if (distance <= 0) {
                    clearInterval(countdownFunction);
                    return;
                }

                document.getElementById("days").innerText = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                document.getElementById("hours").innerText = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                document.getElementById("minutes").innerText = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
            }, 1000);
        }

        // Open Invitation
        function openInvitation() {
            document.getElementById('cover-page').classList.add('-translate-y-full');
            document.body.style.overflowY = 'auto';
            document.getElementById('main-content').classList.remove('opacity-0');
            document.getElementById('fab-container').classList.remove('opacity-0');
            document.getElementById('bottom-nav').classList.remove('translate-y-32');
            
            toggleMusic(true);

            // Menampilkan Tooltip Music 3 detik di awal
            const musicInfo = document.getElementById('music-info');
            setTimeout(() => {
                musicInfo.classList.remove('opacity-0', 'translate-x-4');
                musicInfo.classList.add('opacity-100', 'translate-x-0');
                setTimeout(() => {
                    musicInfo.classList.remove('opacity-100', 'translate-x-0');
                    musicInfo.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => {
                        musicInfo.style = '';
                    }, 500);
                }, 3000);
            }, 1500);
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

        // AUTO OPEN RSVP AT BOTTOM
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

        // RSVP Modal Logic
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
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');

            btnHadir.className = 'w-full py-4 border-2 border-dark rounded-xl bg-white text-secondary text-xs font-bold uppercase tracking-widest hover:bg-light Transition-all';
            btnAbsen.className = 'w-full py-4 border-2 border-dark rounded-xl bg-white text-primary text-xs font-bold uppercase tracking-widest hover:bg-light Transition-all';
            
            if(status === 'Hadir') {
                btnHadir.className = 'w-full py-4 border-2 border-dark rounded-xl bg-dark text-white text-xs font-bold uppercase tracking-widest';
                guestDiv.classList.remove('hidden');
                document.getElementById('input-guest-count').value = 1;
                setGuestCount(1);
            } else {
                btnAbsen.className = 'w-full py-4 border-2 border-dark rounded-xl bg-dark text-white text-xs font-bold uppercase tracking-widest';
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
                    btn.className = 'guest-btn flex-1 py-2.5 bg-dark text-white border-2 border-dark rounded-xl font-bold';
                } else {
                    btn.className = 'guest-btn flex-1 py-2.5 bg-white border-2 border-dark rounded-xl font-bold hover:bg-light';
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
                btn.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Tersalin!';
                btn.classList.add('bg-funky', 'text-white');
                toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-funky', 'text-white');
                    toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                }, 2000);
            });
        }

        // Live Streaming Platform Switch
        function switchPlatform(id, title, desc, iconClass, link) {
            document.getElementById('platform-title').innerText = title;
            document.getElementById('platform-desc').innerText = desc;
            document.getElementById('platform-icon').className = iconClass + ' text-6xl text-primary mb-6 animate-wobble-slow';
            document.getElementById('platform-link').href = link;
        }

        // Gift Modal & Confirm
        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if(show) modal.classList.remove('hidden'); else modal.classList.add('hidden');
        }
        function selectGiftAttendance(status) {
            document.getElementById('gift-confirm-status').value = status;
            const btnHadir = document.getElementById('gift-btn-hadir');
            const btnAbsen = document.getElementById('gift-btn-absen');
            const wrapper = document.getElementById('gift-confirm-pax-wrapper');
            
            btnHadir.className = "flex-1 py-3 bg-white border-2 border-dark text-primary rounded-full font-bold text-xs uppercase tracking-widest hover:bg-bg transition-colors";
            btnAbsen.className = "flex-1 py-3 bg-white border-2 border-dark text-primary rounded-full font-bold text-xs uppercase tracking-widest hover:bg-bg transition-colors";
            
            if (status === 'hadir') {
                btnHadir.className = "flex-1 py-3 bg-primary text-white border-2 border-dark rounded-full font-bold text-xs uppercase tracking-widest transition-colors";
                wrapper.classList.remove('hidden');
                document.getElementById('gift-confirm-pax').value = 1;
                setGiftPax(1);
            } else {
                btnAbsen.className = "flex-1 py-3 bg-primary text-white border-2 border-dark rounded-full font-bold text-xs uppercase tracking-widest transition-colors";
                wrapper.classList.add('hidden');
                document.getElementById('gift-confirm-pax').value = 0;
            }
        }

        function setGiftPax(count) {
            const customContainer = document.getElementById('gift-confirm-custom-pax-container');
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
                btn.className = "gift-pax-btn flex-1 py-2 bg-white border-2 border-dark rounded-full font-bold text-xs text-secondary hover:bg-bg transition-colors";
                if (text == count || (count === 'custom' && text === '3+')) {
                    btn.className = "gift-pax-btn flex-1 py-2 bg-primary text-white border-2 border-dark rounded-full font-bold text-xs";
                }
            });
        }

        let selId, selName;
        function confirmGift(id, name) {
            selId = id; selName = name;
            document.getElementById('confirm-text').innerHTML = `Apakah Anda yakin ingin mengirim <b>${name}</b> sebagai kado pernikahan kami?`;
            
            const nameInput = document.getElementById('gift-confirm-name');
            const paxCustomInput = document.getElementById('gift-confirm-custom-pax-input');
            
            if (nameInput) nameInput.value = "{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}";
            if (paxCustomInput) paxCustomInput.value = '';
            
            selectGiftAttendance('hadir');

            document.getElementById('confirm-modal').classList.remove('hidden');
            
            document.getElementById('final-confirm-btn').onclick = () => {
                const finalName = nameInput ? nameInput.value.trim() : 'Hamba Allah';
                const finalStatus = document.getElementById('gift-confirm-status').value || 'hadir';
                let finalPax = 0;
                if (finalStatus === 'hadir') {
                    finalPax = parseInt(document.getElementById('gift-confirm-pax').value) || 1;
                }
                if (isNaN(finalPax) || finalPax < 0) finalPax = 0;

                document.getElementById('confirm-modal').classList.add('hidden');
                
                sendRsvpData({
                    guest_name: finalName || 'Hamba Allah',
                    status_rsvp: finalStatus,
                    pax: finalPax,
                    message: `Telah memilih kado: ${name} 🎁`
                });

                document.getElementById('gift-toast').style.opacity = '1';
                document.getElementById('gift-toast').style.transform = 'translate(-50%, -20px)';
                setTimeout(() => { 
                    document.getElementById('gift-toast').style.opacity = '0'; 
                    document.getElementById('gift-toast').style.transform = 'translate(-50%, 0)'; 
                    toggleGiftModal(false); 
                }, 3000);
            };
        }
        document.getElementById('gift-confirm-custom-pax-input')?.addEventListener('input', function() {
            document.getElementById('gift-confirm-pax').value = this.value || 4;
        });
        function closeConfirmModal() { document.getElementById('confirm-modal').classList.add('hidden'); }

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
        function nextImg() { curImg = (curImg+1)%images.length; updateLB(); }
        function prevImg() { curImg = (curImg-1+images.length)%images.length; updateLB(); }

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
                card.className = 'bg-white border-2 border-dark p-4 rounded-2xl flex gap-4';
                card.innerHTML = `
                    <div class="w-10 h-10 bg-funky/20 border border-dark rounded-full shrink-0 flex items-center justify-center text-dark font-bold">
                        ${wish.nama.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 text-left">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="font-bold text-dark text-sm">${wish.nama}</h5>
                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 border border-dark rounded-md bg-accent">${wish.status}</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed font-light">${wish.pesan}</p>
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
                        <div id="temp-success" class="absolute inset-0 z-50 flex items-center justify-center bg-dark/95 backdrop-blur-md">
                            <div class="text-center">
                                <i class="fa-solid fa-check-circle text-6xl text-primary mb-4"></i>
                                <h3 class="text-2xl font-serif text-white mb-2">Terima Kasih</h3>
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
