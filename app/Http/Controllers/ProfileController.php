<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman Edit Profile (gaya Itemku).
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Simpan pembaruan profil ke database.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone_number'  => ['nullable', 'string', 'max:25'],
            'gender'        => ['nullable', 'string', 'in:Laki-laki,Perempuan,Lainnya,Male,Female,Other'],
            'date_of_birth' => ['nullable', 'date'],
            'avatar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
        ], [
            'name.required'     => 'Nama tidak boleh kosong.',
            'avatar.image'      => 'File foto profil harus berupa gambar.',
            'avatar.mimes'      => 'Format foto harus berupa .JPG, .PNG, atau .GIF.',
            'avatar.max'        => 'Ukuran foto maksimal 5MB.',
            'date_of_birth.date'=> 'Format tanggal lahir tidak valid.',
        ]);

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan avatar baru
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name          = $validated['name'];
        $user->phone_number  = $validated['phone_number'] ?? null;
        $user->gender        = $validated['gender'] ?? null;
        $user->date_of_birth = $validated['date_of_birth'] ?? null;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil disimpan dan diperbarui!');
    }
}
