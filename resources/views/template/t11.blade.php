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
            $coverDateDisplay = \Carbon\Carbon::parse($firstEvent['date'])->format('d.m.Y');
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
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=0058a3&bgcolor=FFFFFF&data=' . urlencode($qrData);

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
    <title>{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }} - IKEA Style Wedding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Oswald:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#f5f5f5', 
                        surface: '#ffffff',
                        primary: '#0058a3', // IKEA Blue
                        secondary: '#ffdb00', // IKEA Yellow
                        dark: '#111111', 
                        muted: '#999999',
                        borderLight: '#dfdfdf'
                    },
                    fontFamily: {
                        sans: ['"Noto Sans"', 'sans-serif'],
                        display: ['"Oswald"', 'sans-serif'],
                    },
                    animation: {
                        'pulse-fast': 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>

    <style>
        html, body { 
            max-width: 100vw; 
            overflow-x: hidden; 
            background-color: #f5f5f5; 
            color: #111111;
            -webkit-font-smoothing: antialiased;
        }

        /* Scrollbar IKEA */
        ::-webkit-scrollbar { width: 8px; background: #f5f5f5; }
        ::-webkit-scrollbar-thumb { background: #0058a3; border-radius: 0; }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f5f5f5; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #ffdb00; }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* IKEA UI Elements */
        .ikea-card {
            background: #ffffff;
            border: 1px solid #dfdfdf;
            transition: all 0.3s ease;
        }
        .ikea-card:hover {
            border-color: #0058a3;
            box-shadow: 0 8px 24px rgba(0,88,163,0.1);
        }

        .price-tag {
            background-color: #ffdb00;
            color: #111111;
            font-family: 'Oswald', sans-serif;
            padding: 4px 12px;
            display: inline-block;
            box-shadow: 3px 3px 0px #0058a3;
        }

        .ikea-btn {
            background-color: #0058a3;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.2s;
            border-radius: 4px;
        }
        .ikea-btn:hover { background-color: #111111; }
        .ikea-btn-yellow {
            background-color: #ffdb00;
            color: #111111;
            font-weight: 700;
            text-transform: uppercase;
            transition: all 0.2s;
            border-radius: 4px;
        }
        .ikea-btn-yellow:hover { background-color: #e5c500; }

        /* Masonry Grid */
        .masonry { column-count: 2; column-gap: 1rem; }
        @media (min-width: 768px) { .masonry { column-count: 3; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1rem; }

        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>

<body class="font-sans selection:bg-secondary selection:text-dark">

    <audio id="bg-music" loop>
        <source src="{{ !empty($invitation->music_id) && $invitation->music ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3' }}" type="audio/mpeg">
    </audio>

    <div id="music-tooltip" class="fixed right-20 md:right-24 bottom-28 z-[60] bg-primary text-white border-2 border-secondary p-3 px-5 shadow-[4px_4px_0px_#ffdb00] opacity-0 transition-all duration-500 pointer-events-none transform translate-x-4 flex items-center gap-3">
        <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center text-primary">
            <i class="fa-solid fa-play text-xs"></i>
        </div>
        <div>
            <p class="text-[9px] font-bold uppercase tracking-widest text-secondary leading-none">Soundtrack</p>
            <p class="font-bold text-xs mt-1">{{ !empty($invitation->music_id) && $invitation->music ? $invitation->music->title : 'Beautiful in White' }}</p>
        </div>
    </div>

    <div id="cover-page" class="fixed inset-0 z-[100] flex flex-col md:flex-row bg-white transition-transform duration-700 ease-in-out overflow-hidden">
        
        <div class="w-full md:w-2/3 h-1/2 md:h-full relative bg-bg">
            <img src="{{ $coverImage }}" class="w-full h-full object-cover">
            <div class="absolute top-6 left-6 price-tag text-2xl">{{ $content['cover_badge'] ?? 'NEW COLLECTION' }}</div>
        </div>

        <div class="w-full md:w-1/3 h-1/2 md:h-full bg-white p-8 md:p-12 flex flex-col justify-center relative z-10">
            <div class="bg-primary w-20 h-10 flex items-center justify-center mb-8">
                <span class="text-secondary font-display text-2xl tracking-tighter">LOVE</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-display text-primary uppercase mb-2 leading-none">{{ $firstPerson['nickname'] }} <br>& {{ $secondPerson['nickname'] }}</h1>
            <p class="font-bold text-xs uppercase tracking-widest text-muted mb-10">{{ $content['cover_theme_subtitle'] ?? 'The Wedding Catalog 2026' }}</p>
            
            <div class="border-t-2 border-primary pt-6 mb-10">
                <p class="text-[10px] text-muted uppercase font-bold tracking-widest mb-1">{{ $content['cover_recipient_prefix'] ?? 'Designed For:' }}</p>
                <p id="guest-name" class="text-2xl font-bold text-dark uppercase">{{ $guestNameDisplay }}</p>
            </div>

            <button onclick="openInvitation()" class="ikea-btn-yellow w-full py-4 text-sm flex items-center justify-center gap-3 shadow-[4px_4px_0px_#0058a3]">
                Buka Katalog <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <main id="main-content" class="min-h-screen opacity-0 transition-opacity duration-1000 pb-20 relative w-full overflow-hidden">

        <nav class="fixed top-0 w-full z-50 bg-primary border-b-4 border-secondary p-4 md:px-8 flex justify-between items-center transition-all duration-300" id="main-nav">
            <div class="flex items-center gap-3">
                <div class="bg-secondary px-3 py-1 font-display text-primary text-xl">LOVE</div>
                <h1 class="font-bold text-white text-xs tracking-widest uppercase hidden md:block">Wedding Catalog</h1>
            </div>
            <div class="flex items-center gap-4 text-secondary">
                <i class="fa-solid fa-heart animate-pulse-fast text-xl"></i>
            </div>
        </nav>

        <section id="home" class="min-h-screen pt-32 px-6 md:px-20 bg-white grid grid-cols-1 lg:grid-cols-2 gap-12 items-center border-b border-borderLight w-full">
            <div class="order-2 lg:order-1 reveal">
                <div class="price-tag text-2xl mb-6">{{ $coverDateDisplay }}</div>
                <h2 class="text-6xl md:text-8xl font-display text-primary mb-6 leading-none uppercase">{{ $content['home_title'] ?? 'Perfectly Together.' }}</h2>
                <p class="text-lg font-medium text-gray-600 mb-10 max-w-md">{!! nl2br(e($content['quotes'] ?? 'Koleksi momen terbaik untuk memulai hidup baru. Bergabunglah merayakan hari bahagia kami.')) !!}</p>
                
                <div class="flex flex-wrap gap-4">
                    <button onclick="openRSVP()" class="ikea-btn px-10 py-4 text-sm shadow-[4px_4px_0px_#ffdb00]">
                        RSVP Sekarang
                    </button>
                </div>
            </div>
            
            <div class="order-1 lg:order-2 reveal relative">
                <div class="w-full aspect-[4/5] bg-bg border border-borderLight p-4 relative">
                    <div class="absolute top-2 left-2 text-[10px] font-bold text-muted uppercase">Art. 001/HOME</div>
                    <img src="{{ $coverImage }}" class="w-full h-full object-cover">
                </div>
            </div>
        </section>

        <section id="cast" class="py-24 px-6 md:px-20 bg-bg w-full">
            <div class="max-w-6xl mx-auto relative z-10">
                <div class="mb-16 reveal flex flex-col md:flex-row justify-between items-end border-b-4 border-primary pb-4">
                    <h3 class="text-4xl md:text-5xl font-display text-primary uppercase">The Mempelai</h3>
                    <p class="font-bold text-xs uppercase tracking-widest text-muted hidden md:block">Product Details</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-16">
                    <div class="ikea-card p-6 md:p-8 flex flex-col group reveal">
                        <div class="w-full aspect-square bg-bg mb-6 relative overflow-hidden">
                            <span class="absolute top-3 right-3 bg-white px-2 py-1 text-[10px] font-bold border border-borderLight z-10">GROOM</span>
                            <img src="{{ $groom['photo'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Groom">
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">002.{{ strtoupper(substr($groom['nickname'], 0, 3)) }}.26</p>
                            <h4 class="text-3xl font-display text-dark uppercase mb-2">{{ $groom['name'] }}</h4>
                            <p class="text-sm text-gray-600 font-medium">{{ $groom['gender_text'] }} tercinta dari Bapak {{ $groom['father'] }} & Ibu {{ $groom['mother'] }}</p>
                            @if(!empty($groom['ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $groom['ig']) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-xs text-primary font-bold"><i class="fa-brands fa-instagram"></i> {{ $groom['ig'] }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="ikea-card p-6 md:p-8 flex flex-col group reveal">
                        <div class="w-full aspect-square bg-bg mb-6 relative overflow-hidden">
                            <span class="absolute top-3 right-3 bg-white px-2 py-1 text-[10px] font-bold border border-borderLight z-10">BRIDE</span>
                            <img src="{{ $bride['photo'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Bride">
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-1">003.{{ strtoupper(substr($bride['nickname'], 0, 3)) }}.26</p>
                            <h4 class="text-3xl font-display text-dark uppercase mb-2">{{ $bride['name'] }}</h4>
                            <p class="text-sm text-gray-600 font-medium">{{ $bride['gender_text'] }} tercinta dari Bapak {{ $bride['father'] }} & Ibu {{ $bride['mother'] }}</p>
                            @if(!empty($bride['ig']))
                            <a href="https://instagram.com/{{ str_replace('@', '', $bride['ig']) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-xs text-primary font-bold"><i class="fa-brands fa-instagram"></i> {{ $bride['ig'] }}</a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (($content['is_turut_mengundang_active'] ?? false) && !empty($content['turut_mengundang']))
                    <div class="mt-16 ikea-card p-8 bg-white reveal">
                        <p class="font-bold text-[10px] uppercase tracking-widest text-muted mb-6 text-center">Turut Mengundang</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center font-bold text-sm text-dark">
                            @foreach ($content['turut_mengundang'] as $tamu)
                                <p>{{ trim($tamu) }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        @if (($content['is_story_active'] ?? false) && !empty($content['love_stories']))
        <section id="cerita" class="py-24 px-6 bg-white border-y border-borderLight w-full">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16 reveal">
                    <span class="price-tag text-sm mb-4">ASSEMBLY GUIDE</span>
                    <h3 class="text-4xl md:text-5xl font-display text-primary uppercase">Kisah Perjalanan</h3>
                </div>

                <div class="space-y-12 relative before:absolute before:inset-0 before:left-8 md:before:left-1/2 before:-translate-x-px before:h-full before:w-1 before:bg-borderLight">
                    
                    @foreach ($content['love_stories'] as $index => $story)
                    <div class="relative flex flex-col md:flex-row gap-6 items-center reveal">
                        <div class="absolute left-8 md:left-1/2 w-8 h-8 bg-secondary border-4 border-white rounded-full transform -translate-x-1/2 flex items-center justify-center font-black text-xs">{{ $index + 1 }}</div>
                        <div class="w-full md:w-1/2 md:pr-12 md:text-right pl-20 md:pl-0">
                            <span class="text-[10px] font-bold tracking-widest uppercase text-muted block mb-1">{{ $story['year'] ?? '' }}</span>
                            <h5 class="text-2xl font-display text-primary uppercase mb-2">{{ $story['title'] ?? '' }}</h5>
                            <p class="text-sm text-gray-600 font-medium bg-bg p-4 border border-borderLight md:bg-transparent md:border-0 md:p-0">{!! nl2br(e($story['description'] ?? '')) !!}</p>
                        </div>
                        <div class="w-full md:w-1/2 pl-20 md:pl-12 hidden md:block">
                            @if(!empty($story['image']))
                            <img src="{{ asset('storage/' . $story['image']) }}" class="w-full h-40 object-cover border border-borderLight p-2 bg-white">
                            @endif
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </section>
        @endif

        @if ($content['is_gallery_active'] ?? false)
        <section id="gallery" class="py-24 bg-bg w-full border-b border-borderLight">
            <div class="px-6 md:px-20 max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6 reveal border-b-4 border-primary pb-4">
                    <h3 class="text-4xl md:text-5xl font-display text-primary uppercase leading-none">Inspiration <br>Gallery</h3>
                    <p class="text-xs font-bold text-muted uppercase tracking-widest">Koleksi Momen Bahagia</p>
                </div>

                @php $youtubeLink = $content['youtube_links'][0] ?? null; @endphp
                @if ($youtubeLink)
                <div class="mb-12 reveal">
                    <div class="w-full aspect-video bg-white border border-borderLight p-2 shadow-md">
                        <iframe class="w-full h-full" src="{{ str_replace('watch?v=', 'embed/', $youtubeLink) }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                @endif

                <div class="masonry reveal">
                    @foreach ($invitation->galleries ?? [] as $index => $gallery)
                    <div class="masonry-item ikea-card p-2 cursor-pointer relative group" onclick="openLightbox({{ $index }})">
                        <span class="absolute top-4 right-4 bg-secondary text-dark px-2 py-1 text-[8px] font-black uppercase z-10 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">View</span>
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" class="gallery-img w-full h-auto object-cover">
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <section id="lokasi" class="py-24 px-6 bg-white w-full">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="reveal">
                    <h3 class="text-5xl font-display uppercase text-primary mb-2">Store Locations</h3>
                    <p class="font-bold text-muted text-xs uppercase tracking-widest mb-10">Tempat Pelaksanaan Acara</p>
                    
                    <div class="space-y-6">
                        <div class="ikea-card p-8 flex flex-col sm:flex-row gap-6 items-start">
                            <div class="bg-bg w-16 h-16 flex items-center justify-center border border-borderLight shrink-0">
                               <i class="fa-solid fa-ring text-2xl text-primary"></i>
                            </div>
                            <div class="flex-1 w-full">
                                <h4 class="text-2xl font-display uppercase text-dark mb-4">Akad Nikah</h4>
                                <div class="space-y-2 text-sm font-medium text-gray-600 mb-6">
                                    <p><i class="fa-regular fa-calendar text-primary w-5"></i> {{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</p>
                                    <p><i class="fa-regular fa-clock text-primary w-5"></i> {{ $content['akad_time'] ?? '08:00' }} - {{ $content['akad_time_end'] ?? 'Selesai' }} WIB</p>
                                    <p><i class="fa-solid fa-location-dot text-primary w-5"></i> {{ $content['akad_location'] ?? 'Kediaman Mempelai' }}</p>
                                </div>
                                <a href="{{ $content['akad_map'] ?? '#' }}" target="_blank" class="ikea-btn w-full block text-center py-3 text-[10px]">Petunjuk Arah</a>
                            </div>
                        </div>

                        @if (!empty($content['events']) && is_array($content['events']))
                            @foreach ($content['events'] as $index => $evt)
                            <div class="ikea-card p-8 flex flex-col sm:flex-row gap-6 items-start">
                                <div class="bg-bg w-16 h-16 flex items-center justify-center border border-borderLight shrink-0">
                                    <i class="fa-solid fa-champagne-glasses text-2xl text-primary"></i>
                                </div>
                                <div class="flex-1 w-full">
                                    <h4 class="text-2xl font-display uppercase text-dark mb-4">{{ $evt['title'] ?? 'Resepsi' }}</h4>
                                    <div class="space-y-2 text-sm font-medium text-gray-600 mb-6">
                                        <p><i class="fa-regular fa-calendar text-primary w-5"></i> {{ !empty($evt['date']) ? \Carbon\Carbon::parse($evt['date'])->translatedFormat('l, d F Y') : 'Sabtu, 18 Juli 2026' }}</p>
                                        <p><i class="fa-regular fa-clock text-primary w-5"></i> {{ $evt['time'] ?? '11:00' }} - {{ $evt['time_end'] ?? 'Selesai' }}</p>
                                        <p><i class="fa-solid fa-location-dot text-primary w-5"></i> {{ $evt['location'] ?? 'Grand Ballroom Hotel' }}</p>
                                    </div>
                                    <a href="{{ $evt['map_link'] ?? '#' }}" target="_blank" class="{{ $index % 2 == 0 ? 'ikea-btn-yellow' : 'ikea-btn' }} w-full block text-center py-3 text-[10px]">Petunjuk Arah</a>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                <div class="reveal h-full min-h-[400px]">
                    <div class="w-full h-full ikea-card p-2 bg-white">
                        @php
                            $firstEvt = !empty($content['events']) ? collect($content['events'])->first() : null;
                            $mapUrl = $firstEvt['map_link'] ?? ($content['akad_map'] ?? null);
                        @endphp
                        @if ($mapUrl && str_contains($mapUrl, 'google.com/maps'))
                            <iframe src="{{ $mapUrl }}" class="w-full h-full min-h-[400px]" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @else
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d107.57311709235512!3d-6.903444341687889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" class="w-full h-full min-h-[400px]" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        @if ($content['is_livestream_active'] ?? false)
        <section id="live-streaming" class="py-24 px-6 bg-primary text-white w-full">
            <div class="max-w-4xl mx-auto text-center reveal">
                <div class="price-tag text-sm mb-6 inline-block">VIRTUAL SHOWROOM</div>
                <h2 class="text-4xl md:text-5xl font-display uppercase mb-6">Siaran Langsung</h2>
                <p class="text-white/80 font-medium mb-12">Saksikan momen sakral kami secara virtual melalui platform pilihan Anda.</p>

                <div id="streaming-display" class="bg-white p-2 border-4 border-secondary shadow-[8px_8px_0px_#111] max-w-2xl mx-auto transition-transform duration-500 hover:-translate-y-1">
                    <div class="bg-bg text-dark p-12 flex flex-col items-center">
                        <i id="platform-icon" class="fa-brands fa-youtube text-6xl text-primary mb-4"></i>
                        <h3 id="platform-title" class="text-3xl font-display uppercase mb-2">YouTube Live</h3>
                        <p id="platform-desc" class="text-muted font-bold text-[10px] uppercase tracking-widest mb-8">Mulai Pukul 09.00 WIB</p>
                        <a id="platform-link" href="#" class="ikea-btn px-10 py-4">Tonton Sekarang</a>
                    </div>
                </div>

                @if(!empty($content['live_streams']) && is_array($content['live_streams']))
                <div class="mt-12">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-white/50 mb-4">Pilih Platform</p>
                    <div class="flex justify-center gap-3">
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
                        <button onclick="switchPlatform('{{ $platform }}', '{{ $title }}', 'Live Broadcast', '{{ $iconClass }}', '{{ $stream['link'] ?? '#' }}')" class="w-12 h-12 bg-white text-primary rounded-sm hover:bg-secondary hover:text-dark flex items-center justify-center text-xl transition-all"><i class="{{ $iconClass }}"></i></button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </section>
        @endif

        <section id="guest-stats" class="py-24 px-6 bg-bg w-full border-b border-borderLight">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="reveal">
                    <h3 class="text-5xl font-display uppercase text-primary mb-4">Stock Info</h3>
                    <p class="font-bold text-muted text-xs uppercase tracking-widest mb-10">Kehadiran & Doa Restu</p>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="ikea-card p-8 text-center shadow-sm">
                            <h4 id="total-attendance" class="text-6xl font-display text-primary mb-2">{{ $totalAttendance }}</h4>
                            <p class="text-[10px] font-bold uppercase tracking-widest border-t border-borderLight pt-4">Tamu Hadir</p>
                        </div>
                        <div class="ikea-card p-8 text-center shadow-[4px_4px_0px_#0058a3] border-primary relative overflow-hidden">
                            <div class="absolute top-0 right-0 bg-secondary w-8 h-8 rounded-bl-full"></div>
                            <h4 id="total-wishes" class="text-6xl font-display text-primary mb-2">{{ $totalWishes }}</h4>
                            <p class="text-[10px] font-bold uppercase tracking-widest border-t border-borderLight pt-4">Ucapan Doa</p>
                        </div>
                    </div>
                </div>

                <div class="ikea-card p-6 h-[500px] flex flex-col reveal shadow-md">
                    <div class="flex items-center justify-between border-b-2 border-primary pb-4 mb-4">
                        <span class="font-bold uppercase tracking-widest text-[10px] text-primary"><i class="fa-solid fa-comments mr-2"></i> Product Reviews</span>
                        <button id="btn-load-more" onclick="renderWishes()" class="text-[10px] font-bold uppercase hover:text-primary transition-colors"><i class="fa-solid fa-rotate-right"></i> Refresh</button>
                    </div>
                    
                    <div id="wishes-container" class="flex-1 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                        <!-- Wishes list rendered via JS -->
                    </div>
                </div>
            </div>
        </section>

        @if ($content['is_gift_active'] ?? false)
        <section id="hadiah" class="py-24 px-6 bg-white w-full border-b border-borderLight">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16 reveal">
                    <span class="price-tag text-sm mb-4">PAYMENT OPTIONS</span>
                    <h2 class="text-4xl md:text-5xl font-display uppercase text-primary">Tanda Kasih</h2>
                    <p class="text-sm font-medium text-gray-600 mt-4 max-w-lg mx-auto">Doa restu adalah hadiah terbaik. Namun untuk tanda kasih digital, silakan gunakan metode pembayaran berikut.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if (!empty($content['banks']) && is_array($content['banks']))
                        @foreach ($content['banks'] as $index => $bank)
                        @php $logoUrl = $masterLogos[strtolower($bank['name'])] ?? null; @endphp
                        <div class="ikea-card p-8 text-center flex flex-col items-center justify-center reveal">
                            <div class="bg-bg border border-borderLight px-6 py-2 mb-6 w-32 h-12 flex items-center justify-center">
                                @if ($logoUrl)
                                <img src="{{ asset('storage/' . $logoUrl) }}" class="h-4 object-contain grayscale" alt="{{ $bank['name'] }}">
                                @else
                                <span class="font-bold text-dark text-sm">{{ strtoupper($bank['name']) }}</span>
                                @endif
                            </div>
                            <h3 id="rek-{{ $index }}" class="text-2xl font-display tracking-widest mb-1">{{ $bank['account_number'] }}</h3>
                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-8">A.n {{ $bank['account_name'] ?? $bank['account_owner'] ?? '' }}</p>
                            <button onclick="copyToClipboard('rek-{{ $index }}', this)" class="ikea-btn w-full py-3 text-[10px]">Salin Nomor</button>
                        </div>
                        @endforeach
                    @endif
                </div>

                <div id="copy-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[300] bg-dark text-white px-6 py-3 font-bold text-[10px] uppercase tracking-widest flex items-center gap-2 opacity-0 translate-y-4 transition-all pointer-events-none shadow-lg">
                    <i class="fa-solid fa-check text-secondary"></i> Tersalin!
                </div>
            </div>
        </section>
        @endif

        <section id="kirim-kado" class="py-24 px-6 bg-bg border-b border-borderLight w-full">
            <div class="max-w-4xl mx-auto ikea-card p-8 md:p-12 flex flex-col md:flex-row gap-10 items-center reveal shadow-[8px_8px_0px_#dfdfdf]">
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <i class="fa-solid fa-box-open text-4xl text-primary mb-6"></i>
                    <h2 class="text-3xl font-display uppercase text-dark mb-4">Delivery Service</h2>
                    <p class="text-sm font-medium text-gray-600 mb-8">Layanan pengiriman kado fisik langsung ke gudang cinta kami, atau cek daftar belanja wishlist.</p>
                    <button onclick="toggleGiftModal(true)" class="ikea-btn-yellow px-8 py-3 text-[10px] shadow-[3px_3px_0px_#0058a3]">Shopping List</button>
                </div>
                
                <div class="w-full md:w-1/2 bg-white border border-borderLight p-6 md:p-8 text-center relative shadow-sm">
                    <span class="absolute top-0 right-0 bg-primary text-white font-bold text-[8px] uppercase px-3 py-1">Delivery Address</span>
                    <p id="alamat-kado" class="font-sans font-medium text-sm mb-8 text-dark pt-4">{!! nl2br(e($content['alamat_kado'] ?? '')) !!}</p>
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button onclick="copyToClipboard('alamat-kado', this)" class="bg-bg border border-borderLight text-dark px-6 py-2.5 font-bold text-[10px] uppercase hover:bg-borderLight transition-all">
                            <i class="fa-regular fa-copy mr-2"></i> Salin
                        </button>
                    </div>
                </div>
            </div>

            <div id="gift-modal" class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/80 backdrop-blur-sm" onclick="toggleGiftModal(false)"></div>
                <div class="relative bg-bg w-full max-w-lg border border-borderLight flex flex-col max-h-[80vh] shadow-2xl">
                    <div class="p-6 border-b border-borderLight flex justify-between items-center bg-white">
                        <div>
                            <h3 class="text-2xl font-display uppercase text-primary">Shopping List</h3>
                            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mt-1">Product Wishlist</p>
                        </div>
                        <button onclick="toggleGiftModal(false)" class="w-8 h-8 bg-ikeaGray text-dark flex items-center justify-center hover:bg-primary hover:text-white transition-colors"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    
                    <div class="overflow-y-auto p-6 space-y-4 custom-scrollbar">
                        @if (!empty($content['gifts']) && is_array($content['gifts']))
                            @foreach ($content['gifts'] as $idx => $gift)
                            <div id="item-{{ $idx }}" class="ikea-card p-4 flex flex-col sm:flex-row justify-between gap-4 items-center">
                                <div class="text-center sm:text-left">
                                    <h4 class="font-bold text-dark text-sm uppercase">{{ $gift['item_name'] ?? '' }}</h4>
                                    <p class="text-[10px] text-gray-500 font-bold">{{ $gift['description'] ?? '' }}</p>
                                </div>
                                <button onclick="confirmGift('item-{{ $idx }}', '{{ addslashes($gift['item_name'] ?? '') }}')" class="ikea-btn px-6 py-2 text-[10px] w-full sm:w-auto">Add to Cart</button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div id="confirm-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-dark/80 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
                <div class="relative bg-white w-full max-w-md p-8 text-center border-t-8 border-secondary shadow-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
                    <i class="fa-solid fa-cart-arrow-down text-4xl text-primary mb-4"></i>
                    <h4 class="text-2xl font-display uppercase text-dark mb-2">Checkout Item</h4>
                    <p id="confirm-text" class="text-xs font-medium text-gray-600 mb-6"></p>
                    
                    <div class="space-y-6 text-left mb-6 font-sans">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-muted mb-2">Customer Name</label>
                            <input type="text" id="gift-confirm-name" class="w-full py-3 px-4 bg-bg border border-borderLight focus:outline-none focus:border-primary text-sm font-medium text-dark" value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}" placeholder="Your Name">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-muted mb-2">Attendance Status</label>
                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" id="gift-btn-hadir" onclick="selectGiftAttendance('hadir')" class="w-full py-3 border border-borderLight bg-primary text-white text-xs font-bold uppercase">Yes, Attend</button>
                                <button type="button" id="gift-btn-absen" onclick="selectGiftAttendance('tidak_hadir')" class="w-full py-3 border border-borderLight bg-white text-dark text-xs font-bold uppercase hover:bg-bg transition-all">No, Reject</button>
                            </div>
                            <input type="hidden" id="gift-confirm-status" value="hadir">
                        </div>

                        <div id="gift-confirm-pax-wrapper" class="bg-bg border border-borderLight p-4 flex flex-col gap-2">
                            <label class="block text-[10px] uppercase font-bold tracking-widest text-muted mb-1 font-bold">Number of Guests</label>
                            <div class="flex gap-2">
                                <button type="button" onclick="setGiftPax(1)" class="gift-pax-btn flex-1 py-2 bg-primary text-white border border-borderLight font-bold text-xs">1</button>
                                <button type="button" onclick="setGiftPax(2)" class="gift-pax-btn flex-1 py-2 bg-white border border-borderLight font-bold text-xs hover:border-primary transition-colors">2</button>
                                <button type="button" onclick="setGiftPax('custom')" class="gift-pax-btn flex-1 py-2 bg-white border border-borderLight font-bold text-xs hover:border-primary transition-colors">3+</button>
                            </div>
                            
                            <div id="gift-confirm-custom-pax-container" class="hidden mt-3">
                                <input type="number" id="gift-confirm-custom-pax-input" placeholder="Enter quantity" class="w-full bg-white border border-borderLight p-3 font-medium outline-none text-sm">
                            </div>
                            <input type="hidden" id="gift-confirm-pax" value="1">
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                        <button id="final-confirm-btn" class="ikea-btn-yellow w-full py-3 text-[10px]">Confirm Order</button>
                        <button onclick="closeConfirmModal()" class="bg-bg text-dark border border-borderLight w-full py-3 font-bold text-[10px] uppercase tracking-widest hover:bg-borderLight transition-all">Cancel</button>
                    </div>
                </div>
            </div>

            <div id="gift-toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-[700] px-8 py-3 bg-dark text-white font-bold text-[10px] uppercase tracking-widest opacity-0 transition-all duration-500 text-center pointer-events-none shadow-xl border-l-4 border-secondary">
                Item Added. Thank you!
            </div>
        </section>

        @if ($content['is_dresscode_active'] ?? true)
        <section id="guest-info" class="py-32 px-6 md:px-20 bg-white relative overflow-hidden w-full border-b-4 border-primary">
            <div class="max-w-6xl mx-auto relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6 reveal border-b-4 border-secondary pb-4">
                    <div>
                        <h2 class="text-5xl font-display uppercase text-primary leading-none">Terms & Services</h2>
                        <p class="text-sm font-bold text-muted uppercase tracking-widest mt-2">Panduan Informasi Tamu</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-[9px] font-black uppercase tracking-wider">
                        <span class="bg-green-600 text-white px-2 py-1 shadow-sm">100% Quality Match</span>
                        <span class="bg-bg border border-borderLight px-2 py-1 text-dark">Model {{ date('Y') }}</span>
                        <span class="bg-bg border border-borderLight px-2 py-1 text-dark">Age 17+</span>
                        <span class="bg-bg border border-borderLight px-2 py-1 text-dark">1 Unit</span>
                        <span class="bg-dark text-white px-2 py-1 shadow-sm">Premium 4K</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 w-full">
                    
                    <div class="lg:col-span-2 space-y-10">
                        <div class="ikea-card p-8 bg-primary text-white reveal">
                            <div class="flex items-center justify-between border-b border-white/20 pb-4 mb-4">
                                <h4 class="uppercase tracking-widest text-[10px] font-bold text-secondary">Dresscode Specification</h4>
                                <span class="bg-secondary text-primary px-2 py-0.5 text-[8px] font-black uppercase">Required</span>
                            </div>
                            <h3 class="text-2xl font-display uppercase mb-2">{{ $content['dresscode_title'] ?? 'Formal & Elegant' }}</h3>
                            <p class="font-medium text-sm text-white/80 leading-relaxed">
                                "{!! nl2br(e($content['dresscode_desc'] ?? 'Your presence is our greatest gift, your elegance completes our joy. Mohon hadir dengan busana terbaik bernuansa formal.')) !!}"
                            </p>
                        </div>

                        <div class="reveal">
                            <h5 class="text-dark text-xs font-black uppercase tracking-widest mb-6 inline-block bg-secondary px-3 py-1">Safety Guidelines (Prokes)</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div id="protokol-cuci-tangan" class="ikea-card p-4 flex flex-col items-center text-center gap-2 hover:border-primary group">
                                    <i class="fa-solid fa-hands-bubbles text-primary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-muted group-hover:text-primary">Wash Hands</span>
                                </div>
                                <div id="protokol-pakai-masker" class="ikea-card p-4 flex flex-col items-center text-center gap-2 hover:border-primary group">
                                    <i class="fa-solid fa-head-side-mask text-primary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-muted group-hover:text-primary">Wear Mask</span>
                                </div>
                                <div id="protokol-jaga-jarak-1" class="ikea-card p-4 flex flex-col items-center text-center gap-2 hover:border-primary group">
                                    <i class="fa-solid fa-people-arrows text-primary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-muted group-hover:text-primary">Keep Distance</span>
                                </div>
                                <div id="protokol-hindari-kerumunan" class="ikea-card p-4 flex flex-col items-center text-center gap-2 hover:border-primary group">
                                    <i class="fa-solid fa-users-slash text-primary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-muted group-hover:text-primary">No Crowds</span>
                                </div>
                                <div id="protokol-cek-suhu" class="ikea-card p-4 flex flex-col items-center text-center gap-2 hover:border-primary group">
                                    <i class="fa-solid fa-temperature-high text-primary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-muted group-hover:text-primary">Temp Check</span>
                                </div>
                                <div id="protokol-desinfektan" class="ikea-card p-4 flex flex-col items-center text-center gap-2 hover:border-primary group">
                                    <i class="fa-solid fa-spray-can-sparkles text-primary text-2xl group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-muted group-hover:text-primary">Sanitize</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ikea-card bg-bg h-full flex flex-col p-8 relative reveal">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-white border-l border-b border-borderLight flex items-center justify-center rounded-bl-full"><i class="fa-solid fa-info text-muted"></i></div>
                        <h5 class="text-primary text-xl font-display uppercase tracking-widest flex items-center gap-2 mb-6 border-b-2 border-primary pb-2 w-max">
                            Etiquette Manual
                        </h5>
                        <div class="space-y-4 overflow-y-auto pr-2 custom-scrollbar flex-1 max-h-[500px]">
                            <div id="adab-sholat" class="flex gap-4 bg-white border border-borderLight p-4 items-center">
                                <div class="text-primary text-lg w-6 shrink-0 text-center"><i class="fa-solid fa-mosque"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Waktu Sholat</p><p class="text-[10px] text-gray-500 font-medium">Memperhatikan waktu ibadah saat acara.</p></div>
                            </div>
                            <div id="adab-makan-minum" class="flex gap-4 bg-white border border-borderLight p-4 items-center">
                                <div class="text-primary text-lg w-6 shrink-0 text-center"><i class="fa-solid fa-utensils"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Adab Makan</p><p class="text-[10px] text-gray-500 font-medium">Makan & minum dengan cara duduk.</p></div>
                            </div>
                            <div id="adab-mendoakan" class="flex gap-4 bg-white border border-borderLight p-4 items-center">
                                <div class="text-primary text-lg w-6 shrink-0 text-center"><i class="fa-solid fa-hands-praying"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Doa Restu</p><p class="text-[10px] text-gray-500 font-medium">Memberikan doa keberkahan bagi kami.</p></div>
                            </div>
                            <div id="adab-jaga-jarak-2" class="flex gap-4 bg-white border border-borderLight p-4 items-center">
                                <div class="text-primary text-lg w-6 shrink-0 text-center"><i class="fa-solid fa-restroom"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Jaga Jarak</p><p class="text-[10px] text-gray-500 font-medium">Menjaga batasan antara tamu pria & wanita.</p></div>
                            </div>
                            <div id="adab-pakaian-sopan" class="flex gap-4 bg-white border border-borderLight p-4 items-center">
                                <div class="text-primary text-lg w-6 shrink-0 text-center"><i class="fa-solid fa-shirt"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Baju Sopan</p><p class="text-[10px] text-gray-500 font-medium">Berbusana menutup aurat dan rapi.</p></div>
                            </div>
                            <div id="adab-larangan-foto" class="flex gap-4 bg-white border border-borderLight p-4 items-center">
                                <div class="text-primary text-lg w-6 shrink-0 text-center"><i class="fa-solid fa-video-slash"></i></div>
                                <div><p class="font-bold text-[10px] uppercase text-dark">Izin Foto</p><p class="text-[10px] text-gray-500 font-medium">Meminta izin sebelum mendokumentasikan.</p></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        @endif

        <section id="qr-tamu" class="py-24 px-6 md:px-20 bg-bg w-full pb-40">
            <div class="max-w-2xl mx-auto flex flex-col items-center ikea-card p-10 relative z-10 reveal shadow-[8px_8px_0px_#dfdfdf]">
                <div class="text-center border-b-2 border-primary w-full pb-4 mb-8">
                    <h2 class="text-3xl font-display text-primary uppercase">Member Card</h2>
                    <p class="font-bold text-[10px] uppercase tracking-widest text-muted">Akses Gate Masuk</p>
                </div>

                <div class="border border-borderLight p-4 bg-white mb-6">
                    <img id="qr-image" src="{{ $qrCodeUrl }}" class="w-48 h-48" alt="QR">
                </div>
                
                <div class="bg-gray-100 w-full p-4 border border-borderLight text-center">
                    <p class="font-bold text-[9px] uppercase tracking-widest text-gray-500 mb-1">Customer Name</p>
                    <h3 id="guest-name-qr" class="text-2xl font-black text-dark uppercase">{{ $guestNameDisplay }}</h3>
                </div>
            </div>
        </section>

        <footer class="py-16 px-6 bg-dark text-white text-center w-full relative z-10">
            <div class="max-w-xl mx-auto flex flex-col items-center">
                <div class="bg-primary px-3 py-1 mb-6">
                    <h2 class="text-4xl font-display text-secondary italic tracking-tighter uppercase">LOVE</h2>
                </div>
                <h3 class="text-3xl font-display uppercase mb-2">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-10">Product of {{ date('Y') }}</p>
                
                <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank" class="border border-gray-600 px-6 py-2 flex items-center justify-center gap-3 hover:bg-white hover:text-dark transition-all text-xs font-bold uppercase tracking-widest mb-8">
                    <i class="fa-brands fa-instagram text-lg"></i> Follow @ruangrestu
                </a>
                
                <p class="font-sans text-[8px] font-bold uppercase text-gray-600 tracking-widest">© {{ date('Y') }}. All Rights Reserved. Assembled with care.</p>
            </div>
        </footer>

    </main>

    <section id="rsvp-modal" class="fixed inset-0 z-[100] invisible transition-all duration-500 overflow-hidden flex items-end md:items-center justify-center">
        <div onclick="closeRSVP()" class="absolute inset-0 bg-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-500" id="rsvp-overlay"></div>
        
        <div id="rsvp-content" class="relative w-full md:max-w-xl bg-white border-t-8 border-primary transform translate-y-full transition-transform duration-700 flex flex-col max-h-[90vh] p-2 shadow-2xl">
            
            <div class="overflow-y-auto p-6 md:p-8 custom-scrollbar relative w-full h-full">
                <div class="w-12 h-1.5 bg-borderLight rounded-full mx-auto mb-6 md:hidden"></div>
                <div class="w-full flex justify-end mb-2">
                    <button onclick="closeRSVP()" class="w-8 h-8 bg-bg border border-borderLight flex items-center justify-center hover:bg-primary hover:text-white transition-colors"><i class="fa-solid fa-xmark"></i></button>
                </div>
                
                <div class="text-left mb-8 border-b-2 border-primary pb-4">
                    <span class="price-tag text-[10px] mb-2 inline-block">CHECKOUT</span>
                    <h2 class="text-4xl font-display text-primary uppercase">RSVP Form</h2>
                </div>

                <form id="form-rsvp" class="space-y-6 text-left">
                    <input type="hidden" id="input-status" value="Hadir">
                    <input type="hidden" id="input-guest-count" value="1">
                    <div>
                        <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Full Name</label>
                        <input type="text" id="input-nama-rsvp" value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}" class="w-full bg-bg border border-borderLight focus:border-primary p-3 text-sm font-bold outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Attendance</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir" class="w-full py-3 border border-borderLight bg-primary text-white text-xs font-bold uppercase"><i class="fa-solid fa-check mr-2"></i> Yes, Attend</button>
                            <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen" class="w-full py-3 border border-borderLight bg-white text-dark text-xs font-bold uppercase hover:bg-bg transition-all"><i class="fa-solid fa-xmark mr-2 text-red-600"></i> No, Reject</button>
                        </div>
                    </div>

                    <div id="guest-selection">
                        <div class="bg-bg border border-borderLight p-4 flex flex-col gap-2">
                            <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-1 block">Quantity</label>
                            <div class="flex gap-2">
                                <button type="button" onclick="setGuestCount(1)" class="guest-btn flex-1 py-2 bg-primary text-white border border-borderLight font-bold text-xs">1</button>
                                <button type="button" onclick="setGuestCount(2)" class="guest-btn flex-1 py-2 bg-white border border-borderLight font-bold text-xs hover:border-primary transition-colors">2</button>
                                <button type="button" onclick="setGuestCount('custom')" class="guest-btn flex-1 py-2 bg-white border border-borderLight font-bold text-xs hover:border-primary transition-colors">3+</button>
                            </div>
                            <div id="custom-pax-container" class="hidden mt-4">
                                <input type="number" id="custom-pax-input" placeholder="Masukkan jumlah tamu" class="w-full bg-white border border-borderLight p-4 rounded-full font-medium outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-[10px] uppercase tracking-widest text-dark mb-2 block">Wishes / Notes</label>
                        <textarea id="input-pesan-rsvp" rows="3" class="w-full bg-bg border border-borderLight focus:border-primary p-3 text-sm font-medium outline-none resize-none transition-all"></textarea>
                    </div>

                    <div class="pt-4 flex flex-col gap-3">
                        <button type="submit" class="ikea-btn w-full py-4 text-xs">Submit Form</button>
                        <button type="button" onclick="closeRSVP()" class="w-full py-3 bg-white text-dark font-bold text-[10px] uppercase tracking-widest border border-borderLight hover:bg-bg transition-all">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <nav id="bottom-nav" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] transition-all duration-700 translate-y-32">
        <div class="bg-white border-2 border-dark shadow-[4px_4px_0px_#0058a3] p-1 flex">
            <ul class="flex justify-around items-center h-12 w-[300px] md:w-[350px]">
                <li class="h-full"><a href="#home" class="flex items-center justify-center w-12 h-full text-dark hover:bg-bg transition-colors"><i class="fa-solid fa-house"></i></a></li>
                <li class="h-full"><a href="#gallery" class="flex items-center justify-center w-12 h-full text-dark hover:bg-bg transition-colors"><i class="fa-solid fa-image"></i></a></li>
                <li class="h-full"><a href="#lokasi" class="flex items-center justify-center w-12 h-full text-dark hover:bg-bg transition-colors"><i class="fa-solid fa-location-dot"></i></a></li>
                <li class="h-full px-1 flex items-center">
                    <a href="javascript:void(0)" onclick="openRSVP()" class="flex items-center justify-center px-6 h-9 ikea-btn-yellow text-[10px]">RSVP</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="fab-container" class="fixed right-4 md:right-6 bottom-24 flex flex-col gap-3 z-40 opacity-0 transition-opacity duration-1000">
        
        <div class="relative flex items-center group">
            <button id="btn-music" onclick="toggleMusic()" class="w-10 h-10 md:w-12 md:h-12 bg-white border border-borderLight flex items-center justify-center text-primary shadow-md hover:bg-bg transition-colors">
                <i class="fa-solid fa-music animate-spin-slow text-sm md:text-base" id="icon-music"></i>
            </button>
        </div>

        <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-10 h-10 md:w-12 md:h-12 bg-white border border-borderLight flex items-center justify-center text-dark shadow-md hover:bg-bg transition-colors">
            <i class="fa-solid fa-angles-down text-sm md:text-base" id="icon-scroll"></i>
        </button>
    </div>

    <div id="lightbox" class="fixed inset-0 z-[200] hidden flex-col items-center justify-center bg-dark/95 backdrop-blur-sm p-4 w-full h-full">
        <div class="w-full flex justify-between items-center p-6 absolute top-0">
            <span class="price-tag text-xs"><span id="current-count">1</span> / <span id="total-count">4</span></span>
            <button onclick="closeLightbox()" class="w-10 h-10 bg-white text-dark flex justify-center items-center hover:bg-borderLight transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="flex items-center justify-center w-full h-[70vh] px-4 mt-8">
            <img id="lightbox-img" src="" class="max-h-full max-w-full border-4 border-white bg-white p-2 object-contain transition-opacity duration-300">
        </div>
        <div class="absolute bottom-8 flex gap-4">
            <button onclick="prevImg()" class="w-12 h-12 bg-white border border-borderLight rounded-full text-dark flex items-center justify-center hover:bg-bg transition-all"><i class="fa-solid fa-arrow-left"></i></button>
            <button onclick="nextImg()" class="w-12 h-12 bg-white border border-borderLight rounded-full text-dark flex items-center justify-center hover:bg-bg transition-all"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        let guestName = "{{ $guestNameDisplay }}";

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
                    setTimeout(() => { musicInfo.style = ''; }, 500);
                }, 3000);
            }, 1500);
        }

        document.getElementById('btn-music').addEventListener('mouseenter', () => {
            document.getElementById('music-tooltip').classList.remove('opacity-0', 'translate-x-4');
            document.getElementById('music-tooltip').classList.add('opacity-100', 'translate-x-0');
        });
        document.getElementById('btn-music').addEventListener('mouseleave', () => {
            document.getElementById('music-tooltip').classList.remove('opacity-100', 'translate-x-0');
            document.getElementById('music-tooltip').classList.add('opacity-0', 'translate-x-4');
        });

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

        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if(entry.isIntersecting) entry.target.classList.add('active'); });
        }, { threshold: 0.15 });
        reveals.forEach(el => observer.observe(el));

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
            btnHadir.classList.add('bg-white', 'text-dark', 'border-borderLight'); btnAbsen.classList.add('bg-white', 'text-dark', 'border-borderLight');
            
            if(status === 'Hadir') {
                btnHadir.classList.add('bg-primary', 'text-white', 'border-primary'); btnHadir.classList.remove('bg-white', 'text-dark', 'border-borderLight');
                guestDiv.classList.remove('hidden');
                document.getElementById('input-guest-count').value = 1;
                setGuestCount(1);
            } else {
                btnAbsen.classList.add('bg-primary', 'text-white', 'border-primary'); btnAbsen.classList.remove('bg-white', 'text-dark', 'border-borderLight');
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
                    btn.className = 'guest-btn flex-1 py-3 bg-primary text-white border border-primary font-bold text-xs';
                } else {
                    btn.className = 'guest-btn flex-1 py-3 bg-white border border-borderLight rounded-none font-bold text-xs text-secondary hover:text-primary shadow-sm transition-colors';
                }
            });
        }
        document.getElementById('custom-pax-input').addEventListener('input', function() {
            document.getElementById('input-guest-count').value = this.value || 3;
        });

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

        function switchPlatform(id, title, desc, iconClass, link) {
            document.getElementById('platform-title').innerText = title;
            document.getElementById('platform-desc').innerText = desc;
            document.getElementById('platform-icon').className = iconClass + ' text-6xl text-primary mb-4 animate-pulse-fast';
            document.getElementById('platform-link').href = link;
        }

        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if(show) modal.classList.remove('hidden'); else modal.classList.add('hidden');
        }
        function selectGiftAttendance(status) {
            document.getElementById('gift-confirm-status').value = status;
            const btnHadir = document.getElementById('gift-btn-hadir');
            const btnAbsen = document.getElementById('gift-btn-absen');
            const wrapper = document.getElementById('gift-confirm-pax-wrapper');
            
            btnHadir.className = "w-full py-3 border border-borderLight bg-white text-dark text-xs font-bold uppercase hover:bg-bg transition-all";
            btnAbsen.className = "w-full py-3 border border-borderLight bg-white text-dark text-xs font-bold uppercase hover:bg-bg transition-all";
            
            if (status === 'hadir') {
                btnHadir.className = "w-full py-3 border border-borderLight bg-primary text-white text-xs font-bold uppercase";
                wrapper.classList.remove('hidden');
                document.getElementById('gift-confirm-pax').value = 1;
                setGiftPax(1);
            } else {
                btnAbsen.className = "w-full py-3 border border-borderLight bg-primary text-white text-xs font-bold uppercase";
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
                btn.className = "gift-pax-btn flex-1 py-2 bg-white border border-borderLight font-bold text-xs hover:border-primary transition-colors text-dark";
                if (text == count || (count === 'custom' && text === '3+')) {
                    btn.className = "gift-pax-btn flex-1 py-2 bg-primary text-white border border-borderLight font-bold text-xs";
                }
            });
        }
        let selId, selName;
        function confirmGift(id, name) {
            selId = id; selName = name;
            document.getElementById('confirm-text').innerHTML = `Checkout <b>${name}</b> dari daftar wishlist?`;
            
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
                setTimeout(() => { document.getElementById('gift-toast').style.opacity = '0'; toggleGiftModal(false); }, 2500);
            };
        }
        document.getElementById('gift-confirm-custom-pax-input')?.addEventListener('input', function() {
            document.getElementById('gift-confirm-pax').value = this.value || 4;
        });
        function closeConfirmModal() { document.getElementById('confirm-modal').classList.add('hidden'); }

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
                card.className = 'bg-bg border border-borderLight p-4 flex gap-4 items-start text-left';
                card.innerHTML = `
                    <div class="w-8 h-8 bg-white border border-borderLight rounded-full shrink-0 flex items-center justify-center text-muted font-bold">
                        ${wish.nama.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <h5 class="font-bold text-dark text-xs">${wish.nama}</h5>
                            <span class="text-[8px] font-bold uppercase px-2 py-0.5 border border-borderLight bg-white text-secondary">${wish.status}</span>
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
