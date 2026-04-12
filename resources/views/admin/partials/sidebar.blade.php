<div id="sidebar-backdrop"
    class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden transition-opacity duration-300"
    onclick="toggleSidebar()"></div>

<aside id="admin-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 glass-sidebar h-screen p-8 transform -translate-x-full lg:translate-x-0 lg:static lg:sticky lg:top-0 transition-transform duration-300 ease-in-out">

    <button type="button" onclick="toggleSidebar()"
        class="absolute top-6 right-6 lg:hidden bg-white p-2 rounded-xl border border-slate-100 shadow-sm text-slate-400 hover:text-rRed hover:border-rRed focus:outline-none focus:ring-2 focus:ring-rOrange transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <div class="mb-8">
        <h1 class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
            RuangRestu
        </h1>
        <p class="text-xs text-slate-400 mt-1 font-bold tracking-widest uppercase">Panel Kontrol</p>
    </div>

    <nav class="flex-1 space-y-1.5 overflow-y-auto pr-2 custom-scrollbar pb-10">
        <div class="pt-4 pb-1">
            <p class="px-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Main Menu</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                </path>
            </svg>
            Dashboard
        </a>

        <div class="pt-4 pb-1">
            <p class="px-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Transaksi</p>
        </div>
        <a href="{{ route('admin.orders.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.orders.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            Riwayat Pembayaran
        </a>

        <div class="pt-4 pb-1">
            <p class="px-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Manajemen Users</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.users.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
            </svg>
            Data Pengguna
        </a>

        <div class="pt-4 pb-1">
            <p class="px-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Master Data</p>
        </div>
        <a href="{{ route('admin.categories.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                </path>
            </svg>
            Kategori Tema
        </a>

        <a href="{{ route('admin.packages.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.packages.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            Paket Harga
        </a>

        <a href="{{ route('admin.templates.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.templates.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                </path>
            </svg>
            Template Undangan
        </a>

        <a href="{{ route('admin.musics.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.musics.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
                </path>
            </svg>
            Musik Latar
        </a>

        <a href="{{ route('admin.banks.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.banks.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                </path>
            </svg>
            Bank & E-Wallet
        </a>

        <div class="pt-4 pb-1">
            <p class="px-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Pengaturan</p>
        </div>
        <a href="{{ route('admin.profile.edit') }}"
            class="nav-item flex items-center gap-4 px-5 py-3.5 rounded-2xl transition group {{ request()->routeIs('admin.profile.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil Admin
        </a>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-100 shrink-0">
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

        <button type="button" onclick="openLogoutModal()"
            class="w-full flex items-center gap-4 px-5 py-3.5 rounded-2xl font-bold text-slate-400 hover:text-white hover:bg-rRed transition group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            Keluar
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
        <p class="text-slate-500 text-sm mb-8 leading-relaxed">Apakah Anda yakin ingin keluar dari panel kontrol saat ini?</p>
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