<div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
    <h4 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        Data Mempelai Wanita
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
            <input type="text" name="bride_name" value="{{ old('bride_name', $content['bride_name'] ?? '') }}"
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
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ayah</label>
            <input type="text" name="bride_father" value="{{ old('bride_father', $content['bride_father'] ?? '') }}"
                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                placeholder="Bpk. Fulan">
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Ibu</label>
            <input type="text" name="bride_mother" value="{{ old('bride_mother', $content['bride_mother'] ?? '') }}"
                class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-rOrange"
                placeholder="Ibu Fulanah">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-bold text-slate-700 mb-2">Username Instagram
                (Opsional)</label>
            <div class="flex items-center">
                <span
                    class="bg-slate-100 border border-slate-200 border-r-0 text-slate-500 px-4 py-3 rounded-l-xl font-medium text-sm">instagram.com/</span>
                <input type="text" name="bride_ig" value="{{ old('bride_ig', $content['bride_ig'] ?? '') }}"
                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-r-xl focus:ring-rOrange"
                    placeholder="username">
            </div>
        </div>
        <div class="md:col-span-2 mt-4">
            <label class="block text-sm font-bold text-slate-700 mb-2">Foto Mempelai Wanita</label>
            <div class="flex items-center gap-4">
                <img src="{{ !empty($content['bride_photo']) ? asset('storage/' . $content['bride_photo']) : 'https://ui-avatars.com/api/?name=Wanita' }}"
                    class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                <input type="file" name="bride_photo" accept="image/*"
                    class="w-full py-2 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm">
            </div>
        </div>
    </div>
</div>
