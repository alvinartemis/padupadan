<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5;url={{ route('login') }}"> {{-- Meta refresh sebagai fallback --}}
    <title>Forgot Password</title>
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
            justify-content: center;
            padding: 2rem;
            color: #173F63;
            text-align: center;
        }
        .logo {
            width: 120px;
            height: auto;
            position: absolute;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
        }
        .status-badge {
            width: 100px;
            height: 100px;
            margin-bottom: 1.5rem;
            object-fit: contain;
        }
        h1 {
            font-size: 2.8rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.4);
        }
        p {
            color: #F0F0F0;
            margin-bottom: 2rem;
            font-size: 1.1rem;
            line-height: 1.6;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
            max-width: 500px;
        }
        .redirect-message {
            font-size: 0.9rem;
            color: #D1D5DB;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .redirect-message a {
            color: #F4F4F4;
            text-decoration: underline;
            font-weight: 600;
        }
        .redirect-message a:hover {
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <img src="{{ asset('img/alogo.png') }}" alt="Padu Padan Logo" class="logo">

    <img src="{{ asset('img/badge.png') }}" alt="Success Badge" class="status-badge">

    <h1>Password Changed Successfully!</h1>
    <p>Your password has been updated. You can now log in with your new password.</p>
    <p class="redirect-message">
        You will be redirected to the <a href="{{ route('login') }}">login page</a> in <span id="countdown">5</span> seconds...
    </p>

    <script>
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');

        const interval = setInterval(function() {
            seconds--;
            if (countdownElement) {
                countdownElement.textContent = seconds;
            }
            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('login') }}";
            }
        }, 1000);
    </script>
</body>
</html>