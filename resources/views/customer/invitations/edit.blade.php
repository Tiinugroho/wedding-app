@extends('customer.partials.app')
@section('title', 'Kelola Isi Undangan')

@section('content')
    <header class="mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('customer.invitations.index') }}"
                class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800">Kelola Isi Undangan</h2>
                <p class="text-slate-400 mt-1">Lengkapi data mempelai dan acara untuk <span
                        class="text-rOrange font-semibold">ruangrestu.com/{{ $invitation->slug }}</span></p>
            </div>
        </div>

        <a href="{{ url('/' . $invitation->slug) }}" target="_blank"
            class="hidden md:flex items-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                </path>
            </svg>
            Lihat Live Preview
        </a>
    </header>

    @if (session('success'))
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('customer.invitations.update', $invitation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
            <div class="xl:col-span-8 space-y-8">

                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i class="feather icon-user"></i>
                        </div>
                        Data Mempelai Pria
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="groom_name"
                                value="{{ old('groom_name', $content['groom_name'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Lengkap Pria">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Panggilan</label>
                            <input type="text" name="groom_nickname"
                                value="{{ old('groom_nickname', $content['groom_nickname'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Panggilan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Orang Tua</label>
                            <input type="text" name="groom_parents"
                                value="{{ old('groom_parents', $content['groom_parents'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Putra dari...">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center">
                            <i class="feather icon-user"></i>
                        </div>
                        Data Mempelai Wanita
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="bride_name"
                                value="{{ old('bride_name', $content['bride_name'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Lengkap Wanita">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Panggilan</label>
                            <input type="text" name="bride_nickname"
                                value="{{ old('bride_nickname', $content['bride_nickname'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Nama Panggilan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Orang Tua</label>
                            <input type="text" name="bride_parents"
                                value="{{ old('bride_parents', $content['bride_parents'] ?? '') }}"
                                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                                placeholder="Putri dari...">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                            <i class="feather icon-calendar"></i>
                        </div>
                        Waktu & Lokasi Acara
                    </h4>
                    <div class="space-y-6">
                        <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100">
                            <h5 class="font-bold text-orange-600 mb-4">Akad / Pemberkatan</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="date" name="akad_date"
                                    value="{{ old('akad_date', $content['akad_date'] ?? '') }}"
                                    class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl">
                                <input type="text" name="akad_time"
                                    value="{{ old('akad_time', $content['akad_time'] ?? '') }}"
                                    class="w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                    placeholder="Jam (Contoh: 08:00 WIB)">
                                <input type="text" name="akad_location"
                                    value="{{ old('akad_location', $content['akad_location'] ?? '') }}"
                                    class="md:col-span-2 w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                    placeholder="Nama Gedung/Lokasi">
                                <input type="url" name="akad_map"
                                    value="{{ old('akad_map', $content['akad_map'] ?? '') }}"
                                    class="md:col-span-2 w-full py-2.5 px-4 bg-white border border-slate-200 rounded-xl"
                                    placeholder="Link Google Maps">
                            </div>
                        </div>
                    </div>
                </div>

                @include('customer.invitations.partials.music_selector')

                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center"><i
                                class="feather icon-heart"></i></div>
                        Cerita Cinta (Love Story)
                    </h4>

                    @if (empty($packageLogic['has_love_story']) || $packageLogic['has_love_story'] == false)
                        <div
                            class="absolute inset-0 z-10 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 mt-16">
                            <h5 class="font-bold text-slate-800 mb-1">Fitur Terkunci di Paket {{ $currentPackageName }}
                            </h5>
                            <button type="button"
                                class="btn-open-upgrade bg-rOrange text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg">Upgrade
                                Paket</button>
                        </div>
                    @endif

                    <div
                        class="space-y-4 {{ empty($packageLogic['has_love_story']) || !$packageLogic['has_love_story'] ? 'opacity-30 pointer-events-none' : '' }}">
                        <textarea name="love_story_1" rows="3" class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl"
                            placeholder="Ceritakan pertemuan pertama...">{{ old('love_story_1', $content['love_story_1'] ?? '') }}</textarea>
                        <textarea name="love_story_2" rows="3" class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl"
                            placeholder="Ceritakan momen lamaran...">{{ old('love_story_2', $content['love_story_2'] ?? '') }}</textarea>
                    </div>
                </div>

                <div
                    class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                            <i class="feather icon-gift"></i></div>
                        Kado Digital / Amplop
                    </h4>

                    @if (empty($packageLogic['has_digital_gift']) || $packageLogic['has_digital_gift'] == false)
                        <div
                            class="absolute inset-0 z-10 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 mt-16">
                            <h5 class="font-bold text-slate-800 mb-1">Fitur Eksklusif</h5>
                            <button type="button"
                                class="btn-open-upgrade bg-rOrange text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg">Upgrade
                                Paket</button>
                        </div>
                    @endif

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ empty($packageLogic['has_digital_gift']) || !$packageLogic['has_digital_gift'] ? 'opacity-30 pointer-events-none' : '' }}">
                        <select name="bank_name" class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <option value="BCA"
                                {{ old('bank_name', $content['bank_name'] ?? '') == 'BCA' ? 'selected' : '' }}>BCA
                            </option>
                            <option value="Mandiri"
                                {{ old('bank_name', $content['bank_name'] ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                Mandiri</option>
                            <option value="BRI"
                                {{ old('bank_name', $content['bank_name'] ?? '') == 'BRI' ? 'selected' : '' }}>BRI
                            </option>
                        </select>
                        <input type="text" name="bank_account_name"
                            value="{{ old('bank_account_name', $content['bank_account_name'] ?? '') }}"
                            class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl"
                            placeholder="Atas Nama">
                        <input type="number" name="bank_account_number"
                            value="{{ old('bank_account_number', $content['bank_account_number'] ?? '') }}"
                            class="md:col-span-2 w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl"
                            placeholder="Nomor Rekening">
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4
                        class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center"><i
                                class="feather icon-image"></i></div>
                        Galeri Foto ({{ $invitation->galleries->where('type', 'photo')->count() }} /
                        {{ $packageLogic['gallery_limit'] ?? 5 }})
                    </h4>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach ($invitation->galleries->where('type', 'photo') as $img)
                            <div class="relative group aspect-square rounded-2xl overflow-hidden border border-slate-100">
                                <img src="{{ asset('storage/' . $img->file_path) }}" class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button type="button" onclick="handleDeletePhoto('{{ $img->id }}')"
                                        class="bg-white text-red-500 p-2 rounded-full hover:bg-red-500 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        @if ($invitation->galleries->where('type', 'photo')->count() < ($packageLogic['gallery_limit'] ?? 5))
                            <div class="relative aspect-square">
                                <input type="file" name="gallery_files[]" id="gallery-input" multiple class="hidden"
                                    onchange="previewImages(this)">
                                <button type="button" onclick="document.getElementById('gallery-input').click()"
                                    class="w-full h-full border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center hover:border-rOrange hover:bg-orange-50 transition">
                                    <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-slate-500">Pilih Foto</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                </div>
            </div>

            <div class="xl:col-span-4">
                <div class="sticky top-10 bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-xl text-center">
                    <h4 class="font-extrabold text-2xl mb-2">Simpan Perubahan</h4>
                    <p class="text-sm text-slate-400 mb-8 leading-relaxed">Pastikan semua data sudah benar sebelum
                        disimpan.</p>
                    <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-rRed to-rOrange rounded-2xl font-bold text-white hover:scale-105 transition shadow-lg flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                        <span>Simpan Data Undangan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <form id="global-delete-photo-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <div id="upgradeModal"
        class="fixed inset-0 z-[60] hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300"
            id="upgradeModalContent">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-800">Upgrade Paket</h3>
                    <p class="text-sm text-slate-500">Paket saat ini: <strong
                            class="text-slate-700">{{ $currentPackageName }}</strong></p>
                </div>
                <button type="button" id="closeUpgradeBtn"
                    class="w-10 h-10 rounded-full bg-white border text-slate-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-8">
                @if ($upgradePackages->isEmpty())
                    <p class="text-center text-slate-500">Anda sudah menggunakan paket tertinggi.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($upgradePackages as $pkg)
                            @php $selisihHarga = $pkg->price - $currentPackagePrice; @endphp
                            <div class="border-2 border-slate-100 rounded-3xl p-5 flex items-center justify-between">
                                <div>
                                    <h5 class="font-extrabold text-lg">{{ $pkg->name }}</h5>
                                    <p class="text-2xl font-extrabold text-rOrange">Rp
                                        {{ number_format($selisihHarga, 0, ',', '.') }}</p>
                                </div>
                                <button type="button" class="px-6 py-2 bg-slate-900 text-white rounded-xl">Pilih</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImages(input) {
            const container = document.getElementById('preview-container');
            container.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className =
                            'relative aspect-square rounded-2xl overflow-hidden border-2 border-rOrange';
                        div.innerHTML =
                            `<img src="${e.target.result}" class="w-full h-full object-cover opacity-60">
                                         <div class="absolute inset-0 flex items-center justify-center"><span class="bg-rOrange text-white text-[10px] px-2 py-1 rounded-lg font-bold uppercase">Ready</span></div>`;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function handleDeletePhoto(photoId) {
            if (confirm('Hapus foto ini secara permanen?')) {
                const deleteForm = document.getElementById('global-delete-photo-form');
                deleteForm.action = `/customer/gallery/${photoId}`;
                deleteForm.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const upgradeModal = document.getElementById('upgradeModal');
            const openUpgradeBtns = document.querySelectorAll('.btn-open-upgrade');
            const closeUpgradeBtn = document.getElementById('closeUpgradeBtn');

            openUpgradeBtns.forEach(btn => btn.addEventListener('click', () => {
                upgradeModal.classList.remove('hidden');
                setTimeout(() => upgradeModal.classList.remove('opacity-0'), 20);
            }));

            closeUpgradeBtn.addEventListener('click', () => {
                upgradeModal.classList.add('opacity-0');
                setTimeout(() => upgradeModal.classList.add('hidden'), 300);
            });
        });
    </script>
@endpush
