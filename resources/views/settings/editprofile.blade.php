@extends('layouts.settings')

@section('title', 'Edit Profile')

@section('content')

@php
    // Definisikan pilihan untuk dropdown di sini
    $genderOptions = [
        'male' => 'Male',
        'female' => 'Female',
        // Tambahkan 'other' atau pilihan lain jika diperlukan
    ];

    // Ambil dari $bodytypeIcons keys di file quiz Anda, dan tambahkan 'rectangle'
    $bodyTypeOptions = [
        'hourglass' => 'Hourglass',
        'apple' => 'Apple',
        'pear' => 'Pear',
        'triangle' => 'Triangle', // Anda punya 'triangel.png', saya asumsikan value-nya 'triangle'
        'round' => 'Round',
        'inverted_triangle' => 'Inverted Triangle', // Anda punya 'intri.png'
        'rectangle' => 'Rectangle', // Tambahan berdasarkan quiz (mrec.png, frec.png)
        // Tambahkan opsi lain jika ada
    ];

    // Ambil dari $skinColors keys di file quiz Anda
    $skinToneOptions = [
        'cool' => 'Cool',
        'warm' => 'Warm',
        'neutral' => 'Neutral',
        'olive' => 'Olive',
    ];

    // Ambil dari $styleImages keys di file quiz Anda, tambahkan 'chic'
    $fashionStyleOptions = [
        'casual' => 'Casual',
        'formal' => 'Formal',
        'unique' => 'Unique',
        'stylish' => 'Stylish',
        'chic' => 'Chic', // Tambahan dari contoh data sebelumnya
        // Tambahkan opsi lain jika ada
    ];
@endphp

<style>
       /* Tambahan CSS untuk styling profile picture upload */
    /* Kontainer baru untuk mengatur posisi ikon edit di luar lingkaran gambar */
    .profile-picture-wrapper {
        position: relative; /* Penting untuk posisi absolute ikon */
        width: 120px; /* Ukuran gambar */
        height: 120px; /* Ukuran gambar */
        margin: 0 auto 1rem auto; /* Memusatkan wrapper */
    }

    .profile-picture-container {
        position: relative; /* Tetap relatif untuk gambar di dalamnya */
        width: 100%; /* Mengisi wrapper */
        height: 100%; /* Mengisi wrapper */
        border-radius: 50%;
        overflow: hidden; /* Tetap hidden untuk gambar di dalam lingkaran */
        border: 2px solid #ccc;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-picture-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .edit-icon-overlay {
        position: absolute;
        bottom: 0; /* Sesuaikan posisi ikon pensil relatif terhadap wrapper */
        right: 0; /* Sesuaikan posisi ikon pensil relatif terhadap wrapper */
        background-color: #F4BC43; /* Warna latar ikon */
        border-radius: 50%;
        width: 35px; /* Ukuran ikon */
        height: 35px; /* Ukuran ikon */
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: background-color 0.3s ease;
        /* Tambahan: Geser sedikit keluar dari lingkaran */
        transform: translate(10%, 10%); /* Geser 10% dari lebar/tinggi ikon */
    }

    .edit-icon-overlay:hover {
        background-color: #e0a830; /* Warna hover */
    }

    .edit-icon-overlay svg {
        fill: white; /* Warna ikon SVG */
        width: 18px; /* Ukuran ikon di dalam overlay */
        height: 18px;
    }

    /* Sembunyikan input file asli */
    #profile_picture {
        display: none;
    }

    /* Style untuk modal overlay dan konten */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none; /* Sembunyikan secara default */
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        text-align: center;
        max-width: 400px;
        width: 90%;
    }

    .modal-content h2 {
        margin-top: 0;
        color: #333;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .modal-buttons button {
        background-color: #F4BC43;
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
        margin: 0 10px;
    }

    .modal-buttons button:hover {
        background-color: #e0a830;
    }
</style>

<h2 style="text-align: center; margin-bottom: 2rem;">Account Center</h2>

{{-- Form untuk mengupdate profil, termasuk upload foto --}}
<form id="editForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
      style="max-width: 800px; margin: 0 auto; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
    @csrf
    @method('PUT')

    {{-- Bagian untuk Foto Profil dengan ikon edit --}}
    <div style="margin-bottom: 1.5rem; text-align: center;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Profile Photo</label>
            <div class="profile-picture-wrapper">
            <div class="profile-picture-container" id="profilePictureContainer">
                @if($user->profilepicture)
                    <img src="{{ Storage::url($user->profilepicture) }}" alt="Current Profile" id="profileImagePreview">
                @else
                    <img src="{{ asset('images/default_profile.png') }}" alt="Default Profile" id="profileImagePreview">
                @endif
            </div>
            <div class="edit-icon-overlay" id="editIconOverlay">
                <!-- Ikon pensil SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </div>
        </div>
        {{-- Input file asli yang disembunyikan --}}
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
        @error('profile_picture')
            <span style="color:red; font-size:0.8em; display: block; margin-top: 0.5rem;">{{ $message }}</span>
        @enderror
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->nama) }}"
               style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        @error('name') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label for="username" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Username</label>
        <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}"
               style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        @error('username') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
               style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        @error('email') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">New Password (leave blank if no change)</label>
        <input id="password" name="password" type="password" placeholder="Enter new password"
               style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        @error('password') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>
    <div style="margin-bottom: 1.5rem;">
        <label for="password_confirmation" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Confirm New Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm new password"
               style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
    </div>

    {{-- Gender Dropdown --}}
    <div style="margin-bottom: 1.5rem;">
        <label for="gender" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Gender</label>
        <select id="gender" name="gender" style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
            <option value="">Select Gender</option>
            @foreach ($genderOptions as $value => $label)
                <option value="{{ $value }}" {{ old('gender', $user->gender) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('gender') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>

    {{-- Body Type Dropdown --}}
    <div style="margin-bottom: 1.5rem;">
        <label for="body_type" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Body Type</label>
        <select id="body_type" name="body_type" style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
            <option value="">Select Body Type</option>
            @foreach ($bodyTypeOptions as $value => $label)
                <option value="{{ $value }}" {{ old('body_type', $user->bodytype) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('body_type') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>

    {{-- Skin Tone Dropdown --}}
    <div style="margin-bottom: 1.5rem;">
        <label for="skin_tone" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Skin Tone</label>
        <select id="skin_tone" name="skin_tone" style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
            <option value="">Select Skin Tone</option>
            @foreach ($skinToneOptions as $value => $label)
                <option value="{{ $value }}" {{ old('skin_tone', $user->skintone) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('skin_tone') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>

    {{-- Fashion Style Dropdown --}}
    <div style="margin-bottom: 1.5rem;">
        <label for="fashion_style" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Fashion Style</label>
        <select id="fashion_style" name="fashion_style" style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
            <option value="">Select Fashion Style</option>
            @foreach ($fashionStyleOptions as $value => $label)
                <option value="{{ $value }}" {{ old('fashion_style', $user->style) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('fashion_style') <span style="color:red; font-size:0.8em;">{{ $message }}</span> @enderror
    </div>

    <div style="text-align: center; margin-top: 2rem;">
        <button type="submit"
                style="
                    padding: 12px 30px;
                    background-color: #F4BC43;
                    color: white;
                    font-weight: 600;
                    border: none;
                    border-radius: 25px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                    width: auto;
                    min-width: 120px;
                ">
            Save
        </button>
    </div>
</form>

{{-- Modal Konfirmasi --}}
<div id="confirmationModal" class="modal-overlay">
    <div class="modal-content">
        <h2>Are you sure to save?</h2>
        <div class="modal-buttons">
            <button id="confirmYesBtn">Yes</button>
            <button id="confirmNoBtn">No</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editForm = document.getElementById('editForm');
        const modal = document.getElementById('confirmationModal');

        // Logic untuk upload foto profil
        const profilePictureInput = document.getElementById('profile_picture');
        const profileImagePreview = document.getElementById('profileImagePreview');
        const editIconOverlay = document.getElementById('editIconOverlay');

        if (editIconOverlay && profilePictureInput && profileImagePreview) {
            editIconOverlay.addEventListener('click', function() {
                profilePictureInput.click(); // Memicu klik pada input file tersembunyi
            });

            profilePictureInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImagePreview.src = e.target.result; // Menampilkan preview gambar
                    }
                    reader.readAsDataURL(file);
                }
            });
        }


        if (editForm && modal) {
            const confirmYesBtn = document.getElementById('confirmYesBtn');
            const confirmNoBtn = document.getElementById('confirmNoBtn');

            editForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Mencegah form submit langsung
                modal.style.display = 'flex'; // Tampilkan modal
            });

            if (confirmYesBtn) {
                confirmYesBtn.addEventListener('click', function () {
                    modal.style.display = 'none'; // Sembunyikan modal
                    editForm.submit(); // Submit form jika user mengkonfirmasi
                });
            }

            if (confirmNoBtn) {
                confirmNoBtn.addEventListener('click', function () {
                    modal.style.display = 'none'; // Sembunyikan modal
                });
            }

            window.addEventListener('click', function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none'; // Sembunyikan modal jika klik di luar
                }
            });
        } else {
            if (!editForm) console.error("Form dengan ID 'editForm' tidak ditemukan.");
            if (!modal) console.error("Modal dengan ID 'confirmationModal' tidak ditemukan.");
        }
    });
</script>

@endsection
