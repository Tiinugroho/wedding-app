<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangRestu | Platform Undangan Digital Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('cst/css/landing.css') }}">
</head>

<body class="bg-slate-50 text-slate-800 font-sans overflow-x-hidden relative">

    {{-- TOAST NOTIFICATION --}}
    @if (session('success') || session('error') || session('status'))
    <div id="toast-notification" class="fixed top-24 right-4 md:right-8 z-[100] transform transition-all duration-500 translate-x-0 opacity-100 flex items-center p-4 mb-4 text-slate-500 bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 min-w-[300px]" role="alert">
        @if (session('success') || session('status') == 'verification-link-sent')
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-green-500 bg-green-100 rounded-xl">
            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
        </div>
        @elseif(session('error'))
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-100 rounded-xl">
            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
            </svg>
        </div>
        @endif
        <div class="ms-4 text-sm font-bold text-slate-700 pr-6">
            {{ session('success') ?? session('error') }}
            {{ session('status') == 'verification-link-sent' ? 'Link verifikasi telah dikirim ke email Anda.' : '' }}
        </div>
        <button type="button" onclick="closeToast()" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
    <script>
        function closeToast() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.classList.replace('translate-x-0', 'translate-x-full');
                toast.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }
        }
        setTimeout(closeToast, 5000);
    </script>
    @endif

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 glass-light top-0 left-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="{{ route('welcome') }}" class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
                        RuangRestu
                    </a>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-8">
                        <a href="#home" class="text-slate-600 hover:text-rRed font-bold transition duration-300">Beranda</a>
                        <a href="#cara-kerja" class="text-slate-600 hover:text-rRed font-bold transition duration-300">Cara Kerja</a>
                        <a href="#produk" class="text-slate-600 hover:text-rRed font-bold transition duration-300">Katalog Tema</a>
                        <a href="#harga" class="text-slate-600 hover:text-rRed font-bold transition duration-300">Harga</a>
                        @auth
                        <a href="{{ route('customer.dashboard') }}" class="bg-slate-900 text-white px-6 py-2.5 rounded-full font-bold shadow-md hover:bg-slate-800 transition">Dashboard</a>
                        @endauth
                        @guest
                        <a href="{{ route('login') }}" class="bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-2.5 rounded-full font-bold glow-btn">
                            Masuk / Daftar
                        </a>
                        @endguest
                    </div>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-600 hover:text-rRed focus:outline-none p-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md border-t border-slate-100 absolute w-full left-0 top-20 shadow-xl">
            <div class="px-4 pt-2 pb-6 space-y-2 flex flex-col text-center">
                <a href="#home" class="mobile-link text-slate-600 hover:text-rRed font-bold py-3 border-b border-slate-50">Beranda</a>
                <a href="#cara-kerja" class="mobile-link text-slate-600 hover:text-rRed font-bold py-3 border-b border-slate-50">Cara Kerja</a>
                <a href="#produk" class="mobile-link text-slate-600 hover:text-rRed font-bold py-3 border-b border-slate-50">Katalog Tema</a>
                <a href="#harga" class="mobile-link text-slate-600 hover:text-rRed font-bold py-3 border-b border-slate-50">Harga</a>
                @auth
                <a href="{{ route('customer.dashboard') }}" class="mobile-link bg-slate-900 text-white px-6 py-3 rounded-full font-bold mt-4 mx-8 shadow-md">Dashboard</a>
                @endauth
                @guest
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-3 rounded-full font-bold mt-4 mx-8 shadow-md">Masuk / Daftar</a>
                @endguest
            </div>
        </div>
    </nav>

    <main>
        {{-- HERO SECTION --}}
        <section id="home" class="relative pt-28 pb-16 md:pt-36 md:pb-24 lg:pt-48 lg:pb-32 flex items-center justify-center min-h-screen overflow-hidden">
            <div class="absolute top-10 md:top-20 left-1/4 w-48 md:w-72 h-48 md:h-72 bg-rLightOrange rounded-full mix-blend-multiply filter blur-2xl md:blur-3xl opacity-30 animate-blob z-0"></div>
            <div class="absolute top-28 md:top-40 right-1/4 w-48 md:w-72 h-48 md:h-72 bg-rRed rounded-full mix-blend-multiply filter blur-2xl md:blur-3xl opacity-20 animate-blob animation-delay-2000 z-0"></div>
            <div class="absolute -bottom-8 left-1/3 w-48 md:w-72 h-48 md:h-72 bg-rYellow rounded-full mix-blend-multiply filter blur-2xl md:blur-3xl opacity-30 animate-blob z-0"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 w-full">
                <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8">

                    <div class="w-full lg:w-1/2 text-center lg:text-left reveal flex flex-col items-center lg:items-start">
                        <span class="inline-block py-1.5 px-4 rounded-full bg-white border border-rLightOrange/30 text-rOrange text-xs sm:text-sm font-bold mb-6 shadow-sm">
                            ✨ Platform Undangan Digital Terbaik
                        </span>

                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-4 sm:mb-6 text-slate-800 leading-tight">
                            Mulai dengan Doa,<br class="hidden sm:block lg:hidden xl:block">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-rRed via-rOrange to-rYellow">
                                Diabadikan dengan Restu
                            </span>
                        </h1>

                        <p class="mt-2 sm:mt-4 max-w-xl text-base sm:text-lg text-slate-500 mb-8 sm:mb-10 leading-relaxed font-medium">
                            Sebarkan momen bahagiamu dengan mudah. Buat undangan website premium dengan fitur RSVP, QR Code, dan Amplop Digital hanya dalam hitungan menit.
                        </p>

                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 w-full sm:w-auto">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-gradient-to-r from-rRed to-rOrange text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg glow-btn text-center hover:-translate-y-1 transition-transform">
                                Buat Undangan
                            </a>
                            <a href="#produk" class="w-full sm:w-auto bg-white text-slate-700 border border-slate-200 px-6 sm:px-8 py-3 sm:py-4 rounded-full font-bold text-base sm:text-lg hover:bg-slate-50 transition text-center flex items-center justify-center gap-2">
                                Lihat Katalog
                            </a>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2 relative flex justify-center items-center reveal mt-8 lg:mt-0" style="transition-delay: 200ms;">

                        <div class="relative z-20 w-[240px] h-[500px] sm:w-[280px] sm:h-[580px] bg-slate-900 rounded-[2.5rem] sm:rounded-[3rem] border-[6px] sm:border-[8px] border-slate-800 shadow-2xl overflow-hidden transition-all duration-300">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 sm:w-32 h-5 sm:h-6 bg-slate-800 rounded-b-xl sm:rounded-b-2xl z-30"></div>
                            <div class="w-full h-full bg-white bg-[url('https://images.unsplash.com/photo-1607190074257-dd4b7af0309f?q=80&w=1887&auto=format&fit=crop')] bg-cover bg-center">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-black/10"></div>
                                <div class="absolute bottom-8 sm:bottom-10 left-0 w-full text-center text-white px-4">
                                    <p class="text-[10px] sm:text-xs uppercase tracking-widest mb-1">The Wedding Of</p>
                                    <h2 class="text-2xl sm:text-3xl font-serif mb-2">Jati & Nabila</h2>
                                    <p class="text-xs sm:text-sm border border-white/30 inline-block px-3 sm:px-4 py-1 sm:py-1.5 rounded-full backdrop-blur-md">14 . 02 . 2026</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -right-2 sm:-right-4 lg:-right-8 top-16 sm:top-20 glass-light p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-xl z-30 hidden md:block animate-bounce" style="animation-duration: 4s;">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[8px] sm:text-[10px] text-slate-400 font-bold uppercase tracking-wider">Konfirmasi Hadir</p>
                                    <p class="text-[10px] sm:text-xs font-bold text-slate-800">Keluarga Bpk. Fulan</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -left-4 sm:-left-10 lg:-left-14 bottom-24 sm:bottom-32 glass-light p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-xl z-30 hidden md:block animate-bounce" style="animation-duration: 5s;">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-rOrange/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-rOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[8px] sm:text-[10px] text-slate-400 font-bold uppercase tracking-wider">Kado Digital</p>
                                    <p class="text-[10px] sm:text-xs font-bold text-slate-800">Dana Berhasil Masuk</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute w-48 sm:w-64 h-48 sm:h-64 bg-rRed rounded-full filter blur-[80px] sm:blur-[100px] opacity-20 z-10"></div>
                    </div>

                </div>
            </div>
        </section>

        {{-- SECTION CARA KERJA --}}
        <section id="cara-kerja" class="py-20 md:py-28 bg-white relative border-t border-slate-100 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal">

                <div class="text-center mb-16 md:mb-20">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-800 tracking-tight">
                        Sangat Mudah, <span class="bg-clip-text text-transparent bg-gradient-to-r from-rOrange to-rRed">Cukup 3 Langkah</span>
                    </h2>
                    <p class="text-slate-500 mt-4 font-medium max-w-2xl mx-auto text-base sm:text-lg">
                        Tidak perlu jago coding atau desain. Semua sudah otomatis!
                    </p>
                </div>

                <div class="relative max-w-5xl mx-auto">

                    <div class="hidden md:block absolute top-10 lg:top-12 left-[16%] right-[16%] h-1 bg-gradient-to-r from-rLightOrange/40 via-rOrange/60 to-slate-800/20 rounded-full z-0"></div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6 lg:gap-12 relative z-10 text-center">

                        <div class="group bg-white p-6 lg:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 hover:-translate-y-2 hover:shadow-2xl hover:border-rLightOrange/50 transition-all duration-300">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 mx-auto bg-white border-4 border-rLightOrange rounded-full flex items-center justify-center shadow-lg shadow-rOrange/10 mb-6 text-2xl lg:text-3xl font-extrabold text-rOrange group-hover:scale-110 transition-transform duration-300">
                                1
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-rOrange transition-colors">Pilih Paket & Tema</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">
                                Pilih desain tema undangan yang paling cocok dengan selera pernikahanmu dari katalog eksklusif kami.
                            </p>
                        </div>

                        <div class="group bg-white p-6 lg:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 hover:-translate-y-2 hover:shadow-2xl hover:border-rOrange/50 transition-all duration-300">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 mx-auto bg-gradient-to-br from-rOrange to-rRed border-4 border-white text-white rounded-full flex items-center justify-center shadow-lg shadow-rOrange/30 mb-6 text-2xl lg:text-3xl font-extrabold group-hover:scale-110 transition-transform duration-300">
                                2
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-rOrange transition-colors">Isi Data Lengkap</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">
                                Masukkan data mempelai, jadwal akad & resepsi, foto galeri pre-wedding, hingga link alamat Google Maps.
                            </p>
                        </div>

                        <div class="group bg-white p-6 lg:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 hover:-translate-y-2 hover:shadow-2xl hover:border-slate-800/50 transition-all duration-300">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 mx-auto bg-slate-800 border-4 border-white text-white rounded-full flex items-center justify-center shadow-lg shadow-slate-900/30 mb-6 text-2xl lg:text-3xl font-extrabold group-hover:scale-110 transition-transform duration-300">
                                3
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-slate-900 transition-colors">Sebarkan Tautan</h3>
                            <p class="text-slate-500 text-sm leading-relaxed">
                                Undangan digitalmu sudah aktif! Langsung salin link-nya dan bagikan ke WhatsApp teman serta kerabatmu.
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </section>
        {{-- SECTION FITUR --}}
        <section id="feature" class="py-24 relative bg-slate-50 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal">

                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-800 tracking-tight">
                        Fitur <span class="bg-clip-text text-transparent bg-gradient-to-r from-rOrange to-rRed">Lengkap & Premium</span>
                    </h2>
                    <p class="text-slate-500 mt-4 font-medium max-w-2xl mx-auto text-base sm:text-lg">
                        Fasilitas undangan modern untuk memanjakan tamu dan mempermudah kamu. Semua dalam satu platform.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

                    <div class="bg-white p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-2xl border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-rRed text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10">BEST VALUE</div>
                        <div class="w-14 h-14 rounded-2xl bg-rRed/10 text-rRed flex items-center justify-center mb-6 group-hover:bg-rRed group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3">Masa Aktif Selamanya</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Tanpa biaya perpanjangan! Undanganmu akan terus aktif menjadi kenangan manis yang bisa diakses kapan pun.</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-2xl border border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-500 flex items-center justify-center mb-6 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3">Coba Dulu, Bayar Nanti</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Buat undangan dan lihat hasilnya secara gratis. Lakukan pembayaran hanya ketika kamu sudah 100% puas dengan desainnya.</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-2xl border border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-rOrange/10 text-rOrange flex items-center justify-center mb-6 group-hover:bg-rOrange group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3">Buku Tamu (RSVP)</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Dapatkan kepastian jumlah tamu yang hadir dan terima ucapan doa indah langsung di layar dashboard undanganmu.</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-2xl border border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-500 flex items-center justify-center mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3">Amplop & Kado Digital</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Tamu yang berhalangan hadir dapat mengirimkan hadiah atau angpao langsung ke rekening Bank atau E-Wallet kamu dengan mudah.</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-2xl border border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-500 flex items-center justify-center mb-6 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3">Check-in QR Code</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Selamat tinggal buku tamu kertas! Scan QR Code tamu di lokasi acara untuk proses check-in yang canggih dan bebas antre.</p>
                    </div>

                    <div class="bg-white p-8 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-2xl border border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-pink-100 text-pink-500 flex items-center justify-center mb-6 group-hover:bg-pink-500 group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3">Musik Latar Otomatis</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">Bangun suasana romantis dengan menambahkan lagu favorit yang akan diputar otomatis saat tamu membuka undanganmu.</p>
                    </div>

                </div>
            </div>
        </section>
        {{-- 🔥 KATALOG TEMA (DIPERBAIKI) 🔥 --}}
        <section id="produk" class="py-24 relative bg-white border-t border-slate-100 overflow-hidden">
            <div class="absolute top-0 right-0 w-72 h-72 bg-rLightOrange rounded-full mix-blend-multiply filter blur-[100px] opacity-30 z-0"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-rRed rounded-full mix-blend-multiply filter blur-[100px] opacity-10 z-0"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center reveal">

                <div class="max-w-3xl mx-auto">
                    <span class="inline-block py-1.5 px-4 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mb-6 tracking-widest uppercase border border-slate-200">
                        ✨ Koleksi Desain RuangRestu
                    </span>

                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-800 mb-6 leading-tight tracking-tight">
                        Temukan Tema <span class="bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">Paling Sempurna</span> Untukmu
                    </h2>

                    <p class="text-slate-500 font-medium text-base sm:text-lg mb-12 leading-relaxed">
                        Dari desain elegan, modern, hingga tradisional, kami menyediakan puluhan <span class="font-semibold text-slate-700">template eksklusif</span> yang responsif dan siap disesuaikan dengan kisah cintamu.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-8 sm:mt-0">
                    <a href="{{ route('katalog') }}" class="group bg-slate-900 text-white px-8 sm:px-10 py-4 rounded-full font-bold text-base sm:text-lg hover:-translate-y-1 hover:bg-slate-800 transition-all duration-300 shadow-xl shadow-slate-900/20 flex items-center justify-center gap-3 w-full sm:w-auto">
                        <i class="fa-solid fa-layer-group group-hover:rotate-12 transition-transform"></i>
                        Jelajahi Semua Tema
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

            </div>
        </section>

        {{-- SECTION HARGA --}}
        <section id="harga" class="py-24 bg-slate-50 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 reveal">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Harga Jujur & <span class="text-rOrange">Transparan</span></h2>
                    <p class="text-slate-500 font-medium">Bayar sekali, dapatkan fitur sepuasnya tanpa biaya langganan bulanan tersembunyi.</p>
                </div>

                <div class="flex flex-wrap justify-center gap-6 lg:gap-8 max-w-6xl mx-auto items-stretch">
                    @forelse($packages as $package)
                    @php
                    $features = is_array($package->features) ? $package->features : json_decode($package->features, true) ?? [];
                    $display = $features['display'] ?? [];
                    $includedItems = $display['included'] ?? [];
                    $excludedItems = $display['excluded'] ?? [];

                    $masaAktif = 'Masa Aktif Standar';
                    foreach ($includedItems as $key => $item) {
                    if (stripos($item, 'Masa Aktif') !== false) {
                    $masaAktif = $item;
                    unset($includedItems[$key]);
                    break;
                    }
                    }

                    $isPremium = strtolower($package->name) == 'premium' || $package->price > 50000;
                    @endphp

                    <div class="w-full md:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1.5rem)] flex flex-col bg-white p-8 md:p-10 rounded-[2.5rem] border-2 {{ $isPremium ? 'border-rOrange shadow-2xl shadow-rOrange/20' : 'border-slate-100 shadow-xl shadow-slate-200/50' }} relative transition-all duration-300 hover:-translate-y-2">

                        @if($isPremium)
                        <div class="absolute top-0 right-0 bg-gradient-to-r from-rRed to-rOrange text-white px-5 py-1.5 rounded-bl-2xl rounded-tr-[2.3rem] font-bold text-[10px] uppercase tracking-widest shadow-md z-20">Paling Laris</div>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">{{ $package->name }}</h3>
                            <p class="text-sm text-slate-500 font-medium h-10 overflow-hidden line-clamp-2">{{ $package->description }}</p>
                        </div>

                        <div class="flex items-end gap-2 mb-6">
                            <span class="text-4xl font-black text-slate-800">Rp {{ number_format($package->price / 1000, 0, ',', '.') }}<span class="text-xl">rb</span></span>
                            @if ($package->original_price)
                            <span class="text-lg font-bold text-slate-400 line-through mb-1">Rp {{ number_format($package->original_price / 1000, 0, ',', '.') }}rb</span>
                            @endif
                        </div>

                        <div class="mb-6">
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 {{ $isPremium ? 'bg-orange-50 text-rOrange border-orange-200' : 'bg-slate-50 text-slate-600 border-slate-200' }} text-xs font-bold rounded-xl border">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $masaAktif }}
                            </span>
                        </div>

                        <hr class="border-slate-100 mb-6">

                        <ul class="space-y-4 mb-8 text-sm flex-1">
                            @if (is_array($includedItems) && count($includedItems) > 0)
                            @foreach ($includedItems as $included_feature)
                            <li class="flex items-start text-slate-700 font-semibold">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $included_feature }}</span>
                            </li>
                            @endforeach
                            @endif
                            @if (is_array($excludedItems) && count($excludedItems) > 0)
                            @foreach ($excludedItems as $excluded_feature)
                            <li class="flex items-start text-slate-400 font-medium">
                                <svg class="w-5 h-5 text-slate-300 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>{{ $excluded_feature }}</span>
                            </li>
                            @endforeach
                            @endif
                        </ul>

                        <div class="mt-auto pt-4">
                            @auth
                            <a href="{{ route('customer.dashboard') }}" class="block text-center w-full py-4 {{ $isPremium ? 'bg-gradient-to-r from-rRed to-rOrange text-white shadow-lg shadow-rRed/30 glow-btn border-0' : 'bg-slate-900 text-white hover:bg-slate-800' }} rounded-2xl font-bold transition">Buat Sekarang</a>
                            @else
                            <a href="{{ route('login') }}" class="block text-center w-full py-4 {{ $isPremium ? 'bg-gradient-to-r from-rRed to-rOrange text-white shadow-lg shadow-rRed/30 glow-btn border-0' : 'bg-slate-900 text-white hover:bg-slate-800' }} rounded-2xl font-bold transition">Daftar Sekarang</a>
                            @endauth
                        </div>
                    </div>
                    @empty
                    <div class="w-full text-center py-10 text-slate-500">Belum ada paket harga yang tersedia.</div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- CTA BOTTOM --}}
        <section id="cta" class="py-24 relative reveal">
            <div class="max-w-5xl mx-auto px-4">
                <div class="bg-slate-900 p-12 md:p-16 rounded-[3.5rem] text-center relative overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-rRed filter blur-[80px] rounded-full opacity-40"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-rOrange filter blur-[80px] rounded-full opacity-40"></div>

                    <div class="relative z-10">
                        <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Momen Spesial Dimulai Dari Sini</h2>
                        <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto">Tinggalkan cara lama yang repot. Beralih ke undangan digital RuangRestu yang modern, ramah lingkungan, dan siap disebarkan ke ratusan tamu dalam 1 klik.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-white text-slate-900 px-10 py-5 rounded-full font-bold text-xl hover:scale-105 transition-transform shadow-lg">
                            Mulai Buat Gratis
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
                        RuangRestu
                    </a>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Dimulai dengan Doa, Diabadikan dengan Restu.</p>
                </div>
                <div class="flex space-x-6 text-slate-500 font-medium">
                    <a href="javascript:void(0)" onclick="openModal('syarat')" class="hover:text-rRed transition">Syarat & Ketentuan</a>
                    <a href="javascript:void(0)" onclick="openModal('privasi')" class="hover:text-rRed transition">Kebijakan Privasi</a>
                    <a href="javascript:void(0)" onclick="openModal('bantuan')" class="hover:text-rRed transition">Bantuan</a>
                </div>
            </div>
            <div class="border-t border-slate-100 mt-8 pt-8 text-center text-slate-400 text-xs font-bold uppercase tracking-wider">
                &copy; {{ date('Y') }} RuangRestu.com. All rights reserved.
            </div>
        </div>
    </footer>

    <section id="modals-here">
        <div id="modal-overlay"
            class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

            <div id="modal-content"
                class="bg-white w-full max-w-2xl max-h-[80vh] rounded-[2.5rem] shadow-2xl overflow-hidden relative flex flex-col scale-95 opacity-0 transition-all duration-300">

                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h3 id="modal-title" class="text-2xl font-bold text-slate-800">Judul Modal</h3>
                    <button onclick="closeModal()"
                        class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-800 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="modal-body" class="p-8 overflow-y-auto text-slate-600 leading-relaxed">
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50 flex justify-end">
                    <button onclick="closeModal()"
                        class="px-6 py-2.5 bg-slate-800 text-white rounded-xl font-semibold hover:bg-slate-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('cst/js/landing.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');
            const mobileLinks = document.querySelectorAll('.mobile-link');

            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                    iconOpen.classList.toggle('hidden');
                    iconClose.classList.toggle('hidden');
                });
                mobileLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        menu.classList.add('hidden');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    });
                });
            }
        });
    </script>
</body>

</html>