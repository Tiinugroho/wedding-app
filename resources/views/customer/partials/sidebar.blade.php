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

        {{-- <a href="{{ route('customer.blast.index') }}"
            class="nav-item flex items-center gap-4 px-5 py-4 rounded-2xl transition group {{ request()->routeIs('customer.blast.*') ? 'active' : 'text-slate-400 font-semibold hover:bg-slate-100 hover:text-slate-600' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                </path>
            </svg>
            Blast WhatsApp
        </a> --}}

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

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-5 py-4 rounded-2xl font-bold text-slate-400 hover:text-rRed hover:bg-rRed/5 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
