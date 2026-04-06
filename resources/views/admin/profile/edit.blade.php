@extends('admin.partials.app')
@section('title', 'Profil Admin')

@push('styles')
    {{-- Library Cropper.js untuk potong foto --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .avatar-wrapper { position: relative; display: inline-block; }
        .avatar-preview-img { width: 140px; height: 140px; object-fit: cover; border-radius: 50%; border: 4px solid white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .btn-edit-avatar { position: absolute; bottom: 5px; right: 5px; border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; z-index: 10; border: 3px solid white; }
        
        /* Memastikan area cropper berbentuk lingkaran dan tidak nempel ke pinggir modal */
        .cropper-view-box, .cropper-face { border-radius: 50%; }
        .img-container { width: 100%; max-height: 450px; }
        #cropper-image { display: block; max-width: 100%; }
    </style>
@endpush

@section('content')
    <header class="flex flex-row items-center mb-10 gap-4">
        <button type="button" onclick="toggleSidebar()" class="lg:hidden bg-white p-2.5 rounded-xl border border-slate-100 shadow-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-rOrange transition group">
            <svg class="w-6 h-6 group-hover:text-rOrange transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800">Pengaturan Profil</h2>
            <p class="text-slate-400 text-sm md:text-base mt-1">Kelola data diri, foto profil, dan keamanan akun Anda.</p>
        </div>
    </header>

    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium shadow-sm">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Perubahan berhasil disimpan!
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- SEKSI KIRI: INFO PROFIL --}}
        <div class="lg:col-span-7 space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                
                {{-- Form Kirim Ulang Verifikasi Email --}}
                <form id="send-verification" method="post" action="{{ route('admin.verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('admin.profile.update') }}">
                    @csrf @method('patch')
                    <input type="hidden" name="avatar_base64" class="avatar_base64_input">

                    <div class="flex flex-col items-center sm:flex-row gap-8 pb-8 mb-8 border-b border-slate-100">
                        <div class="avatar-wrapper">
                            @php
                                $avatar = auth()->user()->avatar;
                                $avatarUrl = $avatar ? (filter_var($avatar, FILTER_VALIDATE_URL) ? $avatar : asset('storage/'.$avatar)) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random&color=fff';
                            @endphp
                            <img src="{{ $avatarUrl }}" class="avatar-preview-img avatar-preview-element" alt="Avatar Admin">
                            
                            <label class="btn-edit-avatar bg-slate-900 text-white cursor-pointer hover:bg-rOrange hover:scale-110 transition shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input type="file" class="avatar-upload-input hidden" accept="image/*">
                            </label>
                        </div>
                        <div class="text-center sm:text-left">
                            <h4 class="text-lg font-bold text-slate-800">Foto Profil Admin</h4>
                            <p class="text-sm text-slate-400 mt-1 max-w-[200px]">Klik ikon kamera untuk menyesuaikan foto profil.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange transition outline-none @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange transition outline-none @error('email') border-red-500 @enderror">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="mt-3 bg-amber-50 p-4 rounded-xl border border-amber-100">
                                    <p class="text-sm text-amber-700 mb-1">Alamat email Anda belum diverifikasi.</p>
                                    <button form="send-verification" class="text-sm font-bold text-amber-600 hover:text-amber-800 hover:underline">
                                        Klik di sini untuk mengirim ulang email verifikasi.
                                    </button>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="font-bold text-sm text-green-600 mt-2">Tautan verifikasi baru telah dikirim ke alamat email Anda.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="mt-8 w-full sm:w-auto px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition shadow-xl">
                        Simpan Profil
                    </button>
                </form>
            </div>
        </div>

        {{-- SEKSI KANAN: PASSWORD & DELETE ACCOUNT --}}
        <div class="lg:col-span-5 space-y-8">
            
            {{-- Update Password Card --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    Ubah Password
                </h3>

                <form method="post" action="{{ route('admin.password.update') }}" class="space-y-4">
                    @csrf @method('put')
                    
                    <div>
                        <input type="password" name="current_password" placeholder="Sandi Saat Ini" class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange outline-none">
                        @error('current_password', 'updatePassword') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <input type="password" name="password" placeholder="Sandi Baru" class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange outline-none">
                        @error('password', 'updatePassword') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Sandi" class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange outline-none">
                        @error('password_confirmation', 'updatePassword') <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <button type="submit" class="w-full py-4 mt-2 bg-orange-50 text-rOrange border border-orange-100 rounded-2xl font-bold hover:bg-rOrange hover:text-white transition shadow-sm">
                        Update Password
                    </button>
                </form>
            </div>

            {{-- Delete Account Card --}}
            <div class="bg-red-50 p-8 rounded-[2.5rem] border border-red-100 shadow-sm relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 opacity-10">
                    <svg class="w-32 h-32 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="relative z-10">
                    <h3 class="text-xl font-bold text-red-600 mb-2">Hapus Akun</h3>
                    <p class="text-red-500 text-sm mb-6 leading-relaxed">Setelah dihapus, semua data akan hilang secara permanen dan tidak dapat dikembalikan.</p>
                    
                    @if ($errors->userDeletion->isNotEmpty())
                        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium border border-red-200">
                            Gagal menghapus: {{ $errors->userDeletion->first('password') }}
                        </div>
                    @endif

                    <button type="button" onclick="openDeleteModal()" class="px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-500/30">
                        Hapus Akun Permanen
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CROP FOTO (TAILWIND) --}}
    <div id="cropModal" class="fixed inset-0 z-[110] hidden bg-slate-900/90 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl transform transition-all">
            <div class="p-6 border-b border-slate-100 text-center">
                <h3 class="text-xl font-bold text-slate-800">Sesuaikan Foto</h3>
            </div>
            
            <div class="p-8 bg-slate-50 flex justify-center items-center">
                <div class="img-container"> 
                    <img id="cropper-image" src="">
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeManualModal()" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">Batal</button>
                <button type="button" id="crop-btn" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition">Terapkan</button>
            </div>
        </div>
    </div>

    {{-- MODAL DELETE ACCOUNT (TAILWIND) --}}
    <div id="deleteAccountModal" class="fixed inset-0 z-[110] hidden bg-slate-900/90 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
            <form method="post" action="{{ route('admin.profile.destroy') }}">
                @csrf @method('delete')
                
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Hapus Akun Anda?</h3>
                    <p class="text-slate-500 text-sm mb-6 leading-relaxed">Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun ini secara permanen.</p>
                    
                    <input type="password" name="password" placeholder="Kata Sandi Anda" required class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition outline-none text-center">
                </div>

                <div class="p-6 border-t border-slate-100 flex gap-4 bg-slate-50">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-500/30">Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        // === LOGIKA CROPPER ===
        let cropper;
        const cropModal = document.getElementById('cropModal');
        const cropperImage = document.getElementById('cropper-image');

        function openManualModal() {
            cropModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeManualModal() {
            cropModal.classList.add('hidden');
            document.body.style.overflow = '';
            if (cropper) { cropper.destroy(); cropper = null; }
        }

        document.querySelectorAll('.avatar-upload-input').forEach(input => {
            input.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        cropperImage.src = event.target.result;
                        openManualModal();
                        setTimeout(() => {
                            if (cropper) cropper.destroy();
                            cropper = new Cropper(cropperImage, {
                                aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 0.8, responsive: true,
                                restore: false, guides: true, center: true, highlight: false, cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false,
                            });
                        }, 200);
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });

        document.getElementById('crop-btn').addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({ width: 500, height: 500, imageSmoothingQuality: 'high' });
                const base64 = canvas.toDataURL('image/jpeg');
                
                document.querySelectorAll('.avatar-preview-element').forEach(img => img.src = base64);
                document.querySelectorAll('.avatar_base64_input').forEach(input => input.value = base64);
                closeManualModal();
            }
        });

        // Tutup modal crop jika klik di luar box
        cropModal.addEventListener('click', function(e) {
            if (e.target === cropModal) closeManualModal();
        });

        // === LOGIKA MODAL DELETE ACCOUNT ===
        const deleteModal = document.getElementById('deleteAccountModal');
        
        function openDeleteModal() {
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Tutup modal delete jika klik di luar box
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) closeDeleteModal();
        });
    </script>
@endpush