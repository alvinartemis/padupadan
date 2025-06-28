<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Penting: Import fasad Storage
use Illuminate\Validation\Rule;

class EditProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function editprofile()
    {
        // Menggunakan fasad Auth untuk mendapatkan instance pengguna yang sedang login
        $user = Auth::user();
        // Pastikan view ini ada di: resources/views/settings/editprofile.blade.php
        return view('settings.editprofile', compact('user'));
    }

    /**
     * Memperbarui informasi profil pengguna, termasuk foto profil.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Mendapatkan instance pengguna yang sedang login
        $user = Auth::user();

        // Definisikan opsi yang valid untuk validasi Rule::in()
        // Ini HARUS SAMA dengan keys dari array options yang Anda definisikan di editprofile.blade.php
        $genderOptions = ['male', 'female'];
        $bodyTypeOptions = ['hourglass', 'apple', 'pear', 'triangle', 'round', 'inverted_triangle', 'rectangle'];
        $skinToneOptions = ['cool', 'warm', 'neutral', 'olive'];
        $fashionStyleOptions = ['casual', 'formal', 'unique', 'stylish', 'chic'];

        // Validasi data yang masuk dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                // Pastikan 'idPengguna' adalah nama primary key di tabel 'pengguna'
                // Sesuaikan 'pengguna' dengan nama tabel aktual Anda jika berbeda (misal: 'users')
                Rule::unique('pengguna', 'username')->ignore($user->idPengguna, 'idPengguna')
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('pengguna', 'email')->ignore($user->idPengguna, 'idPengguna')
            ],
            // 'confirmed' akan memeriksa field 'password_confirmation'
            'password' => 'nullable|string|min:6|confirmed',
            'gender' => ['nullable', 'string', Rule::in($genderOptions)],
            'body_type' => ['nullable', 'string', Rule::in($bodyTypeOptions)],
            'skin_tone' => ['nullable', 'string', Rule::in($skinToneOptions)],
            'fashion_style' => ['nullable', 'string', Rule::in($fashionStyleOptions)],
            // Aturan validasi untuk file gambar profil
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB, tambahkan svg
        ]);

        // Inisialisasi payload data yang akan diupdate ke database
        // Kunci array ini HARUS sesuai dengan nama kolom di database Anda
        $updatePayload = [
            'nama' => $validatedData['name'],          // form 'name' -> db 'nama'
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            // Gunakan nilai lama jika input kosong dan nullable, atau biarkan $validatedData yang mengatur (jika null, akan jadi null)
            'gender' => $validatedData['gender'] ?? $user->gender,
            'bodytype' => $validatedData['body_type'] ?? $user->bodytype,     // form 'body_type' -> db 'bodytype'
            'skintone' => $validatedData['skin_tone'] ?? $user->skintone,     // form 'skin_tone' -> db 'skintone'
            'style' => $validatedData['fashion_style'] ?? $user->style,      // form 'fashion_style' -> db 'style'
        ];

        // Jika password diisi di form, tambahkan ke payload update
        if (!empty($validatedData['password'])) {
            $updatePayload['password'] = Hash::make($validatedData['password']);
        }

        // Logika untuk upload foto profil
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            // Membuat nama unik untuk file agar tidak terjadi tumpang tindih
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Path penyimpanan: storage/app/public/profile_pictures/
            // Metode storeAs() akan menyimpan file dan mengembalikan path relatif
            $path = $file->storeAs('profile_pictures', $fileName, 'public');

            // Hapus gambar lama jika ada dan bukan gambar default
            // Anda perlu menentukan path/nama file gambar default jika ada.
            // Contoh: Jika gambar default tersimpan di 'public/images/default_profile.png',
            // dan di DB hanya disimpan 'images/default_profile.png'.
            // Atau jika defaultnya null atau string kosong.
            $defaultProfilePicturePath = 'default_profile.png'; // Ganti dengan path default yang Anda gunakan

            // Periksa apakah user memiliki foto profil lama dan bukan path default
            // Serta pastikan file tersebut memang ada sebelum mencoba menghapusnya
            if ($user->profilepicture && $user->profilepicture !== $defaultProfilePicturePath && Storage::disk('public')->exists($user->profilepicture)) {
                Storage::disk('public')->delete($user->profilepicture);
            }

            // Simpan path gambar baru ke kolom 'profilepicture' di database
            // Ini akan menggantikan path gambar lama
            $updatePayload['profilepicture'] = $path;
        }

        // Lakukan update pada instance pengguna yang sedang login
        $user->update($updatePayload);

        // Redirect kembali ke halaman edit profil dengan pesan sukses
        // Pastikan rute 'profile' sudah terdefinisi di web.php
        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
