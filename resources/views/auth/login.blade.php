<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangRestu | Masuk & Daftar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('cst/css/auth.css') }}">
</head>

<body
    class="min-h-screen flex flex-col justify-between bg-slate-50 text-slate-800 font-sans overflow-y-auto selection:bg-rOrange selection:text-white">

    <a href="{{ url('/') }}"
        class="fixed top-6 left-6 z-50 flex items-center gap-2 bg-white/80 backdrop-blur-md px-4 py-2 rounded-full border border-slate-200 text-slate-600 text-sm font-semibold hover:text-rRed transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Kembali
    </a>

    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 w-full mt-12 md:mt-0">

        <div class="w-full max-w-lg">
            <div
                class="glass-auth rounded-[2rem] p-6 sm:p-10 relative overflow-hidden bg-white shadow-xl border border-slate-100">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-rYellow/20 rounded-full blur-3xl"></div>

                <div id="login-sec" class="active-auth relative z-10">
                    <div class="text-center mb-6">
                        <h1
                            class="text-1xl md:text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
                            RuangRestu
                        </h1>
                        <p class="text-slate-500 mt-1 text-sm font-medium">Langkah pertama menuju hari bahagia.</p>
                    </div>
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-slate-800">Masuk</h2>
                        <p class="text-slate-500 text-sm mt-1">Gunakan Akun Google atau data manual Anda.</p>
                    </div>

                    <div class="mb-6">
                        <a href="{{ route('google.login') }}"
                            class="w-full py-3 px-6 bg-white border border-slate-200 rounded-xl flex items-center justify-center gap-3 font-bold text-sm text-slate-700 hover:bg-slate-50 hover:border-rOrange transition-all shadow-sm active:scale-95">
                            <svg class="w-5 h-5" viewBox="0 0 48 48">
                                <path fill="#EA4335"
                                    d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                                </path>
                                <path fill="#4285F4"
                                    d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                                </path>
                                <path fill="#FBBC05"
                                    d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z">
                                </path>
                                <path fill="#34A853"
                                    d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                                </path>
                            </svg>
                            Masuk dengan Google
                        </a>

                        <div class="relative mt-6 mb-4">
                            <div class="absolute inset-0 flex items-center"><span
                                    class="w-full border-t border-slate-100"></span></div>
                            <div class="relative flex justify-center text-[10px] uppercase"><span
                                    class="bg-white px-4 text-slate-400 font-bold tracking-widest">Atau</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-500 ml-1">Email / WhatsApp</label>
                            <input type="text" name="login_id" value="{{ old('login_id') }}"
                                placeholder="Masukkan detail akun" required
                                class="input-style w-full px-5 py-3 rounded-xl border @error('login_id') border-rRed @else border-slate-200 @enderror bg-slate-50/50 outline-none focus:border-rOrange text-sm transition-colors">
                            @error('login_id')
                                <p class="text-rRed text-xs font-semibold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <label class="text-xs font-bold text-slate-500 ml-1">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-xs font-bold text-rOrange hover:text-rRed transition">Lupa?</a>
                                @endif
                            </div>
                            <input type="password" name="password" placeholder="••••••••" required
                                class="input-style w-full px-5 py-3 rounded-xl border @error('password') border-rRed @else border-slate-200 @enderror bg-slate-50/50 outline-none focus:border-rOrange text-sm transition-colors">
                            @error('password')
                                <p class="text-rRed text-xs font-semibold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center px-1 pt-1">
                            <input type="checkbox" id="remember" name="remember"
                                class="w-4 h-4 rounded accent-rRed border-slate-300">
                            <label for="remember" class="ml-2 text-xs font-medium text-slate-600 cursor-pointer">Tetap
                                masuk</label>
                        </div>

                        <button type="submit"
                            class="w-full py-3.5 bg-gradient-to-r from-rRed to-rOrange text-white rounded-xl font-bold text-sm shadow-lg shadow-rRed/20 hover:scale-[1.02] transition-all active:scale-95 mt-4">
                            Masuk ke Dashboard
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-slate-500 text-sm font-medium">Belum punya akun?
                            <button type="button" onclick="switchAuth('register')"
                                class="text-rRed font-bold hover:underline">Daftar di sini</button>
                        </p>
                    </div>
                </div>

                <div id="register-sec" class="hidden-auth relative z-10">
                    <div class="text-center mb-6">
                        <h1
                            class="text-1xl md:text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
                            RuangRestu
                        </h1>
                        <p class="text-slate-500 mt-1 text-sm font-medium">Langkah pertama menuju hari bahagia.</p>
                    </div>
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-slate-800">Buat Akun</h2>
                        <p class="text-slate-500 text-sm mt-1">Daftar instan dengan Google atau isi manual.</p>
                    </div>

                    <a href="{{ route('google.login') }}"
                        class="w-full py-3 px-6 bg-white border border-slate-200 rounded-xl flex items-center justify-center gap-3 font-bold text-sm text-slate-700 hover:bg-slate-50 hover:border-rOrange transition-all shadow-sm mb-6 active:scale-95">
                        <svg class="w-5 h-5" viewBox="0 0 48 48">
                            <path fill="#EA4335"
                                d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z">
                            </path>
                            <path fill="#4285F4"
                                d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z">
                            </path>
                            <path fill="#FBBC05"
                                d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24s.92 7.54 2.56 10.78l7.97-6.19z">
                            </path>
                            <path fill="#34A853"
                                d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z">
                            </path>
                        </svg>
                        Daftar dengan Google
                    </a>

                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center"><span
                                class="w-full border-t border-slate-100"></span></div>
                        <div class="relative flex justify-center text-[10px] uppercase"><span
                                class="bg-white px-4 text-slate-400 font-bold tracking-widest">Atau Manual</span></div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Nama Anda" required autofocus
                                class="input-style w-full px-5 py-3 rounded-xl border @error('name') border-rRed @else border-slate-200 @enderror bg-slate-50/50 outline-none focus:border-rOrange text-sm transition-colors">
                            @error('name')
                                <p class="text-rRed text-xs font-semibold ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">WhatsApp</label>
                                <input type="tel" name="phone_number" value="{{ old('phone_number') }}"
                                    placeholder="08..." required
                                    class="input-style w-full px-5 py-3 rounded-xl border @error('phone_number') border-rRed @else border-slate-200 @enderror bg-slate-50/50 outline-none focus:border-rOrange text-sm transition-colors">
                                @error('phone_number')
                                    <p class="text-rRed text-xs font-semibold ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="mail@example.com" required
                                    class="input-style w-full px-5 py-3 rounded-xl border @error('email') border-rRed @else border-slate-200 @enderror bg-slate-50/50 outline-none focus:border-rOrange text-sm transition-colors">
                                @error('email')
                                    <p class="text-rRed text-xs font-semibold ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Password</label>
                                <input type="password" name="password" placeholder="••••••••" required
                                    class="input-style w-full px-5 py-3 rounded-xl border @error('password') border-rRed @else border-slate-200 @enderror bg-slate-50/50 outline-none focus:border-rOrange text-sm transition-colors">
                                @error('password')
                                    <p class="text-rRed text-xs font-semibold ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1">Konfirmasi</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi" required
                                    class="input-style w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50/50 outline-none focus:border-rOrange text-sm transition-colors">
                            </div>
                        </div>

                        <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-100 mt-2">
                            <div class="flex items-start">
                                <input type="checkbox" id="agree" required class="mt-0.5 w-4 h-4 accent-rOrange">
                                <label for="agree"
                                    class="ml-2.5 text-[11px] text-slate-500 leading-relaxed font-medium cursor-pointer">
                                    Setuju dengan <a href="javascript:void(0)" onclick="openModal('syarat')"><span
                                            class="text-rOrange font-bold">Syarat</span></a>
                                    serta <a href="javascript:void(0)" onclick="openModal('privasi')"><span
                                            class="text-rOrange font-bold">Privasi</span></a> kami.
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3.5 bg-gradient-to-r from-rRed to-rOrange text-white rounded-xl font-bold text-sm shadow-lg shadow-rRed/20 hover:scale-[1.02] transition-all mt-4">
                            Daftar Akun
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-slate-500 text-sm font-medium">Sudah punya akun?
                            <button type="button" onclick="switchAuth('login')"
                                class="text-rRed font-bold hover:underline">Masuk sekarang</button>
                        </p>
                    </div>
                </div>
                <footer class="w-full text-center py-2 text-slate-400 text-xs font-medium">
                    &copy; {{ date('Y') }} RuangRestu.com
                </footer>
            </div>
        </div>

    </main>


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
    <script>
        function switchAuth(type) {
            const loginSec = document.getElementById('login-sec');
            const registerSec = document.getElementById('register-sec');

            if (type === 'register') {
                loginSec.classList.replace('active-auth', 'hidden-auth');
                registerSec.classList.replace('hidden-auth', 'active-auth');
                loginSec.style.display = 'none';
                registerSec.style.display = 'block';
            } else {
                registerSec.classList.replace('active-auth', 'hidden-auth');
                loginSec.classList.replace('hidden-auth', 'active-auth');
                registerSec.style.display = 'none';
                loginSec.style.display = 'block';
            }
        }

        @if ($errors->has('name') || $errors->has('phone_number') || ($errors->has('email') && old('name') !== null))
            document.addEventListener("DOMContentLoaded", function() {
                switchAuth('register');
            });
        @else
            document.addEventListener("DOMContentLoaded", function() {
                switchAuth('login');
            });
        @endif
    </script>
    <script src="{{ asset('cst/js/landing.js') }}"></script>
</body>

</html>
