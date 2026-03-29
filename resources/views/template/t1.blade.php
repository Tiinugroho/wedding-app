<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of {{ $content['groom_nickname'] ?? 'Rama' }} & {{ $content['bride_nickname'] ?? 'Shinta' }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('preview/template1.css') }}">
</head>

<body class="font-main bg-pattern text-stone-900 antialiased overflow-x-hidden">

    @php
        // Format Tanggal Hero Section dari tanggal Akad
        $heroDate = !empty($content['akad_date'])
            ? \Carbon\Carbon::parse($content['akad_date'])->locale('id')->translatedFormat('l, d F Y')
            : 'Sabtu, 17 Agustus 2026';
    @endphp

    <section
        class="relative h-screen flex items-center justify-center text-center p-6 bg-cover bg-center overflow-hidden"
        style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80&w=2070');">
        <div class="absolute inset-0 bg-black/40 z-0"></div>
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-48 h-48 bg-stone-500/10 rounded-full blur-3xl animate-float delay-1">
        </div>

        <div class="relative z-10 text-white space-y-8" data-aos="zoom-out" data-aos-duration="2000">
            <div class="space-y-2">
                <p class="uppercase tracking-[0.4em] text-[10px] sm:text-xs text-stone-200">The Wedding of</p>
                <h1 class="font-aesthetic text-7xl sm:text-9xl text-stone-100 drop-shadow-lg">
                    {{ $content['groom_nickname'] ?? 'Rama' }} & {{ $content['bride_nickname'] ?? 'Shinta' }}
                </h1>
            </div>
            <div class="space-y-4">
                <div class="h-[1px] w-24 bg-white/40 mx-auto"></div>
                <h3 id="guest-name" class="font-serif-elegant text-2xl md:text-3xl italic font-light tracking-wide">
                    {{ $guestData->name ?? 'Tamu Undangan' }}
                </h3>
                <div class="h-[1px] w-24 bg-white/40 mx-auto"></div>
            </div>
            <p class="text-sm sm:text-base tracking-[0.2em] uppercase text-stone-300">{{ $heroDate }}</p>
            <div class="mt-12">
                <a href="#mempelai"
                    class="inline-block px-8 py-3 border border-white/50 rounded-full text-xs tracking-widest uppercase text-white hover:bg-white hover:text-stone-900 transition-all duration-500 backdrop-blur-sm">
                    Buka Undangan
                </a>
            </div>
        </div>
    </section>

    <div id="mempelai"></div>
    <section class="py-20 md:py-32 px-6 overflow-hidden">
        <div class="max-w-6xl mx-auto space-y-24">
            <div class="text-center" data-aos="fade-up">
                <h2 class="font-aesthetic text-6xl text-stone-900 mb-6">Assalamu’alaikum Wr. Wb.</h2>
                <p class="max-w-xl mx-auto text-stone-500 leading-relaxed font-serif-elegant">
                    Dengan memohon rahmat Allah SWT, kami mengundang Anda untuk merayakan ikatan suci pernikahan kami.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-20 items-center">
                <div class="text-center group" data-aos="fade-right">
                    <div class="relative w-64 h-80 mx-auto mb-8">
                        <div
                            class="absolute inset-0 border border-stone-200 translate-x-4 translate-y-4 rounded-t-full transition-transform group-hover:translate-x-2 group-hover:translate-y-2">
                        </div>
                        <img src="{{ !empty($content['groom_photo']) ? asset('storage/' . $content['groom_photo']) : 'https://images.soco.id/230-58.jpg.jpeg' }}"
                            class="w-full h-full object-cover rounded-t-full shadow-xl relative z-10" alt="Groom">
                    </div>
                    <h3 class="font-aesthetic text-6xl text-stone-900 mb-2">{{ $content['groom_nickname'] ?? 'Rama' }}
                    </h3>
                    <p class="font-serif-elegant text-xl font-bold tracking-widest text-stone-800">
                        {{ $content['groom_name'] ?? 'Rama Raditya Putra' }}</p>
                    <p class="text-stone-500 text-sm mt-4 italic">
                        {{ $content['groom_parents'] ?? 'Putra Pertama dari Bpk. Nama Ayah & Ibu Nama Ibu' }}</p>
                    @if (!empty($content['groom_ig']))
                        <div class="mt-6 flex justify-center gap-4">
                            <a href="{{ $content['groom_ig'] }}" target="_blank"
                                class="text-stone-400 hover:text-stone-900 transition-colors text-xs uppercase tracking-widest">Instagram</a>
                        </div>
                    @endif
                </div>

                <div class="text-center group" data-aos="fade-left">
                    <div class="relative w-64 h-80 mx-auto mb-8">
                        <div
                            class="absolute inset-0 border border-stone-200 -translate-x-4 translate-y-4 rounded-t-full transition-transform group-hover:-translate-x-2 group-hover:translate-y-2">
                        </div>
                        <img src="{{ !empty($content['bride_photo']) ? asset('storage/' . $content['bride_photo']) : 'https://images.pexels.com/photos/157757/wedding-dresses-fashion-character-bride-157757.jpeg' }}"
                            class="w-full h-full object-cover rounded-t-full shadow-xl relative z-10" alt="Bride">
                    </div>
                    <h3 class="font-aesthetic text-6xl text-stone-900 mb-2">
                        {{ $content['bride_nickname'] ?? 'Shinta' }}</h3>
                    <p class="font-serif-elegant text-xl font-bold tracking-widest text-stone-800">
                        {{ $content['bride_name'] ?? 'Shinta Amelia Putri' }}</p>
                    <p class="text-stone-500 text-sm mt-4 italic">
                        {{ $content['bride_parents'] ?? 'Putri Kedua dari Bpk. Nama Ayah & Ibu Nama Ibu' }}</p>
                    @if (!empty($content['bride_ig']))
                        <div class="mt-6 flex justify-center gap-4">
                            <a href="{{ $content['bride_ig'] }}" target="_blank"
                                class="text-stone-400 hover:text-stone-900 transition-colors text-xs uppercase tracking-widest">Instagram</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if (!empty($content['turut_mengundang_groom']) || !empty($content['turut_mengundang_bride']))
        <section class="py-20 md:py-32 px-6 bg-stone-50 overflow-hidden" data-aos="fade-up">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <p class="uppercase tracking-[0.3em] text-[10px] text-stone-500 mb-4">Hormat Kami</p>
                    <h2 class="font-aesthetic text-6xl text-stone-900 mb-4">Turut Mengundang</h2>
                    <div class="h-[1px] w-20 bg-stone-300 mx-auto"></div>
                    <p class="mt-6 text-stone-600 font-serif-elegant italic text-sm md:text-base">Keluarga Besar &
                        Kerabat</p>
                </div>

                <div
                    class="glass-card p-10 md:p-16 rounded-[3rem] border border-white shadow-sm relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-stone-200/50 rounded-full blur-3xl"></div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12 relative z-10">
                        @if (!empty($content['turut_mengundang_groom']))
                            <div class="space-y-6" data-aos="fade-right" data-aos-delay="200">
                                <div class="flex items-center gap-4 border-b border-stone-200 pb-3">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-widest text-stone-900">Keluarga
                                        Pria</span>
                                    <div class="h-[1px] flex-grow bg-stone-100"></div>
                                </div>
                                <div class="text-stone-600 text-sm md:text-base font-serif-elegant leading-relaxed">
                                    {!! nl2br(e($content['turut_mengundang_groom'])) !!}
                                </div>
                            </div>
                        @endif

                        @if (!empty($content['turut_mengundang_bride']))
                            <div class="space-y-6" data-aos="fade-left" data-aos-delay="400">
                                <div class="flex items-center gap-4 border-b border-stone-200 pb-3">
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-widest text-stone-900">Keluarga
                                        Wanita</span>
                                    <div class="h-[1px] flex-grow bg-stone-100"></div>
                                </div>
                                <div class="text-stone-600 text-sm md:text-base font-serif-elegant leading-relaxed">
                                    {!! nl2br(e($content['turut_mengundang_bride'])) !!}
                                </div>
                            </div>
                        @endif

                        <div class="md:col-span-2 pt-8 text-center border-t border-stone-100" data-aos="zoom-in">
                            <p
                                class="font-serif-elegant italic text-stone-400 text-xs md:text-sm leading-relaxed max-w-lg mx-auto">
                                "Serta seluruh keluarga besar, kerabat, dan rekan sejawat yang tidak dapat kami sebutkan
                                satu per satu tanpa mengurangi rasa hormat kami."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <section class="py-24 bg-stone-900 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-stone-50 to-transparent opacity-10"></div>
        <div class="max-w-5xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20" data-aos="fade-down">
                <h2 class="font-aesthetic text-7xl text-stone-200">The Ceremony</h2>
                <p class="font-serif-elegant tracking-widest text-xs uppercase mt-4 text-stone-400">Waktu & Tempat
                    Pelaksanaan</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="glass-card p-12 rounded-[3rem] text-center space-y-6 flex flex-col items-center"
                    data-aos="fade-up">
                    <div
                        class="w-12 h-12 border border-stone-400 rounded-full flex items-center justify-center mx-auto text-stone-400 italic">
                        01</div>
                    <h3 class="font-aesthetic text-5xl">Akad Nikah</h3>
                    <div class="space-y-2">
                        <p class="font-bold text-stone-200 tracking-widest text-xs uppercase">
                            {{ !empty($content['akad_date']) ? \Carbon\Carbon::parse($content['akad_date'])->locale('id')->translatedFormat('l, d F Y') : 'SENIN, 17 AGUSTUS 2026' }}
                        </p>
                        <p class="text-stone-400">{{ $content['akad_time'] ?? '08.00 - 10.00 WIB' }}</p>
                    </div>
                    <p class="text-sm leading-relaxed text-stone-300">
                        {{ $content['akad_location'] ?? 'Masjid Raya Pekanbaru' }} <br>
                        {{ $content['akad_address'] ?? 'Jl. Senapelan No. 128, Riau' }}</p>

                    <div class="flex flex-col space-y-4 pt-4">
                        @if (!empty($content['akad_map']))
                            <a href="{{ $content['akad_map'] }}" target="_blank"
                                class="inline-block border-b border-stone-400 text-[10px] tracking-widest hover:text-stone-100 transition uppercase">Google
                                Maps</a>
                        @endif

                        <a href="#"
                            class="flex items-center justify-center px-6 py-2 border border-stone-500 rounded-full text-[10px] tracking-[0.2em] uppercase hover:bg-stone-200 hover:text-stone-900 transition duration-500">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Simpan Jadwal
                        </a>
                    </div>
                </div>

                <div class="glass-card p-12 rounded-[3rem] text-center space-y-6 flex flex-col items-center"
                    data-aos="fade-up" data-aos-delay="200">
                    <div
                        class="w-12 h-12 border border-stone-400 rounded-full flex items-center justify-center mx-auto text-stone-400 italic">
                        02</div>
                    <h3 class="font-aesthetic text-5xl">Resepsi</h3>
                    <div class="space-y-2">
                        <p class="font-bold text-stone-200 tracking-widest text-xs uppercase">
                            {{ !empty($content['resepsi_date']) ? \Carbon\Carbon::parse($content['resepsi_date'])->locale('id')->translatedFormat('l, d F Y') : 'SENIN, 17 AGUSTUS 2026' }}
                        </p>
                        <p class="text-stone-400">{{ $content['resepsi_time'] ?? '11.00 - 16.00 WIB' }}</p>
                    </div>
                    <p class="text-sm leading-relaxed text-stone-300">
                        {{ $content['resepsi_location'] ?? 'Grand Ballroom Hotel' }} <br>
                        {{ $content['resepsi_address'] ?? 'Pekanbaru, Riau' }}</p>

                    <div class="flex flex-col space-y-4 pt-4">
                        @if (!empty($content['resepsi_map']))
                            <a href="{{ $content['resepsi_map'] }}" target="_blank"
                                class="inline-block border-b border-stone-400 text-[10px] tracking-widest hover:text-stone-100 transition uppercase">Google
                                Maps</a>
                        @endif

                        <a href="#"
                            class="flex items-center justify-center px-6 py-2 border border-stone-500 rounded-full text-[10px] tracking-[0.2em] uppercase hover:bg-stone-200 hover:text-stone-900 transition duration-500">
                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Simpan Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto space-y-16">
            <div class="text-center" data-aos="fade-up">
                <h2 class="font-aesthetic text-6xl text-stone-900 mb-4">Our Moments</h2>
                <p class="text-stone-400 font-serif-elegant italic">Cerita indah dalam bingkai</p>
            </div>

            <div class="columns-2 md:columns-4 gap-4 space-y-4">
                @forelse($invitation->galleries->where('type', 'photo') as $key => $gallery)
                    <img src="{{ asset('storage/' . $gallery->file_path) }}"
                        onclick="openModal({{ $key }})"
                        class="gallery-item w-full rounded-3xl shadow-md cursor-pointer hover:brightness-90 transition duration-500"
                        data-aos="zoom-in">
                @empty
                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&q=80"
                        class="w-full rounded-3xl shadow-md">
                    <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&q=80"
                        class="w-full rounded-3xl shadow-md">
                @endforelse
            </div>
        </div>
    </section>

    @if (!empty($content['love_stories']) && count($content['love_stories']) > 0)
        <section id="story-section"
            class="relative py-24 md:py-32 px-4 md:px-6 bg-cover bg-fixed bg-center overflow-hidden"
            style="background-image: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&q=80');">
            <div class="absolute inset-0 bg-stone-900/80 backdrop-blur-[2px]"></div>

            <div class="relative z-10 max-w-5xl mx-auto">
                <div class="text-center mb-16 md:mb-24" data-aos="fade-up">
                    <h2 class="font-aesthetic text-5xl md:text-7xl text-white mb-4">Our Love Story</h2>
                    <div class="h-[1px] w-20 bg-white/30 mx-auto"></div>
                    <p class="mt-4 text-stone-300 font-serif-elegant italic text-sm md:text-base">Sebuah perjalanan
                        yang kami syukuri</p>
                </div>

                <div class="relative">
                    <div
                        class="absolute left-4 md:left-1/2 transform md:-translate-x-1/2 h-full w-[1px] bg-gradient-to-b from-transparent via-white/30 to-transparent">
                    </div>

                    <div class="space-y-16 md:space-y-32">
                        @foreach ($content['love_stories'] as $index => $story)
                            <div class="relative flex flex-col md:flex-row {{ $index % 2 == 1 ? 'md:flex-row-reverse' : '' }} items-start md:items-center justify-between"
                                data-aos="fade-up">
                                <div
                                    class="hidden md:block w-5/12 {{ $index % 2 == 1 ? 'text-left' : 'text-right' }}">
                                    <div
                                        class="p-6 bg-white/10 backdrop-blur-md rounded-[2rem] border border-white/10">
                                        <span
                                            class="text-[10px] uppercase tracking-widest text-stone-400">{{ $story['year'] }}</span>
                                        <h4 class="font-aesthetic text-3xl text-white mt-1 mb-3">{{ $story['title'] }}
                                        </h4>
                                        <p class="text-stone-300 text-sm font-serif-elegant leading-relaxed">
                                            {{ $story['description'] }}</p>
                                    </div>
                                </div>
                                <div
                                    class="z-10 w-8 h-8 md:w-10 md:h-10 bg-white rounded-full flex items-center justify-center absolute left-0 md:left-1/2 transform md:-translate-x-1/2 shadow-lg">
                                    <div class="w-2 h-2 bg-stone-900 rounded-full"></div>
                                </div>
                                <div
                                    class="w-full md:w-5/12 {{ $index % 2 == 1 ? 'pr-0 md:pr-0 pl-12 md:pl-0' : 'pl-12 md:pl-0' }}">
                                    <div
                                        class="md:hidden mb-4 p-5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
                                        <span
                                            class="text-[10px] uppercase tracking-widest text-stone-400">{{ $story['year'] }}</span>
                                        <h4 class="font-aesthetic text-2xl text-white mt-1 mb-2">{{ $story['title'] }}
                                        </h4>
                                        <p class="text-stone-300 text-sm font-serif-elegant">
                                            {{ $story['description'] }}</p>
                                    </div>

                                    @if (!empty($story['image']))
                                        <img src="{{ asset('storage/' . $story['image']) }}"
                                            class="rounded-3xl border-2 border-white/10 w-full object-cover aspect-video md:aspect-auto">
                                    @else
                                        <div
                                            class="h-40 rounded-3xl border-2 border-white/10 bg-white/5 backdrop-blur flex items-center justify-center">
                                            <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (!empty($content['enable_health_protocol']) || !empty($content['enable_dresscode']))
        <section class="py-20 md:py-32 px-6 bg-stone-50 overflow-hidden">
            <div class="max-w-5xl mx-auto">
                <div class="grid md:grid-cols-2 gap-16 md:gap-8 relative">
                    @if (!empty($content['enable_health_protocol']))
                        <div class="space-y-10" data-aos="fade-right">
                            <div class="text-left">
                                <h2 class="font-aesthetic text-5xl text-stone-900 mb-2">Health Protocol</h2>
                                <p class="font-serif-elegant text-stone-500 italic text-sm">Demi kenyamanan bersama,
                                    mohon untuk tetap mengikuti protokol kesehatan:</p>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="text-center p-6 glass-card rounded-3xl border border-white shadow-sm">
                                    <div class="w-10 h-10 mx-auto mb-4 text-stone-800"><svg fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg></div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-900">Gunakan
                                        Masker</p>
                                </div>
                                <div class="text-center p-6 glass-card rounded-3xl border border-white shadow-sm">
                                    <div class="w-10 h-10 mx-auto mb-4 text-stone-800"><svg fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg></div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-900">Physical
                                        Distancing</p>
                                </div>
                                <div class="text-center p-6 glass-card rounded-3xl border border-white shadow-sm">
                                    <div class="w-10 h-10 mx-auto mb-4 text-stone-800"><svg fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.628.283a2 2 0 01-1.186.127l-1.314-.328a2 2 0 00-1.186.127l-.628.283a6 6 0 00-3.86.517l-2.387.477a2 2 0 00-1.022.547V18a2 2 0 002 2h11a2 2 0 002-2v-2.572z">
                                            </path>
                                        </svg></div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-900">Cuci
                                        Tangan</p>
                                </div>
                                <div class="text-center p-6 glass-card rounded-3xl border border-white shadow-sm">
                                    <div class="w-10 h-10 mx-auto mb-4 text-stone-800"><svg fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg></div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-stone-900">Hindari
                                        Kontak</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($content['enable_dresscode']))
                        <div class="space-y-10" data-aos="fade-left">
                            <div class="text-left">
                                <h2 class="font-aesthetic text-5xl text-stone-900 mb-2">Guest Info</h2>
                                <p class="font-serif-elegant text-stone-500 italic text-sm">Informasi penting terkait
                                    kehadiran tamu:</p>
                            </div>
                            <div class="space-y-6">
                                <div
                                    class="flex items-start gap-6 p-6 bg-white rounded-[2rem] border border-stone-100 shadow-sm">
                                    <div class="p-3 bg-stone-50 rounded-2xl text-stone-800">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-widest text-stone-900 mb-1">
                                            Dresscode</h4>
                                        <p class="text-stone-600 text-sm leading-relaxed font-serif-elegant italic">
                                            {{ $content['dresscode'] ?? 'Bebas Rapi' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </section>
    @endif

    <section class="relative py-32 px-6 bg-cover bg-fixed"
        style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80&w=2070');">
        <div class="absolute inset-0 bg-stone-900/40"></div>
        <div class="relative z-10 max-w-xl mx-auto glass-card p-10 md:p-12 rounded-[3rem] shadow-2xl"
            data-aos="zoom-in">
            <div class="text-center mb-10">
                <h2 class="font-aesthetic text-6xl text-stone-900">Kehadiran</h2>
                <p class="text-stone-600 mt-2 text-sm italic font-serif-elegant">Kami sangat menantikan kehadiran Anda
                </p>
            </div>

            <form id="formRsvpAjax" action="{{ route('rsvp.store', $invitation->slug) }}" method="POST"
                class="space-y-6">
                @csrf
                <input type="hidden" name="guest_id" value="{{ $guestData->id ?? '' }}">

                <div class="space-y-1">
                    <label class="text-[10px] uppercase tracking-widest text-stone-500 font-bold ml-2">Nama
                        Lengkap</label>
                    <input type="text" name="guest_name" required value="{{ $guestData->name ?? '' }}"
                        {{ isset($guestData) ? 'readonly' : '' }}
                        class="w-full p-4 rounded-2xl {{ isset($guestData) ? 'bg-stone-100/50 text-stone-600' : 'bg-white/50' }} border border-white/50 focus:bg-white focus:ring-2 focus:ring-stone-900 outline-none transition"
                        placeholder="Masukkan nama Anda">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label
                            class="text-[10px] uppercase tracking-widest text-stone-500 font-bold ml-2">Jumlah</label>
                        <select name="pax"
                            class="w-full p-4 rounded-2xl bg-white/50 border border-white/50 focus:bg-white outline-none cursor-pointer">
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                            <option value="3">3 Orang</option>
                            <option value="4">4 Orang</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] uppercase tracking-widest text-stone-500 font-bold ml-2">Konfirmasi</label>
                        <select name="status_rsvp"
                            class="w-full p-4 rounded-2xl bg-white/50 border border-white/50 focus:bg-white outline-none cursor-pointer">
                            <option value="hadir">Hadir</option>
                            <option value="ragu">Mungkin (Ragu)</option>
                            <option value="tidak_hadir">Berhalangan</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] uppercase tracking-widest text-stone-500 font-bold ml-2">Pesan & Doa
                        Restu</label>
                    <textarea name="message" required rows="4"
                        class="w-full p-4 rounded-2xl bg-white/50 border border-white/50 focus:bg-white focus:ring-2 focus:ring-stone-900 outline-none transition resize-none"
                        placeholder="Tuliskan ucapan atau doa Anda di sini..."></textarea>
                </div>

                <button type="submit" id="btnSubmitRsvp"
                    class="w-full bg-stone-900 text-white py-4 rounded-2xl font-bold tracking-widest hover:bg-stone-800 transition shadow-xl uppercase text-xs">
                    Kirim Konfirmasi
                </button>
            </form>
        </div>
    </section>

    @if (!empty($content['banks']) && count($content['banks']) > 0)
        <section class="py-24 px-6 bg-white" data-aos="fade-up">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-16">
                    <h2 class="font-aesthetic text-6xl text-stone-900 mb-4">Wedding Gift</h2>
                    <div class="h-[1px] w-20 bg-stone-300 mx-auto mb-6"></div>
                    <p
                        class="max-w-md mx-auto text-stone-500 font-serif-elegant italic text-sm md:text-base leading-relaxed">
                        Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika Anda ingin memberikan
                        tanda kasih, Anda dapat menyalurkannya melalui:
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    @foreach ($content['banks'] as $index => $bank)
                        <div
                            class="p-10 bg-stone-50 rounded-[3rem] border border-stone-100 shadow-sm hover:shadow-md transition-shadow duration-500">
                            <h4 class="font-bold text-stone-800 text-xl mb-4">{{ $bank['name'] }}</h4>
                            <div class="space-y-2">
                                <p class="text-xs uppercase tracking-[0.2em] text-stone-400">Nomor Rekening</p>
                                <p id="norek{{ $index }}"
                                    class="text-xl font-bold tracking-widest text-stone-800">
                                    {{ $bank['account_number'] }}</p>
                                <p class="text-stone-600 font-serif-elegant">a.n. {{ $bank['account_name'] }}</p>
                                <button onclick="copyToClipboard('norek{{ $index }}')"
                                    class="mt-6 px-6 py-2 bg-stone-900 text-white text-[10px] uppercase tracking-widest rounded-full hover:bg-stone-700 transition duration-300">
                                    Salin Nomor
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-16 text-stone-400 text-[10px] uppercase tracking-[0.3em]">
                    Terima Kasih Atas Kebaikan Anda
                </div>
            </div>
        </section>
    @endif

    <footer class="py-24 px-6 bg-white text-center">
        <div class="max-w-3xl mx-auto" data-aos="fade-up">
            <div class="mb-12">
                <svg class="w-10 h-10 text-stone-300 mx-auto mb-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M14.017 21L14.017 18C14.017 16.8954 13.1216 16 12.017 16C10.9124 16 10.017 16.8954 10.017 18L10.017 21H2V12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12V21H14.017Z" />
                </svg>
                <p class="font-serif-elegant italic text-stone-600 leading-relaxed text-lg">
                    "Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu
                    sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya diantaramu rasa
                    kasih dan sayang."
                </p>
                <span class="block mt-4 text-xs tracking-widest text-stone-400 uppercase font-bold">— QS. Ar-Rum: 21
                    —</span>
            </div>

            <p class="text-stone-500 text-sm mb-12">Merupakan suatu kehormatan dan kebahagiaan bagi kami <br> apabila
                Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu.</p>
            <h2 class="font-aesthetic text-5xl text-stone-900">{{ $content['groom_nickname'] ?? 'Rama' }} &
                {{ $content['bride_nickname'] ?? 'Shinta' }}</h2>

            <div class="mt-20 pt-10 border-t border-stone-100">
                <a href="https://www.instagram.com/ruangrestu.undangan/">
                    <p class="text-[10px] tracking-[0.3em] text-stone-400 uppercase">{{ Date('Y') }} • RUANGRESTU
                    </p>
                </a>
            </div>
        </div>
    </footer>

    <div id="imageModal"
        class="fixed inset-0 z-[100] hidden bg-black/95 flex items-center justify-center p-4 backdrop-blur-md">
        <button onclick="closeModal()"
            class="absolute top-10 right-10 text-white text-4xl font-light z-[110]">×</button>
        <button onclick="changeImage(-1)"
            class="absolute left-4 md:left-10 text-white/50 hover:text-white text-5xl transition z-[110]">‹</button>
        <button onclick="changeImage(1)"
            class="absolute right-4 md:right-10 text-white/50 hover:text-white text-5xl transition z-[110]">›</button>
        <div class="relative max-w-5xl max-h-[85vh] flex items-center justify-center"
            onclick="event.stopPropagation()">
            <img id="modalImg"
                class="max-w-full max-h-[85vh] rounded-xl shadow-2xl scale-95 opacity-0 transition-all duration-300">
        </div>
    </div>

    <div id="rsvpModal" class="fixed inset-0 z-[150] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm"></div>

        <div class="relative bg-white p-10 md:p-12 rounded-[3rem] max-w-sm w-full text-center shadow-2xl"
            data-aos="zoom-in">
            <div
                class="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-stone-100">
                <svg class="w-10 h-10 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7">
                    </path>
                </svg>
            </div>
            <h3 class="font-aesthetic text-4xl text-stone-900 mb-2">Terima Kasih</h3>
            <p class="text-stone-500 font-serif-elegant italic text-sm mb-8 leading-relaxed">
                Konfirmasi kehadiran dan pesan Anda telah kami terima dengan penuh sukacita.
            </p>
            <button onclick="closeRsvpModal()"
                class="w-full bg-stone-900 text-white py-4 rounded-2xl font-bold tracking-widest hover:bg-stone-800 transition uppercase text-[10px]">
                Tutup
            </button>
        </div>
    </div>

    @if ($invitation->music)
        <audio id="weddingMusic" loop autoplay>
            <source src="{{ asset('storage/' . $invitation->music->file_path) }}" type="audio/mpeg">
        </audio>
        <button id="musicControl" onclick="toggleMusic()"
            class="fixed bottom-6 left-6 z-[100] w-12 h-12 bg-white/80 backdrop-blur-md border border-stone-200 rounded-full flex items-center justify-center shadow-lg hover:bg-white transition-all duration-300">
            <svg id="icon-on" class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
                </path>
            </svg>
            <svg id="icon-off" class="hidden w-5 h-5 text-stone-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15zM17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2">
                </path>
            </svg>
        </button>
    @endif

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('preview/template1.js') }}"></script>

    <script>
        function copyToClipboard(elementId) {
            var copyText = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(copyText).then(function() {
                alert("Nomor rekening berhasil disalin: " + copyText);
            });
        }

        // PERUBAHAN ID DI SINI JUGA
        const rsvpFormAjax = document.getElementById("formRsvpAjax");

        if (rsvpFormAjax) {
            rsvpFormAjax.addEventListener("submit", function(e) {
                e.preventDefault();

                let form = this;
                let btn = form.querySelector('button[type="submit"]');
                let originalText = btn.innerHTML;

                btn.innerHTML = 'MENGIRIM...';
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');

                let formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errData => {
                                throw errData;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const modal = document.getElementById("rsvpModal");
                            if (modal) modal.classList.remove("hidden");
                            form.reset();
                        } else {
                            alert(data.message || 'Terjadi kesalahan.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (error.errors) {
                            let errorMessages = Object.values(error.errors).map(val => val.join(', ')).join(
                                '\n');
                            alert("Gagal mengirim data:\n" + errorMessages);
                        } else {
                            alert('Gagal mengirim pesan. Pastikan koneksi internet stabil.');
                        }
                    })
                    .finally(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                        btn.classList.remove('opacity-70', 'cursor-not-allowed');
                    });
            });
        }

        function closeRsvpModal() {
            const modal = document.getElementById("rsvpModal");
            if (modal) modal.classList.add("hidden");
        }
    </script>
</body>

</html>
