<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangRestu | Undangan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('cst/css/landing.css') }}">
</head>
<body class="bg-slate-50 text-slate-800 font-sans overflow-x-hidden relative">

    {{-- TOAST NOTIFICATION (CSS Murni + Sedikit JS) --}}
    @if (session('success') || session('error') || session('status'))
        <div id="toast-notification" class="fixed top-24 right-4 md:right-8 z-[100] transform transition-all duration-500 translate-x-0 opacity-100 flex items-center p-4 mb-4 text-slate-500 bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 min-w-[300px]" role="alert">
            @if (session('success') || session('status') == 'verification-link-sent')
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-green-500 bg-green-100 rounded-xl">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
                    <span class="sr-only">Check icon</span>
                </div>
            @elseif(session('error'))
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-100 rounded-xl">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>
                    <span class="sr-only">Error icon</span>
                </div>
            @endif
            <div class="ms-4 text-sm font-bold text-slate-700 pr-6">
                {{ session('success') ?? session('error') }}
                {{ session('status') == 'verification-link-sent' ? 'Link verifikasi telah dikirim ke email Anda.' : '' }}
            </div>
            <button type="button" onclick="closeToast()" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
        <script>
            // Script untuk menghilangkan toast otomatis setelah 5 detik
            function closeToast() {
                const toast = document.getElementById('toast-notification');
                if(toast) {
                    toast.classList.replace('translate-x-0', 'translate-x-full');
                    toast.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => toast.remove(), 500); // Hapus dari DOM setelah animasi selesai
                }
            }
            setTimeout(closeToast, 5000);
        </script>
    @endif

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
                        <a href="#home" class="text-slate-600 hover:text-rRed font-medium transition duration-300">Home</a>
                        <a href="#produk" class="text-slate-600 hover:text-rRed font-medium transition duration-300">Produk</a>
                        <a href="#harga" class="text-slate-600 hover:text-rRed font-medium transition duration-300">Harga</a>
                        @auth
                            <a href="{{ route('customer.dashboard') }}" class="text-slate-600 hover:text-rRed font-medium transition duration-300">Dashboard</a>
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-2.5 rounded-full font-semibold glow-btn">
                                Login
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
                <a href="#home" class="mobile-link text-slate-600 hover:text-rRed font-medium py-3 border-b border-slate-50">Home</a>
                <a href="#produk" class="mobile-link text-slate-600 hover:text-rRed font-medium py-3 border-b border-slate-50">Produk</a>
                <a href="#harga" class="mobile-link text-slate-600 hover:text-rRed font-medium py-3 border-b border-slate-50">Harga</a>
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="mobile-link text-slate-600 hover:text-rRed font-medium py-3">Dashboard</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-3 rounded-full font-semibold mt-4 mx-8 shadow-md">Login</a>
                @endguest
            </div>
        </div>
    </nav>
    
    <main>
        <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 flex items-center justify-center min-h-screen overflow-hidden">
            <div class="absolute top-20 left-1/4 w-72 h-72 bg-rLightOrange rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob z-0"></div>
            <div class="absolute top-40 right-1/4 w-72 h-72 bg-rRed rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 z-0"></div>
            <div class="absolute -bottom-8 left-1/3 w-72 h-72 bg-rYellow rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob z-0"></div>

            <div class="max-w-7xl mx-auto px-4 z-10">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="lg:w-1/2 text-center lg:text-left reveal">
                        <span class="inline-block py-1 px-4 rounded-full bg-white border border-rLightOrange/30 text-rOrange text-sm font-semibold mb-6 shadow-sm">
                            ✨ Ciptakan Momen Tak Terlupakan
                        </span>
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 text-slate-800 leading-tight">
                            Mulai dengan Doa,<br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-rRed via-rOrange to-rYellow">
                                Diabadikan dengan Restu
                            </span>
                        </h1>
                        <p class="mt-4 max-w-2xl text-lg text-slate-500 mb-10 leading-relaxed">
                            Buat undangan digital impianmu dengan desain elegan yang bersih, fitur lengkap, dan kemudahan mengatur daftar tamu dalam satu genggaman.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-4 rounded-full font-bold text-lg glow-btn text-center">
                                Buat Undangan Sekarang
                            </a>
                            <a href="#produk" class="bg-white text-slate-700 border border-slate-200 px-8 py-4 rounded-full font-bold text-lg hover:bg-slate-50 transition text-center">
                                Lihat Demo
                            </a>
                        </div>
                    </div>

                    <div class="lg:w-1/2 relative flex justify-center items-center reveal" style="transition-delay: 200ms;">
                        <div class="relative z-20 w-[280px] h-[580px] bg-slate-900 rounded-[3rem] border-[8px] border-slate-800 shadow-2xl overflow-hidden">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-slate-800 rounded-b-2xl z-30"></div>
                            <div class="w-full h-full bg-white bg-[url('https://images.unsplash.com/photo-1607190074257-dd4b7af0309f?q=80&w=1887&auto=format&fit=crop')] bg-cover bg-center">
                                <div class="absolute inset-0 bg-black/5"></div>
                            </div>
                        </div>

                        <div class="absolute -right-4 top-20 glass-light p-4 rounded-2xl shadow-xl z-30 hidden md:block animate-bounce" style="animation-duration: 4s;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Konfirmasi Hadir</p>
                                    <p class="text-xs font-bold text-slate-800">Jati & Nabila</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -left-10 bottom-32 glass-light p-4 rounded-2xl shadow-xl z-30 hidden md:block animate-bounce" style="animation-duration: 5s;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-rOrange/10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-rOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Kado Digital</p>
                                    <p class="text-xs font-bold text-slate-800">Berhasil Terkirim</p>
                                </div>
                            </div>
                        </div>
                        <div class="absolute w-64 h-64 bg-rRed rounded-full filter blur-[100px] opacity-20 z-10"></div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Feature Tetap --}}
        <section id="feature" class="py-20 relative bg-white/50 border-t border-b border-white">
            <div class="max-w-7xl mx-auto px-4 reveal">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">Fitur <span class="text-rOrange">Lengkap</span> untuk Harimu</h2>
                    <p class="text-slate-500 mt-3">Semua yang kamu butuhkan sudah kami siapkan dengan rapi.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- 1 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,90,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rRed/10 text-rRed flex items-center justify-center mb-4 group-hover:bg-rRed group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Masa Aktif 1 Bulan</h3>
                        <p class="text-slate-500 text-sm">Undanganmu akan menjadi memori digital yang bisa diakses kapan saja dalam masa aktif 1 bulan.</p>
                    </div>
                    {{-- 2 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,139,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rOrange/10 text-rOrange flex items-center justify-center mb-4 group-hover:bg-rOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Buku Tamu</h3>
                        <p class="text-slate-500 text-sm">Kelola daftar RSVP, terima ucapan, dan doa secara real-time dari para tamu.</p>
                    </div>
                    {{-- 3 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,169,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rLightOrange/10 text-rLightOrange flex items-center justify-center mb-4 group-hover:bg-rLightOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">QR Code Tamu</h3>
                        <p class="text-slate-500 text-sm">Sistem check-in modern menggunakan QR code unik untuk setiap tamu yang hadir.</p>
                    </div>
                    {{-- 4 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,212,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rYellow/10 text-rYellow flex items-center justify-center mb-4 group-hover:bg-rYellow group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Beragam Tema</h3>
                        <p class="text-slate-500 text-sm">Pilihan desain premium yang *clean* dan elegan, dapat disesuaikan dengan warnamu.</p>
                    </div>
                    {{-- 5 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,90,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rRed/10 text-rRed flex items-center justify-center mb-4 group-hover:bg-rRed group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Story Mempelai</h3>
                        <p class="text-slate-500 text-sm">Bagikan linimasa perjalanan cinta kalian yang indah kepada para tamu.</p>
                    </div>
                    {{-- 6 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,139,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rOrange/10 text-rOrange flex items-center justify-center mb-4 group-hover:bg-rOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Google Maps</h3>
                        <p class="text-slate-500 text-sm">Integrasi navigasi akurat agar tamu mudah menemukan lokasi acaramu.</p>
                    </div>
                    {{-- 7 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,169,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rLightOrange/10 text-rLightOrange flex items-center justify-center mb-4 group-hover:bg-rLightOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Amplop Digital</h3>
                        <p class="text-slate-500 text-sm">Fitur transfer langsung dan e-wallet untuk menerima hadiah tanpa repot.</p>
                    </div>
                    {{-- 8 --}}
                    <div class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,212,90,0.1)]">
                        <div class="w-12 h-12 rounded-full bg-rYellow/10 text-rYellow flex items-center justify-center mb-4 group-hover:bg-rYellow group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Pilihan Musik</h3>
                        <p class="text-slate-500 text-sm">Tambahkan lagu favorit, video profile, dan custom teks pengantar undangan.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- KATALOG TEMA (DIPERBARUI) --}}
        <section id="produk" class="py-24 relative bg-white">
            <div class="max-w-7xl mx-auto px-4 reveal">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                    <div class="max-w-xl">
                        <h2 class="text-4xl font-extrabold text-slate-800 mb-4">Template <span class="text-rOrange">Eksklusif</span></h2>
                        <p class="text-slate-500">Pilih desain yang mencerminkan karakter cintamu. Semua template responsif dan sangat mudah disesuaikan.</p>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <a href="{{ route('katalog') }}" class="text-rRed font-bold flex items-center hover:translate-x-2 transition-transform">
                            Lihat Semua Tema <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($templates as $template)
                        <div tabindex="0" class="group relative bg-slate-50 rounded-[2.5rem] overflow-hidden border border-slate-100 transition-all duration-500 hover:shadow-2xl hover:shadow-rOrange/10 focus:outline-none">
                            <div class="h-[400px] overflow-hidden relative bg-stone-100">
                                <div class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-110 group-focus:scale-110">
                                    <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden">
                                        <iframe src="{{ asset('preview/' . $template->view_path . '/index.html') }}" class="absolute top-0 left-0 w-[400%] h-[400%] origin-top-left scale-[0.25] border-0" scrolling="no" tabindex="-1"></iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-white/40 to-transparent opacity-0 group-hover:opacity-100 group-focus:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8 pointer-events-none">
                                <div class="bg-white/80 backdrop-blur-md p-6 rounded-3xl translate-y-10 group-hover:translate-y-0 group-focus:translate-y-0 transition-transform duration-500 pointer-events-auto shadow-lg border border-white/50 text-center">
                                    <h3 class="text-xl font-bold text-slate-800">{{ $template->name }}</h3>
                                    <p class="text-slate-500 text-[10px] uppercase font-bold tracking-wider mb-4">{{ $template->category->name ?? 'Umum' }}</p>
                                    <a href="{{ asset('preview/' . $template->view_path . '/index.html') }}" target="_blank" class="block w-full py-3 bg-slate-800 text-white rounded-2xl font-semibold hover:bg-rOrange transition shadow-md">
                                        Preview Live
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 text-slate-500">Belum ada template yang diunggah.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="cta" class="py-24 relative reveal">
            <div class="max-w-5xl mx-auto px-4">
                <div class="glass-light p-12 md:p-16 rounded-[3rem] text-center relative overflow-hidden border-2 border-white shadow-[0_20px_50px_rgba(255,90,90,0.08)]">
                    <div class="absolute inset-0 bg-gradient-to-br from-rRed/5 via-white to-rYellow/5"></div>
                    <div class="relative z-10">
                        <h2 class="text-4xl md:text-5xl font-extrabold text-slate-800 mb-6">Siap Menyebarkan Kabar Bahagiamu?</h2>
                        <p class="text-slate-600 text-lg mb-10 max-w-2xl mx-auto">Gabung bersama kami dan bagikan kebahagiaanmu dalam bentuk undangan digital yang elegan dan fungsional.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-rRed to-rOrange text-white px-10 py-5 rounded-full font-bold text-xl glow-btn hover:scale-105 transition-transform">
                            Mulai Buat Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="harga" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 reveal">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-extrabold text-slate-800 mb-4">Investasi Kebahagiaan</h2>
                    <p class="text-slate-500">Satu kali bayar, aktif selamanya. Tanpa biaya langganan bulanan.</p>
                </div>

                <div class="max-w-md mx-auto items-stretch">
                    @forelse($packages as $package)
                        @php
                            $features = is_array($package->features) ? $package->features : json_decode($package->features, true) ?? [];
                            $display = $features['display'] ?? [];
                            $includedItems = $display['included'] ?? [];
                            $excludedItems = $display['excluded'] ?? [];

                            $masaAktif = 'Unlimited';
                            foreach ($includedItems as $key => $item) {
                                if (stripos($item, 'Masa Aktif') !== false) {
                                    $masaAktif = $item;
                                    unset($includedItems[$key]); 
                                    break;
                                }
                            }
                        @endphp
                        <div class="flex flex-col h-full bg-white p-10 md:p-12 rounded-[3.5rem] border-2 border-rRed shadow-2xl shadow-rRed/20 relative z-10 overflow-hidden transition-transform duration-300 hover:-translate-y-2">
                            <div class="absolute top-0 right-0 bg-rRed text-white px-6 py-2 rounded-bl-3xl font-bold text-sm z-20 shadow-md">PILIHAN TERBAIK</div>
                            <h3 class="text-3xl font-bold text-slate-800 mb-2">{{ $package->name }}</h3>
                            <div class="flex items-end mb-4 gap-3 flex-wrap">
                                <span class="text-5xl font-extrabold text-slate-800">Rp {{ number_format($package->price / 1000, 0, ',', '.') }}rb</span>
                                @if ($package->original_price)
                                    <span class="text-lg md:text-xl font-bold text-slate-400 line-through mb-1">Rp {{ number_format($package->original_price / 1000, 0, ',', '.') }}rb</span>
                                @endif
                            </div>
                            <div class="mb-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full border border-slate-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $masaAktif }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-500 mb-6 font-medium">{{ $package->description }}</p>
                            <ul class="space-y-3 mb-10 text-slate-600 text-sm flex-1">
                                @if (is_array($includedItems) && count($includedItems) > 0)
                                    @foreach ($includedItems as $included_feature)
                                        <li class="flex items-start text-slate-700 font-medium">
                                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            <span>{{ $included_feature }}</span>
                                        </li>
                                    @endforeach
                                @endif
                                @if (is_array($excludedItems) && count($excludedItems) > 0)
                                    @foreach ($excludedItems as $excluded_feature)
                                        <li class="flex items-start text-slate-400 line-through">
                                            <svg class="w-5 h-5 text-slate-300 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            <span>{{ $excluded_feature }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                            @auth
                                <a href="{{ route('customer.dashboard') }}" class="mt-auto block text-center w-full py-4 bg-gradient-to-r from-rRed to-rOrange text-white border-0 shadow-lg shadow-rRed/30 glow-btn rounded-2xl font-bold transition relative z-20">Pilih Paket</a>
                            @else
                                <a href="{{ route('login') }}" class="mt-auto block text-center w-full py-4 bg-gradient-to-r from-rRed to-rOrange text-white border-0 shadow-lg shadow-rRed/30 glow-btn rounded-2xl font-bold transition relative z-20">Daftar Sekarang</a>
                            @endauth
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 w-full">Belum ada paket harga yang tersedia.</div>
                    @endforelse
                </div>
            </div>
        </section>

    </main>
    <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <a href="#" class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">RuangRestu</a>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Dimulai dengan Doa, Diabadikan dengan Restu.</p>
                </div>
                <div class="flex space-x-6 text-slate-500 font-medium">
                    <a href="javascript:void(0)" onclick="openModal('syarat')" class="hover:text-rRed transition">Syarat & Ketentuan</a>
                    <a href="javascript:void(0)" onclick="openModal('privasi')" class="hover:text-rRed transition">Kebijakan Privasi</a>
                </div>
            </div>
            <div class="border-t border-slate-100 mt-8 pt-8 text-center text-slate-400 text-sm">
                &copy; 2026 RuangRestu.com. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="{{ asset('cst/js/landing.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');
            const mobileLinks = document.querySelectorAll('.mobile-link');

            if(btn && menu) {
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