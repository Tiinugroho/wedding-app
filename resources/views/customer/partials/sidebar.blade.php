<aside class="hidden lg:flex flex-col w-72 glass-sidebar sticky top-0 h-screen p-8 z-40">
    <div class="mb-12">
        <h1 class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
            RuangRestu
        </h1>
    </div>

    <nav class="flex-1 space-y-3">
        <a href="{{ route('customer.dashboard') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('customer.dashboard') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                </path>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('customer.invitations.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('customer.invitations.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Undangan Saya
        </a>

        <a href="{{ route('customer.orders.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('customer.orders.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Riwayat Pembayaran
        </a>

        <a href="{{ route('customer.profile.edit') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('customer.profile.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil Saya
        </a>
    </nav>

    <div class="mt-auto pt-10 space-y-4">
        <a href="https://wa.me/6289515310917" target="_blank"
            class="block p-5 bg-gradient-to-br from-slate-50 to-slate-100 rounded-[2rem] border border-slate-200 group hover:border-rOrange transition">
            <p class="text-[10px] font-extrabold text-rOrange uppercase tracking-widest mb-2">Customer Service</p>
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-slate-700">Tanya Admin</span>
                <svg class="w-4 h-4 text-rOrange group-hover:translate-x-1 transition" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4 4H3">
                    </path>
                </svg>
            </div>
        </a>

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

        <button type="button" onclick="openLogoutModal()"
            class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold text-slate-400 hover:text-rRed hover:bg-rRed/5 transition group">
            <svg class="w-6 h-6 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            Logout
        </button>
    </div>
</aside>

{{-- MODAL KONFIRMASI LOGOUT --}}
<div id="logout-modal" class="fixed inset-0 z-[9999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-all" onclick="closeLogoutModal()"></div>
    <div class="relative bg-white/95 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] shadow-2xl border-2 border-white text-center max-w-sm w-full mx-4 transform scale-95 transition-all duration-300" id="logout-modal-box">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Akhiri Sesi?</h3>
        <p class="text-slate-500 text-sm mb-8 leading-relaxed">Apakah Anda yakin ingin keluar dari RuangRestu saat ini?</p>
        <div class="flex flex-col gap-3">
            <button type="button" onclick="document.getElementById('logout-form').submit()" class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-2xl font-bold shadow-lg shadow-red-500/30 hover:-translate-y-0.5 transition-all active:scale-95">Ya, Keluar</button>
            <button type="button" onclick="closeLogoutModal()" class="w-full bg-slate-100 text-slate-600 px-6 py-4 rounded-2xl font-bold hover:bg-slate-200 transition-all">Batal</button>
        </div>
    </div>
</div>

{{-- SCRIPT PENGENDALI MODAL LOGOUT --}}
<script>
    function openLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const box = document.getElementById('logout-modal-box');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        setTimeout(() => {
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logout-modal');
        const box = document.getElementById('logout-modal-box');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        modal.classList.add('opacity-0', 'pointer-events-none');
    }
</script>