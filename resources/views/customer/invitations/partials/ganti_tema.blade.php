<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
    <h4
        class="text-xl font-bold text-slate-800 mb-6 flex flex-col md:flex-row md:items-center gap-3 pb-4 border-b border-slate-100 justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                    </path>
                </svg>
            </div>
            Ganti Desain Tema
        </div>
    </h4>

    {{-- FILTER KATEGORI --}}
    @php
        $categories = collect($templates)->map(fn($t) => $t->category->name ?? 'Umum')->unique();
    @endphp
    <div class="flex flex-wrap gap-2 mb-6" id="template-filters">
        <button type="button"
            class="filter-btn px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-bold transition shadow-md"
            data-filter="all">Semua</button>
        @foreach ($categories as $cat)
            <button type="button"
                class="filter-btn px-4 py-2 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 text-xs font-bold transition"
                data-filter="{{ $cat }}">{{ $cat }}</button>
        @endforeach
    </div>

    {{-- GRID TEMA DIPERKECIL --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="template-grid">
        @foreach ($templates as $template)
            {{-- Tambahkan class 'hidden' secara default --}}
            <label class="cursor-pointer relative group template-card hidden"
                data-category="{{ $template->category->name ?? 'Umum' }}">
                <input type="radio" name="template_id" value="{{ $template->id }}" class="peer sr-only"
                    {{ old('template_id', $invitation->template_id) == $template->id ? 'checked' : '' }}>
                <div
                    class="h-full border-2 border-slate-100 rounded-2xl overflow-hidden hover:border-rOrange/50 transition peer-checked:border-rOrange peer-checked:shadow-lg peer-checked:shadow-rOrange/10">
                    <div class="relative h-36 bg-slate-200 overflow-hidden">
                        <div
                            class="absolute inset-0 w-full h-full transition-transform duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden bg-stone-900">
                                <iframe src="{{ asset('preview/' . $template->view_path . '/index.html') }}?thumbnail=1"
                                    class="absolute top-0 left-0 w-[500%] h-[500%] origin-top-left scale-[0.20] border-0"
                                    scrolling="no" tabindex="-1"></iframe>
                            </div>
                        </div>
                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center z-10">
                            <button type="button"
                                class="preview-btn bg-white text-slate-800 px-4 py-2 rounded-full font-bold text-[10px] hover:bg-rOrange hover:text-white transition transform translate-y-4 group-hover:translate-y-0"
                                data-title="{{ $template->name }}"
                                data-path="{{ asset('preview/' . $template->view_path . '/index.html') }}"
                                data-category="{{ $template->category->name ?? 'Umum' }}">
                                Preview
                            </button>
                        </div>
                        <div
                            class="absolute top-2 right-2 w-6 h-6 rounded-full bg-rOrange text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition shadow-md z-10">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-3 bg-white peer-checked:bg-rOrange/5 transition">
                        <h5 class="font-bold text-slate-800 mb-0.5 text-xs truncate">
                            {{ $template->name }}</h5>
                        <span
                            class="text-[9px] font-bold bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded border border-slate-200 uppercase">{{ $template->category->name ?? 'Umum' }}</span>
                    </div>
                </div>
            </label>
        @endforeach
    </div>

    {{-- KONTROL PAGINATION --}}
    <div id="pagination-controls" class="flex justify-center items-center gap-2 mt-8"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsPerPage = 8;
        let currentPage = 1;
        let currentFilter = 'all';

        const cards = Array.from(document.querySelectorAll('.template-card'));
        const filterBtns = document.querySelectorAll('.filter-btn');
        const paginationContainer = document.getElementById('pagination-controls');

        function renderGrid() {
            // 1. Sembunyikan semua kartu secara paksa melalui inline style
            cards.forEach(card => {
                card.style.display = 'none';
                card.classList.add('hidden');
            });

            // 2. Dapatkan data mana saja yang sesuai kategori
            const filteredCards = cards.filter(card => {
                return currentFilter === 'all' || card.dataset.category === currentFilter;
            });

            // 3. Kalkulasi total halaman
            const totalPages = Math.ceil(filteredCards.length / itemsPerPage);
            if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // 4. Tampilkan HANYA 8 data pada limit page yang aktif
            filteredCards.slice(startIndex, endIndex).forEach(card => {
                card.style.display = 'block'; 
                card.classList.remove('hidden');
                
                // Tambahkan transisi fade-in agar mulus saat perpindahan
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.4s ease-in-out';
                    card.style.opacity = '1';
                }, 10);
            });

            // 5. Build kontrol pagination
            renderPaginationControls(totalPages);
        }

        function renderPaginationControls(totalPages) {
            paginationContainer.innerHTML = '';
            
            if (totalPages <= 1) return; 

            // Tombol Prev
            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = `px-3 py-1.5 rounded-lg text-xs font-bold transition ${currentPage === 1 ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'}`;
            prevBtn.innerText = 'Prev';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => { currentPage--; renderGrid(); };
            paginationContainer.appendChild(prevBtn);

            // Angka Halaman
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition flex items-center justify-center ${currentPage === i ? 'bg-slate-900 text-white shadow-md' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'}`;
                btn.innerText = i;
                btn.onclick = () => { currentPage = i; renderGrid(); };
                paginationContainer.appendChild(btn);
            }

            // Tombol Next
            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = `px-3 py-1.5 rounded-lg text-xs font-bold transition ${currentPage === totalPages ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'}`;
            nextBtn.innerText = 'Next';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => { currentPage++; renderGrid(); };
            paginationContainer.appendChild(nextBtn);
        }

        // Listener Filter
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // 🔥 PROTEKSI PENTING: Mencegah script filter lama dari luar component ikut tereksekusi
                e.preventDefault();
                e.stopImmediatePropagation();

                // Manipulasi gaya tombol kategori yang aktif
                filterBtns.forEach(b => {
                    b.classList.remove('bg-slate-900', 'text-white', 'shadow-md');
                    b.classList.add('bg-slate-100', 'text-slate-600');
                });
                this.classList.remove('bg-slate-100', 'text-slate-600');
                this.classList.add('bg-slate-900', 'text-white', 'shadow-md');

                currentFilter = this.dataset.filter;
                currentPage = 1; // Reset ke halaman 1 setiap kali filter diubah
                renderGrid();
            });
        });

        // Eksekusi tampilan awal
        renderGrid();
    });
</script>