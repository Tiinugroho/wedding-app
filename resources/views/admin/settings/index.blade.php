@extends('admin.partials.app')
@section('title', 'Pengaturan Website')

@section('content')
    <header class="flex flex-row justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <button type="button" onclick="toggleSidebar()" class="lg:hidden bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-rOrange transition group">
                <svg class="w-6 h-6 group-hover:text-rOrange transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Pengaturan Website</h2>
                <p class="text-slate-400 text-sm md:text-base mt-1">Kelola konfigurasi situs, info kontak, dan payment gateway.</p>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-[2rem] mb-8 font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-[2rem] mb-8 font-bold flex flex-col gap-2 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Mohon perbaiki kesalahan berikut:</span>
            </div>
            <ul class="list-disc list-inside text-sm font-medium ml-9">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            {{-- KOLOM KIRI: PENGATURAN UMUM --}}
            <div class="xl:col-span-6 space-y-8">
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6 text-rOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        Umum & Kontak
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">Nama Website</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'RuangRestu' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition">
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">Email Dukungan</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@ruangrestu.com' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition">
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">WhatsApp Kontak (Format Kode Negara: 628xxx)</label>
                            <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] ?? '6281234567890' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition">
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">Logo Website</label>
                            @if (!empty($settings['site_logo']))
                                <div class="mb-3 flex items-center gap-4 p-4 border border-slate-100 rounded-2xl bg-slate-50/50">
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-10 object-contain rounded">
                                    <span class="text-xs text-slate-400">Logo saat ini</span>
                                </div>
                            @endif
                            <input type="file" name="site_logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PAYMENT GATEWAY --}}
            <div class="xl:col-span-6 space-y-8">
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6 text-rOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Konfigurasi Payment Gateway
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">Payment Gateway Aktif</label>
                            <select id="active_gateway" name="active_payment_gateway" onchange="switchGateway()" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 transition font-medium cursor-pointer text-sm">
                                <option value="midtrans" {{ ($settings['active_payment_gateway'] ?? 'midtrans') === 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                                <option value="duitku" {{ ($settings['active_payment_gateway'] ?? '') === 'duitku' ? 'selected' : '' }}>Duitku (Redirection)</option>
                            </select>
                        </div>

                        {{-- SEKSI MIDTRANS --}}
                        <div id="section_midtrans" class="space-y-6 border-t border-slate-100 pt-6">
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider text-xs text-slate-400">Pengaturan Midtrans</h4>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Midtrans Merchant ID</label>
                                <input type="text" name="midtrans_merchant_id" value="{{ $settings['midtrans_merchant_id'] ?? '' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition" placeholder="Gxxxxxxxxx">
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Midtrans Client Key</label>
                                <input type="text" name="midtrans_client_key" value="{{ $settings['midtrans_client_key'] ?? '' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition" placeholder="SB-Mid-client-...">
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Midtrans Server Key</label>
                                <input type="password" name="midtrans_server_key" value="{{ $settings['midtrans_server_key'] ?? '' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition" placeholder="SB-Mid-server-...">
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Mode Environment</label>
                                <div class="flex items-center gap-6 mt-3">
                                    <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-slate-600">
                                        <input type="radio" name="midtrans_is_production" value="0" {{ ($settings['midtrans_is_production'] ?? '0') === '0' ? 'checked' : '' }} class="w-5 h-5 text-rOrange focus:ring-rOrange">
                                        Sandbox (Testing)
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-slate-600">
                                        <input type="radio" name="midtrans_is_production" value="1" {{ ($settings['midtrans_is_production'] ?? '') === '1' ? 'checked' : '' }} class="w-5 h-5 text-rOrange focus:ring-rOrange">
                                        Production (Live)
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- SEKSI DUITKU --}}
                        <div id="section_duitku" class="space-y-6 border-t border-slate-100 pt-6 hidden">
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider text-xs text-slate-400">Pengaturan Duitku</h4>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Duitku Merchant Code</label>
                                <input type="text" name="duitku_merchant_code" value="{{ $settings['duitku_merchant_code'] ?? '' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition" placeholder="DSxxxxx">
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Duitku Merchant Key (API Key)</label>
                                <input type="password" name="duitku_merchant_key" value="{{ $settings['duitku_merchant_key'] ?? '' }}" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange focus:bg-white outline-none text-slate-600 text-sm transition" placeholder="Kunci Merchant Duitku">
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Mode Environment</label>
                                <div class="flex items-center gap-6 mt-3">
                                    <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-slate-600">
                                        <input type="radio" name="duitku_is_production" value="0" {{ ($settings['duitku_is_production'] ?? '0') === '0' ? 'checked' : '' }} class="w-5 h-5 text-rOrange focus:ring-rOrange">
                                        Sandbox (Testing)
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-slate-600">
                                        <input type="radio" name="duitku_is_production" value="1" {{ ($settings['duitku_is_production'] ?? '') === '1' ? 'checked' : '' }} class="w-5 h-5 text-rOrange focus:ring-rOrange">
                                        Production (Live)
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-10 py-5 bg-gradient-to-r from-rRed to-rOrange text-white rounded-[2rem] font-extrabold text-lg shadow-xl shadow-rOrange/20 hover:scale-[1.02] transition flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>

    <script>
        function switchGateway() {
            const gateway = document.getElementById('active_gateway').value;
            const midtransSection = document.getElementById('section_midtrans');
            const duitkuSection = document.getElementById('section_duitku');

            if (gateway === 'midtrans') {
                midtransSection.classList.remove('hidden');
                duitkuSection.classList.add('hidden');
            } else if (gateway === 'duitku') {
                duitkuSection.classList.remove('hidden');
                midtransSection.classList.add('hidden');
            }
        }

        // Run once on load to show correct section
        document.addEventListener('DOMContentLoaded', function() {
            switchGateway();
        });
    </script>
@endsection
