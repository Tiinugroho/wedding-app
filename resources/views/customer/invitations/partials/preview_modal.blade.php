<div id="themeModal"
    class="fixed inset-0 z-[60] hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[2rem] w-full max-w-4xl h-[90vh] overflow-hidden flex flex-col shadow-2xl transform scale-95 transition-transform duration-300"
        id="modalContent">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white shrink-0 z-10">
            <div>
                <h3 class="text-xl font-bold text-slate-800" id="modalTitle">Nama Tema</h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider" id="modalCategory">Kategori
                </p>
            </div>
            <button type="button" id="closeModalBtn"
                class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-red-100 hover:text-red-500 transition"><svg
                    class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg></button>
        </div>
        <div class="flex-1 overflow-hidden bg-slate-100/50 relative">
            <iframe id="modalIframe" src="" class="absolute inset-0 w-full h-full border-0"></iframe>
        </div>
        <div class="p-5 border-t border-slate-100 bg-white text-center shrink-0">
            <p class="text-xs text-slate-500 mb-3 font-medium">Tutup jendela preview ini dan klik area kotak tema untuk
                memilihnya.</p>
            <button type="button"
                class="px-8 py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 w-full transition"
                id="footerCloseBtn">Tutup Preview</button>
        </div>
    </div>
</div>
