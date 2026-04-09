<nav class="lg:hidden fixed bottom-6 left-6 right-6 bg-white/90 backdrop-blur-xl border border-slate-100 rounded-[2.5rem] p-4 flex justify-between items-center z-50 shadow-2xl shadow-slate-900/10">
    
    <a href="{{ route('customer.dashboard') }}"
        class="w-14 h-14 flex items-center justify-center rounded-2xl transition-all {{ request()->routeIs('customer.dashboard') ? 'bg-gradient-to-r from-rRed to-rOrange text-white shadow-lg shadow-rRed/20' : 'text-slate-400 hover:text-rRed' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
            </path>
        </svg>
    </a>

    <a href="{{ route('customer.invitations.index') }}"
        class="w-14 h-14 flex items-center justify-center rounded-2xl transition-all {{ request()->routeIs('customer.invitations.*') ? 'bg-gradient-to-r from-rRed to-rOrange text-white shadow-lg shadow-rRed/20' : 'text-slate-400 hover:text-rRed' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
    </a>

    <a href="{{ route('customer.orders.index') }}"
        class="w-14 h-14 flex items-center justify-center rounded-2xl transition-all {{ request()->routeIs('customer.orders.*') ? 'bg-gradient-to-r from-rRed to-rOrange text-white shadow-lg shadow-rRed/20' : 'text-slate-400 hover:text-rRed' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
        </svg>
    </a>

    <a href="{{ route('customer.profile.edit') }}"
        class="w-14 h-14 flex items-center justify-center rounded-2xl transition-all {{ request()->routeIs('customer.profile.*') ? 'bg-gradient-to-r from-rRed to-rOrange text-white shadow-lg shadow-rRed/20' : 'text-slate-400 hover:text-rRed' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
    </a>
</nav>