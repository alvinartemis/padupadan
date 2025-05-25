<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import
use Illuminate\Support\Facades\Hash; // Untuk hashing password manual jika diperlukan
use Illuminate\Validation\Rule;     // Untuk aturan validasi yang lebih kompleks

class EditProfileController extends Controller
{
    public function editprofile()
    {
        $user = Auth::user(); // Menggunakan fasad Auth
        return view('settings.editprofile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            // 'name' adalah nama input di form
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pengguna', 'username')->ignore($user->idPengguna, 'idPengguna') // Pastikan username unik kecuali untuk user ini sendiri
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('pengguna', 'email')->ignore($user->idPengguna, 'idPengguna') // Pastikan email unik kecuali untuk user ini sendiri
            ],
            'password' => 'nullable|string|min:6|confirmed', // 'confirmed' akan memeriksa field 'password_confirmation'
            'gender' => 'nullable|string|max:10',
            'body_type' => 'nullable|string|max:50',
            'fashion_style' => 'nullable|string|max:100',
            'skin_tone' => 'nullable|string|max:50',
            // Tambahkan validasi untuk 'profilepicture' jika Anda menambahkannya
        ]);

        // Data yang akan diupdate ke database (kunci sesuai nama kolom DB)
        $updatePayload = [
            'nama' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'gender' => $validatedData['gender'] ?? null, // Gunakan null jika tidak ada
            'bodytype' => $validatedData['body_type'] ?? null,
            'skintone' => $validatedData['skin_tone'] ?? null,
            'style' => $validatedData['fashion_style'] ?? null,
        ];

        if (!empty($validatedData['password'])) {
            // Model User.php sudah memiliki $casts['password'] = 'hashed', jadi ini akan otomatis di-hash
            $updatePayload['password'] = $validatedData['password'];
        }

        // Logika untuk upload profilepicture bisa ditambahkan di sini
        // if ($request->hasFile('profilepicture')) {
        //     // Proses file dan simpan path ke $updatePayload['profilepicture']
        // }

        $user->update($updatePayload);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
        // Pastikan Anda memiliki route bernama 'profile.show' atau sesuaikan
    }

    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user')); // Pastikan view 'profile.blade.php' ada
    }
}
