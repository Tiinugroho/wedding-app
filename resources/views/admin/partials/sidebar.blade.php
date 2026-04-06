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

    <div class="mb-10">
        <h1 class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-rRed to-rOrange">
            RuangRestu
        </h1>
        <p class="text-xs text-slate-400 mt-1 font-bold tracking-widest uppercase">Panel Kontrol</p>
    </div>

    <nav class="flex-1 space-y-2 overflow-y-auto pr-2 custom-scrollbar">
        <a href="{{ route('admin.dashboard') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                </path>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                </path>
            </svg>
            Kategori Tema
        </a>

        <a href="{{ route('admin.packages.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('admin.packages.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            Paket Harga
        </a>

        <a href="{{ route('admin.templates.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('admin.templates.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                </path>
            </svg>
            Template Undangan
        </a>

        <a href="{{ route('admin.musics.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('admin.musics.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
                </path>
            </svg>
            Musik Latar
        </a>

        <a href="{{ route('admin.profile.edit') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('admin.profile.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Profil Admin
        </a>
    </nav>

    <div class="mt-auto pt-6 border-t border-slate-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold text-slate-400 hover:text-white hover:bg-rRed transition group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
