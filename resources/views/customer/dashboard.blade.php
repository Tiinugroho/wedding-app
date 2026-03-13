@extends('customer.partials.app')
@section('title', 'Dashboard Klien')

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Beranda</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Halo {{ Auth::user()->name }}, selamat datang kembali!</p>
        </div>
        <div class="w-12 h-12 md:w-14 md:h-14 bg-white rounded-2xl flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 group cursor-pointer hover:border-rRed transition">
            <svg class="w-7 h-7 md:w-8 md:h-8 group-hover:text-rRed transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 md:gap-8 mb-12">
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Total Pengunjung</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalViews) }}</span>
                    <span class="text-slate-400 text-xs font-bold mb-1">Orang</span>
                </div>
            </div>
        </div>
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-50/50 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Konfirmasi RSVP</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalRsvp) }}</span>
                    <span class="text-slate-400 text-xs font-bold mb-1">Tamu</span>
                </div>
            </div>
        </div>
        <div class="dashboard-card bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rRed/5 rounded-bl-[5rem] -z-0"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Ucapan Doa</p>
                <div class="flex items-end gap-3">
                    <span class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ number_format($totalWishes) }}</span>
                    <span class="text-rRed text-xs font-bold mb-1">Pesan</span>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl md:text-2xl font-extrabold text-slate-800">Undangan Saya</h3>
            <a href="{{ route('customer.invitations.create') }}" class="group flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-rRed transition shadow-lg shadow-slate-200">
                <span>Buat Baru</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            
            @foreach($invitations as $invitation)
            <div class="bg-white p-6 rounded-[3rem] border border-slate-100 shadow-sm flex flex-col justify-between h-full min-h-[400px]">
                <div>
                    <div class="relative rounded-[2rem] overflow-hidden mb-6 aspect-video bg-slate-100">
                        <img src="{{ asset('storage/' . $invitation->template->thumbnail) }}" class="w-full h-full object-cover" alt="Thumbnail">
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur text-slate-800 text-xs font-bold rounded-full shadow-sm">
                                {{ $invitation->template->name }}
                            </span>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 mb-1">ruangrestu.com/{{ $invitation->slug }}</h4>
                    <p class="text-slate-400 text-sm mb-4 italic">Dibuat pada {{ $invitation->created_at->format('d M Y') }}</p>
                    
                    <div class="flex items-center gap-2 mb-6">
                        @if($invitation->status == 'active')
                            <span class="px-3 py-1 bg-green-100 text-green-600 text-[10px] font-bold uppercase rounded-lg">Aktif</span>
                        @else
                            <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[10px] font-bold uppercase rounded-lg">Draft / Pending</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('customer.invitations.edit', $invitation->id) }}" class="flex items-center justify-center py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                        Kelola Data
                    </a>
                    <a href="{{ url('/' . $invitation->slug) }}" target="_blank" class="flex items-center justify-center py-3 bg-rRed text-white rounded-2xl font-bold text-sm hover:bg-rRed/90 transition shadow-lg shadow-rRed/20">
                        Lihat Live
                    </a>
                </div>
            </div>
            @endforeach

            <a href="{{ route('customer.invitations.create') }}" class="group border-2 border-dashed border-slate-200 rounded-[3rem] flex flex-col items-center justify-center p-12 text-center hover:border-rRed hover:bg-rRed/5 transition-all h-full min-h-[400px]">
                <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-6 group-hover:bg-rRed group-hover:text-white transition-all">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h5 class="text-xl font-bold text-slate-800 mb-2">Buat Undangan Baru</h5>
                <p class="text-slate-400 text-sm">Pilih tema dan mulai buat undangan digital impian Anda.</p>
            </a>

        </div>
    </section>
@endsection