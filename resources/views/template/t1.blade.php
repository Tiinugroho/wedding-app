@php
    // Decode data content dari database
    $content = json_decode($invitation->details->content ?? '{}', true);

    // Konfigurasi dasar
    $isGroomFirst = ($content['couple_order'] ?? 'groom_first') === 'groom_first';

    // ==========================================
    // LOGIKA COUNTDOWN (DARI RESEPSI PERTAMA)
    // ==========================================
    $hasResepsi = false;
    $countdownDateStr = '';
    $coverDateDisplay = '- . - . -';

    if (!empty($content['events']) && is_array($content['events']) && count($content['events']) > 0) {
        $firstEvent = collect($content['events'])->first();
        if (!empty($firstEvent['date'])) {
            $hasResepsi = true;

            // Format Cover (d . m . Y)
            $coverDateDisplay = \Carbon\Carbon::parse($firstEvent['date'])->format('d . m . Y');

            // Format JS Countdown (Y, m-1, d, H, i, s)
            $eventTime = !empty($firstEvent['time']) ? $firstEvent['time'] : '00:00:00';
            $countdownDateStr = \Carbon\Carbon::parse($firstEvent['date'] . ' ' . $eventTime)->format(
                'Y, m-1, d, H, i, s',
            );
        }
    }

    /// ==========================================
    // LOGIKA AKAD (KOSONGKAN JIKA TIDAK DIISI, JANGAN HARI INI)
    // ==========================================
    $rawDate = !empty($content['akad_date']) ? $content['akad_date'] : null;
    $rawTime = !empty($content['akad_time']) ? $content['akad_time'] : null;
    // Gunakan 00:00:00 jika waktu tidak diisi, tapi tetap null jika tanggal kosong
    $akadDateObj = $rawDate ? \Carbon\Carbon::parse($rawDate . ' ' . ($rawTime ?? '00:00:00')) : null;

    // Path untuk cover
    $coverImage = !empty($content['cover_image'])
        ? asset('storage/' . $content['cover_image'])
        : 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=1000&auto=format&fit=crop';

    // ==========================================
    // LOGIKA TAMU & QR CODE (DARI TABEL GUESTS)
    // ==========================================
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

    // ==========================================
    // LOGIKA UCAPAN & RSVP (DARI TABEL WISHES)
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

    $masterLogos = \DB::table('banks')->pluck('logo', 'name')->toArray();
    $masterLogos = array_change_key_case($masterLogos, CASE_LOWER);
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan - {{ $content['groom_nickname'] ?? 'Romeo' }} &
        {{ $content['bride_nickname'] ?? 'Juliet' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            white: '#FFFFFF',
                            elegant: '#FDFDFD',
                            gold: '#C5A065',
                            charcoal: '#333333',
                            lightGold: '#E4D5B7',
                            softWhite: 'rgba(255, 255, 255, 0.9)',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"Inter"', 'sans-serif'],
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
            overflow-y: hidden;
        }

        .floating-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(197, 160, 101, 0.2);
        }

        .landing-mist {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0.95) 100%);
        }

        .text-protected {
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        #guest-name {
            background: linear-gradient(to bottom, #333333, #555555);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #333333;
        }

        .py-4\.5 {
            padding-top: 1.125rem;
            padding-bottom: 1.125rem;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }

        #mempelai {
            background: linear-gradient(to bottom, #FFFFFF 0%, #FDFDFD 100%);
        }

        #mempelai .group:hover .border-brand-lightGold\/40 {
            border-color: rgba(197, 160, 101, 0.7);
            transform: translate(3px, 3px);
        }

        .scroll-custom::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-custom::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-custom::-webkit-scrollbar-thumb {
            background: #C5A065;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #D1D5DB;
        }

        .gift-item-row {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
</head>

<body
    class="bg-brand-elegant text-brand-charcoal font-sans antialiased relative selection:bg-brand-gold selection:text-white">

    <audio id="bg-music" loop>
        <source
            src="{{ !empty($invitation->music_id) ? asset('storage/' . $invitation->music->file_path) : 'https://cdn.pixabay.com/audio/2021/07/18/audio_c993f91966.mp3' }}"
            type="audio/mpeg">
    </audio>

    <div id="cover-page"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-brand-white transition-transform duration-1000 ease-in-out overflow-hidden">
        <div class="absolute inset-0 opacity-[0.22] bg-cover bg-center scale-105 animate-[pulse_10s_infinite]"
            style="background-image: url('{{ $coverImage }}')"></div>
        <div
            class="absolute inset-0 opacity-[0.025] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-brand-white/10 via-brand-white/70 to-brand-white/95"></div>
        <div class="relative z-10 text-center px-6 max-w-lg w-full">

            <p class="text-xs tracking-[0.4em] uppercase text-brand-gold mb-5 font-semibold drop-shadow-sm">The Wedding
                Of</p>

            <h1
                class="text-6xl md:text-7xl font-serif italic text-brand-gold mb-3 tracking-tight leading-none text-protected">
                {{ $content['groom_nickname'] ?? 'Romeo' }} & {{ $content['bride_nickname'] ?? 'Juliet' }}</h1>

            <div class="flex items-center justify-center gap-5 mb-12">
                <div class="h-[1px] w-12 bg-brand-gold/25"></div>
                <p class="text-sm font-sans tracking-[0.25em] text-brand-charcoal uppercase font-light">
                    {{ $coverDateDisplay }}
                </p>
                <div class="h-[1px] w-12 bg-brand-gold/25"></div>
            </div>

            <div
                class="my-9 p-8 md:p-10 bg-white/35 backdrop-blur-sm rounded-[2rem] border border-white/60 shadow-[0_6px_25px_rgba(197,160,101,0.1)] relative overflow-hidden group">
                <p class="text-xs text-gray-500 mb-3.5 italic font-light tracking-wide">
                    {{ $content['cover_greeting'] ?? 'Yth. Bapak/Ibu/Saudara/i' }}</p>
                <h2 id="guest-name"
                    class="text-3xl md:text-4xl font-serif font-semibold text-brand-charcoal leading-snug">
                    {{ $guestNameDisplay }}
                </h2>
                <div
                    class="mt-5 flex items-center justify-center gap-2.5 text-xs text-brand-gold uppercase tracking-[0.1em] font-medium">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>{{ !empty($content['events'][0]['location']) ? $content['events'][0]['location'] : '-' }}</span>
                </div>
            </div>

            <div class="space-y-4 mt-6">
                <button onclick="openInvitation()"
                    class="group relative px-14 py-4.5 bg-brand-gold hover:bg-brand-charcoal text-white rounded-full transition-all duration-500 shadow-xl hover:shadow-brand-gold/15 flex items-center justify-center gap-3.5 mx-auto overflow-hidden">
                    <div
                        class="absolute inset-0 w-1/2 h-full bg-white/25 skew-x-[-25deg] -translate-x-full group-hover:translate-x-[250%] transition-transform duration-1000">
                    </div>
                    <i class="fa-solid fa-envelope-open-text group-hover:scale-110 transition-transform text-lg"></i>
                    <span class="font-semibold tracking-wide text-sm">Buka Undangan</span>
                </button>
            </div>
        </div>
    </div>

    <main id="main-content" class="min-h-screen pb-28 opacity-0 transition-opacity duration-1000">

        <section id="home"
            class="min-h-screen flex flex-col items-center justify-center text-center p-4 md:p-8 bg-cover bg-center bg-fixed relative overflow-hidden"
            style="background-image: url('{{ $coverImage }}')">
            <div class="absolute inset-0 bg-brand-white/60 backdrop-blur-[2px]"></div>

            <div
                class="relative z-10 max-w-2xl w-full p-6 md:p-14 bg-brand-softWhite/80 rounded-[2.5rem] md:rounded-[3rem] border border-white shadow-[0_20px_50px_rgba(0,0,0,0.05)] backdrop-blur-md">

                <div class="mb-8 md:mb-10">
                    <p class="font-serif italic text-xl md:text-3xl text-brand-gold mb-4 text-protected">
                        {!! nl2br(e($content['quotes'] ?? '"And they lived happily ever after."')) !!}
                    </p>
                    <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mb-6"></div>
                    <p
                        class="text-[12px] md:text-sm text-brand-charcoal max-w-md mx-auto leading-relaxed font-light italic px-4">
                        Dengan memohon rahmat dan ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri
                        acara pernikahan kami.
                    </p>
                </div>

                <div class="pt-8 border-t border-brand-lightGold/20">
                    <p
                        class="text-[9px] md:text-[10px] tracking-[0.4em] uppercase text-brand-gold mb-8 md:mb-10 font-semibold">
                        The Waiting Moment</p>
                    <div class="flex flex-wrap justify-center items-center gap-2 md:gap-8">
                        <div class="relative group p-2">
                            <div
                                class="absolute inset-0 border border-dashed border-brand-gold/20 rounded-full animate-[spin_15s_linear_infinite]">
                            </div>
                            <div
                                class="relative flex flex-col items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-white/40 rounded-full backdrop-blur-sm">
                                <span id="days"
                                    class="text-xl md:text-4xl font-serif font-light text-brand-charcoal leading-none">00</span>
                                <span
                                    class="text-[7px] md:text-[10px] uppercase tracking-widest text-brand-gold font-medium mt-1">Days</span>
                            </div>
                        </div>
                        <span
                            class="text-brand-lightGold/40 font-serif italic text-lg md:text-2xl opacity-50 hidden sm:block">&</span>
                        <div class="relative group p-2">
                            <div
                                class="absolute inset-0 border border-dashed border-brand-gold/20 rounded-full animate-[spin_20s_linear_infinite_reverse]">
                            </div>
                            <div
                                class="relative flex flex-col items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-white/40 rounded-full backdrop-blur-sm">
                                <span id="hours"
                                    class="text-xl md:text-4xl font-serif font-light text-brand-charcoal leading-none">00</span>
                                <span
                                    class="text-[7px] md:text-[10px] uppercase tracking-widest text-brand-gold font-medium mt-1">Hours</span>
                            </div>
                        </div>
                        <span
                            class="text-brand-lightGold/40 font-serif italic text-lg md:text-2xl opacity-50 hidden sm:block">&</span>
                        <div class="relative group p-2">
                            <div
                                class="absolute inset-0 border border-dashed border-brand-gold/20 rounded-full animate-[spin_25s_linear_infinite]">
                            </div>
                            <div
                                class="relative flex flex-col items-center justify-center w-16 h-16 md:w-24 md:h-24 bg-white/40 rounded-full backdrop-blur-sm">
                                <span id="minutes"
                                    class="text-xl md:text-4xl font-serif font-light text-brand-charcoal leading-none">00</span>
                                <span
                                    class="text-[7px] md:text-[10px] uppercase tracking-widest text-brand-gold font-medium mt-1">Mins</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="mempelai" class="py-12 px-6 bg-brand-white relative overflow-hidden">
            <div
                class="absolute inset-0 opacity-[0.04] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/linen-headboard.png')]">
            </div>
            <div
                class="absolute -top-20 -left-20 w-96 h-96 border border-brand-lightGold/10 rounded-full pointer-events-none overflow-hidden">
            </div>
            <div
                class="absolute -bottom-32 -right-10 w-[30rem] h-[30rem] border border-brand-lightGold/10 rounded-full pointer-events-none overflow-hidden">
            </div>

            <div class="max-w-6xl mx-auto relative z-10">
                <div class="text-center mb-24">
                    <p class="text-[10px] tracking-[0.5em] uppercase text-brand-gold mb-3 font-semibold drop-shadow-sm">
                        Meet The Couple</p>
                    <div class="h-[1px] w-20 bg-brand-lightGold/30 mx-auto"></div>
                </div>

                @php
                    // --- LOGIKA PERTUKARAN DATA (KIRI & KANAN) ---
                    $isGroomFirst = ($content['couple_order'] ?? 'groom_first') === 'groom_first';

                    // Data Card Kiri
                    $leftName = $isGroomFirst
                        ? $content['groom_name'] ?? 'Romeo Montague'
                        : $content['bride_name'] ?? 'Juliet Capulet';
                    $leftPhoto = $isGroomFirst
                        ? (!empty($content['groom_photo'])
                            ? asset('storage/' . $content['groom_photo'])
                            : 'https://images.soco.id/230-58.jpg.jpeg')
                        : (!empty($content['bride_photo'])
                            ? asset('storage/' . $content['bride_photo'])
                            : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg');
                    $leftLabel = $isGroomFirst ? '- The Groom -' : '- The Bride -';
                    $leftParentsPrefix = $isGroomFirst ? 'Putra Tercinta dari' : 'Putri Tercinta dari';
                    $leftFather = $isGroomFirst
                        ? $content['groom_father'] ?? 'Bapak'
                        : $content['bride_father'] ?? 'Bapak';
                    $leftMother = $isGroomFirst ? $content['groom_mother'] ?? 'Ibu' : $content['bride_mother'] ?? 'Ibu';
                    $leftIg = $isGroomFirst ? $content['groom_ig'] ?? '' : $content['bride_ig'] ?? '';

                    // Data Card Kanan
                    $rightName = $isGroomFirst
                        ? $content['bride_name'] ?? 'Juliet Capulet'
                        : $content['groom_name'] ?? 'Romeo Montague';
                    $rightPhoto = $isGroomFirst
                        ? (!empty($content['bride_photo'])
                            ? asset('storage/' . $content['bride_photo'])
                            : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg')
                        : (!empty($content['groom_photo'])
                            ? asset('storage/' . $content['groom_photo'])
                            : 'https://images.soco.id/230-58.jpg.jpeg');
                    $rightLabel = $isGroomFirst ? '- The Bride -' : '- The Groom -';
                    $rightParentsPrefix = $isGroomFirst ? 'Putri Tercinta dari' : 'Putra Tercinta dari';
                    $rightFather = $isGroomFirst
                        ? $content['bride_father'] ?? 'Bapak'
                        : $content['groom_father'] ?? 'Bapak';
                    $rightMother = $isGroomFirst
                        ? $content['bride_mother'] ?? 'Ibu'
                        : $content['groom_mother'] ?? 'Ibu';
                    $rightIg = $isGroomFirst ? $content['bride_ig'] ?? '' : $content['groom_ig'] ?? '';
                @endphp

                <div class="space-y-24 md:space-y-0 md:flex md:items-center md:justify-center md:gap-20 lg:gap-32">

                    <div class="flex flex-col items-center md:items-start text-center md:text-left group flex-1">
                        <div
                            class="relative mb-10 w-64 h-80 md:w-72 md:h-96 group-hover:-translate-y-2 transition-transform duration-500 ease-out">
                            <div
                                class="absolute -bottom-4 -right-4 inset-0 border border-brand-lightGold/40 rounded-t-[10rem] rounded-b-xl z-0">
                            </div>
                            <div
                                class="absolute inset-0 bg-brand-lightGold overflow-hidden rounded-t-[10rem] rounded-b-xl shadow-[0_15px_45px_rgba(197,160,101,0.1)] z-10 border-4 border-white">
                                <img src="{{ $leftPhoto }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    alt="{{ $leftName }}">
                            </div>
                            <div class="absolute -top-8 -left-8 w-24 h-24 opacity-60 z-20 pointer-events-none">
                                <img src="https://www.transparentpng.com/thumb/flowers-vectors/pink-and-whire-flower-vector-hq-png-6.png"
                                    class="w-full h-full object-contain" alt="ornament">
                            </div>
                        </div>

                        <h3 class="text-3xl lg:text-4xl font-serif font-bold text-brand-charcoal mb-2 leading-tight">
                            {{ $leftName }}</h3>
                        <p
                            class="text-xs text-brand-gold uppercase tracking-[0.3em] font-medium mb-5 border-b border-brand-lightGold/30 inline-block pb-1">
                            {{ $leftLabel }}</p>

                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-500 font-light tracking-wide">{{ $leftParentsPrefix }}</p>
                            <p class="text-sm text-brand-charcoal font-medium">{{ $leftFather }}</p>
                            <p class="text-sm text-brand-charcoal font-medium">& {{ $leftMother }}</p>
                            @if (!empty($leftIg))
                                <a href="https://instagram.com/{{ str_replace('@', '', $leftIg) }}" target="_blank"
                                    class="inline-block mt-3 text-xs text-brand-gold hover:text-brand-charcoal transition-colors">
                                    <i class="fa-brands fa-instagram mr-1"></i> {{ $leftIg }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col items-center md:items-end text-center md:text-right group flex-1">
                        <div
                            class="relative mb-10 w-64 h-80 md:w-72 md:h-96 group-hover:-translate-y-2 transition-transform duration-500 ease-out">
                            <div
                                class="absolute -bottom-4 -left-4 inset-0 border border-brand-lightGold/40 rounded-t-[10rem] rounded-b-xl z-0">
                            </div>
                            <div
                                class="absolute inset-0 bg-brand-lightGold overflow-hidden rounded-t-[10rem] rounded-b-xl shadow-[0_15px_45px_rgba(197,160,101,0.1)] z-10 border-4 border-white">
                                <img src="{{ $rightPhoto }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    alt="{{ $rightName }}">
                            </div>
                            <div
                                class="absolute -bottom-8 -right-8 w-24 h-24 opacity-60 z-20 pointer-events-none rotate-180">
                                <img src="https://www.transparentpng.com/thumb/flowers-vectors/pink-and-whire-flower-vector-hq-png-6.png"
                                    class="w-full h-full object-contain" alt="ornament">
                            </div>
                        </div>

                        <h3 class="text-3xl lg:text-4xl font-serif font-bold text-brand-charcoal mb-2 leading-tight">
                            {{ $rightName }}</h3>
                        <p
                            class="text-xs text-brand-gold uppercase tracking-[0.3em] font-medium mb-5 border-b border-brand-lightGold/30 inline-block pb-1">
                            {{ $rightLabel }}</p>

                        <div class="space-y-1">
                            <p class="text-[11px] text-gray-500 font-light tracking-wide">{{ $rightParentsPrefix }}
                            </p>
                            <p class="text-sm text-brand-charcoal font-medium">{{ $rightFather }}</p>
                            <p class="text-sm text-brand-charcoal font-medium">& {{ $rightMother }}</p>
                            @if (!empty($rightIg))
                                <a href="https://instagram.com/{{ str_replace('@', '', $rightIg) }}" target="_blank"
                                    class="inline-block mt-3 text-xs text-brand-gold hover:text-brand-charcoal transition-colors">
                                    <i class="fa-brands fa-instagram mr-1"></i> {{ $rightIg }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (($content['is_turut_mengundang_active'] ?? false) && !empty($content['turut_mengundang']))
                    <div
                        class="mt-20 pt-12 border-t border-brand-lightGold/15 bg-brand-elegant/50 rounded-3xl p-10 md:p-16 relative">
                        <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-brand-white px-6">
                            <i class="fa-solid fa-quote-left text-brand-lightGold/50 text-3xl"></i>
                        </div>

                        <p class="text-[11px] tracking-[0.4em] uppercase text-gray-400 mb-12 font-medium text-center">
                            Turut Mengundang</p>

                        <div
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-10 gap-y-6 text-xs text-brand-charcoal/80 font-light leading-relaxed max-w-3xl mx-auto text-center md:text-left">
                            @foreach ($content['turut_mengundang'] as $tamu_undangan)
                                <p>{{ trim($tamu_undangan) }}</p>
                            @endforeach
                        </div>

                        <div class="text-center mt-12">
                            <i class="fa-solid fa-quote-right text-brand-lightGold/50 text-3xl opacity-50"></i>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        @if (($content['is_story_active'] ?? false) && !empty($content['love_stories']))
            <section id="story" class="py-28 px-6 bg-brand-white relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
                </div>
                <div class="max-w-3xl mx-auto relative z-10">
                    <div class="text-center mb-20">
                        <p class="text-[10px] tracking-[0.5em] uppercase text-brand-gold mb-3 font-semibold">Our
                            Journey</p>
                        <h2 class="text-4xl font-serif italic text-brand-charcoal">Cerita Cinta Kami</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mt-6"></div>
                    </div>

                    <div class="relative space-y-16">
                        <div
                            class="absolute left-1/2 -translate-x-1/2 top-0 bottom-0 w-[1px] bg-gradient-to-b from-brand-gold/50 via-brand-gold/20 to-transparent">
                        </div>

                        @foreach (array_slice($content['love_stories'], 0, 3) as $story)
                            <div class="story-item relative flex flex-col items-center group">
                                <div
                                    class="z-10 w-4 h-4 rounded-full bg-brand-white border-2 border-brand-gold mb-6 group-hover:scale-125 transition-transform duration-300">
                                </div>
                                <div
                                    class="w-full bg-brand-elegant/50 p-6 md:p-10 rounded-[2.5rem] border border-brand-lightGold/20 shadow-sm backdrop-blur-sm transition-all duration-500 hover:shadow-md">
                                    @if (!empty($story['image']))
                                        <div
                                            class="aspect-video w-full rounded-2xl overflow-hidden mb-6 bg-brand-lightGold">
                                            <img src="{{ asset('storage/' . $story['image']) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                                alt="{{ $story['title'] ?? 'Story Image' }}">
                                        </div>
                                    @endif
                                    <span
                                        class="text-[10px] font-bold tracking-[0.2em] text-brand-gold uppercase">{{ $story['year'] ?? '' }}</span>
                                    <h4 class="text-xl font-serif font-bold text-brand-charcoal mt-2 mb-3">
                                        {{ $story['title'] ?? '' }}</h4>
                                    <p class="text-sm text-gray-500 leading-relaxed font-light">
                                        {{ $story['description'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach

                        @if (count($content['love_stories']) > 3)
                            <div id="extra-stories" class="hidden space-y-16">
                                @foreach (array_slice($content['love_stories'], 3) as $story)
                                    <div class="story-item relative flex flex-col items-center group">
                                        <div
                                            class="z-10 w-4 h-4 rounded-full bg-brand-white border-2 border-brand-gold mb-6 group-hover:scale-125 transition-transform duration-300">
                                        </div>
                                        <div
                                            class="w-full bg-brand-elegant/50 p-6 md:p-10 rounded-[2.5rem] border border-brand-lightGold/20 shadow-sm backdrop-blur-sm transition-all duration-500 hover:shadow-md">
                                            @if (!empty($story['image']))
                                                <div
                                                    class="aspect-video w-full rounded-2xl overflow-hidden mb-6 bg-brand-lightGold">
                                                    <img src="{{ asset('storage/' . $story['image']) }}"
                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                                        alt="{{ $story['title'] ?? 'Story Image' }}">
                                                </div>
                                            @endif
                                            <span
                                                class="text-[10px] font-bold tracking-[0.2em] text-brand-gold uppercase">{{ $story['year'] ?? '' }}</span>
                                            <h4 class="text-xl font-serif font-bold text-brand-charcoal mt-2 mb-3">
                                                {{ $story['title'] ?? '' }}</h4>
                                            <p class="text-sm text-gray-500 leading-relaxed font-light">
                                                {{ $story['description'] ?? '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if (count($content['love_stories']) > 3)
                        <div class="mt-20 text-center">
                            <button id="btn-read-more" onclick="toggleStories()"
                                class="px-10 py-3.5 bg-transparent border border-brand-gold/40 text-brand-gold rounded-full text-xs font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-white transition-all duration-500 shadow-sm">
                                Baca Selengkapnya
                            </button>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @if ($content['is_guest_info_active'] ?? false)
            <section class="py-16 px-6 bg-brand-white text-center">
                <div class="max-w-2xl mx-auto border-2 border-dashed border-brand-lightGold/50 rounded-[2rem] p-8">
                    <i class="fa-solid fa-circle-info text-2xl text-brand-gold mb-4"></i>
                    <h4 class="text-xl font-serif font-bold mb-6 text-brand-charcoal">Informasi Tamu</h4>

                    @if ($content['enable_dresscode'] ?? false)
                        <div class="mb-6">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Dresscode</p>
                            <p class="text-sm font-medium text-brand-charcoal">{{ $content['dresscode'] ?? '' }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl mx-auto p-4 items-start">
                        @if ($content['enable_health_protocol'] ?? false)
                            <div class="flex flex-col items-center">
                                <p
                                    class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-4 text-center">
                                    Protokol Kesehatan</p>
                                <div class="grid grid-cols-3 gap-y-6 gap-x-2 text-brand-gold text-2xl w-full">
                                    <div id="protokol-cuci-tangan" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-hands-bubbles"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Cuci
                                            Tangan</span></div>
                                    <div id="protokol-pakai-masker" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-head-side-mask"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Pakai
                                            Masker</span></div>
                                    <div id="protokol-jaga-jarak" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-people-arrows"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Jaga
                                            Jarak</span></div>
                                    <div id="protokol-hindari-kerumunan" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-users-slash"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">No
                                            Kerumunan</span></div>
                                    <div id="protokol-cek-suhu" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-temperature-high"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Cek
                                            Suhu</span></div>
                                    <div id="protokol-desinfektan" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-spray-can-sparkles"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Desinfektan</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($content['enable_adab_walimah'] ?? false)
                            <div class="flex flex-col items-center">
                                <p
                                    class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-4 text-center">
                                    Adab Walimah</p>
                                <div class="grid grid-cols-3 gap-y-6 gap-x-2 text-brand-gold text-2xl w-full">
                                    <div id="adab-sholat" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-mosque"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Waktu
                                            Sholat</span></div>
                                    <div id="adab-makan-minum" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-utensils"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Adab
                                            Makan</span></div>
                                    <div id="adab-mendoakan" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-hands-praying"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Doa
                                            Restu</span></div>
                                    <div id="adab-jaga-jarak" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-restroom"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Jaga
                                            Jarak</span></div>
                                    <div id="adab-pakaian-sopan" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-shirt"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Baju
                                            Sopan</span></div>
                                    <div id="adab-larangan-foto" class="flex flex-col items-center gap-2"><i
                                            class="fa-solid fa-video-slash"></i><span
                                            class="text-[8px] md:text-[9px] text-gray-500 uppercase text-center leading-tight">Izin
                                            Foto</span></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_gallery_active'] ?? false)
            <section id="gallery" class="py-24 px-6 max-w-6xl mx-auto text-center overflow-hidden">
                <div class="mb-16">
                    <h2 class="text-5xl font-serif text-brand-charcoal mb-4 italic">Our Gallery</h2>
                    <div class="h-[1px] w-12 bg-brand-gold/40 mx-auto mb-4"></div>
                    <p class="text-xs text-brand-gold tracking-[0.4em] uppercase font-medium">Momen Bahagia Kami</p>
                </div>

                @php $youtubeLink = $content['youtube_links'][0] ?? null; @endphp
                @if ($youtubeLink)
                    <div id="video-container" class="mb-12 animate-fade-in">
                        <div
                            class="relative w-full pb-[56.25%] rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white">
                            <iframe id="youtube-iframe" class="absolute top-0 left-0 w-full h-full"
                                src="{{ str_replace('watch?v=', 'embed/', $youtubeLink) }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6" id="photo-grid">
                    @foreach ($invitation->galleries ?? [] as $index => $gallery)
                        <div class="group relative aspect-square bg-brand-lightGold rounded-3xl overflow-hidden shadow-sm border-4 border-white cursor-pointer"
                            onclick="openLightbox({{ $index }})">
                            <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                class="gallery-img w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="Gallery Image">
                            <div
                                class="absolute inset-0 bg-brand-charcoal/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="fa-solid fa-magnifying-glass-plus text-white text-2xl"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <div id="lightbox"
                class="fixed inset-0 z-[200] bg-brand-charcoal/95 backdrop-blur-lg hidden flex-col items-center justify-center p-4">
                <button onclick="closeLightbox()"
                    class="absolute top-6 right-6 text-white text-3xl hover:text-brand-gold transition-colors z-[210]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <button onclick="prevImg()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white text-4xl p-4 z-[210]">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button onclick="nextImg()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-white text-4xl p-4 z-[210]">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <div class="relative max-w-5xl max-h-[80vh] flex items-center justify-center select-none"
                    id="lightbox-container">
                    <img id="lightbox-img" src=""
                        class="max-w-full max-h-[80vh] rounded-xl shadow-2xl object-contain transition-all duration-500"
                        alt="Full Image">
                </div>
            </div>
        @endif

        <section id="lokasi" class="py-12 px-6 bg-brand-elegant border-y border-brand-lightGold/30 shadow-inner">
            
            @php
                // Hitung total card (1 Akad + Jumlah Resepsi jika aktif)
                $totalEvents = 0;
                if (($content['is_event_active'] ?? false) && !empty($content['events']) && is_array($content['events'])) {
                    $totalEvents = count($content['events']);
                }
                $totalCards = 1 + $totalEvents;

                // Tentukan class grid dan max-width berdasarkan jumlah card
                if ($totalCards == 1) {
                    $gridClass = 'grid-cols-1 max-w-lg'; // Jika cuma Akad, jadikan 1 kolom di tengah
                } elseif ($totalCards == 2) {
                    $gridClass = 'grid-cols-1 md:grid-cols-2 max-w-4xl'; // Ekuivalen col-6 (2 kolom)
                } else {
                    $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 max-w-6xl'; // Ekuivalen col-4 (3 kolom)
                }
            @endphp

            <div class="mx-auto text-center {{ $gridClass === 'grid-cols-1 max-w-lg' ? 'max-w-lg' : 'max-w-6xl' }}">
                <h2 class="text-4xl font-serif text-brand-charcoal mb-4">Lokasi Acara</h2>
                <p class="text-sm text-brand-gold tracking-widest uppercase mb-12">Tempat & Waktu</p>

                <div class="grid {{ $gridClass }} gap-8 text-left mx-auto">

                    <div class="bg-brand-white p-10 rounded-3xl shadow-lg border border-brand-lightGold/50 flex flex-col h-full">
                        <div class="flex-grow text-center">
                            <p class="text-xs font-bold uppercase tracking-widest text-brand-gold mb-3">Akad Nikah</p>
                            <i class="fa-solid fa-map-location-dot text-5xl text-brand-gold mb-6"></i>

                            <h3 class="text-2xl font-semibold mb-3">
                                {{ !empty($content['akad_location']) ? $content['akad_location'] : '-' }}
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-8 leading-relaxed">
                                {{ !empty($content['akad_address']) ? $content['akad_address'] : '-' }}<br><br>
                                
                                {{ $akadDateObj ? $akadDateObj->translatedFormat('l, d F Y') : '-' }} <br>
                                
                                @php
                                    $aTime = !empty($content['akad_time']) && $content['akad_time'] !== '00:00' && $content['akad_time'] !== '00:00:00' ? substr($content['akad_time'], 0, 5) : null;
                                    $aTimeEnd = !empty($content['akad_time_end']) ? substr($content['akad_time_end'], 0, 5) : null;
                                @endphp
                                {{ $aTime ? $aTime . ($aTimeEnd ? ' - ' . $aTimeEnd : '') . ' WIB' : '-' }}
                            </p>
                        </div>

                        @if (!empty($content['akad_map']))
                            <div class="mt-auto text-center">
                                <a href="{{ $content['akad_map'] }}" target="_blank"
                                    class="inline-block w-full px-8 py-3 bg-brand-elegant border border-brand-gold text-brand-gold rounded-full hover:bg-brand-gold hover:text-white transition-colors text-sm font-semibold">
                                    <i class="fa-solid fa-location-arrow mr-2"></i> Buka Google Maps
                                </a>
                            </div>
                        @endif
                    </div>

                    @if ($content['is_event_active'] ?? false)
                        @foreach ($content['events'] ?? [] as $event)
                            <div class="bg-brand-white p-10 rounded-3xl shadow-lg border border-brand-lightGold/50 flex flex-col h-full">
                                <div class="flex-grow text-center">
                                    <p class="text-xs font-bold uppercase tracking-widest text-brand-gold mb-3">
                                        {{ $event['title'] ?? 'Resepsi' }}</p>
                                    <i class="fa-solid fa-map-pin text-5xl text-brand-gold mb-6"></i>
                                    
                                    <h3 class="text-2xl font-semibold mb-3">
                                        {{ !empty($event['location']) ? $event['location'] : '-' }}
                                    </h3>
                                    
                                    <p class="text-sm text-gray-600 mb-8 leading-relaxed">
                                        {{ !empty($event['address']) ? $event['address'] : '-' }}<br><br>
                                        
                                        {{ !empty($event['date']) ? \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') : '-' }} <br>
                                        
                                        @php
                                            $eTime = !empty($event['time']) && $event['time'] !== '00:00' && $event['time'] !== '00:00:00' ? substr($event['time'], 0, 5) : null;
                                            $eTimeEnd = !empty($event['time_end']) ? substr($event['time_end'], 0, 5) : null;
                                        @endphp
                                        {{ $eTime ? $eTime . ($eTimeEnd ? ' - ' . $eTimeEnd : '') . ' WIB' : '-' }}
                                    </p>
                                </div>
                                
                                @if (!empty($event['map']))
                                    <div class="mt-auto text-center">
                                        <a href="{{ $event['map'] }}" target="_blank"
                                            class="inline-block w-full px-8 py-3 bg-brand-elegant border border-brand-gold text-brand-gold rounded-full hover:bg-brand-gold hover:text-white transition-colors text-sm font-semibold">
                                            <i class="fa-solid fa-location-arrow mr-2"></i> Buka Google Maps
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </section>

        @if ($content['is_livestream_active'] ?? false)
            <section id="live-streaming" class="py-24 px-6 bg-brand-white relative overflow-hidden font-sans">
                <div
                    class="absolute inset-0 opacity-[0.05] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/graphy-light.png')]">
                </div>
                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-12">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 border border-red-500/20 rounded-full mb-6">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-600 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                            </span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-red-600">Live Virtual
                                Wedding</span>
                        </div>
                        <h2 class="text-4xl font-serif italic text-brand-charcoal mb-4">Siaran Langsung</h2>
                        <p class="text-sm text-gray-500 font-light max-w-md mx-auto">Bergabunglah dalam momen bahagia
                            kami secara virtual melalui platform di bawah ini.</p>
                    </div>

                    <div
                        class="relative max-w-3xl mx-auto p-4 bg-brand-elegant/20 rounded-[2.5rem] border border-brand-gold/10 backdrop-blur-sm">
                        <div id="streaming-display"
                            class="relative aspect-video rounded-[1.8rem] bg-brand-charcoal overflow-hidden flex items-center justify-center shadow-inner">
                            <div class="absolute inset-0 opacity-30 grayscale hover:grayscale-0 transition-all duration-1000 bg-cover bg-center"
                                style="background-image: url('{{ $coverImage }}');"></div>
                            <div class="relative z-10 flex flex-col items-center p-6 text-white text-center">
                                @php
                                    $platform = strtolower($content['live_stream_platform'] ?? 'youtube');
                                    $iconClasses = [
                                        'youtube' => 'fa-brands fa-youtube',
                                        'tiktok' => 'fa-brands fa-tiktok',
                                        'instagram' => 'fa-brands fa-instagram',
                                        'zoom' => 'fa-solid fa-video',
                                    ];
                                    $iconDisplay = $iconClasses[$platform] ?? 'fa-solid fa-video';
                                @endphp
                                <div
                                    class="mb-4 p-5 bg-white/10 backdrop-blur-xl rounded-full border border-white/20 shadow-2xl">
                                    <i id="platform-icon" class="{{ $iconDisplay }} text-5xl"></i>
                                </div>
                                <h3 id="platform-title" class="text-xl font-serif italic mb-2 capitalize">
                                    {{ $platform }} Live</h3>
                                <a id="platform-link" href="{{ $content['live_stream_link'] ?? '#' }}"
                                    target="_blank"
                                    class="px-10 py-3.5 mt-4 bg-brand-gold text-white rounded-full text-[11px] font-bold uppercase tracking-[0.2em] transition-all duration-300 hover:scale-105 shadow-lg active:scale-95">
                                    Gabung Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_wishes_active'] ?? false)
            <section id="guest-stats" class="py-20 px-6 bg-brand-elegant/30 relative overflow-hidden">
                <div class="max-w-4xl mx-auto relative z-10">
                    <div class="text-center mb-12">
                        <p class="text-[10px] tracking-[0.4em] uppercase text-brand-gold mb-3 font-semibold">Guest
                            Participation</p>
                        <h2 class="text-3xl font-serif italic text-brand-charcoal">Kehadiran & Doa</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/20 mx-auto mt-4"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10">
                        <div
                            class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-brand-lightGold/20 flex items-center gap-6 transition-transform hover:scale-[1.02] duration-300">
                            <div
                                class="w-16 h-16 bg-brand-elegant rounded-full flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-users text-brand-gold text-2xl"></i>
                            </div>
                            <div>
                                <h4 id="total-attendance" class="text-4xl font-serif font-bold text-brand-charcoal">0
                                </h4>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-medium">Orang Akan
                                    Hadir</p>
                            </div>
                        </div>
                        <div
                            class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-brand-lightGold/20 flex items-center gap-6 transition-transform hover:scale-[1.02] duration-300">
                            <div
                                class="w-16 h-16 bg-brand-elegant rounded-full flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-comment-dots text-brand-gold text-2xl"></i>
                            </div>
                            <div>
                                <h4 id="total-wishes" class="text-4xl font-serif font-bold text-brand-charcoal">0</h4>
                                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-medium">Ucapan
                                    Hangat</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-16 bg-white/60 backdrop-blur-md rounded-[3rem] border border-brand-lightGold/20 p-6 md:p-10 shadow-sm relative overflow-hidden">
                        <div
                            class="flex items-center justify-between mb-8 border-b border-brand-lightGold/10 pb-4 px-2">
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-brand-gold">Ucapan
                                Sahabat & Keluarga</span>
                            <i class="fa-solid fa-feather-pointed text-brand-gold/40"></i>
                        </div>

                        <div id="wishes-container" class="space-y-6"></div>

                        <div class="mt-12 text-center">
                            <button id="btn-load-more" onclick="loadMoreWishes()" style="display: none;"
                                class="px-8 py-3 bg-transparent border border-brand-gold/40 text-brand-gold rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-white transition-all duration-500 shadow-sm">
                                Lihat Ucapan Lainnya
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_gift_active'] ?? false)
            <section id="hadiah" class="py-24 px-6 bg-brand-white relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
                </div>
                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <i class="fa-solid fa-gift text-brand-gold text-3xl mb-4 opacity-70"></i>
                        <h2 class="text-4xl font-serif italic text-brand-charcoal mb-4">Kirim Hadiah</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mb-6"></div>
                        <p class="text-sm text-gray-500 font-light leading-relaxed max-w-lg mx-auto">
                            Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika Anda ingin
                            memberikan
                            tanda kasih, Anda dapat mengirimkannya melalui:
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
                        @foreach ($content['banks'] ?? [] as $index => $bank)
                            @php
                                $bNameRaw = trim($bank['name'] ?? '');
                                $bNameLower = strtolower($bNameRaw);

                                // 2. Cocokkan nama bank (lowercase) dengan data array, jika tidak ada pakai default
                                $logoUrl =
                                    $masterLogos[$bNameLower] ??
                                    'https://cdn-icons-png.flaticon.com/512/2830/2830284.png';
                            @endphp

                            <div
                                class="group relative p-10 bg-brand-elegant/40 rounded-[3rem] border border-brand-lightGold/20 backdrop-blur-sm transition-all duration-500 hover:shadow-2xl hover:shadow-brand-gold/10 hover:-translate-y-2">
                                <div class="flex flex-col items-center">

                                    <img src="{{ $logoUrl }}"
                                        class="h-10 w-auto object-contain mb-6 transition-all duration-700 hover:scale-110 drop-shadow-sm"
                                        alt="{{ $bNameRaw }}">

                                    <p class="text-[10px] uppercase tracking-[0.3em] text-brand-gold mb-2 font-bold">
                                        {{ $bNameRaw }}</p>
                                    <h3 id="rek-{{ $index }}"
                                        class="text-3xl font-serif font-bold text-brand-charcoal mb-2 tracking-widest">
                                        {{ $bank['account_number'] ?? '-' }}
                                    </h3>
                                    <p class="text-sm text-gray-400 italic mb-8 font-light">
                                        a.n {{ $bank['account_name'] ?? 'Mempelai' }}
                                    </p>

                                    <button onclick="copyToClipboard('rek-{{ $index }}', this)"
                                        class="w-full py-4 bg-brand-gold text-white rounded-2xl text-[11px] font-bold uppercase tracking-[0.2em] transition-all duration-300 hover:bg-brand-charcoal shadow-lg shadow-brand-gold/20 active:scale-95">
                                        <i class="fa-regular fa-copy mr-2"></i>
                                        <span>Salin Nomor</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="copy-toast"
                        class="fixed bottom-28 left-1/2 -translate-x-1/2 z-[300] px-8 py-3.5 bg-brand-charcoal/90 backdrop-blur-md text-white text-[10px] rounded-full tracking-[0.3em] uppercase font-bold opacity-0 transition-all duration-500 pointer-events-none shadow-2xl border border-white/10">
                        Berhasil Disalin
                    </div>
                </div>
            </section>

            <section id="kirim-kado" class="py-24 px-6 bg-brand-white relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
                </div>

                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <i class="fa-solid fa-box-open text-brand-gold text-3xl mb-4 opacity-70"></i>
                        <h2 class="text-4xl font-serif italic text-brand-charcoal mb-4">Kirimkan Kado</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mb-6"></div>
                        <p class="text-sm text-gray-500 font-light max-w-lg mx-auto">
                            Jika Anda ingin mengirimkan tanda kasih berupa barang, silakan kirimkan ke alamat kami atau
                            pilih dari daftar kebutuhan kami.
                        </p>
                    </div>

                    <div class="flex flex-col items-center gap-6">
                        <div
                            class="group relative p-8 bg-brand-elegant/40 rounded-[2.5rem] border border-brand-lightGold/20 backdrop-blur-sm max-w-xl w-full">
                            <p
                                class="text-[10px] uppercase tracking-[0.3em] text-brand-gold mb-3 font-bold text-center">
                                Alamat Pengiriman</p>
                            <div id="alamat-kado"
                                class="text-sm text-gray-600 font-light leading-relaxed mb-6 italic text-center">
                                {{ !empty($content['alamat_kado']) ? $content['alamat_kado'] : $content['akad_address'] ?? 'Alamat belum diatur oleh mempelai.' }}
                            </div>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button onclick="copyToClipboardText('alamat-kado', this)"
                                    class="px-6 py-3 bg-white text-brand-gold border border-brand-gold/20 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all hover:bg-brand-gold hover:text-white">
                                    Salin Alamat
                                </button>
                                <button onclick="toggleGiftModal(true)"
                                    class="px-6 py-3 bg-brand-gold text-white rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all shadow-lg shadow-brand-gold/20 hover:bg-brand-charcoal">
                                    <i class="fa-solid fa-list-check mr-2"></i> Lihat Daftar Kado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="gift-modal" class="fixed inset-0 z-[500] hidden flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-brand-charcoal/70 backdrop-blur-sm"
                        onclick="toggleGiftModal(false)"></div>
                    <div
                        class="relative bg-white w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl flex flex-col max-h-[85vh]">
                        <div class="p-8 border-b border-gray-100 bg-brand-white shrink-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-2xl font-serif italic text-brand-charcoal">Daftar Kebutuhan</h3>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mt-1 font-medium">
                                        Wedding Registry</p>
                                </div>
                                <button onclick="toggleGiftModal(false)"
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-red-500 transition-all">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div class="mt-4 p-4 bg-brand-gold/5 rounded-2xl border border-brand-gold/10 text-center">
                                <p class="text-[11px] text-brand-gold italic leading-relaxed">
                                    "Doa restu Anda adalah yang utama. Namun jika Anda berkenan memberikan tanda kasih
                                    berupa barang, silakan pilih salah satu daftar di bawah ini."
                                </p>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto p-8 pt-4 custom-scrollbar" id="gift-list-container">
                            <div class="space-y-4">
                                @if (!empty($content['gifts']) && is_array($content['gifts']) && count($content['gifts']) > 0)
                                    @foreach ($content['gifts'] as $index => $gift)
                                        <div id="item-{{ $index }}"
                                            class="gift-item-row p-5 rounded-[2rem] border border-gray-100 bg-white flex items-center justify-between gap-4 transition-all hover:border-brand-gold/30">
                                            <div class="flex-1">
                                                <h4
                                                    class="text-xs font-bold text-brand-charcoal uppercase tracking-wider">
                                                    {{ $gift['item_name'] ?? 'Kado' }}</h4>
                                                <p class="text-[10px] text-gray-400 font-light">
                                                    {{ $gift['description'] ?? '' }}</p>
                                            </div>
                                            <button
                                                onclick="confirmGift('item-{{ $index }}', '{{ $gift['item_name'] ?? 'Kado' }}')"
                                                class="shrink-0 px-6 py-3 bg-brand-white text-brand-gold border border-brand-gold/30 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-brand-gold hover:text-white transition-all duration-300">
                                                Saya Bersedia Membantu
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-xs text-gray-500 text-center italic">Daftar kado belum ditambahkan
                                        oleh pengantin.</p>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 bg-gray-50 text-center shrink-0">
                            <p class="text-[10px] text-gray-400 italic">Terima kasih atas segala perhatian dan kebaikan
                                hati Anda.</p>
                        </div>
                    </div>
                </div>

                <div id="confirm-modal" class="fixed inset-0 z-[600] hidden flex items-center justify-center p-6">
                    <div class="absolute inset-0 bg-brand-charcoal/80 backdrop-blur-md"></div>
                    <div class="relative bg-white w-full max-w-sm rounded-[2.5rem] p-10 text-center shadow-2xl">
                        <div
                            class="w-20 h-20 bg-brand-gold/10 text-brand-gold rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-heart text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-serif italic text-brand-charcoal mb-4">Konfirmasi Niat Baik</h4>
                        <p id="confirm-text" class="text-sm text-gray-500 font-light leading-relaxed mb-8">
                            Apakah Anda yakin ingin mengirimkan kado ini? Nama Anda akan tercatat dalam sistem kami
                            sebagai bentuk apresiasi.
                        </p>
                        <div class="flex flex-col gap-3">
                            <button id="final-confirm-btn"
                                class="w-full py-4 bg-brand-gold text-white rounded-2xl text-[11px] font-bold uppercase tracking-widest shadow-lg shadow-brand-gold/20 active:scale-95 transition-all">
                                Ya, Saya Yakin
                            </button>
                            <button onclick="closeConfirmModal()"
                                class="w-full py-4 bg-transparent text-gray-400 text-[10px] font-bold uppercase tracking-widest hover:text-brand-charcoal transition-all">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>

                <div id="gift-toast"
                    class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[700] px-10 py-5 bg-brand-charcoal text-white rounded-full text-[11px] font-bold uppercase tracking-[0.2em] shadow-2xl border border-white/10 opacity-0 transition-all duration-500 pointer-events-none text-center min-w-[320px]">
                    Terima kasih atas ketulusan Anda!
                </div>
            </section>
        @endif

        @if ($content['enable_qr_attendance'] ?? false)
            <section id="qr-tamu" class="py-24 px-6 bg-brand-white relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/paper-fibers.png')]">
                </div>
                <div class="max-w-4xl mx-auto text-center relative z-10">
                    <div class="mb-16">
                        <i class="fa-solid fa-qrcode text-brand-gold text-3xl mb-4 opacity-70"></i>
                        <h2 class="text-4xl font-serif italic text-brand-charcoal mb-4">QR Code Tamu</h2>
                        <div class="h-[1px] w-12 bg-brand-gold/30 mx-auto mb-6"></div>
                        <p class="text-sm text-gray-500 font-light leading-relaxed max-w-lg mx-auto">
                            Silakan tunjukkan QR Code di bawah ini kepada petugas penerima tamu untuk memudahkan proses
                            absensi kehadiran Anda.
                        </p>
                    </div>

                    <div class="flex justify-center">
                        <div
                            class="group relative p-10 bg-brand-elegant/40 rounded-[3rem] border border-brand-lightGold/20 backdrop-blur-sm transition-all duration-500 hover:shadow-2xl hover:shadow-brand-gold/10 max-w-sm w-full mx-auto">
                            <div class="flex flex-col items-center">
                                <div class="relative p-4 bg-white rounded-3xl mb-8 shadow-inner overflow-hidden">
                                    <img id="qr-image" src="{{ $qrCodeUrl }}"
                                        class="w-48 h-48 object-contain transition-transform duration-700 group-hover:scale-105"
                                        alt="QR Code Tamu">
                                    <div
                                        class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-brand-gold/40 m-2">
                                    </div>
                                    <div
                                        class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-brand-gold/40 m-2">
                                    </div>
                                </div>
                                <p class="text-[10px] uppercase tracking-[0.3em] text-brand-gold mb-2 font-bold">
                                    Identitas Tamu</p>
                                <h3 id="guest-name-qr"
                                    class="text-2xl font-serif font-bold text-brand-charcoal mb-1 tracking-wide uppercase">
                                    {{ $guestNameDisplay }}</h3>
                                <p id="guest-id-qr" class="text-sm text-gray-400 italic mb-8 font-light">E-Invitation
                                </p>
                                <div
                                    class="w-full py-4 bg-brand-charcoal/5 text-brand-charcoal rounded-2xl border border-dashed border-brand-gold/30">
                                    <p class="text-[9px] uppercase tracking-[0.2em] font-medium opacity-60">Scan saat
                                        memasuki ruangan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($content['is_wishes_active'] ?? false)
            <section id="rsvp-modal" class="fixed inset-0 z-[100] invisible transition-all duration-500">
                <div onclick="closeRSVP()"
                    class="absolute inset-0 bg-brand-charcoal/40 backdrop-blur-sm opacity-0 transition-opacity duration-500"
                    id="rsvp-overlay"></div>
                <div id="rsvp-content"
                    class="absolute bottom-0 left-0 right-0 bg-brand-white rounded-t-[3rem] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] transform translate-y-full transition-transform duration-500 ease-out px-6 pb-10 pt-4 max-w-2xl mx-auto">
                    <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8"></div>
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-serif text-brand-charcoal mb-2">RSVP & Ucapan</h2>
                        <p class="text-xs text-brand-gold tracking-widest uppercase">Konfirmasi Kehadiran Anda</p>
                    </div>

                    <form class="space-y-4 text-left">
                        <div>
                            <input type="text" id="input-nama-rsvp" placeholder="Nama Lengkap"
                                value="{{ $guestNameDisplay !== 'Tamu Undangan' ? $guestNameDisplay : '' }}"
                                class="w-full p-4 rounded-2xl bg-brand-elegant border border-brand-lightGold/50 focus:outline-none focus:border-brand-gold text-sm placeholder-gray-500">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectAttendance('Hadir')" id="btn-hadir"
                                class="py-3 rounded-xl border border-brand-lightGold/50 text-sm font-medium transition-all bg-brand-elegant text-gray-600">
                                <i class="fa-solid fa-check mr-2"></i>Hadir
                            </button>
                            <button type="button" onclick="selectAttendance('Tidak Hadir')" id="btn-absen"
                                class="py-3 rounded-xl border border-brand-lightGold/50 text-sm font-medium transition-all bg-brand-elegant text-gray-600">
                                <i class="fa-solid fa-xmark mr-2"></i>Absen
                            </button>
                        </div>

                        <div id="guest-selection" class="hidden animate-fade-in">
                            <label
                                class="text-[10px] uppercase tracking-[0.2em] text-brand-gold ml-1 mb-2 block font-semibold">Membawa
                                Tamu?</label>
                            <div class="flex gap-2">
                                <button type="button"
                                    class="flex-1 py-3 rounded-xl border border-brand-lightGold/30 bg-brand-elegant text-xs text-gray-600 hover:border-brand-gold transition-colors">1
                                    Orang</button>
                                <button type="button"
                                    class="flex-1 py-3 rounded-xl border border-brand-lightGold/30 bg-brand-elegant text-xs text-gray-600 hover:border-brand-gold transition-colors">2
                                    Orang</button>
                                <button type="button"
                                    class="flex-1 py-3 rounded-xl border border-brand-lightGold/30 bg-brand-elegant text-xs text-gray-600 hover:border-brand-gold transition-colors">+3
                                    Orang</button>
                            </div>
                        </div>

                        <div>
                            <textarea rows="4" placeholder="Tuliskan doa & ucapan manis Anda..."
                                class="w-full p-4 rounded-2xl bg-brand-elegant border border-brand-lightGold/50 focus:outline-none focus:border-brand-gold text-sm placeholder-gray-500"></textarea>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" onclick="closeRSVP()"
                                class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-semibold text-sm hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="button" onclick="updateGuestStats('hadir')"
                                class="flex-[2] py-4 bg-brand-gold hover:bg-brand-charcoal text-white rounded-2xl font-semibold text-sm transition-all shadow-lg shadow-brand-gold/20">Kirim
                                RSVP</button>
                        </div>
                    </form>
                </div>
            </section>
        @endif

        <footer
            class="py-20 px-6 bg-brand-white border-t border-brand-lightGold/20 text-center relative overflow-hidden">
            <div
                class="absolute inset-0 opacity-[0.02] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/natural-paper.png')]">
            </div>
            <div class="max-w-xl mx-auto relative z-10">
                <div class="mb-8 flex flex-col items-center">
                    <div class="text-3xl font-serif italic text-brand-gold opacity-40 mb-2 uppercase">
                        {{ substr($content['groom_nickname'] ?? 'R', 0, 1) }} &
                        {{ substr($content['bride_nickname'] ?? 'J', 0, 1) }}
                    </div>
                    <div class="h-[1px] w-8 bg-brand-gold/20"></div>
                </div>

                <div class="mb-12">
                    <p class="text-base font-serif italic text-brand-charcoal mb-4 leading-relaxed">
                        "Merupakan suatu kehormatan dan kebahagiaan bagi kami <br class="hidden md:block">
                        apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan doa restu <br
                            class="hidden md:block">
                        atas dimulainya perjalanan ibadah panjang kami ini."
                    </p>
                    <p class="text-[11px] tracking-[0.2em] uppercase text-gray-400 font-light">
                        Sampai jumpa di hari bahagia kami
                    </p>
                </div>

                <div class="mb-12">
                    <p class="text-sm text-brand-charcoal font-medium mb-1">Kami yang berbahagia,</p>
                    <p class="text-xl font-serif italic text-brand-gold">{{ $content['groom_nickname'] ?? 'Romeo' }} &
                        {{ $content['bride_nickname'] ?? 'Juliet' }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Beserta Keluarga Besar</p>
                </div>

                <div
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-brand-elegant/50 border border-brand-lightGold/20 backdrop-blur-sm shadow-sm transition-all hover:shadow-md">
                    <span class="text-[9px] text-gray-400 font-sans uppercase tracking-widest">Digital Invitation
                        by</span>
                    <a href="https://instagram.com/ruangrestu.undangan" target="_blank" rel="noopener noreferrer"
                        class="text-[10px] font-bold text-brand-gold hover:text-brand-charcoal transition-colors flex items-center gap-1.5">
                        <i class="fa-brands fa-instagram text-xs"></i>
                        @ruangrestu.undangan
                    </a>
                </div>

                <p class="mt-10 text-[9px] text-gray-300 font-light tracking-wide">
                    &copy; 2026 {{ $content['groom_nickname'] ?? 'Romeo' }} &
                    {{ $content['bride_nickname'] ?? 'Juliet' }} Wedding. <br class="md:hidden"> All Rights Reserved.
                </p>
            </div>
        </footer>

    </main>

    <div id="fab-container"
        class="fixed right-5 bottom-28 flex flex-col gap-4 z-40 opacity-0 transition-opacity duration-1000">
        <div class="relative flex items-center group">
            <div id="music-info"
                class="absolute right-full mr-3 px-3 py-1 bg-brand-white/90 backdrop-blur border border-brand-lightGold/50 rounded-lg text-brand-gold text-xs whitespace-nowrap shadow-md opacity-0 translate-x-4 pointer-events-none transition-all duration-500 group-hover:opacity-100 group-hover:translate-x-0">
                Now Playing
            </div>
            <button id="btn-music" onclick="toggleMusic()"
                class="w-11 h-11 bg-brand-white backdrop-blur border border-brand-lightGold/70 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-white transition-all">
                <i class="fa-solid fa-music animate-spin-slow" id="icon-music"></i>
            </button>
        </div>

        <button id="btn-scroll" onclick="toggleAutoScroll()"
            class="w-11 h-11 bg-brand-white backdrop-blur border border-brand-lightGold/70 rounded-full flex items-center justify-center text-brand-gold shadow-lg hover:bg-brand-gold hover:text-white transition-all">
            <i class="fa-solid fa-angles-down" id="icon-scroll"></i>
        </button>
    </div>

    <nav id="bottom-nav"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 floating-nav rounded-full transition-transform duration-700 translate-y-32 shadow-2xl">
        <ul class="flex justify-around items-center h-16 w-[320px] md:w-[400px] px-3">
            <li>
                <a href="#home"
                    class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5">
                    <i class="fa-solid fa-house text-xl"></i><span>Home</span>
                </a>
            </li>
            @if ($content['is_gallery_active'] ?? false)
                <li>
                    <a href="#gallery"
                        class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5">
                        <i class="fa-solid fa-images text-xl"></i><span>Gallery</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="#lokasi"
                    class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5">
                    <i class="fa-solid fa-map-location text-xl"></i><span>Lokasi</span>
                </a>
            </li>
            @if ($content['is_wishes_active'] ?? false)
                <li>
                    <a href="javascript:void(0)" onclick="openRSVP()"
                        class="flex flex-col items-center text-gray-600 hover:text-brand-gold transition-colors text-xs gap-1.5">
                        <i class="fa-solid fa-envelope text-xl"></i><span>RSVP</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <script>
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
            const icon = document.getElementById('icon-music');
            if (isMusicPlaying && !forcePlay) {
                audio.pause();
                isMusicPlaying = false;
                icon.classList.remove('fa-music', 'animate-spin');
                icon.classList.add('fa-volume-xmark');
            } else {
                audio.play().then(() => {
                    isMusicPlaying = true;
                    icon.classList.remove('fa-volume-xmark');
                    icon.classList.add('fa-music', 'animate-spin');
                }).catch((error) => console.log("Autoplay musik dicegah browser."));
            }
        }

        function toggleAutoScroll(forceStart = false) {
            const btn = document.getElementById('btn-scroll');
            const icon = document.getElementById('icon-scroll');

            if (isAutoScrolling && !forceStart) {
                clearInterval(scrollInterval);
                isAutoScrolling = false;
                btn.classList.remove('bg-brand-gold', 'text-white');
                btn.classList.add('bg-brand-white', 'text-brand-gold');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-angles-down');
            } else {
                isAutoScrolling = true;
                btn.classList.remove('bg-brand-white', 'text-brand-gold');
                btn.classList.add('bg-brand-gold', 'text-white');
                icon.classList.remove('fa-angles-down');
                icon.classList.add('fa-pause');

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

        document.addEventListener("DOMContentLoaded", function() {
            const musicInfo = document.getElementById('music-info');
            setTimeout(() => {
                musicInfo.classList.remove('opacity-0', 'translate-x-4', 'pointer-events-none');
                musicInfo.classList.add('opacity-100', 'translate-x-0');
                setTimeout(() => {
                    musicInfo.classList.remove('opacity-100', 'translate-x-0');
                    musicInfo.classList.add('opacity-0', 'translate-x-4', 'pointer-events-none');
                    setTimeout(() => {
                        musicInfo.classList.remove('pointer-events-none');
                    }, 500);
                }, 3000);
            }, 1200);
        });

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
    </script>

    <script>
        // Menggunakan ternary untuk mencetak 0 jika $hasResepsi false
        let weddingDate = {{ $hasResepsi ? 'new Date("' . $countdownDateStr . '").getTime()' : '0' }};

        const countdownFunction = setInterval(function() {
            const now = new Date().getTime();
            const distance = weddingDate - now;

            // Jika belum disetting (0) ATAU sudah lewat (< 0)
            if (weddingDate === 0 || distance <= 0) {
                clearInterval(countdownFunction);
                const eDays = document.getElementById("days");
                const eHours = document.getElementById("hours");
                const eMins = document.getElementById("minutes");
                if (eDays) eDays.innerText = "00";
                if (eHours) eHours.innerText = "00";
                if (eMins) eMins.innerText = "00";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

            const eDays = document.getElementById("days");
            const eHours = document.getElementById("hours");
            const eMins = document.getElementById("minutes");

            if (eDays) eDays.innerText = days < 10 ? "0" + days : days;
            if (eHours) eHours.innerText = hours < 10 ? "0" + hours : hours;
            if (eMins) eMins.innerText = minutes < 10 ? "0" + minutes : minutes;
        }, 1000);
    </script>

    <script>
        let currentSelectedItemId = null;
        let currentSelectedItemName = '';

        function confirmGift(id, name) {
            currentSelectedItemId = id;
            currentSelectedItemName = name;
            let guestName = "{{ $guestNameDisplay }}";

            const confirmText = document.getElementById('confirm-text');
            if (confirmText) {
                confirmText.innerHTML =
                    `Terima kasih atas niat baiknya, <b>${guestName}</b>.<br><br>Apakah Anda yakin ingin mengirimkan <b>${name}</b> sebagai tanda kasih? Nama Anda akan kami catat dengan penuh rasa syukur.`;
            }

            const modal = document.getElementById('confirm-modal');
            if (modal) modal.classList.remove('hidden');

            const btn = document.getElementById('final-confirm-btn');
            if (btn) {
                btn.onclick = function() {
                    processClaim(guestName, name);
                };
            }
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirm-modal');
            if (modal) modal.classList.add('hidden');
        }

        function processClaim(guestName, giftName) {
            const itemElement = document.getElementById(currentSelectedItemId);
            if (itemElement) {
                const actionArea = itemElement.querySelector('button');
                if (actionArea) {
                    actionArea.outerHTML = `
                    <div class="flex items-center gap-2 text-green-600 animate-pulse">
                        <span class="text-[9px] font-bold uppercase tracking-widest">Tercatat untuk Anda</span>
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    `;
                }
                itemElement.classList.add('border-green-100', 'bg-green-50/30');
            }

            closeConfirmModal();

            const toast = document.getElementById('gift-toast');
            if (toast) {
                toast.innerHTML =
                    `Hati kami sangat tersentuh, ${guestName}.<br>Kado "${giftName}" telah tercatat atas nama Anda.`;
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

        function toggleGiftModal(show) {
            const modal = document.getElementById('gift-modal');
            if (modal) {
                if (show) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            }
        }
    </script>

    <script>
        const allWishes = [
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

        const elAtt = document.getElementById('total-attendance');
        const elWish = document.getElementById('total-wishes');
        if (elAtt) elAtt.innerText = countAttendance;
        if (elWish) elWish.innerText = countWishes;

        let displayedCount = 0;
        const initialShow = 3;
        const loadStep = 10;

        function renderWishes() {
            const container = document.getElementById('wishes-container');
            const btnLoadMore = document.getElementById('btn-load-more');

            if (!container) return;

            if (allWishes.length === 0) {
                container.innerHTML = `
                    <div id="empty-wishes" class="text-center py-8">
                        <i class="fa-regular fa-comment text-4xl text-brand-gold/40 mb-3"></i>
                        <p class="text-xs text-gray-400 italic">Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</p>
                    </div>
                `;
                if (btnLoadMore) btnLoadMore.style.display = 'none';
                return;
            }

            let nextLimit = (displayedCount === 0) ? initialShow : displayedCount + loadStep;
            const wishesToDisplay = allWishes.slice(displayedCount, nextLimit);

            wishesToDisplay.forEach(wish => {
                const card = document.createElement('div');
                card.className =
                    'wish-card bg-brand-elegant/40 p-6 rounded-[2rem] border border-white animate-fade-in-up transition-all hover:bg-white hover:shadow-md';
                card.innerHTML = `
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-3 gap-1">
                            <h5 class="text-sm font-serif font-bold text-brand-charcoal">${wish.nama}</h5>
                            <span class="text-[9px] text-gray-400 italic uppercase tracking-wider">
                                <i class="fa-regular fa-clock mr-1"></i> ${wish.waktu}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-light italic">"${wish.pesan}"</p>
                    `;
                container.appendChild(card);
            });

            displayedCount = nextLimit;

            if (btnLoadMore && displayedCount >= allWishes.length) {
                btnLoadMore.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderWishes();
        });

        function loadMoreWishes() {
            renderWishes();
        }

        function updateGuestStats(statusHadir) {
            countWishes++;
            if (elWish) elWish.innerText = countWishes;

            if (statusHadir === "hadir") {
                countAttendance++;
                if (elAtt) elAtt.innerText = countAttendance;
            }

            const statsCards = document.querySelectorAll('#guest-stats .font-serif');
            statsCards.forEach(card => {
                card.classList.add('scale-110', 'text-brand-gold');
                setTimeout(() => {
                    card.classList.remove('scale-110', 'text-brand-gold');
                }, 500);
            });

            closeRSVP();

            const container = document.getElementById('wishes-container');
            const name = document.getElementById('input-nama-rsvp').value || 'Tamu Baru';
            const msg = document.querySelector('textarea').value || 'Selamat berbahagia!';

            const emptyState = document.getElementById('empty-wishes');
            if (emptyState) emptyState.remove();

            if (container) {
                const card = document.createElement('div');
                card.className =
                    'wish-card bg-brand-elegant/40 p-6 rounded-[2rem] border border-white animate-fade-in-up transition-all hover:bg-white hover:shadow-md';
                card.innerHTML = `
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-3 gap-1">
                            <h5 class="text-sm font-serif font-bold text-brand-charcoal">${name}</h5>
                            <span class="text-[9px] text-brand-gold italic uppercase tracking-wider font-bold">
                                <i class="fa-regular fa-clock mr-1"></i> Baru Saja
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-light italic">"${msg}"</p>
                    `;
                container.prepend(card);
            }
        }

        let hasShownRSVPAtEnd = false;

        function openRSVP() {
            const modal = document.getElementById('rsvp-modal');
            const overlay = document.getElementById('rsvp-overlay');
            const content = document.getElementById('rsvp-content');

            if (modal && overlay && content) {
                modal.classList.remove('invisible');
                setTimeout(() => {
                    overlay.classList.replace('opacity-0', 'opacity-100');
                    content.classList.replace('translate-y-full', 'translate-y-0');
                }, 10);
            }
        }

        function closeRSVP() {
            const overlay = document.getElementById('rsvp-overlay');
            const content = document.getElementById('rsvp-content');
            const modal = document.getElementById('rsvp-modal');

            if (modal && overlay && content) {
                overlay.classList.replace('opacity-100', 'opacity-0');
                content.classList.replace('translate-y-0', 'translate-y-full');

                setTimeout(() => {
                    modal.classList.add('invisible');
                }, 500);
            }
        }

        function selectAttendance(status) {
            const btnHadir = document.getElementById('btn-hadir');
            const btnAbsen = document.getElementById('btn-absen');
            const guestDiv = document.getElementById('guest-selection');

            if (!btnHadir || !btnAbsen || !guestDiv) return;

            if (status === 'Hadir') {
                btnHadir.classList.add('bg-brand-gold', 'text-white', 'border-brand-gold');
                btnAbsen.classList.remove('bg-brand-gold', 'text-white', 'border-brand-gold');
                guestDiv.classList.remove('hidden');
            } else {
                btnAbsen.classList.add('bg-brand-gold', 'text-white', 'border-brand-gold');
                btnHadir.classList.remove('bg-brand-gold', 'text-white', 'border-brand-gold');
                guestDiv.classList.add('hidden');
            }
        }

        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50) {
                const modalExists = document.getElementById('rsvp-modal');
                if (modalExists && !hasShownRSVPAtEnd) {
                    openRSVP();
                    hasShownRSVPAtEnd = true;
                    if (typeof toggleAutoScroll === "function" && isAutoScrolling) {
                        toggleAutoScroll();
                    }
                }
            }
        }, {
            passive: true
        });
    </script>

    <script>
        function copyToClipboard(id, btn) {
            const el = document.getElementById(id);
            if (!el) return;
            const textToCopy = el.innerText;
            const toast = document.getElementById('copy-toast');

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Tersalin!</span>';
                btn.classList.replace('bg-brand-gold', 'bg-green-600');

                if (toast) toast.classList.replace('opacity-0', 'opacity-100');

                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.replace('bg-green-600', 'bg-brand-gold');
                    if (toast) toast.classList.replace('opacity-100', 'opacity-0');
                }, 2000);
            }).catch(err => console.error('Gagal menyalin: ', err));
        }

        function copyToClipboardText(id, btn) {
            const el = document.getElementById(id);
            if (!el) return;
            const textToCopy = el.innerText;

            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalContent = btn.innerHTML;
                btn.innerHTML = 'Tersalin!';
                btn.classList.replace('text-brand-gold', 'text-green-600');
                btn.classList.replace('border-brand-gold/20', 'border-green-600');

                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.classList.replace('text-green-600', 'text-brand-gold');
                    btn.classList.replace('border-green-600', 'border-brand-gold/20');
                }, 2000);
            }).catch(err => console.error(err));
        }

        function toggleStories() {
            const extraStories = document.getElementById('extra-stories');
            const btn = document.getElementById('btn-read-more');

            if (extraStories && btn) {
                if (extraStories.classList.contains('hidden')) {
                    extraStories.classList.remove('hidden');
                    extraStories.classList.add('animate-fade-in-up');
                    btn.innerText = 'Sembunyikan Cerita';
                } else {
                    extraStories.classList.add('hidden');
                    btn.innerText = 'Baca Selengkapnya';
                }
            }
        }

        const images = Array.from(document.querySelectorAll('.gallery-img')).map(img => img.src);
        let currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            const modal = document.getElementById('lightbox');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeLightbox() {
            const modal = document.getElementById('lightbox');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        function updateLightbox() {
            const imgElement = document.getElementById('lightbox-img');
            if (imgElement) {
                imgElement.style.opacity = '0';
                setTimeout(() => {
                    imgElement.src = images[currentIndex];
                    imgElement.style.opacity = '1';
                }, 200);
            }
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

        let touchStartX = 0;
        let touchEndX = 0;

        const lb = document.getElementById('lightbox');
        if (lb) {
            lb.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            });
            lb.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }

        function handleSwipe() {
            const threshold = 50;
            if (touchEndX < touchStartX - threshold) nextImg();
            if (touchEndX > touchStartX + threshold) prevImg();
        }

        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('lightbox');
            if (!modal || modal.classList.contains('hidden')) return;
            if (e.key === "ArrowRight") nextImg();
            if (e.key === "ArrowLeft") prevImg();
            if (e.key === "Escape") closeLightbox();
        });
    </script>
</body>

</html>
