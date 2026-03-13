@extends('admin.partials.app')
@section('title', 'Profile')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .avatar-preview-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #dee2e6;
        }

        .btn-edit-avatar {
            position: absolute;
            bottom: 5px;
            right: 5px;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #cropper-image {
            max-width: 100%;
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="row">

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Profile Information</h5>
                            <p class="text-muted text-sm mb-0">Update your account's profile information, email address, and
                                avatar.</p>
                        </div>
                        <div class="card-body">

                            <form id="send-verification" method="post" action="{{ route('admin.verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <input type="hidden" name="avatar_base64" id="avatar_base64">

                                <div class="text-center mb-4">
                                    @php
                                        $userAvatar = Auth::user()->avatar ?? null;
                                        if ($userAvatar) {
                                            $avatarUrl = filter_var($userAvatar, FILTER_VALIDATE_URL)
                                                ? $userAvatar
                                                : asset('storage/' . $userAvatar);
                                        } else {
                                            $avatarUrl = asset('adm/assets/images/user/avatar-2.jpg');
                                        }
                                    @endphp

                                    <div class="avatar-wrapper">
                                        <img src="{{ $avatarUrl }}" id="avatar-preview"
                                            class="avatar-preview-img shadow-sm" alt="User Avatar">
                                        <label for="avatar-upload" class="btn btn-primary btn-edit-avatar shadow"
                                            title="Change Avatar" style="cursor: pointer;">
                                            <i class="ph ph-camera"></i>
                                        </label>
                                        <input type="file" id="avatar-upload" class="d-none"
                                            accept="image/png, image/jpeg, image/jpg">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required
                                        autofocus autocomplete="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required
                                        autocomplete="username">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                        <div class="mt-2">
                                            <p class="text-sm text-warning mb-1">
                                                Your email address is unverified.
                                                <button form="send-verification"
                                                    class="btn btn-link p-0 m-0 align-baseline text-decoration-none">
                                                    Click here to re-send the verification email.
                                                </button>
                                            </p>
                                            @if (session('status') === 'verification-link-sent')
                                                <p class="font-medium text-sm text-success">
                                                    A new verification link has been sent to your email address.
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>

                                    @if (session('status') === 'profile-updated')
                                        <span class="text-success fw-bold">Saved!</span>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>Update Password</h5>
                            <p class="text-muted text-sm mb-0">Ensure your account is using a long, random password to stay
                                secure.</p>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('admin.password.update') }}">
                                @csrf
                                @method('put')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password"
                                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                        id="current_password" name="current_password" autocomplete="current-password">
                                    @error('current_password', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password"
                                        class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                        id="password" name="password" autocomplete="new-password">
                                    @error('password', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                        id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                                    @error('password_confirmation', 'updatePassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex align-items-center gap-3 mt-4">
                                    <button type="submit" class="btn btn-primary">Update Password</button>

                                    @if (session('status') === 'password-updated')
                                        <span class="text-success fw-bold">Saved!</span>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card border border-danger">
                        <div class="card-header bg-light-danger">
                            <h5 class="text-danger">Delete Account</h5>
                            <p class="text-muted text-sm mb-0">Once your account is deleted, all of its resources and data
                                will be permanently deleted.</p>
                        </div>
                        <div class="card-body">

                            @if ($errors->userDeletion->isNotEmpty())
                                <div class="alert alert-danger">
                                    Failed to delete account: {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif

                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteAccountModal">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('admin.profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteAccountModalLabel">Are you sure you want to delete your account?
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-sm text-muted">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Please
                            enter your password to confirm you would like to permanently delete your account.
                        </p>

                        <div class="mb-3 mt-4">
                            <label for="password_delete" class="form-label sr-only">Password</label>
                            <input type="password" class="form-control" id="password_delete" name="password"
                                placeholder="Enter your password to confirm" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropModalLabel">Crop Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="img-container p-3" style="max-height: 400px; overflow: hidden; text-align: center;">
                        <img id="cropper-image" src="" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop-btn">Crop & Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const avatarUpload = document.getElementById('avatar-upload');
        const cropperImage = document.getElementById('cropper-image');
        const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

        // 1. Saat file dipilih, tampilkan di modal Cropper
        avatarUpload.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                const reader = new FileReader();

                reader.onload = function(event) {
                    cropperImage.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            }
        });

        // 2. Inisialisasi Cropper saat modal terbuka
        document.getElementById('cropModal').addEventListener('shown.bs.modal', function() {
            cropper = new Cropper(cropperImage, {
                aspectRatio: 1, // Memaksa kotak 1:1
                viewMode: 1, // Gambar tidak bisa digeser keluar kotak
                autoCropArea: 1,
                responsive: true,
            });
        });

        // 3. Hancurkan Cropper saat modal ditutup agar tidak error saat pilih foto lain
        document.getElementById('cropModal').addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            avatarUpload.value = ''; // Reset input file
        });

        // 4. Saat tombol "Crop & Save" diklik
        document.getElementById('crop-btn').addEventListener('click', function() {
            if (cropper) {
                // Ambil area yang dicrop menjadi format base64
                const canvas = cropper.getCroppedCanvas({
                    width: 400, // Resolusi ideal
                    height: 400,
                });

                const base64Image = canvas.toDataURL('image/jpeg');

                // Ganti preview gambar di halaman
                document.getElementById('avatar-preview').src = base64Image;

                // Masukkan data base64 ke input hidden form
                document.getElementById('avatar_base64').value = base64Image;

                // Tutup modal
                cropModal.hide();
            }
        });
    </script>
@endpush
