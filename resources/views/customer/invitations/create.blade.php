@extends('customer.partials.app')
@section('title', 'Buat Undangan Baru')

@section('content')
    <header class="mb-10 flex items-center gap-4">
        <a href="{{ route('customer.invitations.index') }}"
            class="w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm text-slate-400 hover:text-slate-800 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800">Buat Undangan Baru</h2>
            <p class="text-slate-400 mt-1">Langkah 1: Tentukan link, paket, dan desain tema.</p>
        </div>
    </header>

    <form action="{{ route('customer.invitations.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-8 space-y-8">

                {{-- STEP 1: LINK UNDANGAN --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative">
                    <h4 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-sm">1</span>
                        Tentukan Link Undangan
                    </h4>
                    <p class="text-slate-500 text-sm mb-4">Pilih nama unik untuk link undangan Anda. Klik "Cek Link" untuk memastikan ketersediaannya.</p>

                    <div class="flex flex-col md:flex-row items-stretch relative gap-3 md:gap-0">
                        <div class="flex flex-1 min-w-0">
                            <span class="bg-slate-100 border border-slate-200 border-r-0 text-slate-500 px-3 sm:px-4 py-3 md:py-4 flex items-center rounded-l-2xl font-medium text-xs sm:text-sm md:text-base whitespace-nowrap">
                                ruangrestu.com/
                            </span>
                            <input type="text" name="slug" id="slugInput"
                                class="form-control flex-1 py-3 md:py-4 border-slate-200 rounded-r-2xl md:rounded-none md:border-r-0 focus:ring-rOrange focus:border-rOrange text-sm md:text-base min-w-0"
                                placeholder="nama-pasangan" value="{{ old('slug') }}" required autocomplete="off">
                        </div>

                        <button type="button" id="btnCheckSlug"
                            class="bg-slate-900 text-white px-6 py-3 md:py-4 rounded-2xl md:rounded-l-none md:rounded-r-2xl font-bold text-sm hover:bg-slate-800 transition flex items-center justify-center gap-2 shrink-0 whitespace-nowrap">
                            <span>Cek Link</span>
                            <svg id="slugSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>

                    <p id="slugMessage" class="text-sm mt-3 hidden font-medium"></p>

                    @error('slug')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- STEP 2: PILIH PAKET --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <h4 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-sm">2</span>
                        Pilih Paket
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ($packages as $package)
                            <label class="cursor-pointer relative">
                                <input type="radio" name="package_id" value="{{ $package->id }}" class="peer sr-only" required {{ old('package_id') == $package->id ? 'checked' : '' }}>
                                <div class="h-full border-2 border-slate-100 rounded-3xl p-5 hover:border-rOrange/50 transition peer-checked:border-rOrange peer-checked:bg-rOrange/5 peer-checked:shadow-lg peer-checked:shadow-rOrange/10">
                                    <h5 class="font-bold text-slate-800 mb-1">{{ $package->name }}</h5>
                                    <p class="text-rOrange font-extrabold text-xl mb-3">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500 line-clamp-3">{{ $package->description }}</p>

                                    <div class="absolute top-4 right-4 w-6 h-6 rounded-full bg-rOrange text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('package_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- STEP 3: PILIH DESAIN TEMA DENGAN FILTER & PAGINATION --}}
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <h4 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-sm shrink-0">3</span>
                            Pilih Desain Tema
                        </h4>

                        {{-- FILTER KATEGORI --}}
                        @php
                            $categories = $templates->pluck('category.name')->filter()->unique();
                        @endphp
                        <div class="flex overflow-x-auto pb-2 md:pb-0 gap-2 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                            <button type="button" class="filter-btn active px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-slate-900 text-white transition" data-filter="all">Semua</button>
                            @foreach($categories as $cat)
                                <button type="button" class="filter-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap bg-slate-100 text-slate-600 hover:bg-slate-200 transition" data-filter="{{ $cat }}">{{ $cat }}</button>
                            @endforeach
                        </div>
                    </div>

                    {{-- GRID TEMA --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="theme-grid">
                        @foreach ($templates as $template)
                            <label class="cursor-pointer relative group theme-card hidden" data-category="{{ $template->category->name ?? 'Umum' }}">
                                <input type="radio" name="template_id" value="{{ $template->id }}" class="peer sr-only" required {{ old('template_id') == $template->id ? 'checked' : '' }}>

                                <div class="h-full border-2 border-slate-100 rounded-2xl overflow-hidden hover:border-rOrange/50 transition peer-checked:border-rOrange peer-checked:shadow-lg peer-checked:shadow-rOrange/10 flex flex-col bg-white">
                                    <div class="relative h-40 bg-slate-200 overflow-hidden shrink-0">
                                        <div class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-110">
                                            <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden bg-stone-900">
                                                <iframe src="{{ asset('preview/' . $template->view_path . '/index.html') }}?thumbnail=1" class="absolute top-0 left-0 w-[400%] h-[400%] origin-top-left scale-[0.25] border-0" scrolling="no" tabindex="-1"></iframe>
                                            </div>
                                        </div>

                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center z-10">
                                            <button type="button" class="preview-btn bg-white text-slate-800 px-4 py-2 rounded-full font-bold text-[10px] hover:bg-rOrange hover:text-white transition transform translate-y-4 group-hover:translate-y-0" data-title="{{ $template->name }}" data-path="{{ asset('preview/' . $template->view_path . '/index.html') }}" data-category="{{ $template->category->name ?? 'Umum' }}">
                                                Preview
                                            </button>
                                        </div>

                                        <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-rOrange text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition shadow-md z-10">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-white peer-checked:bg-rOrange/5 transition flex-1 flex flex-col justify-center">
                                        <h5 class="font-bold text-slate-800 mb-1 text-sm truncate" title="{{ $template->name }}">{{ $template->name }}</h5>
                                        <span class="text-[9px] font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded border border-slate-200 uppercase tracking-tighter self-start">
                                            {{ $template->category->name ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    {{-- Pesan Data Kosong --}}
                    <div id="emptyThemeMsg" class="hidden text-center py-10 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 mt-4">
                        <p class="text-slate-400 font-medium">Belum ada template untuk kategori ini.</p>
                    </div>

                    {{-- KONTROL PAGINASI (Ditambahkan di sini) --}}
                    <div id="pagination-controls" class="flex flex-wrap justify-center items-center gap-2 mt-8"></div>

                    @error('template_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-10 bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-900/20 text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <h4 class="font-extrabold text-2xl mb-2">Sudah Yakin?</h4>
                    <p class="text-sm text-slate-400 mb-8 leading-relaxed">Pastikan link, paket, dan desain tema sudah sesuai dengan keinginan Anda.</p>

                    <button type="submit" id="submitFormBtn" class="w-full py-4 bg-gradient-to-r from-rRed to-rOrange rounded-2xl font-bold text-white transition shadow-lg shadow-rRed/30 flex justify-center items-center gap-2">
                        <span>Lanjut Isi Data</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                    <p class="text-xs text-slate-400 mt-5">Desain tema masih bisa diganti nanti saat mengedit isi undangan.</p>
                </div>
            </div>

        </div>
    </form>

    {{-- MODAL PREVIEW IFRAME --}}
    <div id="themeModal" class="fixed inset-0 z-[60] hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-[2rem] w-full max-w-4xl h-[90vh] overflow-hidden flex flex-col shadow-2xl transform scale-95 transition-transform duration-300" id="modalContent">

            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white shrink-0 z-10">
                <div>
                    <h3 class="text-xl font-bold text-slate-800" id="modalTitle">Nama Tema</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider" id="modalCategory">Kategori</p>
                </div>
                <button type="button" id="closeModalBtn" class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-red-100 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-hidden bg-slate-100/50 relative">
                <iframe id="modalIframe" src="" class="absolute inset-0 w-full h-full border-0"></iframe>
            </div>

            <div class="p-5 border-t border-slate-100 bg-white text-center shrink-0">
                <p class="text-xs text-slate-500 mb-3 font-medium">Tutup jendela preview ini dan klik area kotak tema untuk memilihnya.</p>
                <button type="button" class="px-8 py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 w-full transition" id="footerCloseBtn">
                    Tutup Preview
                </button>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- SCRIPT UNTUK FILTER KATEGORI & PAGINATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const themeCards = Array.from(document.querySelectorAll('.theme-card'));
            const emptyMsg = document.getElementById('emptyThemeMsg');
            const paginationContainer = document.getElementById('pagination-controls');

            const itemsPerPage = 8; // Batas item per halaman
            let currentPage = 1;
            let currentFilter = 'all';

            function updateGrid() {
                // 1. Dapatkan semua kartu yang lolos filter kategori
                const filteredCards = themeCards.filter(card => {
                    const category = card.getAttribute('data-category');
                    return currentFilter === 'all' || category === currentFilter;
                });

                // 2. Sembunyikan semua kartu terlebih dahulu
                themeCards.forEach(card => card.classList.add('hidden'));

                // 3. Tampilkan pesan kosong jika tidak ada yang lolos filter
                if (filteredCards.length === 0) {
                    emptyMsg.classList.remove('hidden');
                    paginationContainer.innerHTML = '';
                    return;
                } else {
                    emptyMsg.classList.add('hidden');
                }

                // 4. Hitung paginasi
                const totalPages = Math.ceil(filteredCards.length / itemsPerPage);
                if (currentPage > totalPages) currentPage = totalPages;

                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                // 5. Tampilkan kartu sesuai dengan index halaman saat ini
                filteredCards.slice(startIndex, endIndex).forEach(card => {
                    card.classList.remove('hidden');
                });

                // 6. Render kontrol/tombol paginasi
                renderPagination(totalPages);
            }

            function renderPagination(totalPages) {
                paginationContainer.innerHTML = '';

                // Jika hanya 1 halaman, sembunyikan kontrol paginasi
                if (totalPages <= 1) return;

                // Tombol Prev (Kiri)
                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
                prevBtn.className = `w-10 h-10 rounded-full flex items-center justify-center font-bold transition ${currentPage === 1 ? 'bg-slate-100 text-slate-300 cursor-not-allowed' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-rOrange hover:border-rOrange/30'}`;
                prevBtn.disabled = currentPage === 1;
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) { 
                        currentPage--; 
                        updateGrid(); 
                        document.getElementById('theme-grid').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
                paginationContainer.appendChild(prevBtn);

                // Tombol Angka Halaman
                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.type = 'button';
                    pageBtn.textContent = i;
                    pageBtn.className = `w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition ${currentPage === i ? 'bg-slate-900 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100'}`;
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        updateGrid();
                        document.getElementById('theme-grid').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                    paginationContainer.appendChild(pageBtn);
                }

                // Tombol Next (Kanan)
                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
                nextBtn.className = `w-10 h-10 rounded-full flex items-center justify-center font-bold transition ${currentPage === totalPages ? 'bg-slate-100 text-slate-300 cursor-not-allowed' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-rOrange hover:border-rOrange/30'}`;
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) { 
                        currentPage++; 
                        updateGrid(); 
                        document.getElementById('theme-grid').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
                paginationContainer.appendChild(nextBtn);
            }

            // Event listener untuk Filter Kategori
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-slate-900', 'text-white');
                        b.classList.add('bg-slate-100', 'text-slate-600');
                    });
                    
                    this.classList.remove('bg-slate-100', 'text-slate-600');
                    this.classList.add('bg-slate-900', 'text-white');

                    currentFilter = this.getAttribute('data-filter');
                    currentPage = 1; // Kembali ke halaman 1 setiap filter ganti
                    updateGrid();
                });
            });

            // Panggil untuk inisialisasi awal saat halaman dimuat
            updateGrid();
        });
    </script>

    {{-- SCRIPT UNTUK TOMBOL CEK LINK (AJAX) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slugInput = document.getElementById('slugInput');
            const slugMessage = document.getElementById('slugMessage');
            const btnCheckSlug = document.getElementById('btnCheckSlug');
            const slugSpinner = document.getElementById('slugSpinner');
            const btnText = btnCheckSlug ? btnCheckSlug.querySelector('span') : null;
            const submitBtn = document.getElementById('submitFormBtn');

            if (slugInput && btnCheckSlug) {
                if (submitBtn && !slugInput.value) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }

                slugInput.addEventListener('input', function() {
                    this.value = this.value.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                    slugMessage.classList.add('hidden');
                    btnCheckSlug.classList.remove('bg-green-500', 'bg-red-500');
                    btnCheckSlug.classList.add('bg-slate-900', 'hover:bg-slate-800');
                    btnText.textContent = 'Cek Link';

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.remove('hover:scale-105');
                    }
                });

                btnCheckSlug.addEventListener('click', function() {
                    const slug = slugInput.value;
                    if (slug.length < 3) {
                        slugMessage.textContent = 'Link terlalu pendek (minimal 3 karakter).';
                        slugMessage.classList.remove('hidden', 'text-green-500', 'text-red-500');
                        slugMessage.classList.add('text-amber-500');
                        return;
                    }

                    btnCheckSlug.disabled = true;
                    slugSpinner.classList.remove('hidden');
                    btnText.textContent = 'Mengecek...';
                    slugMessage.classList.add('hidden');

                    fetch(`{{ url('/check-slug') }}?slug=${slug}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            btnCheckSlug.disabled = false;
                            slugSpinner.classList.add('hidden');

                            if (data.available) {
                                slugMessage.textContent = 'Yeay! Link undangan tersedia.';
                                slugMessage.classList.remove('hidden', 'text-red-500', 'text-amber-500');
                                slugMessage.classList.add('text-green-500');

                                btnCheckSlug.classList.remove('bg-slate-900', 'bg-red-500', 'hover:bg-slate-800');
                                btnCheckSlug.classList.add('bg-green-500');
                                btnText.textContent = 'Tersedia!';

                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                    submitBtn.classList.add('hover:scale-105');
                                }
                            } else {
                                slugMessage.textContent = 'Maaf, link ini sudah dipakai. Silakan pilih yang lain.';
                                slugMessage.classList.remove('hidden', 'text-green-500', 'text-amber-500');
                                slugMessage.classList.add('text-red-500');

                                btnCheckSlug.classList.remove('bg-slate-900', 'bg-green-500', 'hover:bg-slate-800');
                                btnCheckSlug.classList.add('bg-red-500');
                                btnText.textContent = 'Terpakai';

                                if (submitBtn) {
                                    submitBtn.disabled = true;
                                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                    submitBtn.classList.remove('hover:scale-105');
                                }
                            }
                        })
                        .catch(err => {
                            btnCheckSlug.disabled = false;
                            slugSpinner.classList.add('hidden');
                            btnText.textContent = 'Cek Link';

                            slugMessage.textContent = 'Terjadi kesalahan jaringan.';
                            slugMessage.classList.remove('hidden');
                            console.error(err);
                        });
                });
            }
        });
    </script>

    {{-- SCRIPT UNTUK MODAL PREVIEW TEMA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('themeModal');
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalTitle');
            const modalCategory = document.getElementById('modalCategory');
            const modalIframe = document.getElementById('modalIframe');
            const closeBtn = document.getElementById('closeModalBtn');
            const footerCloseBtn = document.getElementById('footerCloseBtn');
            const previewBtns = document.querySelectorAll('.preview-btn');

            function openModal(title, category, pathUrl) {
                modalTitle.textContent = title;
                modalCategory.textContent = category;
                modalIframe.src = pathUrl;

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 20);

                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('opacity-0');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modalIframe.src = '';
                }, 300);

                document.body.style.overflow = '';
            }

            // Gunakan event delegation karena kita menyembunyikan/menampilkan kartu via JS Pagination
            document.getElementById('theme-grid').addEventListener('click', function(e) {
                const btn = e.target.closest('.preview-btn');
                if (btn) {
                    e.preventDefault();
                    e.stopPropagation();

                    const title = btn.getAttribute('data-title');
                    const category = btn.getAttribute('data-category');
                    const pathUrl = btn.getAttribute('data-path');

                    openModal(title, category, pathUrl);
                }
            });

            closeBtn.addEventListener('click', closeModal);
            footerCloseBtn.addEventListener('click', closeModal);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
@endpush