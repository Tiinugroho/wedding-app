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
        'label' => 'Marapulai',
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
        'label' => 'Anak Daro',
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
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=7F1D1D&bgcolor=FFFFFF&data=' . urlencode($qrData);

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
    <title>{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }} - The Royal Minang Wedding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Great+Vibes&family=Montserrat:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#FFFBF0', // Cream Ivory
                        primary: '#7F1D1D', // Maroon Minang
                        secondary: '#B45309', // Gold/Bronze
                        accent: '#D4AF37', // Gold Metalik
                        dark: '#1A1A1A',
                        borderLight: '#E5E7EB'
                    },
                    fontFamily: {
                        sans: ['"Montserrat"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                        cursive: ['"Great Vibes"', 'cursive'],
                        royal: ['"Cinzel"', 'serif'],
                    },
                    animation: {
                        'spin-slow': 'spin 15s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-soft': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                            '50%': { transform: 'translateY(-15px) rotate(1deg)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Mencegah horizontal scroll */
        html, body { 
            max-width: 100vw; 
            overflow-x: hidden; 
            background-color: #FFFBF0; 
            color: #1A1A1A;
            -webkit-font-smoothing: antialiased;
        }

        /* Songket Pattern Overlay */
        .songket-bg {
            background-image: url('https://www.transparenttextures.com/patterns/natural-paper.png');
            position: relative;
        }
        .songket-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('https://www.transparenttextures.com/patterns/oriental-tiles.png');
            opacity: 0.05;
            pointer-events: none;
        }

        /* Custom Scrollbar Gold */
        ::-webkit-scrollbar { width: 8px; background: #FFFBF0; }
        ::-webkit-scrollbar-thumb { background: #B45309; border-radius: 0px; }

        /* Shapes & Frames */
        .img-gonjong { border-radius: 100px 100px 0 0; }
        .gold-border { border: 4px solid #D4AF37; box-shadow: 0 0 15px rgba(212, 175, 55, 0.3); }
        
        /* Petals/Glitter Container */
        #petal-container {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none; z-index: 50; overflow: hidden;
        }
        .petal {
            position: absolute;
            background-color: #D4AF37;
            border-radius: 50%;
            opacity: 0.6;
            animation: fall linear forwards;
        }
        @keyframes fall {
            to { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }

        /* Reveal Logic */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 1s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #D4AF37; }
    </style>
</head>

<body class="font-sans selection:bg-secondary selection:text-white">

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music_id) && $invitation->music ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3' }}" type="audio/mpeg">
    </audio>

    <div id="petal-container"></div>

    <div id="music-tooltip" class="fixed right-20 md:right-24 bottom-28 z-[60] bg-dark text-white p-3 px-5 border-l-4 border-accent shadow-2xl opacity-0 transition-all duration-500 pointer-events-none transform translate-x-4 flex items-center gap-4">
        <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-accent animate-spin-slow">
            <i class="fa-solid fa-compact-disc text-lg"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold text-accent uppercase tracking-[0.2em] leading-none font-sans">Alek Musik</p>
            <p class="font-serif italic text-sm mt-1">{{ !empty($invitation->music_id) && $invitation->music ? $invitation->music->title : 'Instrumental Minangkabau' }}</p>
        </div>
    </div>

    <div id="cover-page" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-primary transition-transform duration-1000 ease-in-out overflow-hidden songket-bg">
        <div class="absolute inset-0 z-0">
            <img src="{{ $coverImage }}" class="w-full h-full object-cover opacity-20 scale-110">
            <div class="absolute inset-0 bg-gradient-to-b from-primary/80 via-primary/60 to-primary"></div>
        </div>

        <div class="relative z-10 flex flex-col items-center text-center px-6 w-full max-w-2xl">
            <div class="mb-12 relative group">
                <div class="absolute -inset-6 border-2 border-accent/30 rounded-full animate-spin-slow"></div>
                <div class="absolute -inset-2 border-4 border-accent rounded-full shadow-[0_0_30px_rgba(212,175,55,0.5)]"></div>
                <div class="w-44 h-44 md:w-56 md:h-56 rounded-full overflow-hidden relative">
                    <img src="{{ $firstPerson['photo'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                </div>
            </div>

            <p class="font-royal text-accent text-sm md:text-base tracking-[0.5em] mb-4 uppercase">{{ $content['cover_badge'] ?? 'Baralek Gadang' }}</p>
            <h1 class="font-cursive text-7xl md:text-8xl text-white mb-2 drop-shadow-lg">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h1>
            <p class="font-sans font-bold text-xs uppercase tracking-[0.3em] text-white/70 mb-12">{{ $coverDateDisplay }}</p>
            
            <div class="bg-white/5 backdrop-blur-md border border-accent/30 p-8 rounded-3xl w-full mb-10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                    <i class="fa-solid fa-gem text-5xl text-accent"></i>
                </div>
                <p class="text-[10px] text-accent uppercase font-bold tracking-[0.4em] mb-3">{{ $content['cover_greeting'] ?? 'Kabau Sirih Untuak' }}</p>
                <p id="guest-name" class="font-serif text-3xl md:text-4xl text-white font-bold italic">{{ $guestNameDisplay }}</p>
            </div>

            <button onclick="openInvitation()" class="group relative bg-accent text-primary px-12 py-4 rounded-full font-bold uppercase tracking-widest text-xs shadow-[0_10px_30px_rgba(212,175,55,0.3)] overflow-hidden hover:scale-105 transition-all active:scale-95">
                <span class="relative z-10 flex items-center gap-3">Manjalang Alek <i class="fa-solid fa-chevron-right"></i></span>
            </button>
        </div>
    </div>

    <main id="main-content" class="min-h-screen opacity-0 transition-opacity duration-1000 pb-24 w-full overflow-hidden">

        <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b-2 border-accent p-4 md:px-12 flex justify-between items-center transition-all duration-300" id="main-nav">
            <div class="flex items-center gap-3">
                <div class="bg-primary px-3 py-1 rounded">
                    <span class="font-royal text-accent text-xl font-bold">{{ $firstPerson['nickname'] }}&{{ $secondPerson['nickname'] }}</span>
                </div>
            </div>
            <div class="flex items-center gap-4 text-primary">
                <i class="fa-solid fa-heart animate-pulse"></i>
            </div>
        </nav>

        <section id="home" class="relative h-screen flex flex-col items-center justify-center px-6 overflow-hidden w-full songket-bg">
            <div class="absolute inset-0 z-0">
                <img src="{{ $coverImage }}" class="w-full h-full object-cover opacity-10">
                <div class="absolute inset-0 bg-gradient-to-t from-bg via-transparent to-bg"></div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto w-full text-center flex flex-col items-center">
                <div class="mb-12 reveal">
                    <div class="relative inline-block">
                        <div class="absolute -inset-4 border border-accent rotate-3 opacity-50"></div>
                        <img src="{{ $coverImage }}" class="w-64 h-80 object-cover img-gonjong gold-border">
                    </div>
                </div>
                
                <p class="font-royal text-secondary text-sm tracking-[0.5em] mb-4 uppercase reveal">{{ $content['home_subtitle'] ?? 'Pesta Pernikahan' }}</p>
                <h2 class="font-cursive text-7xl md:text-9xl text-primary mb-8 reveal">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h2>
                
                <div class="reveal flex flex-col items-center">
                    <div class="h-px w-32 bg-accent mb-8"></div>
                    <div class="bg-white border-2 border-accent px-10 py-3 rounded-full shadow-xl mb-12">
                        <p class="font-serif italic text-xl text-dark">{{ $coverDateHuman }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 justify-center reveal">
                    <button onclick="openRSVP()" class="bg-primary text-white px-10 py-4 rounded-full font-bold uppercase tracking-widest text-[10px] shadow-2xl hover:bg-dark transition-all">
                        RSVP Kehadiran
                    </button>
                </div>
            </div>
        </section>

        <section id="cast" class="py-32 px-6 md:px-20 bg-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-transparent via-accent to-transparent opacity-50"></div>
            
            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-24 reveal">
                    <h3 class="font-royal text-5xl text-primary mb-4">Anak Daro & Marapulai</h3>
                    <div class="flex items-center justify-center gap-4">
                        <div class="h-px w-12 bg-accent"></div>
                        <i class="fa-solid fa-crown text-accent"></i>
                        <div class="h-px w-12 bg-accent"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 lg:gap-32 max-w-5xl mx-auto items-center">
                    <div class="flex flex-col items-center text-center group reveal">
                        <div class="relative mb-10 p-4">
                            <div class="absolute inset-0 border-2 border-accent rounded-full rotate-6 group-hover:rotate-0 transition-transform"></div>
                            <img src="{{ $firstPerson['photo'] }}" class="w-64 h-64 md:w-80 md:h-80 object-cover rounded-full border-8 border-white shadow-2xl relative z-10 grayscale hover:grayscale-0 transition-all duration-700">
                        </div>
                        <span class="text-accent text-[10px] uppercase tracking-[0.4em] font-bold mb-4 block">{{ $firstPerson['label'] }}</span>
                        <h4 class="font-serif text-3xl md:text-4xl text-primary font-bold mb-4">{{ $firstPerson['name'] }}</h4>
                        <p class="font-sans font-medium text-gray-500 leading-relaxed max-w-xs">{{ $firstPerson['gender_text'] }} dari Bapak {{ $firstPerson['father'] }} <br> & Ibu {{ $firstPerson['mother'] }}</p>
                        @if(!empty($firstPerson['ig']))
                        <a href="https://instagram.com/{{ str_replace('@', '', $firstPerson['ig']) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-xs text-primary font-bold"><i class="fa-brands fa-instagram"></i> {{ $firstPerson['ig'] }}</a>
                        @endif
                    </div>

                    <div class="flex flex-col items-center text-center group reveal">
                        <div class="relative mb-10 p-4">
                            <div class="absolute inset-0 border-2 border-accent rounded-full -rotate-6 group-hover:rotate-0 transition-transform"></div>
                            <img src="{{ $secondPerson['photo'] }}" class="w-64 h-64 md:w-80 md:h-80 object-cover rounded-full border-8 border-white shadow-2xl relative z-10 grayscale hover:grayscale-0 transition-all duration-700">
                        </div>
                        <span class="text-accent text-[10px] uppercase tracking-[0.4em] font-bold mb-4 block">{{ $secondPerson['label'] }}</span>
                        <h4 class="font-serif text-3xl md:text-4xl text-primary font-bold mb-4">{{ $secondPerson['name'] }}</h4>
                        <p class="font-sans font-medium text-gray-500 leading-relaxed max-w-xs">{{ $secondPerson['gender_text'] }} dari Bapak {{ $secondPerson['father'] }} <br> & Ibu {{ $secondPerson['mother'] }}</p>
                        @if(!empty($secondPerson['ig']))
                        <a href="https://instagram.com/{{ str_replace('@', '', $secondPerson['ig']) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-xs text-primary font-bold"><i class="fa-brands fa-instagram"></i> {{ $secondPerson['ig'] }}</a>
                        @endif
                    </div>
                </div>

                @if (($content['is_turut_mengundang_active'] ?? false) && !empty($content['turut_mengundang']))
                    <div class="mt-24 max-w-4xl mx-auto p-12 bg-bg rounded-[2.5rem] border-2 border-accent/20 text-center reveal shadow-xl">
                        <p class="font-royal text-secondary text-xs uppercase tracking-[0.3em] mb-8">Turut Mengundang</p>
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
        <section id="cerita" class="py-32 px-6 bg-bg relative overflow-hidden w-full">
            <div class="absolute inset-0 z-0 opacity-5">
                <img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?auto=format&fit=crop&w=2000&q=80" class="w-full h-full object-cover">
            </div>

            <div class="text-center mb-24 relative z-10 reveal">
                <span class="font-royal text-secondary text-xs uppercase tracking-[0.5em] block mb-4">Carito Cinto</span>
                <h3 class="font-serif italic text-5xl md:text-6xl text-primary font-bold">Kisah Perjalanan Kami</h3>
            </div>

            <div class="space-y-24 max-w-5xl mx-auto relative z-10">
                @foreach ($content['love_stories'] as $index => $story)
                @php $isLeft = $index % 2 === 0; @endphp
                <div class="flex flex-col {{ $isLeft ? 'md:flex-row' : 'md:flex-row-reverse' }} gap-10 items-center bg-white p-6 md:p-8 rounded-[2rem] border-2 border-accent/20 shadow-xl reveal group">
                    <div class="w-full md:w-1/2 overflow-hidden rounded-2xl shadow-lg border-4 border-white">
                        @if(!empty($story['image']))
                        <img src="{{ asset('storage/' . $story['image']) }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-700">
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 text-center {{ $isLeft ? 'md:text-left' : 'md:text-right' }}">
                        <div class="inline-block bg-primary text-accent px-4 py-1 rounded text-[10px] font-bold uppercase mb-4">{{ $story['year'] ?? '' }}</div>
                        <h5 class="font-serif text-2xl text-primary mb-4 font-bold">{{ $story['title'] ?? '' }}</h5>
                        <p class="text-sm text-gray-600 leading-relaxed italic">"{!! nl2br(e($story['description'] ?? '')) !!}"</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if ($content['is_gallery_active'] ?? false)
        <section id="gallery" class="py-32 bg-white w-full relative overflow-hidden">
            <div class="px-6 md:px-20 text-center mb-20 relative z-10 reveal">
                <h3 class="text-6xl font-cursive text-primary mb-4">Galeri Kenangan</h3>
                <span class="text-secondary text-[10px] font-bold uppercase tracking-[0.3em] block">Saksi Bisu Cinto Kami</span>
            </div>

            @php $youtubeLink = $content['youtube_links'][0] ?? null; @endphp
            @if ($youtubeLink)
            <div class="px-6 md:px-20 mb-20 max-w-5xl mx-auto relative z-10 reveal">
                <div class="relative w-full aspect-video rounded-[2.5rem] overflow-hidden bg-primary p-3 shadow-2xl gold-border group">
                    <div class="w-full h-full rounded-[2rem] overflow-hidden relative">
                        <iframe class="absolute inset-0 w-full h-full" src="{{ str_replace('watch?v=', 'embed/', $youtubeLink) }}" title="Wedding Trailer" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            @endif

            <div class="px-6 md:px-20 max-w-7xl mx-auto relative z-10 reveal">
                <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
                    @foreach ($invitation->galleries ?? [] as $index => $gallery)
                    <div class="break-inside-avoid group cursor-pointer relative overflow-hidden img-gonjong gold-border shadow-xl transition-all duration-500" onclick="openLightbox({{ $index }})">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" class="gallery-img w-full h-auto object-cover group-hover:scale-110 transition-transform duration-1000 grayscale hover:grayscale-0">
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <section id="lokasi" class="py-32 px-6 bg-primary relative overflow-hidden w-full text-white">
            <div class="absolute inset-0 opacity-20">
                <img src="{{ $coverImage }}" class="w-full h-full object-cover">
            </div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-24 reveal">
                    <h3 class="font-royal text-5xl text-accent mb-4 italic uppercase tracking-widest">Waktu & Lokasi</h3>
                    <p class="text-white/70 font-medium">Tampek Alek Digala</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto items-start">
                    <div class="bg-white/10 backdrop-blur-md p-10 md:p-12 rounded-[3rem] border border-accent/30 shadow-2xl reveal group">
                        <div class="bg-accent text-primary px-6 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-widest mb-8 inline-block shadow-lg">Ijab Qabul</div>
                        <h4 class="font-serif text-3xl text-accent mb-8 font-bold uppercase">Akad Nikah</h4>
                        <div class="space-y-6 mb-10 text-white italic">
                            <div class="flex items-center gap-4 border-b border-white/10 pb-4"><i class="fa-regular fa-calendar text-accent text-xl"></i> {{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</div>
                            <div class="flex items-center gap-4 border-b border-white/10 pb-4"><i class="fa-regular fa-clock text-accent text-xl"></i> {{ $content['akad_time'] ?? '08:00' }} - {{ $content['akad_time_end'] ?? 'Selesai' }} WIB</div>
                            <div class="flex items-start gap-4">
                                <i class="fa-solid fa-location-dot text-accent text-xl mt-1"></i> 
                                <span class="font-sans not-italic">{{ $content['akad_location'] ?? 'Kediaman Mempelai Wanita' }}<br><span class="text-xs text-white/60">{{ $content['akad_address'] ?? '' }}</span></span>
                            </div>
                        </div>
                        <a href="{{ $content['akad_map'] ?? '#' }}" target="_blank" class="block w-full text-center py-4 bg-accent text-primary rounded-full font-bold text-[11px] uppercase tracking-widest hover:bg-white transition-all shadow-xl">Lihat Peta Lokasi</a>
                    </div>

                    @if (!empty($content['events']) && is_array($content['events']))
                        @foreach ($content['events'] as $index => $evt)
                        <div class="bg-white/10 backdrop-blur-md p-10 md:p-12 rounded-[3rem] border border-accent/30 shadow-2xl reveal group lg:mt-16">
                            <div class="bg-accent text-primary px-6 py-1.5 rounded-full font-bold text-[10px] uppercase tracking-widest mb-8 inline-block shadow-lg">{{ $evt['title'] ?? 'Baralek Gadang' }}</div>
                            <h4 class="font-serif text-3xl text-accent mb-8 font-bold uppercase">{{ $evt['title'] ?? 'Resepsi Pernikahan' }}</h4>
                            <div class="space-y-6 mb-10 text-white italic">
                                <div class="flex items-center gap-4 border-b border-white/10 pb-4"><i class="fa-regular fa-calendar text-accent text-xl"></i> {{ !empty($evt['date']) ? \Carbon\Carbon::parse($evt['date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</div>
                                <div class="flex items-center gap-4 border-b border-white/10 pb-4"><i class="fa-regular fa-clock text-accent text-xl"></i> {{ $evt['time'] ?? '11:00' }} - {{ $evt['time_end'] ?? 'Selesai' }} WIB</div>
                                <div class="flex items-start gap-4">
                                    <i class="fa-solid fa-location-dot text-accent text-xl mt-1"></i> 
                                    <span class="font-sans not-italic">{{ $evt['location'] ?? 'Grand Ballroom Hotel Santika' }}<br><span class="text-xs text-white/60">{{ $evt['address'] ?? '' }}</span></span>
                                </div>
                            </div>
                            <a href="{{ $evt['map_link'] ?? '#' }}" target="_blank" class="block w-full text-center py-4 bg-accent text-primary rounded-full font-bold text-[11px] uppercase tracking-widest hover:bg-white transition-all shadow-xl">Lihat Peta Lokasi</a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        @if ($content['is_livestream_active'] ?? false)
        <section id="live-streaming" class="py-32 px-6 bg-white w-full border-y border-borderLight relative overflow-hidden">
            <div class="absolute top-0 right-0 p-10 opacity-5 text-primary"><i class="fa-solid fa-mosque text-[200px]"></i></div>
            
            <div class="max-w-4xl mx-auto text-center reveal relative z-10">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-8 border border-accent shadow-inner">
                    <i class="fa-solid fa-video text-3xl text-primary"></i>
                </div>
                <h2 class="font-royal text-4xl text-primary mb-6 uppercase tracking-widest">Siaran Langsung Alek</h2>
                <p class="text-gray-500 italic mb-12 max-w-lg mx-auto">"Saksikan momen sakral kami secara virtual melalui platform pilihan Anda."</p>

                <div id="streaming-display" class="bg-primary p-4 rounded-[3rem] shadow-2xl max-w-2xl mx-auto transition-transform duration-500 overflow-hidden relative group hover:scale-[1.02]">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/oriental-tiles.png')]"></div>
                    <div class="p-12 flex flex-col items-center relative z-10">
                        <i id="platform-icon" class="fa-brands fa-youtube text-7xl text-accent mb-6 animate-pulse"></i>
                        <h3 id="platform-title" class="font-royal text-3xl text-white mb-2 uppercase">YouTube Live</h3>
                        <p id="platform-desc" class="text-accent font-bold text-[10px] uppercase tracking-widest mb-10">Mulai Pukul 09.00 WIB</p>
                        <a id="platform-link" href="#" target="_blank" class="bg-accent text-primary px-12 py-4 rounded-full font-bold uppercase tracking-widest text-[11px] shadow-xl hover:bg-white transition-all">Tonton Siaran</a>
                    </div>
                </div>

                @if(!empty($content['live_streams']) && is_array($content['live_streams']))
                <div class="flex justify-center gap-4 mt-12">
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
                    <button onclick="switchPlatform('{{ $platform }}', '{{ $title }}', 'Live Broadcast', '{{ $iconClass }}', '{{ $stream['link'] ?? '#' }}')" class="w-12 h-12 bg-white border border-accent rounded-full text-primary hover:bg-primary hover:text-white flex items-center justify-center text-xl transition-all shadow-md"><i class="{{ $iconClass }}"></i></button>
                    @endforeach
                </div>
                @endif
            </div>
        </section>
        @endif

        <section id="guest-stats" class="py-24 px-6 bg-bg relative overflow-hidden w-full">
            <div class="max-w-5xl mx-auto relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-royal text-primary mb-4 uppercase">Kehadiran & Doa</h2>
                    <div class="h-1 w-20 bg-accent mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16 max-w-3xl mx-auto">
                    <div class="flex flex-col items-center p-10 bg-white border-2 border-accent rounded-[2.5rem] shadow-xl hover:shadow-2xl transition-all group">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-accent mb-6 shadow-inner group-hover:scale-110 transition-transform"><i class="fa-solid fa-user-check text-2xl"></i></div>
                        <h4 id="total-attendance" class="text-6xl font-serif text-primary mb-3 font-bold">{{ $totalAttendance }}</h4>
                        <p class="text-[10px] uppercase tracking-widest text-secondary font-bold">Tamu Hadir</p>
                    </div>
                    <div class="flex flex-col items-center p-10 bg-white border-2 border-accent rounded-[2.5rem] shadow-xl hover:shadow-2xl transition-all group">
                        <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-accent mb-6 shadow-inner group-hover:scale-110 transition-transform"><i class="fa-solid fa-envelope-open-text text-2xl"></i></div>
                        <h4 id="total-wishes" class="text-6xl font-serif text-primary mb-3 font-bold">{{ $totalWishes }}</h4>
                        <p class="text-[10px] uppercase tracking-widest text-secondary font-bold">Ucapan Hangat</p>
                    </div>
                </div>

                <div class="bg-white rounded-[3rem] border border-accent/30 shadow-2xl overflow-hidden max-w-4xl mx-auto w-full">
                    <div class="flex items-center justify-between p-8 border-b border-accent/20 bg-primary/5">
                        <div class="flex items-center gap-3 text-primary">
                            <i class="fa-solid fa-feather-pointed text-xl"></i>
                            <span class="text-xs font-bold uppercase tracking-[0.2em]">Papan Ucapan Doa</span>
                        </div>
                    </div>

                    <div id="wishes-container" class="max-h-[450px] overflow-y-auto scroll-custom p-8 space-y-6">
                        <!-- Wishes list rendered via JS -->
                    </div>

                    <div class="p-8 text-center border-t border-borderLight bg-white">
                        <button id="btn-load-more" onclick="renderWishes()" class="px-10 py-3 bg-primary text-accent border border-accent rounded-full font-bold text-[10px] uppercase tracking-[0.2em] shadow-lg hover:bg-dark transition-all">
                            Refresh Ucapan
                        </button>
                    </div>
                </div>
            </div>
        </section>

        @if ($content['is_gift_active'] ?? false)
        <section id="hadiah" class="py-24 px-6 bg-white border-t border-borderLight relative overflow-hidden w-full">
            <div class="max-w-4xl mx-auto relative z-10 text-center">
                <div class="mb-16 reveal">
                    <span class="text-[11px] tracking-[0.5em] uppercase text-secondary font-bold block mb-4 italic">Cando Kasiah</span>
                    <h2 class="text-5xl font-royal text-primary mb-6 uppercase">Tanda Kasih</h2>
                    <p class="text-sm text-gray-500 font-medium leading-relaxed max-w-xl mx-auto">
                        Doa restu sanak sudaro adolah hadiah paliang baharago untuak kami. Namun jikok nio mamberikan tanda kasih digital, buliah malalui rekening di bawah ko:
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-3xl mx-auto">
                    @if (!empty($content['banks']) && is_array($content['banks']))
                        @foreach ($content['banks'] as $index => $bank)
                        @php $logoUrl = $masterLogos[strtolower($bank['name'])] ?? null; @endphp
                        <div class="bg-bg border-2 border-accent p-12 rounded-[3rem] text-center hover:shadow-2xl transition-all group relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-1 bg-accent group-hover:h-full transition-all duration-700 -z-10 opacity-5"></div>
                            <div class="h-14 bg-white rounded-xl inline-flex items-center justify-center px-10 shadow-sm mb-8 border border-borderLight">
                                @if ($logoUrl)
                                <img src="{{ asset('storage/' . $logoUrl) }}" class="h-6 object-contain" alt="{{ $bank['name'] }}">
                                @else
                                <span class="font-bold text-dark text-sm">{{ strtoupper($bank['name']) }}</span>
                                @endif
                            </div>
                            <h4 id="rek-{{ $index }}" class="text-3xl font-royal text-primary tracking-widest mb-2">{{ $bank['account_number'] }}</h4>
                            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-10">a.n {{ $bank['account_name'] ?? $bank['account_owner'] ?? '' }}</p>
                            <button onclick="copyToClipboard('rek-{{ $index }}', this)" class="w-full py-4 bg-primary text-accent rounded-full font-bold text-[10px] uppercase tracking-widest shadow-xl hover:bg-dark transition-all">
                                Salin Nomor
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>

                <div id="copy-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[300] px-10 py-4 bg-primary text-accent rounded-full shadow-2xl border-2 border-accent font-bold text-[10px] uppercase tracking-widest opacity-0 translate-y-4 transition-all duration-500 pointer-events-none flex items-center gap-3">
                    <i class="fa-solid fa-check-circle"></i> Nomor Berhasil Tersalin
                </div>
            </div>
        </section>
        @endif

        <section id="kirim-kado" class="py-24 px-6 bg-white relative overflow-hidden w-full border-t border-borderLight">
            <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-10 items-center bg-bg border-2 border-accent p-10 md:p-12 rounded-[3.5rem] shadow-2xl w-full relative z-10">
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center text-accent text-3xl mx-auto md:mx-0 mb-8 shadow-xl"><i class="fa-solid fa-gift"></i></div>
                    <h2 class="text-4xl font-royal text-primary mb-4 uppercase">Kirim Kado</h2>
                    <p class="text-sm font-medium mb-10 text-gray-600 leading-relaxed italic">"Kasiah nan batakuak, kado nan dibawok." Jikok ingin mangirimkan kado fisik, silakan tuju alamat di bawah ko:</p>
                    <button onclick="toggleGiftModal(true)" class="bg-primary text-accent px-10 py-4 rounded-full font-bold text-[10px] uppercase tracking-widest hover:bg-dark transition-all shadow-xl">Daftar Kado</button>
                </div>
                
                <div class="w-full md:w-1/2 bg-white border-2 border-accent rounded-[2.5rem] p-10 text-center relative shadow-sm">
                    <div class="absolute -top-4 -right-4 bg-primary text-accent font-bold text-[9px] uppercase px-5 py-2 rounded shadow-lg z-10">Alamat Pangiriman</div>
                    <p id="alamat-kado" class="font-serif text-lg mb-10 leading-relaxed text-dark font-bold italic">{!! nl2br(e($content['alamat_kado'] ?? '')) !!}</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button onclick="copyToClipboard('alamat-kado', this)" class="bg-bg text-primary border border-accent px-6 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest hover:bg-primary hover:text-accent transition-all w-full sm:w-auto">
                            <i class="fa-regular fa-copy mr-2"></i> Salin Alamat
                        </button>
                    </div>
                </div>
            </div>

            <div id="gift-modal" class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/80 backdrop-blur-sm" onclick="toggleGiftModal(false)"></div>
                <div class="relative bg-white w-full max-w-lg rounded-[3rem] border-4 border-accent shadow-2xl flex flex-col max-h-[80vh]">
                    <div class="p-8 border-b-2 border-accent/20 flex justify-between items-center bg-bg rounded-t-[2.7rem]">
                        <div>
                            <h3 class="text-2xl font-royal text-primary uppercase">Wishlist Kami</h3>
                        </div>
                        <button onclick="toggleGiftModal(false)" class="w-10 h-10 bg-primary text-accent rounded-full flex items-center justify-center hover:bg-dark transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    
                    <div class="overflow-y-auto p-8 space-y-6 custom-scrollbar">
                        @if (!empty($content['gifts']) && is_array($content['gifts']))
                            @foreach ($content['gifts'] as $idx => $gift)
                            <div id="item-{{ $idx }}" class="p-6 rounded-3xl border border-accent/30 bg-bg flex flex-col sm:flex-row justify-between gap-6 items-center group hover:border-primary transition-all">
                                <div class="text-center sm:text-left">
                                    <h4 class="font-bold text-primary text-base uppercase">{{ $gift['item_name'] ?? '' }}</h4>
                                    <p class="text-[10px] text-gray-500 font-bold uppercase mt-1">{{ $gift['description'] ?? '' }}</p>
                                </div>
                                <button onclick="confirmGift('item-{{ $idx }}', '{{ addslashes($gift['item_name'] ?? '') }}')" class="px-8 py-3 bg-primary text-accent font-bold text-[10px] uppercase tracking-widest rounded-full hover:bg-dark transition-all w-full sm:w-auto shadow-lg">Pilih</button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div id="confirm-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-6">
                <div class="absolute inset-0 bg-dark/90 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
                <div class="relative bg-white w-full max-w-md p-8 md:p-10 rounded-[3rem] text-center shadow-2xl border-4 border-accent max-h-[90vh] overflow-y-auto custom-scrollbar">
                    <i class="fa-solid fa-heart-circle-check text-5xl text-primary mb-4 animate-pulse"></i>
                    <h4 class="text-2xl font-royal text-primary mb-2 uppercase">Niat Baiak</h4>
                    <p id="confirm-text" class="text-xs text-gray-600 mb-6 leading-relaxed font-medium italic"></p>
                    
                    <div class="space-y-6 text-left mb-6 font-sans">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-secondary mb-2">Nama Pengirim</label>
                            <input type="text" id="gift-confirm-name" class="w-full py-3 px-4 bg-bg border-2 border-accent rounded-2xl focus:outline-none text-sm text-dark font-medium" value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}" placeholder="Nama Anda">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-secondary mb-2">Status Kehadiran</label>
                            <div class="flex gap-4">
                                <button type="button" id="gift-btn-hadir" onclick="selectGiftAttendance('hadir')" class="flex-1 py-3 bg-primary text-accent border-2 border-accent rounded-full font-bold text-xs uppercase tracking-widest transition-colors">Hadir</button>
                                <button type="button" id="gift-btn-absen" onclick="selectGiftAttendance('tidak_hadir')" class="flex-1 py-3 bg-white border-2 border-accent text-primary rounded-full font-bold text-xs uppercase tracking-widest hover:bg-bg transition-colors">Tidak Hadir</button>
                            </div>
                            <input type="hidden" id="gift-confirm-status" value="hadir">
                        </div>

                        <div id="gift-confirm-pax-wrapper">
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-secondary mb-2">Jumlah Orang</label>
                            <div class="flex gap-3 mb-4">
                                <button type="button" onclick="setGiftPax(1)" class="gift-pax-btn flex-1 py-2.5 bg-primary text-accent border-2 border-accent rounded-full font-bold text-xs transition-colors">1</button>
                                <button type="button" onclick="setGiftPax(2)" class="gift-pax-btn flex-1 py-2.5 bg-white border border-accent/30 rounded-full font-bold text-xs text-secondary hover:text-primary transition-colors">2</button>
                                <button type="button" onclick="setGiftPax(3)" class="gift-pax-btn flex-1 py-2.5 bg-white border border-accent/30 rounded-full font-bold text-xs text-secondary hover:text-primary transition-colors">3</button>
                                <button type="button" onclick="setGiftPax('custom')" class="gift-pax-btn flex-1 py-2.5 bg-white border border-accent/30 rounded-full font-bold text-xs text-secondary hover:text-primary transition-colors">3+</button>
                            </div>
                            
                            <div id="gift-confirm-custom-pax-container" class="hidden">
                                <input type="number" id="gift-confirm-custom-pax-input" placeholder="Masukkan Jumlah Orang" class="w-full py-3 px-4 bg-white border-2 border-accent rounded-2xl focus:outline-none text-center font-bold text-sm">
                            </div>
                            <input type="hidden" id="gift-confirm-pax" value="1">
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                        <button id="final-confirm-btn" class="w-full py-3.5 bg-primary text-accent rounded-full font-bold text-[11px] uppercase tracking-widest shadow-xl">Yo, Ambo Satuju</button>
                        <button onclick="closeConfirmModal()" class="w-full py-3.5 bg-bg text-secondary border border-accent rounded-full font-bold text-[11px] uppercase tracking-widest">Batal</button>
                    </div>
                </div>
            </div>

            <div id="gift-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[700] px-10 py-5 bg-primary text-accent border-2 border-accent rounded-full shadow-2xl font-bold text-[11px] uppercase tracking-widest opacity-0 transition-all duration-500 text-center pointer-events-none">
                Tarimo Kasih Banyak!
            </div>
        </section>

        @if ($content['is_dresscode_active'] ?? true)
        <section id="guest-info" class="py-32 px-6 md:px-16 bg-[#1a0b0d] relative overflow-hidden w-full text-white">
            <div class="absolute top-0 left-0 w-full h-40 bg-gradient-to-b from-primary/50 to-transparent opacity-60"></div>
            
            <div class="max-w-6xl mx-auto relative z-10 w-full">

                <div class="mb-16 text-center md:text-left">
                    <h2 class="text-5xl md:text-6xl font-royal text-white mb-8 italic uppercase tracking-tighter">Informasi Tamu</h2>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-[10px] md:text-xs font-bold font-sans">
                        <span class="text-green-400 border border-green-400/30 bg-green-400/10 px-4 py-1.5 rounded-full uppercase tracking-widest">98% Match</span>
                        <span class="text-gray-300">2026</span>
                        <span class="px-3 py-1 border border-gray-500 text-gray-300 rounded-sm">17+</span>
                        <span class="text-gray-300">1 Alek Gadang</span>
                        <span class="px-3 py-1 border border-accent text-accent rounded-sm uppercase tracking-widest">Ultra HD 4K</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 w-full">
                    
                    <div class="lg:col-span-2 space-y-16 w-full">
                        <div class="bg-white/5 border border-white/10 p-10 rounded-[3rem] backdrop-blur-md relative overflow-hidden group">
                            <div class="absolute -right-10 -bottom-10 opacity-5 group-hover:opacity-10 transition-opacity"><i class="fa-solid fa-shirt text-[200px]"></i></div>
                            <div class="flex items-center gap-4 mb-6">
                                <span class="w-1 h-8 bg-accent rounded-full"></span>
                                <h4 class="text-gray-300 uppercase tracking-[0.4em] text-xs font-bold">Dresscode Specification</h4>
                            </div>
                            <p class="text-white text-xl md:text-3xl font-serif italic leading-relaxed">
                                <span class="font-bold font-sans text-sm uppercase text-accent block mb-4 not-italic tracking-[0.3em]">{{ $content['dresscode_title'] ?? 'Formal & Sopan' }}</span> 
                                "{!! nl2br(e($content['dresscode_desc'] ?? 'Your presence is our greatest gift. Kami mamanjek doa restu sanak sadonyo jo pakaian nan terbaik & sopan.')) !!}"
                            </p>
                        </div>

                        <div class="pt-8 border-t border-white/10">
                            <h5 class="text-gray-400 text-xs font-bold uppercase tracking-[0.3em] mb-10">Protokol Kasihatan</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 w-full">
                                <div id="protokol-cuci-tangan" class="flex flex-col items-center justify-center text-center gap-4 bg-white/5 p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <i class="fa-solid fa-hands-bubbles text-accent text-3xl"></i>
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">Cuci Tangan</span>
                                </div>
                                <div id="protokol-pakai-masker" class="flex flex-col items-center justify-center text-center gap-4 bg-white/5 p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <i class="fa-solid fa-head-side-mask text-accent text-3xl"></i>
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">Pakai Masker</span>
                                </div>
                                <div id="protokol-jaga-jarak" class="flex flex-col items-center justify-center text-center gap-4 bg-white/5 p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <i class="fa-solid fa-people-arrows text-accent text-3xl"></i>
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">Jaga Jarak</span>
                                </div>
                                <div id="protokol-hindari-kerumunan" class="flex flex-col items-center justify-center text-center gap-4 bg-white/5 p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <i class="fa-solid fa-users-slash text-accent text-3xl"></i>
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">No Kerumunan</span>
                                </div>
                                <div id="protokol-cek-suhu" class="flex flex-col items-center justify-center text-center gap-4 bg-white/5 p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <i class="fa-solid fa-temperature-high text-accent text-3xl"></i>
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">Cek Suhu</span>
                                </div>
                                <div id="protokol-desinfektan" class="flex flex-col items-center justify-center text-center gap-4 bg-white/5 p-8 rounded-3xl border border-white/10 hover:bg-white/10 transition-colors">
                                    <i class="fa-solid fa-spray-can-sparkles text-accent text-3xl"></i>
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">Desinfektan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-black/40 p-10 rounded-[3.5rem] border border-accent shadow-2xl h-max w-full">
                        <h5 class="text-accent text-[11px] font-bold uppercase tracking-[0.4em] flex items-center justify-center gap-3 mb-10 border-b border-white/10 pb-6">
                            <i class="fa-solid fa-book-open"></i> Adab Walimah
                        </h5>
                        <div class="space-y-8">
                            <div id="adab-sholat" class="flex gap-5 items-start">
                                <div class="text-accent text-2xl w-8 shrink-0 mt-0.5 text-center"><i class="fa-solid fa-mosque"></i></div>
                                <div><p class="text-white font-bold text-xs uppercase tracking-widest mb-1.5">Waktu Sholat</p><p class="text-gray-400 text-xs font-medium leading-relaxed">Mamparhatikan waktu ibadah katiko acara balangsuang.</p></div>
                            </div>
                            <div id="adab-makan-minum" class="flex gap-5 items-start">
                                <div class="text-accent text-2xl w-8 shrink-0 mt-0.5 text-center"><i class="fa-solid fa-utensils"></i></div>
                                <div><p class="text-white font-bold text-xs uppercase tracking-widest mb-1.5">Adab Makan</p><p class="text-gray-400 text-xs font-medium leading-relaxed">Makan & minum jo caro duduak nan sopan.</p></div>
                            </div>
                            <div id="adab-mendoakan" class="flex gap-5 items-start">
                                <div class="text-accent text-2xl w-8 shrink-0 mt-0.5 text-center"><i class="fa-solid fa-hands-praying"></i></div>
                                <div><p class="text-white font-bold text-xs uppercase tracking-widest mb-1.5">Doa Restu</p><p class="text-gray-400 text-xs font-medium leading-relaxed">Mamberikan doa kabaraka'an untuak kami baduo.</p></div>
                            </div>
                            <div id="adab-jaga-jarak" class="flex gap-5 items-start">
                                <div class="text-accent text-2xl w-8 shrink-0 mt-0.5 text-center"><i class="fa-solid fa-restroom"></i></div>
                                <div><p class="text-white font-bold text-xs uppercase tracking-widest mb-1.5">Jaga Jarak</p><p class="text-gray-400 text-xs font-medium leading-relaxed">Manjago batasan antaro tamu pria & wanita.</p></div>
                            </div>
                            <div id="adab-pakaian-sopan" class="flex gap-5 items-start">
                                <div class="text-accent text-2xl w-8 shrink-0 mt-0.5 text-center"><i class="fa-solid fa-shirt"></i></div>
                                <div><p class="text-white font-bold text-xs uppercase tracking-widest mb-1.5">Baju Sopan</p><p class="text-gray-400 text-xs font-medium leading-relaxed">Berbusana nan manutuik aurat dan rapi.</p></div>
                            </div>
                            <div id="adab-larangan-foto" class="flex gap-5 items-start">
                                <div class="text-accent text-2xl w-8 shrink-0 mt-0.5 text-center"><i class="fa-solid fa-video-slash"></i></div>
                                <div><p class="text-white font-bold text-xs uppercase tracking-widest mb-1.5">Izin Foto</p><p class="text-gray-400 text-xs font-medium leading-relaxed">Maminto izin sabalun mandokumentasikan momen.</p></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        @endif

        <section id="qr-tamu" class="py-24 px-6 md:px-20 bg-white relative border-t border-borderLight pb-40 w-full">
            <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center gap-12 bg-bg border-4 border-accent rounded-[3.5rem] p-10 md:p-16 shadow-2xl w-full relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary transform translate-x-16 -translate-y-16 rotate-45 opacity-5"></div>
                
                <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                    <div class="bg-white p-6 rounded-[2.5rem] border-2 border-accent shadow-inner mb-8">
                        <img id="qr-image" src="{{ $qrCodeUrl }}" class="w-44 h-44 rounded-xl" alt="QR Code">
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-primary">Akses Gate Masuk</p>
                </div>
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <h2 class="text-4xl font-royal text-primary mb-6 uppercase tracking-widest leading-tight">Digital Access Pass</h2>
                    <p class="text-sm font-medium text-gray-500 mb-10 leading-relaxed italic">"Silakan tunjukkan kode QR ko ka petugas di pintu masuak untuak verifikasi kehadiran sanak sadonyo."</p>
                    <div class="bg-white border-2 border-accent px-8 py-6 rounded-3xl shadow-md">
                        <p class="text-[9px] uppercase font-bold text-gray-400 tracking-[0.3em] mb-2">Identify Guest</p>
                        <h3 id="guest-name-qr" class="font-serif italic text-3xl text-primary font-bold uppercase">{{ $guestNameDisplay }}</h3>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-24 px-6 bg-primary text-center relative overflow-hidden border-t-8 border-accent w-full pb-40">
            <div class="max-w-2xl mx-auto relative z-10">
                <div class="bg-accent text-primary px-5 py-1 mb-8 inline-block shadow-lg">
                    <h2 class="font-royal text-3xl font-bold uppercase tracking-widest italic">R & J</h2>
                </div>
                <h3 class="text-white font-cursive text-6xl mb-8">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h3>
                <p class="font-serif italic text-white/70 text-lg mb-12 leading-relaxed">"Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir."</p>
                <div class="h-px w-24 bg-accent mx-auto mb-10 opacity-50"></div>
                <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank" class="inline-flex items-center gap-3 text-accent hover:text-white transition-colors bg-white/5 px-8 py-2.5 rounded-full border border-accent/30 font-bold text-[10px] uppercase tracking-widest">
                    <i class="fa-brands fa-instagram text-xl"></i> @ruangrestu.undangan
                </a>
            </div>
        </footer>

    </main>

    <section id="rsvp-modal" class="fixed inset-0 z-[100] invisible transition-all duration-500 flex items-end md:items-center justify-center w-full h-full overflow-hidden">
        <div onclick="closeRSVP()" class="absolute inset-0 bg-dark/80 backdrop-blur-md opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>
        <div id="rsvp-content" class="relative w-full md:max-w-xl bg-white rounded-t-[3.5rem] md:rounded-[3.5rem] shadow-2xl transform translate-y-full transition-transform duration-700 flex flex-col max-h-[90vh] border-t-8 border-accent">
            <div class="overflow-y-auto px-10 md:px-12 pb-12 pt-10 custom-scrollbar relative w-full">
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8 md:hidden"></div>
                <div class="flex justify-between items-center mb-10 border-b-2 border-primary/10 pb-6">
                    <h2 class="text-4xl font-cursive text-primary">Konfirmasi Alek</h2>
                    <button onclick="closeRSVP()" class="w-12 h-12 bg-bg rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-colors border border-accent"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <form id="form-rsvp" class="space-y-8 w-full">
                    <input type="hidden" id="input-status" value="Hadir">
                    <input type="hidden" id="input-guest-count" value="1">
                    <div>
                        <label class="font-bold text-[10px] uppercase tracking-[0.4em] text-secondary mb-3 block pl-4 italic">Namonyo Sanak</label>
                        <input type="text" id="input-nama-rsvp" value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}" placeholder="Isi namo sanak di siko..." class="w-full bg-bg border-2 border-accent/20 focus:border-primary p-5 rounded-full font-medium outline-none transition-colors shadow-inner text-sm italic">
                    </div>
                    
                    <div>
                        <label class="font-bold text-[10px] uppercase tracking-[0.4em] text-secondary mb-3 block pl-4 italic">Kabar Kehadiran</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir" class="py-4 border-2 border-accent/30 rounded-full bg-primary text-accent text-[11px] font-bold uppercase tracking-widest transition-all shadow-sm"><i class="fa-solid fa-check mr-2"></i> Ambo Hadir</button>
                            <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen" class="py-4 border-2 border-accent/30 rounded-full bg-white text-secondary text-[11px] font-bold uppercase tracking-widest hover:bg-primary hover:text-accent transition-all shadow-sm"><i class="fa-solid fa-xmark mr-2 text-red-600"></i> Ambo Absen</button>
                        </div>
                    </div>

                    <div id="guest-selection">
                        <div class="bg-primary/5 border border-accent/20 p-6 rounded-3xl">
                            <label class="font-bold text-[10px] uppercase tracking-[0.4em] text-primary mb-4 block text-center italic">Barapo Urang?</label>
                            <div class="flex gap-3">
                                <button type="button" onclick="setGuestCount(1)" class="guest-btn flex-1 py-3 bg-primary text-accent border border-accent/30 rounded-full font-bold text-xs shadow-sm transition-colors">1</button>
                                <button type="button" onclick="setGuestCount(2)" class="guest-btn flex-1 py-3 bg-white border border-accent/30 rounded-full font-bold text-xs text-secondary hover:text-primary shadow-sm transition-colors">2</button>
                                <button type="button" onclick="setGuestCount('custom')" class="guest-btn flex-1 py-3 bg-white border border-accent/30 rounded-full font-bold text-xs text-secondary hover:text-primary shadow-sm transition-colors">3+</button>
                            </div>
                            <div id="custom-pax-container" class="hidden mt-4">
                                <input type="number" id="custom-pax-input" placeholder="Masukkan jumlah tamu" class="w-full bg-white border border-accent/30 p-4 rounded-full font-medium outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-[10px] uppercase tracking-[0.4em] text-secondary mb-3 block pl-4 italic">Doa Panuahi Kasiah</label>
                        <textarea id="input-pesan-rsvp" rows="4" placeholder="Tuliskan doa restu sanak di siko..." class="w-full bg-bg border-2 border-accent/20 focus:border-primary p-5 rounded-[2.5rem] font-medium outline-none resize-none transition-colors shadow-inner text-sm italic"></textarea>
                    </div>

                    <div class="pt-4 flex flex-col gap-4">
                        <button type="submit" class="w-full py-5 bg-primary text-accent rounded-full font-bold text-[11px] uppercase tracking-[0.4em] shadow-2xl hover:scale-[1.02] transition-transform">Kirim Konfirmasi</button>
                        <button type="button" onclick="closeRSVP()" class="w-full py-4 bg-white text-secondary font-bold text-[11px] uppercase tracking-[0.3em] border border-accent/30 rounded-full">Tutuik</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div id="fab-container" class="fixed right-4 md:right-6 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000 w-auto">
        <div class="relative flex items-center group">
            <button id="btn-music" onclick="toggleMusic()" class="w-14 h-14 bg-white border-2 border-accent rounded-full flex items-center justify-center text-primary shadow-2xl hover:scale-110 transition-transform">
                <i class="fa-solid fa-music animate-spin-slow text-xl" id="icon-music"></i>
            </button>
        </div>
        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-14 h-14 bg-primary text-accent border-2 border-accent rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition-transform">
            <i class="fa-solid fa-angles-down text-xl" id="icon-scroll"></i>
        </button>
    </div>

    <div id="lightbox" class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-dark/95 backdrop-blur-md p-4 w-full h-full">
        <div class="w-full flex justify-between items-center p-6 absolute top-0">
            <span class="bg-primary border-2 border-accent text-accent px-5 py-2 rounded-full text-xs font-bold tracking-[0.2em] uppercase"><span id="current-count">1</span> / <span id="total-count">4</span></span>
            <button onclick="closeLightbox()" class="w-12 h-12 bg-white rounded-full text-primary flex items-center justify-center hover:bg-accent transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <div class="flex items-center justify-center w-full h-[70vh] px-4 mt-8">
            <img id="lightbox-img" src="" class="max-h-full max-w-full border-[10px] border-white shadow-2xl object-contain transition-opacity duration-300">
        </div>
        <div class="absolute bottom-8 flex gap-6 bg-white/10 p-2 rounded-full backdrop-blur-md border border-white/20">
            <button onclick="prevImg()" class="w-14 h-14 bg-white rounded-full text-primary flex items-center justify-center hover:bg-accent transition-all shadow-lg"><i class="fa-solid fa-chevron-left text-lg"></i></button>
            <button onclick="nextImg()" class="w-14 h-14 bg-white rounded-full text-primary flex items-center justify-center hover:bg-accent transition-all shadow-lg"><i class="fa-solid fa-chevron-right text-lg"></i></button>
        </div>
    </div>

    <nav id="bottom-nav" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-white/90 backdrop-blur-lg border-2 border-accent rounded-full shadow-2xl p-1.5 flex shadow-[0_10px_30px_rgba(127,29,29,0.3)]">
            <ul class="flex justify-around items-center h-12 w-[320px] md:w-[400px]">
                <li class="h-full"><a href="#home" class="flex items-center justify-center w-14 h-full text-primary hover:bg-bg rounded-full transition-colors"><i class="fa-solid fa-house text-lg"></i></a></li>
                <li class="h-full"><a href="#gallery" class="flex items-center justify-center w-14 h-full text-primary hover:bg-bg rounded-full transition-colors"><i class="fa-solid fa-image text-lg"></i></a></li>
                <li class="h-full"><a href="#lokasi" class="flex items-center justify-center w-14 h-full text-primary hover:bg-bg rounded-full transition-colors"><i class="fa-solid fa-location-dot text-lg"></i></a></li>
                <li class="h-full px-2 flex items-center">
                    <a href="javascript:void(0)" onclick="openRSVP()" class="flex items-center justify-center px-8 h-9 bg-primary text-accent rounded-full text-[10px] font-bold uppercase tracking-[0.3em] hover:bg-dark transition-all border border-accent shadow-md">RSVP</a>
                </li>
            </ul>
        </div>
    </nav>

    <script>
        // Data & UI Setup
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
            
            const musicInfo = document.getElementById('music-tooltip');
            setTimeout(() => {
                musicInfo.classList.remove('opacity-0', 'translate-x-4');
                musicInfo.classList.add('opacity-100', 'translate-x-0');
                setTimeout(() => {
                    musicInfo.classList.remove('opacity-100', 'translate-x-0');
                    musicInfo.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => musicInfo.style = '', 500);
                }, 5000);
            }, 2000);
        }

        // Hover Music Tooltip
        document.getElementById('btn-music').addEventListener('mouseenter', () => {
            document.getElementById('music-tooltip').classList.remove('opacity-0', 'translate-x-4');
            document.getElementById('music-tooltip').classList.add('opacity-100', 'translate-x-0');
        });
        document.getElementById('btn-music').addEventListener('mouseleave', () => {
            document.getElementById('music-tooltip').classList.remove('opacity-100', 'translate-x-0');
            document.getElementById('music-tooltip').classList.add('opacity-0', 'translate-x-4');
        });

        // Floating Petals Animation
        function createPetal() {
            const container = document.getElementById('petal-container');
            if(!container) return;
            const petal = document.createElement('div');
            petal.classList.add('petal');
            const size = Math.random() * 8 + 4;
            petal.style.width = `${size}px`; petal.style.height = `${size}px`;
            petal.style.left = `${Math.random() * 100}vw`;
            petal.style.animationDuration = `${Math.random() * 4 + 6}s`;
            container.appendChild(petal);
            setTimeout(() => petal.remove(), 10000);
        }
        setInterval(createPetal, 1000);

        // Intersection Observer (Reveal on Scroll)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if(entry.isIntersecting) entry.target.classList.add('active'); });
        }, { threshold: 0.15 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

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
        function toggleAutoScroll() {
            const icon = document.getElementById('icon-scroll');
            if (isAutoScrolling) {
                clearInterval(scrollInterval); isAutoScrolling = false;
                icon.classList.replace('fa-pause', 'fa-angles-down');
            } else {
                isAutoScrolling = true;
                icon.classList.replace('fa-angles-down', 'fa-pause');
                scrollInterval = setInterval(() => {
                    window.scrollBy({ top: 1, behavior: 'auto' });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 5) toggleAutoScroll();
                }, 30);
            }
        }

        // Auto Open RSVP at Bottom
        let hasShownRSVPAtEnd = false;
        window.addEventListener('scroll', () => {
            const coverPage = document.getElementById('cover-page');
            if (coverPage && !coverPage.classList.contains('-translate-y-full')) {
                return;
            }
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 150) {
                if (!hasShownRSVPAtEnd) {
                    openRSVP(); hasShownRSVPAtEnd = true;
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
            btnHadir.classList.remove('bg-primary', 'text-accent'); btnAbsen.classList.remove('bg-primary', 'text-accent');
            if(status === 'Hadir') {
                btnHadir.classList.add('bg-primary', 'text-accent'); guestDiv.classList.remove('hidden');
                document.getElementById('input-guest-count').value = 1;
                setGuestCount(1);
            } else {
                btnAbsen.classList.add('bg-primary', 'text-accent'); guestDiv.classList.add('hidden');
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
                    btn.className = 'guest-btn flex-1 py-3 bg-primary text-accent border border-accent/30 rounded-full font-bold text-xs shadow-sm transition-colors';
                } else {
                    btn.className = 'guest-btn flex-1 py-3 bg-white border border-accent/30 rounded-full font-bold text-xs text-secondary hover:text-primary shadow-sm transition-colors';
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
                toast.classList.replace('opacity-0', 'opacity-100');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    toast.classList.replace('opacity-100', 'opacity-0');
                }, 2000);
            });
        }

        // Streaming Logic
        function switchPlatform(id, title, desc, iconClass, link) {
            const disp = document.getElementById('streaming-display');
            disp.style.opacity = '0';
            setTimeout(() => {
                document.getElementById('platform-title').innerText = title;
                document.getElementById('platform-desc').innerText = desc;
                document.getElementById('platform-icon').className = iconClass + ' text-7xl text-accent mb-6 animate-pulse';
                document.getElementById('platform-link').href = link;
                disp.style.opacity = '1';
            }, 300);
        }

        // Gift
        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if(show) modal.classList.remove('hidden'); else modal.classList.add('hidden');
        }
        function selectGiftAttendance(status) {
            document.getElementById('gift-confirm-status').value = status;
            const btnHadir = document.getElementById('gift-btn-hadir');
            const btnAbsen = document.getElementById('gift-btn-absen');
            const wrapper = document.getElementById('gift-confirm-pax-wrapper');
            
            btnHadir.className = "flex-1 py-3 bg-white border-2 border-accent text-primary rounded-full font-bold text-xs uppercase tracking-widest transition-colors";
            btnAbsen.className = "flex-1 py-3 bg-white border-2 border-accent text-primary rounded-full font-bold text-xs uppercase tracking-widest transition-colors";
            
            if (status === 'hadir') {
                btnHadir.className = "flex-1 py-3 bg-primary text-accent border-2 border-accent rounded-full font-bold text-xs uppercase tracking-widest transition-colors";
                wrapper.classList.remove('hidden');
                document.getElementById('gift-confirm-pax').value = 1;
                setGiftPax(1);
            } else {
                btnAbsen.className = "flex-1 py-3 bg-primary text-accent border-2 border-accent rounded-full font-bold text-xs uppercase tracking-widest transition-colors";
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
                btn.className = "gift-pax-btn flex-1 py-2.5 bg-white border border-accent/30 rounded-full font-bold text-xs text-secondary hover:text-primary transition-colors";
                if (text == count || (count === 'custom' && text === '3+')) {
                    btn.className = "gift-pax-btn flex-1 py-2.5 bg-primary text-accent border-2 border-accent rounded-full font-bold text-xs transition-colors";
                }
            });
        }
        let selId, selName;
        function confirmGift(id, name) {
            selId = id; selName = name;
            document.getElementById('confirm-text').innerHTML = `Apakah sanak yakin ingin mangirim <b>${name}</b> sabagai kado pernikahan untuak kami?`;
            
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
                setTimeout(() => { document.getElementById('gift-toast').style.opacity = '0'; toggleGiftModal(false); }, 3000);
            };
        }
        document.getElementById('gift-confirm-custom-pax-input')?.addEventListener('input', function() {
            document.getElementById('gift-confirm-pax').value = this.value || 4;
        });
        function closeConfirmModal() { document.getElementById('confirm-modal').classList.add('hidden'); }

        // Lightbox
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
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
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
                card.className = 'flex gap-5 p-6 rounded-3xl bg-bg border border-borderLight shadow-sm text-left';
                card.innerHTML = `
                    <div class="w-12 h-12 bg-primary/10 border border-primary/20 rounded-full shrink-0 flex items-center justify-center text-primary font-bold">
                        ${wish.nama.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="font-bold text-primary text-sm">${wish.nama}</h5>
                            <span class="text-[9px] font-bold uppercase px-3 py-0.5 border border-accent bg-white rounded-full text-secondary">${wish.status}</span>
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
                        <div id="temp-success" class="absolute inset-0 z-50 flex items-center justify-center bg-primary/95 backdrop-blur-sm">
                            <div class="text-center">
                                <i class="fa-solid fa-check-circle text-6xl text-white mb-4 animate-pulse"></i>
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
