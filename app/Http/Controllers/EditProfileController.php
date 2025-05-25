<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import
// use Illuminate\Support\Facades\Hash; // Tidak wajib di sini jika model sudah pakai 'hashed' cast
use Illuminate\Validation\Rule;     // Untuk aturan validasi yang lebih kompleks
// use App\Models\User; // Tidak perlu import User jika menggunakan Auth::user() yang sudah merupakan instance model User

class EditProfileController extends Controller
{
    public function editprofile()
    {
        $user = Auth::user(); // Menggunakan fasad Auth
        // Pastikan view ini ada di: resources/views/settings/editprofile.blade.php
        return view('settings.editprofile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Definisikan opsi yang valid untuk validasi Rule::in()
        // Ini HARUS SAMA dengan keys dari array options yang Anda definisikan di editprofile.blade.php
        $genderOptions = ['male', 'female'];
        $bodyTypeOptions = ['hourglass', 'apple', 'pear', 'triangle', 'round', 'inverted_triangle', 'rectangle'];
        $skinToneOptions = ['cool', 'warm', 'neutral', 'olive'];
        $fashionStyleOptions = ['casual', 'formal', 'unique', 'stylish', 'chic'];

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                // Pastikan 'idPengguna' adalah nama primary key di tabel 'pengguna'
                Rule::unique('pengguna', 'username')->ignore($user->idPengguna, 'idPengguna')
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('pengguna', 'email')->ignore($user->idPengguna, 'idPengguna')
            ],
            'password' => 'nullable|string|min:6|confirmed', // 'confirmed' akan memeriksa field 'password_confirmation'
            'gender' => ['nullable', 'string', Rule::in($genderOptions)],
            'body_type' => ['nullable', 'string', Rule::in($bodyTypeOptions)],
            'skin_tone' => ['nullable', 'string', Rule::in($skinToneOptions)], // Nama input form adalah 'skin_tone'
            'fashion_style' => ['nullable', 'string', Rule::in($fashionStyleOptions)], // Nama input form adalah 'fashion_style'
            // Tambahkan validasi untuk 'profilepicture' jika Anda menambahkannya di form
            // 'profilepicture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Data yang akan diupdate ke database (kunci sesuai nama kolom DB)
        $updatePayload = [
            'nama' => $validatedData['name'], // form 'name' -> db 'nama'
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            // Gunakan nilai lama jika input kosong dan nullable, atau biarkan $validatedData yang mengatur (jika null, akan jadi null)
            'gender' => $validatedData['gender'] ?? $user->gender,
            'bodytype' => $validatedData['body_type'] ?? $user->bodytype,         // form 'body_type' -> db 'bodytype'
            'skintone' => $validatedData['skin_tone'] ?? $user->skintone,         // form 'skin_tone' -> db 'skintone'
            'style' => $validatedData['fashion_style'] ?? $user->style,     // form 'fashion_style' -> db 'style'
        ];

        if (!empty($validatedData['password'])) {
            // Model User.php Anda sebaiknya sudah memiliki $casts['password'] = 'hashed',
            // sehingga hashing terjadi otomatis saat nilai baru diset dan disimpan.
            $updatePayload['password'] = $validatedData['password'];
        }

        // Logika untuk upload profilepicture bisa ditambahkan di sini
        // if ($request->hasFile('nama_input_gambar_profil_anda')) { // ganti 'nama_input_gambar_profil_anda'
        //     // Hapus gambar lama jika ada dan bukan gambar default
        //     // if ($user->profilepicture && $user->profilepicture !== 'path/default.jpg') {
        //     //     Storage::disk('public')->delete($user->profilepicture);
        //     // }
        //     // Simpan gambar baru
        //     // $path = $request->file('nama_input_gambar_profil_anda')->store('profile_pictures', 'public');
        //     // $updatePayload['profilepicture'] = $path; // 'profilepicture' adalah nama kolom di DB
        // }

        $user->update($updatePayload);

        // Redirect kembali ke halaman edit profil dengan pesan sukses
        return redirect()->route('profile')->with('success', 'Profile updated successfully!');

    }
}
