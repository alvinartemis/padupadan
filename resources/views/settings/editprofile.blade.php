@extends('settings')

@section('title', 'Edit Profile')

@section('content')
<h2 style="text-align: center; margin-bottom: 2rem;">Account Center</h2>

<div style="display: flex; justify-content: center; margin-bottom: 2rem;">
    <img src="{{ asset('bece.png') }}" alt="Profile Photo"
         style="border-radius: 50%; height: 100px; width: 100px; object-fit: cover; border: 2px solid #ccc;">
</div>

<form id="editForm" method="POST" action="{{ route('profile.update') }}"
      onsubmit="return confirmSubmit()"
      style="max-width: 800px; margin: 0 auto; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
    @csrf
    @method('PUT')

    <!-- Input Fields -->
        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Name</label>
            <input id="name" name="name" type="text" value="Blair Waldorf"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="username" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Username</label>
            <input id="username" name="username" type="text" value="BlairWaldorf"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email</label>
            <input id="email" name="email" type="email" value="blair@example.com"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Password</label>
            <input id="password" name="password" type="password" placeholder="Enter new password"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="gender" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Gender</label>
            <input id="gender" name="gender" type="text" value="Female"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="body_type" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Body Type</label>
            <input id="body_type" name="body_type" type="text" value="Hourglass"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="skin_tone" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Skin Tone</label>
            <input id="skin_tone" name="skin_tone" type="text" value="Warm"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="fashion_style" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Fashion Style</label>
            <input id="fashion_style" name="fashion_style" type="text" value="Chic"
                   style="width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff;">
        </div>
    <!-- ... (Input lainnya tetap seperti punyamu) ... -->

    <!-- Tombol Submit -->
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
        const confirmYesBtn = document.getElementById('confirmYesBtn');
        const confirmNoBtn = document.getElementById('confirmNoBtn');
        // const saveButton = document.getElementById('saveButton'); // Jika Anda menggunakan ID pada tombol save

        // Hilangkan onsubmit="return confirmSubmit()" dari tag form di atas
        // dan fungsi confirmSubmit() sebelumnya.

        editForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Mencegah form submit secara langsung
            modal.style.display = 'flex'; // Tampilkan modal
        });

        confirmYesBtn.addEventListener('click', function () {
            modal.style.display = 'none'; // Sembunyikan modal
            editForm.submit(); // Lanjutkan submit form
            // Redirect ke /profile akan diurus oleh response server setelah form disubmit
        });

        confirmNoBtn.addEventListener('click', function () {
            modal.style.display = 'none'; // Sembunyikan modal
        });

        // Opsional: Sembunyikan modal jika klik di luar area modal-content
        window.addEventListener('click', function (event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

@endsection
