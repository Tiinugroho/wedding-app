<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Tema | RuangRestu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('cst/css/landing.css') }}">
</head>
<body class="bg-slate-50 text-slate-800 font-sans overflow-x-hidden relative">

    {{-- TOAST NOTIFICATION --}}
    @if (session('success') || session('error') || session('status'))
        <div id="toast-notification" class="fixed top-24 right-4 md:right-8 z-[100] transform transition-all duration-500 translate-x-0 opacity-100 flex items-center p-4 mb-4 text-slate-500 bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-slate-100 min-w-[300px]" role="alert">
            @if (session('success') || session('status') == 'verification-link-sent')
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-green-500 bg-green-100 rounded-xl">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
                </div>
            @elseif(session('error'))
                <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-100 rounded-xl">
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>
                </div>
            @endif
            <div class="ms-4 text-sm font-bold text-slate-700 pr-6">
                {{ session('success') ?? session('error') }}
                {{ session('status') == 'verification-link-sent' ? 'Link verifikasi telah dikirim ke email Anda.' : '' }}
            </div>
            <button type="button" onclick="closeToast()" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8">
                <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
        <script>
            function closeToast() {
                const toast = document.getElementById('toast-notification');
                if(toast) {
                    toast.classList.replace('translate-x-0', 'translate-x-full');
                    toast.classList.replace('opacity-100', 'opacity-0');
                    setTimeout(() => toast.remove(), 500);
                }
            }
            setTimeout(closeToast, 5000);
        </script>
    @endif

    {{-- NAVBAR SAMA DENGAN WELCOME --}}
    <nav class="fixed w-full z-50 glass-light top-0 left-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="{{ route('welcome') }}" class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange flex items-center gap-2">
                        RuangRestu
                    </a>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-8">
                        {{-- Arahkan kembali ke halaman depan --}}
                        <a href="{{ route('welcome') }}#home" class="text-slate-600 hover:text-rRed font-medium transition duration-300">Home</a>
                        <a href="{{ route('welcome') }}#produk" class="text-slate-600 hover:text-rRed font-medium transition duration-300">Produk</a>
                        <a href="{{ route('welcome') }}#harga" class="text-slate-600 hover:text-rRed font-medium transition duration-300">Harga</a>
                        
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
                <a href="{{ route('welcome') }}#home" class="mobile-link text-slate-600 hover:text-rRed font-medium py-3 border-b border-slate-50">Home</a>
                <a href="{{ route('welcome') }}#produk" class="mobile-link text-slate-600 hover:text-rRed font-medium py-3 border-b border-slate-50">Produk</a>
                <a href="{{ route('welcome') }}#harga" class="mobile-link text-slate-600 hover:text-rRed font-medium py-3 border-b border-slate-50">Harga</a>
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
        <section class="relative bg-white/50 border-y border-white">
            <div class="max-w-7xl mx-auto px-4 reveal pt-28 lg:pt-32 pb-16">
                
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-rRed transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                {{-- JUDUL HALAMAN --}}
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 mb-4">Eksplorasi <span class="text-rOrange">Desain</span></h1>
                    <p class="text-slate-500 text-lg max-w-2xl mx-auto">Temukan tema undangan digital yang paling menggambarkan kisah cintamu. Dari minimalis hingga mewah, semua ada di sini.</p>
                </div>

                {{-- FILTER BUTTONS --}}
                <div class="flex flex-wrap justify-center gap-3 mb-12">
                    <button class="filter-btn active px-6 py-2.5 rounded-full font-bold text-sm transition-all duration-300 bg-slate-900 text-white shadow-lg" data-filter="all">
                        Semua Tema
                    </button>
                    @foreach($categories as $cat)
                        <button class="filter-btn px-6 py-2.5 rounded-full font-bold text-sm transition-all duration-300 bg-white text-slate-500 hover:bg-slate-100 border border-slate-200" data-filter="cat-{{ $cat->id }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>

                {{-- GRID KATALOG TEMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="template-grid">
                    @forelse($templates as $template)
                        <div class="template-card filter-item cat-{{ $template->category_id ?? 'none' }} group relative bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 transition-all duration-500 hover:shadow-2xl hover:shadow-rOrange/10 hover:-translate-y-2">
                            
                            {{-- Iframe Preview --}}
                            <div class="h-[350px] overflow-hidden relative bg-stone-100">
                                <div class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden">
                                        <iframe src="{{ asset('preview/' . $template->view_path . '/index.html') }}?thumbnail=1" class="absolute top-0 left-0 w-[400%] h-[400%] origin-top-left scale-[0.25] border-0" scrolling="no" tabindex="-1"></iframe>
                                    </div>
                                </div>
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-white/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6 pointer-events-none">
                                    <div class="bg-white/80 backdrop-blur-md p-5 rounded-3xl translate-y-10 group-hover:translate-y-0 transition-transform duration-500 pointer-events-auto shadow-lg border border-white/50 text-center">
                                        <a href="{{ asset('preview/' . $template->view_path . '/index.html') }}" target="_blank" class="block w-full py-3 bg-slate-800 text-white rounded-2xl font-semibold hover:bg-rOrange transition shadow-md">
                                            Preview Live
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Info Text --}}
                            <div class="p-6 border-t border-slate-100">
                                <span class="inline-block px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold rounded-full uppercase tracking-wider mb-3">
                                    {{ $template->category->name ?? 'Umum' }}
                                </span>
                                <h3 class="text-xl font-bold text-slate-800 mb-4">{{ $template->name }}</h3>
                                
                                @auth
                                    <a href="{{ route('customer.invitations.create') }}" class="block text-center w-full py-3 border border-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-900 hover:text-white hover:border-slate-900 transition">Gunakan Tema Ini</a>
                                @else
                                    <a href="{{ route('login') }}" class="block text-center w-full py-3 border border-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-900 hover:text-white hover:border-slate-900 transition">Buat Undangan</a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20 text-slate-500">
                            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <p class="text-lg font-medium">Belum ada template yang diunggah untuk saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER SAMA DENGAN WELCOME --}}
    <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <a href="{{ route('welcome') }}" class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
                        RuangRestu
                    </a>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Dimulai dengan Doa, Diabadikan dengan Restu.</p>
                </div>
                <div class="flex space-x-6 text-slate-500 font-medium">
                    <a href="javascript:void(0)" class="hover:text-rRed transition">Syarat & Ketentuan</a>
                    <a href="javascript:void(0)" class="hover:text-rRed transition">Kebijakan Privasi</a>
                    <a href="javascript:void(0)" class="hover:text-rRed transition">Bantuan</a>
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
            // SCRIPT UNTUK TOGGLE MENU MOBILE
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

            // SCRIPT FILTER KATALOG
            const filterBtns = document.querySelectorAll('.filter-btn');
            const items = document.querySelectorAll('.filter-item');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Reset class button
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-slate-900', 'text-white', 'shadow-lg');
                        b.classList.add('bg-white', 'text-slate-500');
                    });
                    
                    // Set active button
                    this.classList.remove('bg-white', 'text-slate-500');
                    this.classList.add('bg-slate-900', 'text-white', 'shadow-lg');

                    const filterValue = this.getAttribute('data-filter');

                    items.forEach(item => {
                        item.style.transition = 'all 0.4s ease';
                        
                        if (filterValue === 'all' || item.classList.contains(filterValue)) {
                            item.style.display = 'block';
                            setTimeout(() => {
                                item.style.opacity = '1';
                                item.style.transform = 'scale(1)';
                            }, 50);
                        } else {
                            item.style.opacity = '0';
                            item.style.transform = 'scale(0.9)';
                            setTimeout(() => {
                                item.style.display = 'none';
                            }, 400); 
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>