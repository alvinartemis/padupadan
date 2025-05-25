<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padu Padan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .splash-container {
            background-image: url('{{ asset('img/bg.png') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
            background-color: #f8fafc;
        }

        .splash-container.hidden {
            display: none;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .bg-custom {
            background-image: url('{{ asset('img/bg.png') }}');
            background-size: cover;
            background-position: center;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 2rem;
        }
        .account-text {
            color: #173F63;
        }
        .signup-button {
            background-color: white;
            color: #173F63;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            transition: background-color 0.1s ease-in-out;
            text-lg;
        }

        .signup-button:hover {
            background-color: #173F63;
            color: white;
        }

        .body-text{
            font-family: 'Poppins', sans-serif;
        }

        .account-link {
            color: #173F63;
            cursor: pointer;
        }

        .account-link:hover,
        .account-link:active {
            text-decoration: underline;
            font-weight: 700;
        }

    </style>
</head>
<body>
    <div class="splash-container" id="splash">
        <img src="{{ asset('img/alogo.png') }}" alt="Padu Padan Logo" class="logo">
    </div>

    <div class="main-content" id="main-content">
        <div class="bg-custom flex justify-center items-center min-h-screen"">
            <div class="container flex flex-col items-center">
                <img src="{{ asset('img/logo.png') }}" alt="Padu Padan Logo" class="logo">
                <a href="{{ url('/register') }}" class="signup-button mb-4">
                    Sign Up
                </a>
                <p class="account-text text-sm text-center">
                    Already have an account?
                    <a href="{{ url('/login') }}" class="account-link">Sign In</a>
                </p>
                <p class="account-text text-sm text-center">
                    <a href="{{ route('stylist.login') }}" class="account-link">Sign In as Stylist</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const splash = document.getElementById('splash');
        const mainContent = document.getElementById('main-content');

        window.onload = function() {
            setTimeout(function() {
                splash.style.opacity = 0;
                setTimeout(function() {
                    splash.style.display = 'none';
                    mainContent.style.opacity = 1;
                }, 1000);
            }, 1000);
        };
    </script>
</body>
</html>
