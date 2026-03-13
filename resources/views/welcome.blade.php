<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangRestu | Undangan Digital</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('cst/css/landing.css') }}">
</head>

<body class="bg-slate-50 text-slate-800 font-sans overflow-x-hidden">

    <nav class="fixed w-full z-50 glass-light top-0 left-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="index.html"
                        class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
                        RuangRestu
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-8">
                        <a href="#home"
                            class="text-slate-600 hover:text-rRed font-medium transition duration-300">Home</a>
                        <a href="#produk"
                            class="text-slate-600 hover:text-rRed font-medium transition duration-300">Produk</a>
                        <a href="#harga"
                            class="text-slate-600 hover:text-rRed font-medium transition duration-300">Harga</a>
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="text-slate-600 hover:text-rRed font-medium transition duration-300">
                                Dashboard
                            </a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}"
                                class="bg-gradient-to-r from-rRed to-rOrange text-white px-6 py-2.5 rounded-full font-semibold glow-btn">
                                Login
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main>

        <section id="home"
            class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 flex items-center justify-center min-h-screen overflow-hidden">
            <div
                class="absolute top-20 left-1/4 w-72 h-72 bg-rLightOrange rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob z-0">
            </div>
            <div
                class="absolute top-40 right-1/4 w-72 h-72 bg-rRed rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 z-0">
            </div>
            <div
                class="absolute -bottom-8 left-1/3 w-72 h-72 bg-rYellow rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob z-0">
            </div>

            <div class="max-w-7xl mx-auto px-4 z-10">
                <div class="flex flex-col lg:flex-row items-center gap-12">

                    <div class="lg:w-1/2 text-center lg:text-left reveal">
                        <span
                            class="inline-block py-1 px-4 rounded-full bg-white border border-rLightOrange/30 text-rOrange text-sm font-semibold mb-6 shadow-sm">
                            ✨ Ciptakan Momen Tak Terlupakan
                        </span>
                        <h1
                            class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 text-slate-800 leading-tight">
                            Mulai dengan Doa,<br>
                            <span
                                class="bg-clip-text text-transparent bg-gradient-to-r from-rRed via-rOrange to-rYellow">
                                Diabadikan dengan Restu
                            </span>
                        </h1>
                        <p class="mt-4 max-w-2xl text-lg text-slate-500 mb-10 leading-relaxed">
                            Buat undangan digital impianmu dengan desain elegan yang bersih, fitur lengkap, dan
                            kemudahan mengatur daftar tamu dalam satu genggaman.
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                            <a href="#buat"
                                class="bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-4 rounded-full font-bold text-lg glow-btn text-center">
                                Buat Undangan Sekarang
                            </a>
                            <a href="#produk"
                                class="bg-white text-slate-700 border border-slate-200 px-8 py-4 rounded-full font-bold text-lg hover:bg-slate-50 transition text-center">
                                Lihat Demo
                            </a>
                        </div>
                    </div>

                    <div class="lg:w-1/2 relative flex justify-center items-center reveal"
                        style="transition-delay: 200ms;">
                        <div
                            class="relative z-20 w-[280px] h-[580px] bg-slate-900 rounded-[3rem] border-[8px] border-slate-800 shadow-2xl overflow-hidden">
                            <div
                                class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-slate-800 rounded-b-2xl z-30">
                            </div>

                            <div
                                class="w-full h-full bg-white bg-[url('https://images.unsplash.com/photo-1607190074257-dd4b7af0309f?q=80&w=1887&auto=format&fit=crop')] bg-cover bg-center">
                                <div class="absolute inset-0 bg-black/5"></div>
                            </div>
                        </div>

                        <div class="absolute -right-4 top-20 glass-light p-4 rounded-2xl shadow-xl z-30 hidden md:block animate-bounce"
                            style="animation-duration: 4s;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-medium">Konfirmasi Hadir</p>
                                    <p class="text-xs font-bold text-slate-800">Jati & Nabila</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -left-10 bottom-32 glass-light p-4 rounded-2xl shadow-xl z-30 hidden md:block animate-bounce"
                            style="animation-duration: 5s;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-rOrange/10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-rOrange" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
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

        <section id="feature" class="py-20 relative bg-white/50 border-t border-b border-white">
            <div class="max-w-7xl mx-auto px-4 reveal">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">Fitur <span
                            class="text-rOrange">Lengkap</span> untuk Harimu</h2>
                    <p class="text-slate-500 mt-3">Semua yang kamu butuhkan sudah kami siapkan dengan rapi.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,90,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rRed/10 text-rRed flex items-center justify-center mb-4 group-hover:bg-rRed group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Masa Aktif Selamanya</h3>
                        <p class="text-slate-500 text-sm">Undanganmu akan menjadi memori digital yang bisa diakses kapan
                            saja tanpa batas waktu.</p>
                    </div>

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,139,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rOrange/10 text-rOrange flex items-center justify-center mb-4 group-hover:bg-rOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Buku Tamu</h3>
                        <p class="text-slate-500 text-sm">Kelola daftar RSVP, terima ucapan, dan doa secara real-time
                            dari para tamu.</p>
                    </div>

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,169,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rLightOrange/10 text-rLightOrange flex items-center justify-center mb-4 group-hover:bg-rLightOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">QR Code Tamu</h3>
                        <p class="text-slate-500 text-sm">Sistem check-in modern menggunakan QR code unik untuk setiap
                            tamu yang hadir.</p>
                    </div>

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,212,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rYellow/10 text-rYellow flex items-center justify-center mb-4 group-hover:bg-rYellow group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Beragam Tema</h3>
                        <p class="text-slate-500 text-sm">Pilihan desain premium yang *clean* dan elegan, dapat
                            disesuaikan dengan warnamu.</p>
                    </div>

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,90,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rRed/10 text-rRed flex items-center justify-center mb-4 group-hover:bg-rRed group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Story Mempelai</h3>
                        <p class="text-slate-500 text-sm">Bagikan linimasa perjalanan cinta kalian yang indah kepada
                            para tamu.</p>
                    </div>

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,139,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rOrange/10 text-rOrange flex items-center justify-center mb-4 group-hover:bg-rOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Google Maps</h3>
                        <p class="text-slate-500 text-sm">Integrasi navigasi akurat agar tamu mudah menemukan lokasi
                            acaramu.</p>
                    </div>

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,169,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rLightOrange/10 text-rLightOrange flex items-center justify-center mb-4 group-hover:bg-rLightOrange group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Amplop Digital</h3>
                        <p class="text-slate-500 text-sm">Fitur transfer langsung dan e-wallet untuk menerima hadiah
                            tanpa repot.</p>
                    </div>

                    <div
                        class="glass-light p-6 rounded-[2rem] hover:-translate-y-2 transition-all duration-300 group hover:shadow-[0_10px_30px_rgba(255,212,90,0.1)]">
                        <div
                            class="w-12 h-12 rounded-full bg-rYellow/10 text-rYellow flex items-center justify-center mb-4 group-hover:bg-rYellow group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Pilihan Musik & Custom</h3>
                        <p class="text-slate-500 text-sm">Tambahkan lagu favorit, video profile, dan custom teks
                            pengantar undangan.</p>
                    </div>

                </div>
            </div>
        </section>

        <section id="cta" class="py-24 relative reveal">
            <div class="max-w-5xl mx-auto px-4">
                <div
                    class="glass-light p-12 md:p-16 rounded-[3rem] text-center relative overflow-hidden border-2 border-white shadow-[0_20px_50px_rgba(255,90,90,0.08)]">
                    <div class="absolute inset-0 bg-gradient-to-br from-rRed/5 via-white to-rYellow/5"></div>

                    <div class="relative z-10">
                        <h2 class="text-4xl md:text-5xl font-extrabold text-slate-800 mb-6">Siap Menyebarkan Kabar
                            Bahagiamu?</h2>
                        <p class="text-slate-600 text-lg mb-10 max-w-2xl mx-auto">Gabung bersama kami dan bagikan
                            kebahagiaanmu dalam bentuk undangan digital yang elegan dan fungsional.</p>
                        <a href="{{ route('login') }}"
                            class="inline-block bg-gradient-to-r from-rRed to-rOrange text-white px-10 py-5 rounded-full font-bold text-xl glow-btn hover:scale-105 transition-transform">
                            Mulai Buat Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="produk" class="py-24 relative bg-white">
            <div class="max-w-7xl mx-auto px-4 reveal">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                    <div class="max-w-xl">
                        <h2 class="text-4xl font-extrabold text-slate-800 mb-4">Template <span
                                class="text-rOrange">Eksklusif</span></h2>
                        <p class="text-slate-500">Pilih desain yang mencerminkan karakter cintamu. Semua template
                            responsif dan sangat mudah disesuaikan.</p>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <a href="#"
                            class="text-rRed font-bold flex items-center hover:translate-x-2 transition-transform">
                            Lihat Semua Tema <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($templates as $template)
                        <div
                            class="group relative bg-slate-50 rounded-[2.5rem] overflow-hidden border border-slate-100 transition-all duration-500 hover:shadow-2xl hover:shadow-rOrange/10">
                            <div class="h-[400px] overflow-hidden">
                                <img src="{{ $template->thumbnail ? asset('storage/' . $template->thumbnail) : 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80' }}"
                                    alt="{{ $template->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-white via-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                                <div
                                    class="glass-light p-6 rounded-3xl translate-y-10 group-hover:translate-y-0 transition-transform duration-500">
                                    <h3 class="text-xl font-bold text-slate-800">{{ $template->name }}</h3>
                                    <p class="text-slate-500 text-sm mb-4">Kategori:
                                        {{ $template->category->name ?? 'Umum' }}</p>
                                    <button
                                        class="w-full py-3 bg-slate-800 text-white rounded-2xl font-semibold hover:bg-rOrange transition">Preview
                                        Tema</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 text-slate-500">
                            Belum ada template yang diunggah.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="harga" class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 reveal">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-extrabold text-slate-800 mb-4">Investasi Kebahagiaan</h2>
                    <p class="text-slate-500">Satu kali bayar, aktif selamanya. Tanpa biaya langganan bulanan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                    @forelse($packages as $package)

                        <div
                            class="flex flex-col h-full {{ $loop->iteration == 2 ? 'bg-white p-12 rounded-[3.5rem] border-2 border-rRed shadow-2xl shadow-rRed/20 relative scale-105 z-10 overflow-hidden' : 'glass-light p-10 rounded-[3rem] border border-white transition-all duration-300 hover:-translate-y-2' }}">

                            @if ($loop->iteration == 2)
                                <div
                                    class="absolute top-0 right-0 bg-rRed text-white px-6 py-2 rounded-bl-3xl font-bold text-sm z-20">
                                    TERPOPULER</div>
                            @endif

                            <h3
                                class="{{ $loop->iteration == 2 ? 'text-2xl' : 'text-xl' }} font-bold text-slate-800 mb-2">
                                {{ $package->name }}</h3>

                            <div class="flex items-end mb-6 gap-3 flex-wrap">
                                <span
                                    class="{{ $loop->iteration == 2 ? 'text-5xl' : 'text-4xl' }} font-extrabold text-slate-800">
                                    Rp {{ number_format($package->price / 1000, 0, ',', '.') }}rb
                                </span>

                                @if ($package->original_price)
                                    <span class="text-lg md:text-xl font-bold text-slate-400 line-through mb-1">
                                        Rp {{ number_format($package->original_price / 1000, 0, ',', '.') }}rb
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-slate-500 mb-6 font-medium">{{ $package->description }}</p>

                            <ul class="space-y-3 mb-10 text-slate-600 text-sm flex-1">
                                @php
                                    $features = is_array($package->features)
                                        ? $package->features
                                        : json_decode($package->features, true) ?? [];
                                @endphp

                                @if (isset($features['included']) && is_array($features['included']))
                                    @foreach ($features['included'] as $included_feature)
                                        <li class="flex items-start text-slate-700 font-medium">
                                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $included_feature }}</span>
                                        </li>
                                    @endforeach
                                @endif

                                @if (isset($features['excluded']) && is_array($features['excluded']))
                                    @foreach ($features['excluded'] as $excluded_feature)
                                        <li class="flex items-start text-slate-400 line-through">
                                            <svg class="w-5 h-5 text-slate-300 mr-3 flex-shrink-0 mt-0.5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>{{ $excluded_feature }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            @auth

                                <a href="#"
                                    class="mt-auto block text-center w-full py-4 {{ $loop->iteration == 2 ? 'bg-gradient-to-r from-rRed to-rOrange text-white border-0 glow-btn' : 'border-2 border-slate-800 text-slate-800 hover:bg-slate-800 hover:text-white' }} rounded-2xl font-bold transition relative z-20">
                                    Pilih Paket
                                </a>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}"
                                    class="mt-auto block text-center w-full py-4 {{ $loop->iteration == 2 ? 'bg-gradient-to-r from-rRed to-rOrange text-white border-0 glow-btn' : 'border-2 border-slate-800 text-slate-800 hover:bg-slate-800 hover:text-white' }} rounded-2xl font-bold transition relative z-20">
                                    Daftar Sekarang
                                </a>
                            @endguest
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-10 text-slate-500">
                            Belum ada paket harga yang tersedia.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>


    </main>
    <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <a href="#"
                        class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
                        RuangRestu
                    </a>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Dimulai dengan Doa, Diabadikan dengan Restu.</p>
                </div>
                <div class="flex space-x-6 text-slate-500 font-medium">
                    <a href="javascript:void(0)" onclick="openModal('syarat')"
                        class="hover:text-rRed transition">Syarat & Ketentuan</a>
                    <a href="javascript:void(0)" onclick="openModal('privasi')"
                        class="hover:text-rRed transition">Kebijakan Privasi</a>
                    <a href="javascript:void(0)" onclick="openModal('bantuan')"
                        class="hover:text-rRed transition">Bantuan</a>
                </div>
            </div>
            <div class="border-t border-slate-100 mt-8 pt-8 text-center text-slate-400 text-sm">
                &copy; 2026 RuangRestu.com. All rights reserved.
            </div>
        </div>
    </footer>

    <section id="modals-here">
        <div id="modal-overlay"
            class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

            <div id="modal-content"
                class="bg-white w-full max-w-2xl max-h-[80vh] rounded-[2.5rem] shadow-2xl overflow-hidden relative flex flex-col scale-95 opacity-0 transition-all duration-300">

                <div
                    class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
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

</body>

</html>
