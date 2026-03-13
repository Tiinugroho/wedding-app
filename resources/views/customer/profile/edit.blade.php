@extends('customer.partials.app')
@section('title', 'Profil Saya')

@push('styles')
    {{-- Library Cropper.js untuk potong foto --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .avatar-wrapper { position: relative; display: inline-block; }
        .avatar-preview-img { width: 140px; height: 140px; object-fit: cover; border-radius: 50%; border: 4px solid white; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .btn-edit-avatar { position: absolute; bottom: 5px; right: 5px; border-radius: 50%; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; z-index: 10; border: 3px solid white; }
        
        /* Memastikan area cropper tidak nempel ke pinggir modal */
        .cropper-view-box, .cropper-face { border-radius: 50%; }
        .img-container { width: 100%; max-height: 450px; }
        #cropper-image { display: block; max-width: 100%; }
    </style>
@endpush

@section('content')
    <header class="mb-10">
        <h2 class="text-3xl font-extrabold text-slate-800">Pengaturan Profil</h2>
        <p class="text-slate-400 mt-1">Kelola data diri, foto profil, dan keamanan akun Anda.</p>
    </header>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-medium">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Perubahan profil berhasil disimpan!
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- SEKSI KIRI: INFO PROFIL --}}
        <div class="lg:col-span-7 space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <form method="post" action="{{ route('customer.profile.update') }}">
                    @csrf
                    @method('patch')

                    <input type="hidden" name="avatar_base64" class="avatar_base64_input">

                    <div class="flex flex-col items-center sm:flex-row gap-8 pb-8 mb-8 border-b border-slate-100">
                        <div class="avatar-wrapper">
                            @php
                                $avatar = auth()->user()->avatar;
                                $avatarUrl = $avatar ? (filter_var($avatar, FILTER_VALIDATE_URL) ? $avatar : asset('storage/'.$avatar)) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random&color=fff';
                            @endphp
                            <img src="{{ $avatarUrl }}" class="avatar-preview-img avatar-preview-element" alt="Avatar">
                            
                            <label class="btn-edit-avatar bg-slate-900 text-white cursor-pointer hover:bg-rOrange hover:scale-110 transition shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input type="file" class="avatar-upload-input hidden" accept="image/*">
                            </label>
                        </div>
                        <div class="text-center sm:text-left">
                            <h4 class="text-lg font-bold text-slate-800">Foto Profil Anda</h4>
                            <p class="text-sm text-slate-400 mt-1 max-w-[200px]">Klik ikon kamera untuk menyesuaikan foto profil.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange transition outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-rOrange/20 focus:border-rOrange transition outline-none">
                        </div>
                    </div>

                    <button type="submit" class="mt-8 w-full sm:w-auto px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition shadow-xl">
                        Simpan Profil
                    </button>
                </form>
            </div>
        </div>

        {{-- SEKSI KANAN: PASSWORD --}}
        <div class="lg:col-span-5">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    Keamanan
                </h3>

                <form method="post" action="{{ route('customer.password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')
                    <input type="password" name="current_password" placeholder="Sandi Saat Ini" class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-rOrange outline-none">
                    @error('current_password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                    <input type="password" name="password" placeholder="Sandi Baru" class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-rOrange outline-none">
                    @error('password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Sandi" class="w-full py-3.5 px-5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-rOrange outline-none">
                    
                    <button type="submit" class="w-full py-4 bg-orange-50 text-rOrange rounded-2xl font-bold hover:bg-orange-100 transition">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CROP (TAILWIND ONLY) --}}
    <div id="cropModal" class="fixed inset-0 z-[110] hidden bg-slate-900/90 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl transform transition-all">
            <div class="p-6 border-b border-slate-100 text-center">
                <h3 class="text-xl font-bold text-slate-800">Sesuaikan Foto</h3>
            </div>
            
            {{-- Container Gambar dengan padding horizontal untuk gap --}}
            <div class="p-8 bg-slate-50 flex justify-center items-center">
                <div class="img-container"> 
                    <img id="cropper-image" src="">
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 flex gap-4">
                <button type="button" onclick="closeManualModal()"
                    class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">Batal</button>
                <button type="button" id="crop-btn"
                    class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition">Terapkan</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const cropModal = document.getElementById('cropModal');
        const cropperImage = document.getElementById('cropper-image');

        function openManualModal() {
            cropModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Lock scroll
        }

        function closeManualModal() {
            cropModal.classList.add('hidden');
            document.body.style.overflow = ''; // Unlock scroll
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }

        // Handle file input
        document.querySelectorAll('.avatar-upload-input').forEach(input => {
            input.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        cropperImage.src = event.target.result;
                        openManualModal();
                        
                        // Beri sedikit delay agar gambar ter-load sempurna di modal sebelum di-crop
                        setTimeout(() => {
                            if (cropper) cropper.destroy();
                            cropper = new Cropper(cropperImage, {
                                aspectRatio: 1,
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 0.8,
                                responsive: true,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        }, 200);
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });

        // Handle Crop Button
        document.getElementById('crop-btn').addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 500,
                    height: 500,
                    imageSmoothingQuality: 'high'
                });
                
                const base64 = canvas.toDataURL('image/jpeg');
                
                // Update Preview
                document.querySelectorAll('.avatar-preview-element').forEach(img => img.src = base64);
                // Update Hidden Input
                document.querySelectorAll('.avatar_base64_input').forEach(input => input.value = base64);
                
                closeManualModal();
            }
        });

        // Close modal when clicking outside content area
        cropModal.addEventListener('click', function(e) {
            if (e.target === cropModal) closeManualModal();
        });
    </script>
@endpush