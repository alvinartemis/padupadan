<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-image: url('{{ asset('img/bgr.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: 2rem; }
        .logo { width: 120px; height: auto; margin-bottom: 2rem; }
        .container { max-width: 450px; width: 100%; padding: 2rem; background-color: #F4F4F4; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-bottom: 2rem; }
        h2 { font-size: 2.5rem; font-weight: 700; color: #173F63; margin-bottom: 0.5rem; text-align: center; }
        p.instruction { color: #173F63; margin-bottom: 1.5rem; text-align: center; font-size: 0.9rem; line-height: 1.5; }
        label { display: block; font-size: 0.875rem; font-weight: 600; color: #173F63; margin-bottom: 0.5rem; }
        .input-group { position: relative; margin-bottom: 1.5rem; min-height: 5.5rem; }
        input[type="password"], input[type="text"] { 
            width: 100%;
            height: 3rem;
            padding: 0.75rem 1rem;
            padding-right: 2.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
            transition: border-color 0.2s ease-in-out;
            color: #1a202c;
            box-sizing: border-box;
        }
        input:focus { outline: none; border-color: #173F63; box-shadow: 0 0 0 3px rgba(23, 63, 99, 0.15); }
        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: calc(50% - 0.5rem);
            cursor: pointer;
            width: 1rem;
            height: 1rem;
        }
        .action-button { background-color: #173F63; color: white; font-family: 'Poppins', sans-serif; font-weight: 600; padding: 0.75rem 2rem; border-radius: 9999px; transition: background-color 0.1s ease-in-out, opacity 0.2s ease; font-size: 1rem; margin: 0 auto; display: block; margin-top: 1.5rem; border: 1px solid #173F63; }
        .action-button:hover:not(:disabled) { background-color: #F4F4F4; color: #173F63; }
        .action-button:disabled { background-color: #aaa; cursor: not-allowed; opacity: 0.7; }
        .error-message { color: #e53e3e; font-size: 0.875rem; margin-top: 0.25rem; display: block; }
        .password-match-warning { color: #dd6b20; font-size: 0.875rem; margin-top: 0.25rem; display: none; /* Sembunyikan default */ }
        .password-match-success { color: #38a169; font-size: 0.875rem; margin-top: 0.25rem; display: none; /* Sembunyikan default */ }
    </style>
</head>
<body class="bg-gray-100">
    <img src="{{ asset('img/alogo.png') }}" alt="Padu Padan Logo" class="logo">
    <div class="container">
        <h2>Set New Password</h2>
        <p class="instruction">Create a new password for your account. Make sure it's strong and memorable.</p>

        <form method="POST" action="{{ route('password.update.new') }}" id="resetPasswordForm">
            @csrf
            {{-- Input tersembunyi untuk email, diambil dari session atau request sebelumnya --}}
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="input-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required>
                <img src="{{ asset('img/EyeSlash.png') }}" alt="Toggle Password Visibility" class="toggle-password" id="togglePassword">
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
                <img src="{{ asset('img/EyeSlash.png') }}" alt="Toggle Password Visibility" class="toggle-password" id="togglePasswordConfirmation">
                <span id="passwordMatchWarning" class="password-match-warning">Passwords do not match.</span>
                <span id="passwordMatchSuccess" class="password-match-success">Passwords match.</span>
            </div>

            <button type="submit" class="action-button" id="savePasswordButton">
                Save New Password
            </button>
        </form>
    </div>

{{-- Bagian HTML dan CSS Anda dari prompt terakhir tetap sama --}}
{{-- ... (HTML Head, Body, Form, dll.) ... --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Reset Password Script V2: DOM Loaded.');

    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const togglePasswordButton = document.getElementById('togglePassword');
    const togglePasswordConfirmationButton = document.getElementById('togglePasswordConfirmation');
    const passwordMatchWarning = document.getElementById('passwordMatchWarning');
    const passwordMatchSuccess = document.getElementById('passwordMatchSuccess');
    const savePasswordButton = document.getElementById('savePasswordButton');

    // Pastikan semua elemen penting ditemukan
    if (!passwordInput) console.error("JS ERROR: passwordInput not found");
    if (!passwordConfirmationInput) console.error("JS ERROR: passwordConfirmationInput not found");
    if (!togglePasswordButton) console.error("JS ERROR: togglePasswordButton not found");
    if (!togglePasswordConfirmationButton) console.error("JS ERROR: togglePasswordConfirmationButton not found");
    if (!passwordMatchWarning) console.error("JS ERROR: passwordMatchWarning not found");
    if (!passwordMatchSuccess) console.error("JS ERROR: passwordMatchSuccess not found");
    if (!savePasswordButton) console.error("JS ERROR: savePasswordButton not found");

    function toggleVisibility(inputField, toggleButton) {
        if (!inputField || !toggleButton) {
            console.error('Toggle visibility: inputField or toggleButton is missing.');
            return;
        }
        const isPassword = inputField.type === 'password';
        inputField.type = isPassword ? 'text' : 'password';
        toggleButton.src = isPassword ? "{{ asset('img/Eye.png') }}" : "{{ asset('img/EyeSlash.png') }}";
        console.log('Toggled visibility for:', inputField.id, 'New type:', inputField.type);
    }

    if (togglePasswordButton && passwordInput) {
        togglePasswordButton.addEventListener('click', function() {
            console.log('Toggle password icon clicked');
            toggleVisibility(passwordInput, this);
        });
    }
    if (togglePasswordConfirmationButton && passwordConfirmationInput) {
        togglePasswordConfirmationButton.addEventListener('click', function() {
            console.log('Toggle password confirmation icon clicked');
            toggleVisibility(passwordConfirmationInput, this);
        });
    }

    function checkPasswordMatch() {
        if (!passwordInput || !passwordConfirmationInput || !passwordMatchWarning || !passwordMatchSuccess || !savePasswordButton) {
            console.error("Password matching check aborted: Critical elements not found.");
            return;
        }

        const pass = passwordInput.value;
        const confirmPass = passwordConfirmationInput.value;
        let enableSaveButton = false;

        passwordMatchWarning.style.display = 'none';
        passwordMatchSuccess.style.display = 'none';
        passwordMatchWarning.textContent = 'Passwords do not match.';


        if (pass.length > 0 && confirmPass.length > 0) {
            if (pass === confirmPass) {
                passwordMatchSuccess.textContent = 'Passwords match.';
                passwordMatchSuccess.style.display = 'block';
                enableSaveButton = true;
                console.log('Passwords match. Save button will be enabled.');
            } else {
                passwordMatchWarning.style.display = 'block';
                enableSaveButton = false;
                console.log('Passwords do NOT match. Save button will be disabled.');
            }
        } else {
            enableSaveButton = false;
            if (confirmPass.length > 0 && pass.length === 0) {
                 passwordMatchWarning.textContent = 'Please enter your new password first.';
                 passwordMatchWarning.style.display = 'block';
                 console.log('New password empty, confirm password has value. Save button disabled.');
            } else {
                console.log('One or both password fields are empty. Save button disabled.');
            }
        }
        
        savePasswordButton.disabled = !enableSaveButton;
        console.log('Save button disabled state:', savePasswordButton.disabled);
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', checkPasswordMatch);
        console.log('Event listener "input" added to passwordInput.');
    }
    if (passwordConfirmationInput) {
        passwordConfirmationInput.addEventListener('input', checkPasswordMatch);
        console.log('Event listener "input" added to passwordConfirmationInput.');
    }

    checkPasswordMatch();
    console.log('Initial checkPasswordMatch called on page load.');
});
</script>
</body>
</html>