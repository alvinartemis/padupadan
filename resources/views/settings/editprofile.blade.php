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

<h2 style="text-align: center; margin-bottom: 2rem;">Account Center</h2>

<div style="display: flex; justify-content: center; margin-bottom: 2rem;">
    <img src="{{ $user->profilepicture ? asset('storage/' . $user->profilepicture) : asset('bece.png') }}" alt="Profile Photo"
         style="border-radius: 50%; height: 100px; width: 100px; object-fit: cover; border: 2px solid #ccc;">
</div>

<form id="editForm" method="POST" action="{{ route('profile.update') }}"
      style="max-width: 800px; margin: 0 auto; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
    @csrf
    @method('PUT')

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

        if (editForm && modal) {
            const confirmYesBtn = document.getElementById('confirmYesBtn');
            const confirmNoBtn = document.getElementById('confirmNoBtn');

            editForm.addEventListener('submit', function (event) {
                event.preventDefault();
                modal.style.display = 'flex';
            });

            if (confirmYesBtn) {
                confirmYesBtn.addEventListener('click', function () {
                    modal.style.display = 'none';
                    editForm.submit();
                });
            }

            if (confirmNoBtn) {
                confirmNoBtn.addEventListener('click', function () {
                    modal.style.display = 'none';
                });
            }

            window.addEventListener('click', function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        } else {
            if (!editForm) console.error("Form dengan ID 'editForm' tidak ditemukan.");
            if (!modal) console.error("Modal dengan ID 'confirmationModal' tidak ditemukan.");
        }
    });
</script>

@endsection
