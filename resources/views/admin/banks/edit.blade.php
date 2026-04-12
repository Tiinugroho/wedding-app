@extends('admin.partials.app')
@section('title', 'Edit Bank')

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.banks.index') }}" class="bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-500 hover:text-rOrange hover:border-rOrange/30 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Edit Bank</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Perbarui data bank atau e-wallet.</p>
            </div>
        </div>
    </header>

    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
        <form action="{{ route('admin.banks.update', $bank->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Bank / E-Wallet <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $bank->name) }}" required placeholder="Contoh: BCA, Mandiri, DANA"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange outline-none transition @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Logo Saat Ini</label>
                <div class="w-24 h-16 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-2 mb-3">
                    @if($bank->logo && str_starts_with($bank->logo, 'http'))
                        <img src="{{ $bank->logo }}" alt="{{ $bank->name }}" class="max-h-full max-w-full object-contain">
                    @elseif($bank->logo)
                        <img src="{{ asset('storage/' . $bank->logo) }}" alt="{{ $bank->name }}" class="max-h-full max-w-full object-contain">
                    @else
                        <span class="text-xs text-slate-400">Tidak ada</span>
                    @endif
                </div>

                <label class="block text-sm font-bold text-slate-700 mb-2">Ganti Logo <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="file" name="logo" accept="image/*"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange outline-none transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-rOrange/10 file:text-rOrange hover:file:bg-rOrange/20">
                <p class="text-xs text-slate-400 mt-2">Format: PNG, JPG, SVG. Maks 2MB. Kosongkan jika tidak ingin mengubah logo.</p>
                @error('logo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $bank->is_active) ? 'checked' : '' }}
                    class="w-5 h-5 rounded text-rOrange focus:ring-rOrange border-slate-300">
                <label for="is_active" class="text-sm font-bold text-slate-700 cursor-pointer">Status Aktif</label>
            </div>

            <div class="pt-6 flex gap-4">
                <button type="submit" class="bg-gradient-to-r from-rRed to-rOrange text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-rRed/20 hover:scale-105 transition-transform active:scale-95">
                    Update Data
                </button>
            </div>
        </form>
    </div>
@endsection