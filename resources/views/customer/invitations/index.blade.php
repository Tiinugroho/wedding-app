@extends('customer.partials.app')
@section('title', 'Undangan Saya')

@section('content')
<header class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800">Undangan Saya</h2>
        <p class="text-slate-400 mt-1">Daftar semua undangan digital yang pernah Anda buat.</p>
    </div>
    <a href="{{ route('customer.invitations.create') }}" class="w-full md:w-auto px-8 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-rRed transition shadow-xl shadow-slate-200 flex items-center justify-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Buat Undangan Baru
    </a>
</header>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

    @forelse($invitations as $invitation)
    @php
    // Decode JSON konten untuk mengambil nama mempelai
    $content = json_decode($invitation->details->content ?? '{}', true);

    // Menentukan nama tampilan
    $brideName = $content['bride_nickname'] ?? 'Wanita';
    $groomName = $content['groom_nickname'] ?? 'Pria';
    $title = ($brideName != 'Wanita' || $groomName != 'Pria') ? "$groomName & $brideName" : "Menunggu Data Mempelai";
    @endphp

    <div class="invitation-card bg-white rounded-[3rem] overflow-hidden border border-slate-100 shadow-sm flex flex-col h-full {{ $invitation->status == 'draft' ? 'opacity-80 grayscale hover:grayscale-0 hover:opacity-100' : '' }}">

        <div class="h-56 bg-slate-200 relative overflow-hidden group rounded-t-2xl">
            <iframe src="{{ url('/' . $invitation->slug) }}?thumbnail=1"
                class="absolute top-0 left-0 w-[400%] h-[400%] origin-top-left scale-[0.25] border-0 pointer-events-none z-0"
                scrolling="no"
                tabindex="-1">
            </iframe>

            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10 transition-opacity duration-500 group-hover:opacity-90"></div>

            <div class="absolute bottom-5 left-5 right-5 flex justify-between items-end z-20">
                <div>
                    @if($invitation->status == 'active')
                    <span class="bg-whatsapp text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg shadow-whatsapp/30">Aktif</span>
                    @elseif($invitation->status == 'unpaid')
                    <span class="bg-rYellow text-slate-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg shadow-rYellow/30">Belum Bayar</span>
                    @else
                    <span class="bg-slate-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Draft</span>
                    @endif
                </div>

                @if($invitation->status == 'active')
                <div class="text-white text-right">
                    <p class="text-[10px] font-bold opacity-60 uppercase tracking-tighter">Live Link</p>
                    <p class="text-xs font-medium truncate w-32" title="ruangrestu.com/{{ $invitation->slug }}">/{{ $invitation->slug }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="p-8 flex flex-col flex-1">
            <div class="mb-6">
                <h4 class="text-2xl font-extrabold text-slate-800 mb-1">{{ $title }}</h4>
                <p class="text-slate-400 text-sm font-medium">Tema: <span class="text-rOrange">{{ $invitation->template->name ?? 'Belum Dipilih' }}</span></p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Dilihat</p>
                    <p class="text-lg font-bold text-slate-800">{{ number_format($invitation->visits_count) }}x</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">RSVP</p>
                    <p class="text-lg font-bold text-slate-800">{{ $invitation->rsvps()->count() }} Tamu</p>
                </div>
            </div>

            <div class="mt-auto space-y-3">
                @if($invitation->status == 'draft')
                <a href="{{ route('customer.invitations.edit', $invitation->id) }}" class="block text-center w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                    Lanjutkan Pembuatan
                </a>
                @else
                <a href="{{ route('customer.invitations.edit', $invitation->id) }}" class="block text-center w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                    Kelola Isi & Galeri
                </a>
                @endif

                <div class="flex gap-3">
                    @if($invitation->status == 'active')
                    <button onclick="navigator.clipboard.writeText('{{ url('/' . $invitation->slug) }}'); alert('Link berhasil disalin!')" class="flex-1 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Copy Link</button>
                    <a href="{{ url('/' . $invitation->slug) }}" target="_blank" class="flex-1 py-3 text-center bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Lihat Live</a>
                    @elseif($invitation->status == 'unpaid')
                    <a href="#" class="flex-1 py-3 text-center bg-rYellow/10 border border-rYellow/30 text-rOrange rounded-xl text-xs font-bold hover:bg-rYellow/20 transition">Lanjutkan Pembayaran</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @empty
    <a href="{{ route('customer.invitations.create') }}" class="group border-2 border-dashed border-slate-200 rounded-[3rem] flex flex-col items-center justify-center p-12 text-center hover:border-rRed hover:bg-rRed/5 transition-all h-full min-h-[400px]">
        <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-6 group-hover:bg-rRed group-hover:text-white transition-all">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </div>
        <h5 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Undangan</h5>
        <p class="text-slate-400 text-sm">Pilih tema dan mulai buat undangan digital pertama Anda.</p>
    </a>
    @endforelse

</div>
@endsection