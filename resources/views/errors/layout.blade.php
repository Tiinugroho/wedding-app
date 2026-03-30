<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | RuangRestu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rRed: '#FF5A5A',
                        rOrange: '#FF8B5A',
                        rYellow: '#FFD45A',
                        rLightOrange: '#FFA35A',
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'floating 3s ease-in-out infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(20px, -30px) scale(1.05)' },
                            '66%': { transform: 'translate(-15px, 15px) scale(0.95)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        floating: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-light {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .glow-btn:hover { box-shadow: 0 10px 25px rgba(255, 90, 90, 0.3); }
        .eye-pupil { transition: transform 0.1s ease-out; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex items-center justify-center p-4 relative overflow-hidden" id="body-area">

    {{-- Background Blobs --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-48 h-48 md:w-80 md:h-80 bg-rLightOrange rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-10 right-10 w-64 h-64 md:w-96 md:h-96 bg-rRed rounded-full mix-blend-multiply filter blur-3xl opacity-15 animate-blob" style="animation-delay: 2s;"></div>
    </div>

    <main class="relative z-10 w-full max-w-xl mx-auto">
        <div class="glass-light p-8 md:p-14 rounded-[2.5rem] md:rounded-[3.5rem] shadow-xl text-center border-2 border-white/60">

            {{-- SVG ANIMASI KARAKTER --}}
            <div class="relative w-40 h-40 md:w-48 md:h-48 mx-auto mb-6 animate-float flex justify-center items-center" id="ghost-container">
                <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-xl" id="ghost-svg">
                    <defs>
                        <linearGradient id="ghostGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#FFD45A" />
                            <stop offset="50%" stop-color="#FF8B5A" />
                            <stop offset="100%" stop-color="#FF5A5A" />
                        </linearGradient>
                    </defs>
                    <path d="M100,20 C50,20 30,60 30,100 C30,150 40,180 50,180 C60,180 65,160 75,160 C85,160 90,180 100,180 C110,180 115,160 125,160 C135,160 140,180 150,180 C160,180 170,150 170,100 C170,60 150,20 100,20 Z" fill="url(#ghostGrad)" />
                    <circle cx="75" cy="85" r="14" fill="#FFFFFF" />
                    <circle cx="125" cy="85" r="14" fill="#FFFFFF" />
                    <circle cx="75" cy="85" r="6" fill="#334155" class="eye-pupil" id="pupil-left" />
                    <circle cx="125" cy="85" r="6" fill="#334155" class="eye-pupil" id="pupil-right" />
                    <ellipse cx="100" cy="115" rx="8" ry="12" fill="#334155" />
                    <ellipse cx="60" cy="105" rx="8" ry="4" fill="#FFFFFF" opacity="0.4" />
                    <ellipse cx="140" cy="105" rx="8" ry="4" fill="#FFFFFF" opacity="0.4" />
                </svg>
                
                {{-- ANGKA ERROR DINAMIS --}}
                <span class="absolute inset-0 flex items-center justify-center text-[110px] md:text-[140px] font-black text-slate-900/5 leading-none select-none -z-10 -mt-8 tracking-tighter">
                    @yield('code')
                </span>
            </div>

            {{-- JUDUL ERROR DINAMIS --}}
            <h1 class="text-3xl md:text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed via-rOrange to-rYellow mb-3">
                @yield('title')
            </h1>

            {{-- PESAN ERROR DINAMIS --}}
            <p class="text-slate-500 text-sm md:text-base mb-10 leading-relaxed max-w-sm mx-auto">
                @yield('message')
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('welcome') }}" class="order-1 sm:order-2 bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-3.5 rounded-2xl md:rounded-full font-bold text-base glow-btn transition-all hover:scale-105 active:scale-95 text-center shadow-lg shadow-rRed/20 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Ke Beranda
                </a>
                <a href="javascript:history.back()" class="order-2 sm:order-1 bg-white text-slate-600 border border-slate-200 px-8 py-3.5 rounded-2xl md:rounded-full font-bold text-base hover:bg-slate-50 transition-all text-center flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>

            <div class="mt-12 pt-6 border-t border-slate-100">
                <p class="text-[10px] md:text-xs text-slate-400 uppercase tracking-[0.2em] mb-2 font-medium">Powered by</p>
                <a href="https://instagram.com/ruangrestu.undangan" target="_blank" rel="noopener noreferrer" class="inline-block group transition-transform hover:scale-105 active:scale-95">
                    <span class="text-xl md:text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed via-rOrange to-rYellow group-hover:from-rOrange group-hover:to-rRed transition-all duration-500">
                        RuangRestu
                    </span>
                </a>
            </div>
            
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bodyArea = document.getElementById('body-area');
            const svgContainer = document.getElementById('ghost-svg');
            const pupilLeft = document.getElementById('pupil-left');
            const pupilRight = document.getElementById('pupil-right');
            const maxMove = 5; 

            function moveEyes(clientX, clientY) {
                if (!svgContainer) return;
                const rect = svgContainer.getBoundingClientRect();
                const svgCenterX = rect.left + rect.width / 2;
                const svgCenterY = rect.top + rect.height / 2;
                const deltaX = clientX - svgCenterX;
                const deltaY = clientY - svgCenterY;
                const angle = Math.atan2(deltaY, deltaX);
                const distance = Math.min(maxMove, Math.sqrt(deltaX*deltaX + deltaY*deltaY) * 0.02);
                const moveX = Math.cos(angle) * distance;
                const moveY = Math.sin(angle) * distance;
                pupilLeft.style.transform = `translate(${moveX}px, ${moveY}px)`;
                pupilRight.style.transform = `translate(${moveX}px, ${moveY}px)`;
            }

            bodyArea.addEventListener('mousemove', (e) => { moveEyes(e.clientX, e.clientY); });
            bodyArea.addEventListener('touchmove', (e) => {
                if(e.touches.length > 0) { moveEyes(e.touches[0].clientX, e.touches[0].clientY); }
            }, { passive: true });
            
            bodyArea.addEventListener('touchend', () => {
                pupilLeft.style.transform = `translate(0px, 0px)`;
                pupilRight.style.transform = `translate(0px, 0px)`;
            });
        });
    </script>
</body>
</html>