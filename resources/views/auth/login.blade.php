<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="icon" href="{{ asset('img/logoy.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('{{ asset('img/bgr.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 2rem;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 2rem;
        }
        .container {
            max-width: 450px;
            padding: 2rem;
            background-color: #F4F4F4;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
        }
        h2 {
            font-size: 3rem;
            font-weight: 700;
            color: #173F63;
            margin-bottom: 1rem;
            text-align: center;
        }
        p {
            color: #173F63;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 1rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #173F63;
            margin-bottom: 0.5rem;
        }
        input {
            width: 100%;
            height: 3rem;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
            transition: border-color 0.2s ease-in-out;
            color: #1a202c;
            box-sizing: border-box;
        }
        input:focus {
            padding: 1rem;
            outline: none;
            border-color: #173F63;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }
        .userinput{
            padding-left: 1rem;
        }
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-input {
            width: 100%;
            padding-right: 2.5rem;
        }
        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            width: 1rem;
            height: 1rem;
        }
        .login-button {
            background-color: white;
            color: #173F63;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            transition: background-color 0.1s ease-in-out;
            text-lg;
            margin: 0 auto;
            display: block;
            margin-top: 2rem;
        }
        .login-button:hover {
            background-color: #173F63;
            color: white;
        }
        .register-link {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #4a5568;
        }
        .register-link a {
            color: #173F63;
        }
        .register-link a:hover {
            font-weight: 600;
            text-decoration: underline;
        }
        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            position: static;
            left: 0;
            bottom: -2.25rem;
            width: 100%;
            text-align: left;
        }
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
            min-height: 5.5rem;
        }
        input::placeholder {
          padding-left: 0.5rem;
        }

        .back-button {
        position: absolute;
        top: 2rem;
        left: 2rem;
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: background-color 0.2s ease-in-out;
        z-index: 10;
        fill: #173F63
        }

        .back-button:hover {
            background-color: #F4BC43;
        }
        .back-button:hover svg {
            fill: white;
        }

    </style>
</head>
<body class="bg-gray-100">
    <button onclick="history.back()" class="back-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width: 20px; height: 20px;">
            <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
        </svg>
    </button>
    <img src="{{ asset('img/alogo.png') }}" alt="Padu Padan Logo" class="logo">
    <div class="container">
        <h2 style="font-size: 3rem; font-weight: 700;">Hello!</h2>
        <p>Welcome back! You've been missed</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Your Username" value="{{ old('username') }}" class="userinput">
                @error('username')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="password-input" placeholder="Your Password" class="userinput">
                    <img src="{{ asset('img/EyeSlash.png') }}" alt="Show Password" class="toggle-password" id="toggle-password">
                </div>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="login-button mb-4">Log In</button>
        </form>
        <p class="register-link">
            <a href="{{ url('/forgot-password') }}">Forgot Password</a>
        </p>
        <p class="register-link">
            Don't have an account? <a href="{{ url('/register') }}">Sign Up</a>
        </p>
    </div>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('toggle-password');
        const loginForm = document.getElementById('login-form'); // Get the form element

        togglePasswordButton.addEventListener('click', function() {
            const passwordVisible = passwordInput.type === 'password';
            passwordInput.type = passwordVisible ? 'text' : 'password';
            togglePasswordButton.src = passwordVisible ? "{{ asset('img/Eye.png') }}" : "{{ asset('img/EyeSlash.png') }}";
        });

        // Get all input elements
        const inputs = document.querySelectorAll('input');

        // Loop through each input
        inputs.forEach(input => {
            // Get the corresponding error message element
            const errorElementId = `${input.id}-error`;
            const errorElement = document.getElementById(errorElementId);

            if (errorElement) {
                // Add a listener to the input's focus event
                input.addEventListener('focus', () => {
                    // When the input is focused, clear the placeholder text
                    input.placeholder = '';
                    input.style.color = '#1a202c';
                });

                input.addEventListener('blur', () => {
                    if (!input.value && errorElement.textContent) {
                        input.placeholder = '';
                        input.style.color = '#1a202c';
                    } else if (!input.value) {
                        input.placeholder = input.name.charAt(0).toUpperCase() + input.name.slice(1);
                        input.style.color = '#1a202c';
                    }
                });
            } else {
                input.addEventListener('focus', () => {
                    input.placeholder = '';
                    input.style.color = '#1a202c';
                });

                input.addEventListener('blur', () => {
                    if (!input.value) {
                        input.placeholder = input.name.charAt(0).toUpperCase() + input.name.slice(1);
                        input.style.color = '#1a202c';
                    }
                });
            }
        });
    </script>
</body>
</html>
