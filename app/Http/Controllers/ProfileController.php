<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Cek role user yang login
        $viewPath = $request->user()->role === 'admin' ? 'admin.profile.edit' : 'customer.profile.edit';

        return view($viewPath, [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Update data standar dari form
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 2. Logika Menangani Crop Avatar Base64
        if ($request->filled('avatar_base64')) {
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $base64Image = $request->avatar_base64;
            $imageParts = explode(';base64,', $base64Image);
            $imageTypeAux = explode('image/', $imageParts[0]);
            $imageType = $imageTypeAux[1]; 
            $imageBase64 = base64_decode($imageParts[1]);

            $fileName = 'avatars/' . Str::random(20) . '.' . $imageType;
            Storage::disk('public')->put($fileName, $imageBase64);
            $user->avatar = $fileName;
        }

        $user->save();

        // ==============================================================
        // PERBAIKAN REDIRECT BERDASARKAN ROLE
        // ==============================================================
        $routeName = $user->role === 'admin' ? 'admin.profile.edit' : 'customer.profile.edit';

        return Redirect::route($routeName)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
