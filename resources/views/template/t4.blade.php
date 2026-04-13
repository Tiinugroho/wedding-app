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
        'photo' => isset($content['groom_photo']) ? asset('storage/' . $content['groom_photo']) : 'https://images.soco.id/230-58.jpg.jpeg',
        'ig' => $content['groom_ig'] ?? '',
        'label' => 'The Groom',
        'gender_text' => 'Putra',
    ];

    $bride = [
        'name' => $content['bride_name'] ?? 'Juliet Capulet',
        'nickname' => $content['bride_nickname'] ?? 'Juliet',
        'father' => $content['bride_father'] ?? 'Bapak Capulet',
        'mother' => $content['bride_mother'] ?? 'Ibu Capulet',
        'photo' => isset($content['bride_photo']) ? asset('storage/' . $content['bride_photo']) : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg',
        'ig' => $content['bride_ig'] ?? '',
        'label' => 'The Bride',
        'gender_text' => 'Putri',
    ];

    $firstPerson = $groomFirst ? $groom : $bride;
    $secondPerson = $groomFirst ? $bride : $groom;

    // 3. Helper Variabel
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

    $coverImage = isset($content['cover_image']) ? asset('storage/' . $content['cover_image']) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2000&auto=format&fit=crop';

    // 4. Helper Platform Icons
    $platformIcons = [
        'youtube' => ['icon' => 'fa-brands fa-youtube text-red-500', 'title' => 'YouTube Live'],
        'instagram' => ['icon' => 'fa-brands fa-instagram text-pink-500', 'title' => 'Instagram Live'],
        'tiktok' => ['icon' => 'fa-brands fa-tiktok text-white', 'title' => 'TikTok Live'],
        'zoom' => ['icon' => 'fa-solid fa-video text-blue-400', 'title' => 'Zoom Meeting'],
        'gmeet' => ['icon' => 'fa-solid fa-camera-retro text-green-400', 'title' => 'Google Meet'],
    ];

    // 5. Data Logo Bank & RSVP
    $masterLogos = \DB::table('banks')->pluck('logo', 'name')->toArray();
    $masterLogos = array_change_key_case($masterLogos, CASE_LOWER);

    $dbWishes = \DB::table('wishes_rsvps')->where('invitation_id', $invitation->id)->orderBy('created_at', 'desc')->get();
    $totalAttendance = \DB::table('wishes_rsvps')->where('invitation_id', $invitation->id)->where('status_rsvp', 'hadir')->sum('pax') ?? 0;
    $totalWishes = $dbWishes->count();
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grab Wedding - {{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        grab: {
                            green: '#00B14F',
                            dark: '#1C1C1C',
                            gray: '#F3F4F6',
                            text: '#333333',
                            light: '#E5F7ED',
                            border: '#E8E8E8'
                        }
                    },
                    fontFamily: { sans: ['"Inter"', 'sans-serif'], },
                    boxShadow: {
                        'app': '0 -4px 20px rgba(0,0,0,0.05)',
                        'card': '0 2px 10px rgba(0,0,0,0.03)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #EFEFEF;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Protect Auto-Popup when cover is open */
        body.cover-locked { overflow: hidden !important; }

        .app-wrapper {
            max-width: 480px;
            margin: 0 auto;
            background-color: #FFFFFF;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
            overflow-x: hidden;
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: #F3F4F6; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #00B14F; border-radius: 10px; }

        .splash-enter { animation: splash-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes splash-up {
            to { transform: translateY(-100%); opacity: 0; pointer-events: none; }
        }

        .timeline-dot::after {
            content: ''; position: absolute; top: 4px; left: -29px;
            width: 12px; height: 12px; border-radius: 50%;
            background-color: white; border: 3px solid #00B14F;
        }

        /* Hilangkan spinner panah di input type number */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
</head>

<body class="text-grab-text selection:bg-grab-green selection:text-white cover-locked" id="body-main">

    <audio id="bg-music" loop>
        @if ($invitation->music_id && $invitation->music)
            <source src="{{ asset('storage/' . $invitation->music->file_path) }}" type="audio/mpeg">
        @else
            <source src="https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3" type="audio/mpeg">
        @endif
    </audio>

    <div class="app-wrapper">

        <div id="cover-page" class="fixed inset-0 z-[200] max-w-[480px] mx-auto w-full h-[100dvh] bg-grab-green flex flex-col items-center justify-center transition-transform duration-700">
            <div class="flex flex-col items-center animate-pulse">
                <h1 class="text-white text-5xl font-black tracking-tighter mb-2">RuangRestu</h1>
                <p class="text-white/80 font-medium text-sm tracking-widest uppercase">Everyday Everything App</p>
            </div>

            <div class="absolute bottom-16 w-full px-8 text-center flex flex-col items-center">
                <p class="text-white mb-6 font-medium text-sm">Hai, <span id="guest-name-cover" class="font-bold">Tamu Undangan</span>!<br>Ada pesanan kebahagiaan untukmu.</p>
                <button onclick="openApp()" class="w-full max-w-xs py-4 bg-white text-grab-green rounded-full font-bold text-sm shadow-xl active:scale-95 transition-transform flex items-center justify-center gap-2">
                    <i class="fa-solid fa-motorcycle"></i> Buka Undangan
                </button>
            </div>
        </div>

        <main id="main-content" class="opacity-0 transition-opacity duration-700 pb-24">

            <header class="sticky top-0 z-[80] bg-white px-4 py-3 shadow-sm flex items-center gap-3 w-full">
                <div class="w-10 h-10 rounded-full bg-grab-light flex items-center justify-center text-grab-green shrink-0">
                    <i class="fa-solid fa-location-dot text-lg"></i>
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Tujuan Perjalanan</p>
                    <div class="flex items-center gap-1">
                        <h1 class="text-sm font-bold text-grab-dark truncate">{{ !empty($content['events'][0]['location']) ? $content['events'][0]['location'] : 'Grand Ballroom' }}</h1>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 bg-gray-100 p-0.5 border border-gray-200">
                    <img src="https://ui-avatars.com/api/?name=Tamu&background=E5F7ED&color=00B14F" id="user-avatar" class="w-full h-full rounded-full" alt="User">
                </div>
            </header>

            <div class="bg-white px-4 pb-4 rounded-b-2xl shadow-card mb-4 border-b border-gray-100">
                <div class="bg-gray-100 rounded-lg p-3 flex items-center gap-3 border border-gray-200">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                    <span class="text-xs text-gray-400 font-medium">Cari layanan: RSVP, Kado, Lokasi...</span>
                </div>
            </div>

            <div class="px-4 space-y-6">

                <section class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden shadow-card group">
                    <img src="{{ $coverImage }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Prewedding">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 text-white">
                        <span class="px-2 py-1 bg-[#FF7A00] text-[9px] font-bold rounded uppercase tracking-wider mb-2 inline-block shadow-sm">Promo Spesial</span>
                        <h2 class="text-2xl font-black leading-tight mb-1">Perjalanan Menuju Sah!</h2>
                        <p class="text-[11px] text-gray-200 font-medium mb-3">Diskon 100% rindu dengan kode: 
                            <span class="font-bold text-grab-dark bg-white px-1.5 py-0.5 rounded">
                                SAH{{ !empty($content['events'][0]['date']) ? \Carbon\Carbon::parse($content['events'][0]['date'])->format('Ymd') : \Carbon\Carbon::parse($akadDate)->format('Ymd') }}
                            </span>
                        </p>
                        <div class="flex gap-2">
                            <button onclick="openRSVP()" class="flex-1 py-2.5 bg-grab-green text-white rounded-lg font-bold text-xs text-center active:scale-95 transition-transform">
                                Pesan RSVP
                            </button>
                            <a href="#jadwal" class="flex-1 py-2.5 bg-white/20 backdrop-blur border border-white/30 text-white rounded-lg font-bold text-xs text-center active:scale-95 transition-transform">
                                Info Lanjut
                            </a>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-4 gap-y-4 gap-x-2 px-1">
                    <a href="#jadwal" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-regular fa-calendar-check text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Jadwal<br>Acara</span>
                    </a>
                    <a href="#jadwal" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-solid fa-map-location-dot text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Titik<br>Jemput</span>
                    </a>
                    <a href="#digital-gift" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Grab<br>Pay</span>
                    </a>
                    <a href="#ulasan" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-solid fa-star text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Rating &<br>Ulasan</span>
                    </a>
                    @if($content['is_livestream_active'] ?? false)
                    <a href="#live-streaming" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-solid fa-video text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Pantau<br>Live</span>
                    </a>
                    @endif
                    <a href="#protokol" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Pusat<br>Aman</span>
                    </a>
                    @if($content['enable_qr_attendance'] ?? false)
                    <a href="#qr-tamu" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-solid fa-qrcode text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Scan<br>QR</span>
                    </a>
                    @endif
                    @if($content['is_gift_active'] ?? false)
                    <a href="#kirim-kado" class="flex flex-col items-center gap-2">
                        <div class="w-[3.25rem] h-[3.25rem] bg-grab-light rounded-2xl flex items-center justify-center text-grab-green shadow-sm active:scale-90 transition-transform">
                            <i class="fa-solid fa-box-open text-xl"></i>
                        </div>
                        <span class="text-[10px] font-bold text-center leading-tight">Grab<br>Express</span>
                    </a>
                    @endif
                </section>

                <hr class="border-gray-200">

                <section class="bg-white rounded-2xl p-5 shadow-card border border-grab-border">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-lg text-grab-dark">Mitra Kebahagiaan</h3>
                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Profil Mempelai</p>
                        </div>
                        <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-md text-[10px] font-bold text-yellow-600 border border-yellow-100 shadow-sm">
                            <i class="fa-solid fa-star"></i> 5.0 Rating
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="relative">
                            <div class="flex flex-col items-center bg-gray-50 rounded-2xl p-6 border border-gray-100 text-center">
                                <div class="relative mb-4">
                                    <img src="{{ $firstPerson['photo'] }}" class="w-28 h-28 rounded-full object-cover border-4 border-grab-green p-1 bg-white shadow-md" alt="Mempelai 1">
                                    <div class="absolute bottom-1 right-1 bg-grab-green text-white text-[10px] font-bold px-2 py-1 rounded-lg border-2 border-white shadow-sm">
                                        {{ $groomFirst ? 'CPP' : 'CPW' }}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-black text-base text-grab-dark tracking-tight">{{ $firstPerson['name'] }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $firstPerson['gender_text'] }} dari Bapak {{ $firstPerson['father'] }} & Ibu {{ $firstPerson['mother'] }}</p>
                                    <div class="mt-3 flex items-center justify-center gap-2">
                                        @if(!empty($firstPerson['ig']))
                                        <a href="{{ $firstPerson['ig'] }}" target="_blank" class="text-[10px] text-grab-green font-bold bg-grab-light px-3 py-1 rounded-full border border-grab-green/20">
                                            <i class="fa-brands fa-instagram mr-1"></i> Instagram
                                        </a>
                                        @endif
                                        <span class="text-[10px] text-gray-400 font-bold bg-white px-3 py-1 rounded-full border border-gray-100">
                                            <i class="fa-solid fa-shield-check mr-1"></i> Terverifikasi
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-center -my-8 relative z-10">
                            <div class="bg-white p-2 rounded-full border border-gray-200 shadow-md text-grab-green transform rotate-90">
                                <i class="fa-solid fa-repeat text-sm"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="flex flex-col items-center bg-gray-50 rounded-2xl p-6 border border-gray-100 text-center">
                                <div class="relative mb-4">
                                    <img src="{{ $secondPerson['photo'] }}" class="w-28 h-28 rounded-full object-cover border-4 border-grab-green p-1 bg-white shadow-md" alt="Mempelai 2">
                                    <div class="absolute bottom-1 left-1 bg-grab-green text-white text-[10px] font-bold px-2 py-1 rounded-lg border-2 border-white shadow-sm">
                                        {{ $groomFirst ? 'CPW' : 'CPP' }}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-black text-base text-grab-dark tracking-tight">{{ $secondPerson['name'] }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $secondPerson['gender_text'] }} dari Bapak {{ $secondPerson['father'] }} & Ibu {{ $secondPerson['mother'] }}</p>
                                    <div class="mt-3 flex items-center justify-center gap-2">
                                        @if(!empty($secondPerson['ig']))
                                        <a href="{{ $secondPerson['ig'] }}" target="_blank" class="text-[10px] text-grab-green font-bold bg-grab-light px-3 py-1 rounded-full border border-grab-green/20">
                                            <i class="fa-brands fa-instagram mr-1"></i> Instagram
                                        </a>
                                        @endif
                                        <span class="text-[10px] text-gray-400 font-bold bg-white px-3 py-1 rounded-full border border-gray-100">
                                            <i class="fa-solid fa-heart mr-1"></i> Penumpang Setia
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                @if (($content['is_story_active'] ?? false) && !empty($content['love_stories']))
                <section class="bg-white rounded-2xl p-5 shadow-card border border-grab-border">
                    <h3 class="font-bold text-lg text-grab-dark mb-6">Riwayat Perjalanan (Cerita Kami)</h3>
                    <div class="relative pl-6 border-l-2 border-dashed border-gray-200 space-y-8 ml-2">
                        @foreach ($content['love_stories'] as $index => $story)
                        <div class="relative">
                            <div class="{{ $loop->last ? 'absolute top-1.5 -left-[33px] w-5 h-5 rounded-full bg-grab-green border-4 border-white shadow-[0_0_0_2px_#00B14F] animate-pulse z-10' : 'absolute top-1.5 -left-[31px] w-4 h-4 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center' }}">
                                @if(!$loop->last) <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div> @endif
                            </div>
                            <span class="{{ $loop->last ? 'text-[9px] font-bold uppercase tracking-widest text-white bg-grab-green px-2 py-0.5 rounded shadow-sm' : 'text-[9px] font-bold uppercase tracking-widest text-gray-400 bg-gray-100 px-2 py-0.5 rounded' }}">
                                {{ $story['year'] }}
                            </span>
                            <h4 class="text-sm font-black text-grab-dark mt-2 uppercase tracking-tight">{{ $story['title'] }}</h4>
                            
                            @if (!empty($story['image']))
                            <div class="mt-3 mb-3 overflow-hidden rounded-xl border border-gray-100 shadow-sm {{ $loop->last ? 'border-2 border-grab-green' : '' }}">
                                <img src="{{ asset('storage/' . $story['image']) }}" alt="{{ $story['title'] }}" class="w-full h-40 object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                            @endif
                            <p class="text-xs text-gray-500 leading-relaxed {{ $loop->last ? 'font-medium' : '' }}">
                                {!! nl2br(e($story['description'])) !!}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                @if ($content['is_gallery_active'] ?? false)
                <section id="gallery" class="pt-2">
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="font-bold text-lg text-grab-dark">Galeri Perjalanan</h3>
                    </div>

                    <div class="flex overflow-x-auto gap-3 pb-4 hide-scrollbar snap-x snap-mandatory px-1">
                        @php $youtubeLink = $content['youtube_links'][0] ?? null; @endphp
                        @if ($youtubeLink)
                            @php
                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $youtubeLink, $match);
                                $youtube_id = $match[1] ?? '';
                            @endphp
                            @if ($youtube_id)
                            <div class="w-60 shrink-0 snap-start bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
                                <div class="relative aspect-video bg-black group">
                                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtube_id }}?controls=0" frameborder="0"></iframe>
                                    <span class="absolute bottom-2 right-2 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded backdrop-blur pointer-events-none">Trailer</span>
                                </div>
                                <div class="p-3">
                                    <h4 class="font-bold text-xs">Official Teaser Video</h4>
                                </div>
                            </div>
                            @endif
                        @endif

                        @foreach ($invitation->galleries as $index => $gallery)
                            <div class="w-40 h-40 shrink-0 snap-start rounded-xl shadow-card border border-gray-100 overflow-hidden cursor-pointer relative group" onclick="openLightbox({{ $index }})">
                                
                                <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                                    class="gallery-img w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                                    <i class="fa-solid fa-expand text-white text-xl"></i>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <section id="jadwal" class="bg-white rounded-2xl p-5 shadow-card border border-grab-border">
                    <h3 class="font-bold text-lg text-grab-dark mb-4">Detail Titik Lokasi</h3>

                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-grab-light rounded-bl-full flex items-start justify-end p-2 opacity-50">
                                <i class="fa-solid fa-ring text-grab-green"></i>
                            </div>
                            <span class="text-[9px] font-bold text-grab-dark uppercase tracking-widest bg-white border border-gray-200 px-2 py-0.5 rounded shadow-sm inline-block mb-2">Titik 1: Akad Nikah</span>
                            <h4 class="font-bold text-sm">{{ \Carbon\Carbon::parse($akadDate)->translatedFormat('l, d F Y') }}</h4>
                            <p class="text-xs text-gray-500 mt-1"><i class="fa-regular fa-clock w-4 text-gray-400"></i> {{ $akadTime }} - {{ $content['akad_time_end'] ?? 'Selesai' }}</p>
                            <p class="text-xs text-gray-500 mt-1 flex items-start">
                                <i class="fa-solid fa-location-dot w-4 text-gray-400 mt-0.5"></i> 
                                <span>{{ $content['akad_location'] ?? 'Lokasi Akad' }}<br><span class="text-[10px]">{{ $content['akad_address'] ?? '' }}</span></span>
                            </p>
                            @if (!empty($content['akad_map']))
                            <a href="{{ $content['akad_map'] }}" target="_blank" class="mt-3 block w-full py-2 bg-white border border-gray-200 text-grab-text rounded-lg text-xs font-bold text-center shadow-sm hover:bg-gray-50 active:scale-95 transition-all">
                                <i class="fa-solid fa-map-location-dot text-grab-green mr-1"></i> Buka Google Maps
                            </a>
                            @endif
                        </div>

                        @foreach ($content['events'] ?? [] as $idx => $event)
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 bg-grab-light rounded-bl-full flex items-start justify-end p-2 opacity-50">
                                <i class="fa-solid fa-champagne-glasses text-grab-green"></i>
                            </div>
                            <span class="text-[9px] font-bold text-white uppercase tracking-widest bg-grab-green px-2 py-0.5 rounded shadow-sm inline-block mb-2">Titik {{ $idx + 2 }}: {{ $event['title'] ?? 'Resepsi' }}</span>
                            <h4 class="font-bold text-sm">{{ \Carbon\Carbon::parse($event['date'] ?? now())->translatedFormat('l, d F Y') }}</h4>
                            <p class="text-xs text-gray-500 mt-1"><i class="fa-regular fa-clock w-4 text-gray-400"></i> {{ $event['time'] }} - {{ $event['time_end'] ?? 'Selesai' }}</p>
                            <p class="text-xs text-gray-500 mt-1 flex items-start">
                                <i class="fa-solid fa-location-dot w-4 text-gray-400 mt-0.5"></i> 
                                <span>{{ $event['location'] ?? 'Lokasi Resepsi' }}<br><span class="text-[10px]">{{ $event['address'] ?? '' }}</span></span>
                            </p>
                            @if (!empty($event['map']))
                            <a href="{{ $event['map'] }}" target="_blank" class="mt-3 block w-full py-2 bg-grab-green text-white rounded-lg text-xs font-bold text-center shadow-md shadow-grab-green/20 hover:bg-[#009b45] active:scale-95 transition-all">
                                <i class="fa-solid fa-diamond-turn-right mr-1"></i> Rute ke Lokasi
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </section>

                @if (($content['is_livestream_active'] ?? false) && !empty($content['live_streams']))
                <section id="live-streaming" class="bg-grab-dark text-white rounded-2xl p-5 shadow-lg relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-grab-green opacity-20 rounded-full blur-2xl"></div>

                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            <h3 class="font-bold text-base">GrabLive Virtual</h3>
                        </div>
                        <span class="text-[10px] bg-white/10 px-2 py-1 rounded font-medium text-gray-300">Multi-Platform</span>
                    </div>

                    @php $firstStream = $content['live_streams'][0]; @endphp
                    <div id="streaming-display" class="bg-[#2A2A2A] rounded-xl overflow-hidden mb-5 border border-white/10 transition-all duration-500 ease-in-out">
                        <div class="aspect-video relative group flex flex-col items-center justify-center p-6 text-center">
                            <img src="{{ $coverImage }}" class="absolute inset-0 w-full h-full object-cover opacity-20">
                            <div class="relative z-10">
                                <i id="platform-icon" class="{{ $platformIcons[$firstStream['platform']]['icon'] ?? 'fa-solid fa-video' }} text-4xl mb-3 transition-all duration-300"></i>
                                <h4 id="platform-title" class="text-sm font-black uppercase tracking-widest">{{ $platformIcons[$firstStream['platform']]['title'] ?? ucfirst($firstStream['platform']) }}</h4>
                                <p id="platform-desc" class="text-[10px] text-gray-400 mt-1 max-w-[200px]">Siaran langsung prosesi pernikahan kami.</p>
                            </div>
                        </div>
                        <a id="platform-link" href="{{ $firstStream['link'] }}" target="_blank" class="block w-full py-3 bg-grab-green text-white font-bold text-xs text-center hover:bg-[#009b45] transition-colors">
                            <i class="fa-solid fa-play text-[10px] mr-1"></i> Gabung Sekarang
                        </a>
                    </div>

                    <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-2 {{ count($content['live_streams']) <= 1 ? 'hidden' : '' }}">
                        @foreach ($content['live_streams'] as $stream)
                            @php $pData = $platformIcons[$stream['platform']] ?? ['icon' => 'fa-solid fa-video', 'title' => ucfirst($stream['platform'])]; @endphp
                            
                            <button onclick="switchPlatform('{{ $pData['title'] }}', 'Tonton siaran langsung di {{ ucfirst($stream['platform']) }}', '{{ $pData['icon'] }}', '{{ $stream['link'] }}')"
                                class="bg-[#2A2A2A] px-4 py-2.5 rounded-xl flex items-center gap-2 shrink-0 border border-white/5 active:scale-90 transition-all">
                                <i class="{{ $pData['icon'] }}"></i>
                                <span class="text-[10px] font-bold">{{ ucfirst($stream['platform']) }}</span>
                            </button>
                        @endforeach
                    </div>
                </section>
                @endif

                @if ($content['is_wishes_active'] ?? false)
                <section id="ulasan" class="bg-white rounded-2xl p-5 shadow-card border border-grab-border">
                    <div class="flex justify-between items-end mb-5">
                        <h3 class="font-bold text-lg text-grab-dark leading-tight">Rating &<br>Ulasan Penumpang</h3>
                        <div class="text-right">
                            <p class="text-3xl font-black text-grab-green" id="total-wishes">{{ $totalWishes }}</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold">Total Ulasan</p>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-5">
                        <div class="flex-1 bg-gray-50 border border-gray-100 p-3 rounded-xl flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-grab-light text-grab-green flex items-center justify-center">
                                <i class="fa-solid fa-users text-xs"></i>
                            </div>
                            <div>
                                <p class="text-lg font-bold leading-none text-grab-dark" id="total-attendance">{{ $totalAttendance }}</p>
                                <p class="text-[9px] text-gray-500 font-medium">Akan Hadir</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Ulasan Terbaru</span>
                            <span class="text-[10px] text-grab-green font-bold">Scroll kebawah <i class="fa-solid fa-chevron-down ml-1"></i></span>
                        </div>

                        <div id="wishes-container" class="space-y-3 max-h-[400px] overflow-y-auto custom-scroll pr-2">
                            </div>
                    </div>

                    <button onclick="openRSVP()" class="w-full py-3 mt-3 bg-white border border-gray-200 text-grab-green rounded-lg text-[10px] font-bold shadow-sm hover:bg-gray-50 active:scale-95 transition-all">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Tulis Ulasan Anda
                    </button>
                </section>
                @endif

                @if ($content['is_gift_active'] ?? false)
                <section id="digital-gift" class="bg-gradient-to-br from-[#0F8C43] to-[#00B14F] rounded-2xl p-5 shadow-lg text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>

                    <div class="flex justify-between items-center mb-5 relative z-10">
                        <div>
                            <h3 class="font-bold text-lg flex items-center gap-2">
                                <i class="fa-solid fa-wallet"></i> GrabKado Pay
                            </h3>
                            <p class="text-[10px] text-white/80 mt-0.5">Tanda Kasih Tanpa Tunai</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-qrcode text-lg"></i>
                        </div>
                    </div>

                    <div class="space-y-3 relative z-10">
                        @foreach ($content['banks'] ?? [] as $index => $bank)
                            @php
                                $bNameRaw = trim($bank['name'] ?? '');
                                $bNameLower = strtolower($bNameRaw);
                                $logoPath = $masterLogos[$bNameLower] ?? null;
                            @endphp
                            <div class="bg-white text-grab-dark rounded-xl p-4 shadow-md">
                                <div class="flex justify-between items-center mb-2 h-6">
                                    @if ($logoPath)
                                        @if (str_starts_with($logoPath, 'http'))
                                            <img src="{{ $logoPath }}" class="h-4 md:h-5 object-contain" alt="{{ $bNameRaw }}">
                                        @else
                                            <img src="{{ asset('storage/' . $logoPath) }}" class="h-4 md:h-5 object-contain" alt="{{ $bNameRaw }}">
                                        @endif
                                    @else
                                        <span class="text-[10px] font-bold uppercase">{{ $bNameRaw }}</span>
                                    @endif
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider text-right line-clamp-1 max-w-[150px]">a.n {{ $bank['account_name'] }}</span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <h4 class="text-lg md:text-xl font-mono font-black tracking-widest text-grab-text" id="rek{{ $index }}">{{ $bank['account_number'] }}</h4>
                                    <button onclick="copyRek('rek{{ $index }}')" class="bg-grab-light text-grab-green px-3 py-1.5 rounded text-[10px] font-bold active:scale-95 transition-transform"><i class="fa-regular fa-copy"></i> Salin</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section id="kirim-kado" class="py-12 bg-[#EFEFEF] relative overflow-hidden -mx-4 px-4">
                    <div class="max-w-md mx-auto relative z-10">
                        <div class="mb-6 text-center">
                            <div class="inline-flex items-center gap-2 bg-grab-light px-3 py-1 rounded-full mb-3">
                                <i class="fa-solid fa-gift text-grab-green text-[10px]"></i>
                                <span class="text-[10px] tracking-widest uppercase text-grab-green font-black">Gift Registry</span>
                            </div>
                            <h2 class="text-2xl font-black text-grab-dark mb-2 tracking-tight">Kirim Kado Fisik</h2>
                            <p class="text-[11px] text-gray-500 font-medium leading-relaxed">Kehadiran Anda adalah kado terbesar. Namun jika Anda ingin mengirimkan tanda kasih, kami telah menyediakan alamat.</p>
                        </div>

                        @if (!empty($content['alamat_kado']))
                        <div class="group relative p-6 bg-white rounded-3xl border border-grab-border shadow-card transition-all hover:shadow-md">
                            <div class="w-12 h-12 bg-grab-light rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-truck-fast text-grab-green text-xl"></i>
                            </div>
                            <p class="text-[9px] text-center uppercase tracking-[0.2em] text-gray-400 mb-2 font-black">Alamat Pengiriman</p>
                            <div id="alamat-kado" class="text-sm text-grab-dark font-bold text-center leading-snug mb-6">
                                {!! nl2br(e($content['alamat_kado'])) !!}
                            </div>
                            <div class="flex flex-col gap-3">
                                <button onclick="navigator.clipboard.writeText(document.getElementById('alamat-kado').innerText); showToast('Alamat Disalin!')" class="w-full py-3 bg-white text-grab-dark rounded-xl font-bold text-xs border border-grab-border active:scale-95 transition-all flex items-center justify-center gap-2 shadow-sm">
                                    <i class="fa-regular fa-copy text-grab-green"></i> Salin Alamat
                                </button>
                                @if (!empty($content['gifts']))
                                <button onclick="toggleModal('wishlist-modal')" class="w-full py-3 bg-grab-green text-white rounded-xl font-black text-xs uppercase tracking-wider active:scale-95 shadow-md shadow-grab-green/20 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-list-ul"></i> Daftar Kebutuhan
                                </button>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </section>
                @endif

                @if ($content['is_guest_info_active'] ?? false)
                <section id="protokol" class="bg-white rounded-2xl p-5 shadow-card border border-grab-border">
                    <h3 class="font-bold text-lg text-grab-dark mb-4">Pusat Keamanan & Panduan</h3>

                    @if ($content['enable_dresscode'] ?? false)
                    <div class="flex gap-3 mb-5 pb-5 border-b border-gray-100">
                        <div class="w-8 h-8 rounded bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-grab-dark">Dresscode Pakaian</h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $content['dresscode'] }}</p>
                        </div>
                    </div>
                    @endif

                    @if ($content['enable_health_protocol'] ?? false)
                    <div class="mb-5 pb-5 border-b border-gray-100">
                        <h4 class="font-bold text-sm text-grab-dark mb-3">Protokol Kesehatan (CHSE)</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                                <i class="fa-solid fa-hands-bubbles text-grab-green mb-1 text-lg"></i>
                                <p class="text-[9px] font-semibold text-gray-600 leading-tight">Cuci Tangan</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                                <i class="fa-solid fa-head-side-mask text-grab-green mb-1 text-lg"></i>
                                <p class="text-[9px] font-semibold text-gray-600 leading-tight">Gunakan Masker</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                                <i class="fa-solid fa-people-arrows text-grab-green mb-1 text-lg"></i>
                                <p class="text-[9px] font-semibold text-gray-600 leading-tight">Jaga Jarak</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                                <i class="fa-solid fa-users-slash text-grab-green mb-1 text-lg"></i>
                                <p class="text-[9px] font-semibold text-gray-600 leading-tight">No Kerumunan</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                                <i class="fa-solid fa-temperature-high text-grab-green mb-1 text-lg"></i>
                                <p class="text-[9px] font-semibold text-gray-600 leading-tight">Cek Suhu</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg text-center border border-gray-100">
                                <i class="fa-solid fa-spray-can-sparkles text-grab-green mb-1 text-lg"></i>
                                <p class="text-[9px] font-semibold text-gray-600 leading-tight">Desinfektan</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($content['enable_adab_walimah'] ?? false)
                    <div>
                        <h4 class="font-bold text-sm text-grab-dark mb-3">Adab Menghadiri Walimah</h4>
                        <div class="space-y-3">
                            <div class="flex gap-3 items-center">
                                <i class="fa-solid fa-mosque text-gray-400 w-5 text-center"></i>
                                <p class="text-xs text-gray-600">Memperhatikan waktu ibadah sholat.</p>
                            </div>
                            <div class="flex gap-3 items-center">
                                <i class="fa-solid fa-utensils text-gray-400 w-5 text-center"></i>
                                <p class="text-xs text-gray-600">Makan dan minum sambil duduk.</p>
                            </div>
                            <div class="flex gap-3 items-center">
                                <i class="fa-solid fa-hands-praying text-gray-400 w-5 text-center"></i>
                                <p class="text-xs text-gray-600">Memberikan doa keberkahan untuk kami.</p>
                            </div>
                            <div class="flex gap-3 items-center">
                                <i class="fa-solid fa-restroom text-gray-400 w-5 text-center"></i>
                                <p class="text-xs text-gray-600">Menjaga batasan antara tamu pria & wanita.</p>
                            </div>
                            <div class="flex gap-3 items-center">
                                <i class="fa-solid fa-shirt text-gray-400 w-5 text-center"></i>
                                <p class="text-xs text-gray-600">Berbusana menutup aurat dan rapi.</p>
                            </div>
                            <div class="flex gap-3 items-center">
                                <i class="fa-solid fa-video-slash text-gray-400 w-5 text-center"></i>
                                <p class="text-xs text-gray-600">Meminta izin sebelum mendokumentasikan.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </section>
                @endif

                @if ($content['enable_qr_attendance'] ?? false)
                <section id="qr-tamu" class="bg-white rounded-2xl p-6 shadow-card border border-grab-border text-center overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-2 bg-grab-green"></div>

                    <h3 class="font-bold text-base text-grab-dark mb-1 mt-2">Kode Akses Penumpang</h3>
                    <p class="text-[10px] text-gray-500 mb-6">Tunjukkan kode ini kepada petugas di lokasi (Scan QR)</p>

                    <div class="inline-block p-2 bg-white border-2 border-gray-100 rounded-xl shadow-sm mb-4 relative">
                        <div class="absolute -top-1 -left-1 w-4 h-4 border-t-4 border-l-4 border-grab-green rounded-tl"></div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 border-t-4 border-r-4 border-grab-green rounded-tr"></div>
                        <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-4 border-l-4 border-grab-green rounded-bl"></div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-4 border-r-4 border-grab-green rounded-br"></div>

                        <img id="qr-image" src="" class="w-32 h-32 object-contain" alt="QR Code Tamu">
                    </div>

                    <h4 id="guest-name-qr" class="text-xl font-black text-grab-dark uppercase tracking-tight">Tamu Undangan</h4>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Tiket E-Invitation</p>
                </section>
                @endif

            </div>
            
            <footer class="mt-10 pb-8 pt-6 px-4 text-center border-t border-gray-200">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3 text-gray-300">
                    <i class="fa-solid fa-leaf text-xl"></i>
                </div>
                <h2 class="text-2xl font-black text-grab-dark uppercase tracking-tighter mb-4">{{ $firstPerson['nickname'] }} & {{ $secondPerson['nickname'] }}</h2>
                <p class="text-xs text-gray-500 mb-8 max-w-[250px] mx-auto leading-relaxed">
                    "Merupakan suatu kehormatan apabila Bapak/Ibu/Saudara/i berkenan hadir."
                </p>

                <a href="https://www.instagram.com/ruangrestu.undangan" target="_blank" class="flex items-center justify-center gap-1 mt-1 text-gray-600 hover:text-grab-green transition-colors">
                    <i class="fa-brands fa-instagram text-xs"></i>
                    <p class="text-[11px] font-bold">@ruangrestu.undangan</p>
                </a>
                <p class="text-[9px] text-gray-400 mt-4">&copy; 2026 Wedding Invitation. <br class="md:hidden">All Memories Reserved.</p>
            </footer>

        </main>
        
        <nav id="bottom-nav" class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-200 flex justify-around items-center pb-safe pt-2 shadow-[0_-10px_20px_rgba(0,0,0,0.03)] z-[90] translate-y-32 transition-transform duration-700 pb-2">
            <a href="#main-content" class="flex flex-col items-center p-2 text-grab-green min-w-[60px]">
                <i class="fa-solid fa-house text-xl mb-1"></i><span class="text-[9px] font-bold">Home</span>
            </a>
            @if ($content['is_gallery_active'] ?? false)
            <a href="#gallery" class="flex flex-col items-center p-2 text-gray-400 hover:text-grab-dark transition-colors min-w-[60px]">
                <i class="fa-solid fa-clapperboard text-xl mb-1"></i><span class="text-[9px] font-semibold">Gallery</span>
            </a>
            @endif
            @if ($content['is_event_active'] ?? false)
            <a href="#jadwal" class="flex flex-col items-center p-2 text-gray-400 hover:text-grab-dark transition-colors min-w-[60px]">
                <i class="fa-solid fa-location-dot text-xl mb-1"></i><span class="text-[9px] font-semibold">Venue</span>
            </a>
            @endif
            @if ($content['is_wishes_active'] ?? false)
            <a href="javascript:void(0)" onclick="openRSVP()" class="flex flex-col items-center p-2 text-gray-400 hover:text-grab-dark transition-colors min-w-[60px] relative">
                <i class="fa-solid fa-paper-plane text-xl mb-1"></i>
                <span class="absolute top-1 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                <span class="text-[9px] font-semibold">RSVP</span>
            </a>
            @endif
        </nav>

        <div id="fab-container" class="fixed right-4 bottom-20 flex flex-col gap-3 z-[85] opacity-0 transition-opacity duration-1000 max-w-[480px] ml-auto left-auto pointer-events-none">
            <button id="btn-music" onclick="toggleMusic()" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-grab-dark shadow-md active:scale-90 transition-transform pointer-events-auto">
                <i class="fa-solid fa-music animate-spin" id="icon-music"></i>
            </button>
            <button id="btn-scroll" onclick="toggleAutoScroll()" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-grab-dark shadow-md active:scale-90 transition-transform pointer-events-auto">
                <i class="fa-solid fa-angles-down text-sm" id="icon-scroll"></i>
            </button>
        </div>

        <div id="toast" class="fixed top-20 left-1/2 -translate-x-1/2 bg-grab-dark text-white px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-2 opacity-0 transition-all duration-300 pointer-events-none z-[150] shadow-xl whitespace-nowrap transform translate-y-4">
            <i class="fa-solid fa-circle-check text-grab-green text-lg"></i> <span id="toast-msg">Tersalin!</span>
        </div>

        <section id="rsvp-modal" class="fixed inset-0 z-[1000] invisible overflow-hidden flex items-end justify-center">
            <div id="rsvp-overlay" onclick="closeRSVP()" class="absolute inset-0 bg-grab-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-500"></div>

            <div id="rsvp-content" class="relative w-full max-w-[480px] bg-white rounded-t-[2.5rem] shadow-2xl transform translate-y-full transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] px-6 pb-10 pt-4 flex flex-col max-h-[92vh]">
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8 flex-shrink-0"></div>

                <div class="mb-6 flex-shrink-0">
                    <h2 class="text-2xl font-black text-grab-dark tracking-tight">Checkout Pesanan (RSVP)</h2>
                    <p class="text-xs text-gray-500 font-medium mt-1">Mohon konfirmasi pesanan kehadiran Anda.</p>
                </div>

                <form id="form-rsvp" class="space-y-6 text-left overflow-y-auto pr-1 flex-1 custom-scroll pb-2">
                    <div class="group">
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-2 block">Nama Lengkap</label>
                        <input type="text" id="input-nama-rsvp" placeholder="Contoh: Budi Santoso" class="w-full bg-gray-50 border border-gray-200 p-4 rounded-2xl text-sm font-bold text-grab-dark outline-none focus:border-grab-green focus:ring-4 focus:ring-grab-green/10 transition-all placeholder:text-gray-300" required>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-2 block">Status Perjalanan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir" class="py-4 rounded-2xl border-2 border-gray-100 bg-gray-50 text-[11px] font-black uppercase tracking-widest transition-all text-gray-400 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-check text-lg"></i> Hadir
                            </button>
                            <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen" class="py-4 rounded-2xl border-2 border-gray-100 bg-gray-50 text-[11px] font-black uppercase tracking-widest transition-all text-gray-400 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-xmark text-lg"></i> Absen
                            </button>
                        </div>
                    </div>
                    
                    <input type="hidden" id="input-status" value="Hadir">
                    <input type="hidden" id="input-guest-count" value="1">

                    <div id="guest-selection" class="hidden overflow-hidden">
                        <div class="bg-gray-50 p-4 rounded-[1.5rem] border border-gray-100 space-y-4">
                            <label class="text-[10px] uppercase tracking-widest text-gray-400 block font-black text-center">Jumlah Penumpang (Tamu)</label>
                            <div class="flex gap-2">
                                <button type="button" onclick="setGuestCount(1)" class="guest-btn flex-1 py-3 rounded-xl bg-white border border-gray-200 text-sm font-black text-grab-dark hover:border-grab-green transition-all shadow-sm">1</button>
                                <button type="button" onclick="setGuestCount(2)" class="guest-btn flex-1 py-3 rounded-xl bg-white border border-gray-200 text-sm font-black text-grab-dark hover:border-grab-green transition-all shadow-sm">2</button>
                                <button type="button" onclick="setGuestCount(3)" class="guest-btn flex-1 py-3 rounded-xl bg-white border border-gray-200 text-sm font-black text-grab-dark hover:border-grab-green transition-all shadow-sm">3</button>
                                <button type="button" onclick="setGuestCount('custom')" class="guest-btn flex-1 py-3 rounded-xl bg-white border border-gray-200 text-sm font-black text-grab-dark hover:border-grab-green transition-all shadow-sm">3+</button>
                            </div>
                            <div id="custom-pax-container" class="hidden">
                                <input type="number" id="custom-pax-input" min="4" placeholder="Ketik jumlah spesifik..." class="w-full bg-white border border-gray-200 p-3 rounded-xl text-sm font-bold text-center text-grab-dark outline-none focus:border-grab-green shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="group">
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-2 block">Catatan (Doa & Ucapan)</label>
                        <textarea id="input-pesan-rsvp" rows="3" placeholder="Tulis ucapan dan doa terbaik Anda..." class="w-full bg-gray-50 border border-gray-200 p-4 rounded-2xl text-sm font-medium text-grab-dark outline-none focus:border-grab-green focus:ring-4 focus:ring-grab-green/10 transition-all resize-none placeholder:text-gray-300" required></textarea>
                    </div>
                </form>

                <div class="flex gap-3 pt-4 border-t border-gray-50 mt-auto flex-shrink-0">
                    <button type="button" onclick="closeRSVP()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-[11px] uppercase tracking-widest active:scale-95 transition-all">Batal</button>
                    <button type="submit" form="form-rsvp" class="flex-[2.5] py-4 bg-grab-green text-white rounded-2xl font-black text-sm uppercase tracking-wider shadow-xl shadow-grab-green/30 active:scale-95 transition-all flex justify-center items-center gap-3">
                        Kirim Konfirmasi <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </div>
            </div>
        </section>

        @if (!empty($content['gifts']))
        <div id="wishlist-modal" class="fixed inset-0 z-[1200] hidden items-center justify-center max-w-[480px] mx-auto p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300 modal-overlay" onclick="toggleModal('wishlist-modal')"></div>

            <div class="bg-white w-full rounded-2xl relative z-10 transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[80vh] overflow-hidden modal-content shadow-2xl">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="font-bold text-lg text-grab-dark">Daftar Wishlist</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Pilih barang yang ingin dikirimkan</p>
                    </div>
                    <button onclick="toggleModal('wishlist-modal')" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-5 overflow-y-auto custom-scroll space-y-3 bg-gray-50 flex-1">
                    @foreach ($content['gifts'] as $index => $gift)
                    <div id="item-{{ $index }}" class="bg-white p-4 rounded-xl border border-gray-100 flex justify-between items-center shadow-sm">
                        <div>
                            <h4 class="font-bold text-sm text-grab-dark">{{ $gift['item_name'] }}</h4>
                            <p class="text-[10px] text-gray-500 mt-0.5">{{ $gift['description'] ?? '' }}</p>
                        </div>
                        <button class="px-4 py-2 bg-grab-green text-white text-[10px] font-bold rounded-lg active:scale-95" onclick="confirmGift('item-{{ $index }}', '{{ $gift['item_name'] }}')">Pilih Ini</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div id="confirm-modal" class="fixed inset-0 z-[1500] hidden flex items-center justify-center p-6 max-w-[480px] mx-auto">
            <div class="absolute inset-0 bg-grab-dark/80 backdrop-blur-md" onclick="closeConfirmModal()"></div>
            <div class="relative bg-white w-full max-w-sm rounded-[32px] p-8 text-center shadow-2xl border border-white animate-slide-up">
                <div class="w-16 h-16 bg-grab-light text-grab-green rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-heart text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-grab-dark mb-2 tracking-tight">Niat Baik Anda</h4>
                <p id="confirm-text" class="text-xs text-gray-500 font-medium leading-relaxed mb-6"></p>

                <div class="text-left space-y-4 mb-6">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-black mb-2">Nama Anda</label>
                        <input type="text" id="input-gift-name" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl text-sm font-bold text-grab-dark outline-none focus:border-grab-green" placeholder="Masukkan nama">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-black mb-2">Jumlah Hadir (Ops. Kado)</label>
                        <input type="number" id="input-gift-pax" min="0" value="0" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl text-sm font-bold text-grab-dark outline-none focus:border-grab-green text-center">
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <button id="final-confirm-btn" class="w-full py-4 bg-grab-green text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-grab-green/20">Kirim Konfirmasi</button>
                    <button onclick="closeConfirmModal()" class="w-full py-3 bg-transparent text-gray-400 font-bold text-[10px] uppercase tracking-widest hover:text-gray-600 transition-colors">Batalkan</button>
                </div>
            </div>
        </div>

        <div id="lightbox" class="fixed inset-0 z-[2000] hidden flex-col items-center justify-center bg-black/95 p-4 transition-all duration-300 max-w-[480px] mx-auto">
            <div class="w-full flex justify-between items-center p-4 absolute top-0 left-0 z-10">
                <span class="text-white font-bold text-xs bg-black/50 px-3 py-1 rounded-full"><span id="current-count">1</span> / <span id="total-count">0</span></span>
                <button onclick="closeLightbox()" class="text-white w-10 h-10 bg-black/50 rounded-full flex items-center justify-center text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="w-full flex items-center justify-center h-[80vh] relative">
                <img id="lightbox-img" src="" class="max-w-full max-h-full object-contain transition-opacity duration-300 rounded" alt="Zoomed Photo">
            </div>
            <div class="absolute bottom-10 flex gap-4">
                <button onclick="prevImg()" class="w-12 h-12 rounded-full bg-white/20 text-white backdrop-blur flex items-center justify-center active:scale-90"><i class="fa-solid fa-chevron-left"></i></button>
                <button onclick="nextImg()" class="w-12 h-12 rounded-full bg-white/20 text-white backdrop-blur flex items-center justify-center active:scale-90"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

    </div>

    <script>
        // 1. SETUP PARAMETER NAMA TAMU
        const urlParams = new URLSearchParams(window.location.search);
        let guestName = urlParams.get('to') ? decodeURIComponent(urlParams.get('to')) : 'Tamu Undangan';

        document.getElementById('guest-name-cover').innerText = guestName;
        const qrNameEl = document.getElementById('guest-name-qr');
        if(qrNameEl) qrNameEl.innerText = guestName;
        const inputRsvpName = document.getElementById('input-nama-rsvp');
        if(inputRsvpName && guestName !== 'Tamu Undangan') inputRsvpName.value = guestName;

        document.getElementById('user-avatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(guestName)}&background=E5F7ED&color=00B14F`;
        const qrImage = document.getElementById('qr-image');
        if(qrImage) qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(guestName)}`;

        // 2. SPLASH SCREEN & MUSIC
        const audio = document.getElementById('bg-music');
        let isMusicPlaying = false;
        let isAutoScrolling = false;
        let scrollInterval;

        // 9. Live Streaming Platform Switch
        function switchPlatform(title, desc, iconClass, link) {
            const display = document.getElementById('streaming-display');
            display.style.opacity = '0';
            display.style.transform = 'scale(0.98) translateY(10px)';
            setTimeout(() => {
                document.getElementById('platform-title').innerText = title;
                
                // 🔥 PERBAIKAN: Menggabungkan class bawaan ikon dengan ukuran yang pas 🔥
                document.getElementById('platform-icon').className = iconClass + ' text-4xl mb-3 transition-all duration-300';
                
                document.getElementById('platform-link').href = link;
                display.style.opacity = '1';
                display.style.transform = 'scale(1) translateY(0)';
            }, 400);
        }

        function openApp() {
            document.getElementById('cover-page').classList.add('splash-enter');
            document.getElementById('body-main').classList.remove('cover-locked');
            
            setTimeout(() => {
                document.getElementById('main-content').classList.remove('opacity-0');
                document.getElementById('fab-container').classList.remove('opacity-0', 'pointer-events-none');
                document.getElementById('bottom-nav').classList.remove('translate-y-32');
                toggleMusic(true);
            }, 300);
        }

        function toggleMusic(forcePlay = false) {
            const icon = document.getElementById('icon-music');
            if (isMusicPlaying && !forcePlay) {
                audio.pause();
                isMusicPlaying = false;
                icon.classList.remove('animate-spin');
                icon.classList.replace('fa-music', 'fa-volume-xmark');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.add('animate-spin');
                    icon.classList.replace('fa-volume-xmark', 'fa-music');
                }).catch(() => console.log("Auto-play prevented."));
            }
        }

        function toggleAutoScroll() {
            const btn = document.getElementById('btn-scroll');
            const icon = document.getElementById('icon-scroll');
            if (isAutoScrolling) {
                clearInterval(scrollInterval);
                isAutoScrolling = false;
                icon.classList.replace('fa-pause', 'fa-angles-down');
            } else {
                isAutoScrolling = true;
                icon.classList.replace('fa-angles-down', 'fa-pause');
                scrollInterval = setInterval(() => {
                    window.scrollBy({ top: 1, behavior: 'auto' });
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 10) toggleAutoScroll();
                }, 35);
            }
        }
        window.addEventListener('wheel', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });
        window.addEventListener('touchmove', () => { if (isAutoScrolling) toggleAutoScroll(); }, { passive: true });

        // 3. COPY TEXT & TOAST
        function copyRek(elementId) {
            const text = document.getElementById(elementId).innerText.replace(/\s+/g, '');
            navigator.clipboard.writeText(text).then(() => showToast('Disalin ke clipboard!'));
        }
        function copyToClipboardText(elementId, btn) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => showToast('Alamat Disalin!'));
        }
        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-msg').innerText = msg;
            toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2500);
        }

        // 4. FETCH DATA WISHES API & STATS
        let allWishes = [
            @foreach ($dbWishes as $wish)
                { nama: "{{ addslashes($wish->guest_name) }}", pesan: "{{ preg_replace("/\r|\n/", ' ', addslashes($wish->message)) }}", waktu: "{{ \Carbon\Carbon::parse($wish->created_at)->translatedFormat('d F Y, H:i') }} WIB" },
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
                    <div class="text-center py-10 opacity-50">
                        <i class="fa-regular fa-comment-dots text-4xl text-gray-300 mb-3"></i>
                        <p class="text-xs text-gray-500 font-medium">Jadilah yang pertama menulis ulasan!</p>
                    </div>`;
                return;
            }

            allWishes.forEach(wish => {
                const card = document.createElement('div');
                card.className = 'bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-3';
                card.innerHTML = `
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-grab-light flex items-center justify-center text-[10px] font-bold text-grab-green"><i class="fa-solid fa-user"></i></div>
                            <h5 class="text-xs font-bold">${wish.nama}</h5>
                        </div>
                        <span class="text-[9px] text-gray-400 font-bold">${wish.waktu}</span>
                    </div>
                    <p class="text-[11px] text-gray-600 leading-relaxed mt-1">"${wish.pesan}"</p>
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
                    const now = new Date();
                    const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                    const timeString = now.toLocaleDateString('id-ID', options).replace('pukul', ',') + ' WIB';

                    allWishes.unshift({ nama: data.guest_name, pesan: data.message, waktu: timeString });
                    countWishes++;
                    if(data.status_rsvp === 'hadir') countAttendance += parseInt(data.pax);
                    
                    const elAtt = document.getElementById('total-attendance');
                    const elWish = document.getElementById('total-wishes');
                    if(elAtt) { elAtt.innerText = countAttendance; elAtt.classList.add('text-grab-green'); setTimeout(()=>elAtt.classList.remove('text-grab-green'), 500); }
                    if(elWish) { elWish.innerText = countWishes; elWish.classList.add('text-grab-green'); setTimeout(()=>elWish.classList.remove('text-grab-green'), 500); }
                    
                    renderWishes();
                    showToast("Pesanan (RSVP) Berhasil Dikirim!");
                }
            } catch (error) { console.error(error); }
        }

        // 5. MODAL RSVP LOGIC
        let hasShownRSVPAtEnd = false;
        function openRSVP() {
            document.getElementById('rsvp-modal').classList.remove('invisible');
            document.getElementById('body-main').style.overflow = 'hidden';
            setTimeout(() => {
                document.getElementById('rsvp-overlay').classList.replace('opacity-0', 'opacity-100');
                document.getElementById('rsvp-content').classList.replace('translate-y-full', 'translate-y-0');
            }, 10);
        }

        function closeRSVP() {
            document.getElementById('rsvp-overlay').classList.replace('opacity-100', 'opacity-0');
            document.getElementById('rsvp-content').classList.replace('translate-y-0', 'translate-y-full');
            document.getElementById('body-main').style.overflow = 'auto';
            setTimeout(() => document.getElementById('rsvp-modal').classList.add('invisible'), 500);
        }

        function selectAttendance(status) {
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');
            document.getElementById('input-status').value = status;

            const baseBtn = "py-4 rounded-2xl border-2 text-[11px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2";
            
            if (status === 'Hadir') {
                btnHadir.className = baseBtn + " border-grab-green bg-grab-light text-grab-green shadow-sm";
                btnAbsen.className = baseBtn + " border-gray-100 bg-gray-50 text-gray-400";
                guestDiv.classList.remove('hidden');
                setGuestCount(1);
            } else {
                btnAbsen.className = baseBtn + " border-grab-dark bg-gray-100 text-grab-dark shadow-sm";
                btnHadir.className = baseBtn + " border-gray-100 bg-gray-50 text-gray-400";
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
                    btn.classList.add('border-grab-green', 'text-grab-green', 'bg-grab-light');
                    btn.classList.remove('bg-white', 'text-grab-dark', 'border-gray-200');
                } else {
                    btn.classList.remove('border-grab-green', 'text-grab-green', 'bg-grab-light');
                    btn.classList.add('bg-white', 'text-grab-dark', 'border-gray-200');
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

        window.addEventListener('scroll', () => {
            if(document.body.classList.contains('cover-locked')) return;
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50) {
                if (!hasShownRSVPAtEnd) {
                    openRSVP();
                    hasShownRSVPAtEnd = true;
                    if (isAutoScrolling) toggleAutoScroll();
                }
            }
        }, { passive: true });

        // 6. GENERAL MODAL & GIFT
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            const overlay = modal.querySelector('.modal-overlay');
            const content = modal.querySelector('.modal-content');

            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    overlay.classList.replace('opacity-0', 'opacity-100');
                    content.classList.replace('scale-95', 'scale-100');
                    content.classList.replace('opacity-0', 'opacity-100');
                }, 10);
            } else {
                overlay.classList.replace('opacity-100', 'opacity-0');
                content.classList.replace('scale-100', 'scale-95');
                content.classList.replace('opacity-100', 'opacity-0');
                document.body.style.overflow = 'auto';
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }
        }
        function toggleGiftModal(show) { toggleModal('wishlist-modal'); }

        let currentGiftId = null;
        function confirmGift(id, name) {
            currentGiftId = id;
            document.getElementById('confirm-text').innerHTML = `Silakan isi data diri Anda untuk mengonfirmasi pengiriman kado: <b>${name}</b>`;
            
            const inputNameEl = document.getElementById('input-gift-name');
            if (inputNameEl && guestName !== 'Tamu Undangan') inputNameEl.value = guestName;
            
            const inputPaxEl = document.getElementById('input-gift-pax');
            if(inputPaxEl) inputPaxEl.value = 0;

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
            setTimeout(()=> toggleGiftModal(false), 500); 
        }

        // 7. LIGHTBOX GALLERY
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
            setTimeout(() => { imgElement.src = images[currentIndex]; imgElement.style.opacity = '1'; }, 200);
        }
        function nextImg() { if(images.length > 0) { currentIndex = (currentIndex + 1) % images.length; updateLightbox(); } }
        function prevImg() { if(images.length > 0) { currentIndex = (currentIndex - 1 + images.length) % images.length; updateLightbox(); } }
    </script>
</body>
</html>