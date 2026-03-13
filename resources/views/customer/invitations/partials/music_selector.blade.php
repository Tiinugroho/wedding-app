@push('styles')
<style>
    /* Sembunyikan ikon centang secara default */
    .btn-select .check-icon { 
        display: none; 
    }
    
    /* Tampilan tombol SAAT DIPILIH (Timpa dengan !important agar menang dari Tailwind) */
    .btn-select.selected {
        background-color: #22c55e !important; /* Hijau Tailwind */
        border-color: #22c55e !important;
        color: #ffffff !important;
    }
    
    /* Munculkan ikon centang SAAT DIPILIH */
    .btn-select.selected .check-icon { 
        display: block; 
    }
    
    /* Tampilan baris tabel SAAT DIPILIH */
    .music-row.active-row {
        background-color: #f0fdf4 !important; /* Hijau pudar Tailwind (green-50) */
    }
</style>
@endpush

<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
    <h4 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
        </div>
        Backsound Musik
    </h4>
    
    <div class="bg-indigo-50 text-indigo-700 p-4 rounded-2xl mb-6 shadow-sm flex items-center font-semibold">
        <svg class="w-6 h-6 me-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span id="banner-text">
            Musik Terpilih: {{ $invitation->music->title ?? 'Belum ada musik' }}
        </span>
    </div>

    <input type="hidden" name="music_id" id="music_id_input" value="{{ $invitation->music_id ?? '' }}">

    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <select id="category-filter" class="form-select flex-1 py-3 px-4 rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="all">Semua Kategori</option>
            @foreach($musics->pluck('category')->unique() as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
        <input type="text" id="search-music" class="form-control flex-[2] py-3 px-4 rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari judul lagu...">
    </div>

    <div class="border border-slate-100 rounded-2xl overflow-hidden">
        <div style="max-height: 350px; overflow-y: auto;">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 font-bold">Judul Lagu & Kategori</th>
                        <th class="px-6 py-4 font-bold text-center">Putar</th>
                        <th class="px-6 py-4 font-bold text-center">Pilih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="music-table-body">
                    @foreach($musics as $music)
                    
                    @php $isSelected = ($invitation->music_id == $music->id); @endphp
                    
                    <tr class="music-row transition hover:bg-slate-50 {{ $isSelected ? 'active-row' : '' }}" data-category="{{ $music->category }}">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 music-title">{{ $music->title }}</div>
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded mt-1 inline-block border border-slate-200">{{ $music->category }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" class="btn-play w-10 h-10 rounded-full bg-slate-100 text-slate-600 hover:bg-indigo-500 hover:text-white transition flex items-center justify-center mx-auto" data-src="{{ asset('storage/' . $music->file_path) }}">
                                <svg class="w-4 h-4 play-icon translate-x-0.5" fill="currentColor" viewBox="0 0 20 20" style="display: block;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                <svg class="w-4 h-4 pause-icon" fill="currentColor" viewBox="0 0 20 20" style="display: none;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button type="button" 
                                class="btn-select w-8 h-8 rounded-full border-2 border-slate-300 text-slate-300 hover:border-green-500 hover:text-green-500 transition mx-auto flex items-center justify-center {{ $isSelected ? 'selected' : '' }}"
                                data-id="{{ $music->id }}" data-title="{{ $music->title }}">
                                <svg class="w-5 h-5 check-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. LOGIKA AUDIO PLAYER
    let globalAudio = new Audio();
    let currentPlayingBtn = null;

    const playButtons = document.querySelectorAll('.btn-play');
    playButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); 
            const src = this.getAttribute('data-src');
            const playIcon = this.querySelector('.play-icon');
            const pauseIcon = this.querySelector('.pause-icon');

            if (currentPlayingBtn === this) {
                if (globalAudio.paused) {
                    globalAudio.play();
                    playIcon.style.display = 'none';
                    pauseIcon.style.display = 'block';
                } else {
                    globalAudio.pause();
                    playIcon.style.display = 'block';
                    pauseIcon.style.display = 'none';
                }
            } else {
                if (currentPlayingBtn) {
                    currentPlayingBtn.querySelector('.play-icon').style.display = 'block';
                    currentPlayingBtn.querySelector('.pause-icon').style.display = 'none';
                }
                globalAudio.src = src;
                globalAudio.play();
                playIcon.style.display = 'none';
                pauseIcon.style.display = 'block';
                currentPlayingBtn = this;
            }
        });
    });

    globalAudio.addEventListener('ended', function() {
        if (currentPlayingBtn) {
            currentPlayingBtn.querySelector('.play-icon').style.display = 'block';
            currentPlayingBtn.querySelector('.pause-icon').style.display = 'none';
            currentPlayingBtn = null;
        }
    });

    // 2. LOGIKA MEMILIH MUSIK (Jauh lebih bersih, hanya toggle class)
    const selectButtons = document.querySelectorAll('.btn-select');
    selectButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const musicId = this.getAttribute('data-id');
            const musicTitle = this.getAttribute('data-title');

            document.getElementById('music_id_input').value = musicId;
            document.getElementById('banner-text').innerHTML = `Musik Terpilih: <b>${musicTitle}</b>`;

            // Reset semua tombol & baris (hilangkan class CSS custom)
            selectButtons.forEach(b => {
                b.classList.remove('selected');
                b.closest('tr').classList.remove('active-row');
            });

            // Aktifkan tombol & baris yang sedang diklik
            this.classList.add('selected');
            this.closest('tr').classList.add('active-row');
        });
    });

    // 3. LOGIKA FILTER
    const searchInput = document.getElementById('search-music');
    const categoryFilter = document.getElementById('category-filter');
    const rows = document.querySelectorAll('.music-row');

    function filterMusicTable() {
        const keyword = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        rows.forEach(row => {
            const title = row.querySelector('.music-title').textContent.toLowerCase();
            const category = row.getAttribute('data-category');

            const matchKeyword = title.includes(keyword);
            const matchCategory = (selectedCategory === 'all') || (category === selectedCategory);

            if (matchKeyword && matchCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('keyup', filterMusicTable);
    categoryFilter.addEventListener('change', filterMusicTable);

});
</script>
@endpush