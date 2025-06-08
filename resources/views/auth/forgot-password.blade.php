<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="{{ asset('img/logoy.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F4BC43;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .main-container {
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .back-button {
            position: absolute;
            top: 2rem;
            left: 2rem;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            transition: background-color 0.2s;
        }
        .back-button:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }
        .illustration {
            width: 180px;
            height: auto;
            margin: 0 auto 1.5rem;
        }
        h2 {
            font-size: 3.5rem;
            font-weight: 700; 
            color: #FFFFFF;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }
        p.instruction {
            color: #FFFFFF;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            font-size: 0.9rem;
            max-width: 350px;
            margin-left: auto;
            margin-right: auto;
        }
        label {
            display: block;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 500;
            color: #FFFFFF;
            margin-bottom: 0.5rem;
        }
        input[type="email"] {
            width: 100%;
            height: 3rem;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: none;
            background-color: #FDE68A;
            font-size: 1rem;
            color: #92400E;
            box-sizing: border-box;
            transition: box-shadow 0.2s ease-in-out;
        }
        input[type="email"]::placeholder {
            color: #B45309;
            opacity: 0.8;
        }
        input[type="email"]:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(253, 230, 138, 0.6);
        }
        .action-button {
            background-color: #FFFFFF;
            color: #F4BC43;
            font-weight: 700;
            padding: 0.85rem 2rem;
            border-radius: 0.75rem;
            transition: background-color 0.2s ease-in-out, transform 0.1s ease, color 0.2s ease;
            font-size: 1rem;
            width: 100%;
            display: block;
            margin-top: 1.5rem;
            border: 2px solid white;
        }
        .action-button:hover {
            background-color: transparent;
            color: #FFFFFF;
        }
        .action-button:active {
            transform: scale(0.98);
        }
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .error-message, .status-message {
            text-align: left;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
            width: 100%;
        }
        .error-message {
            color: #B91C1C;
            background-color: #FFE4E6;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        .status-message {
            color: #065F46;
            background-color: #D1FAE5;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <a href="{{ route('login') }}" class="back-button" title="Back to Login">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    </a>

    <div class="main-container">
        <img src="{{ asset('img/Binocular1.png') }}" alt="Forgot Password Illustration" class="illustration">
        <h2>Forgot Your Password?</h2>
        <p class="instruction">Please enter your email address below. We will send an OTP code to your email.</p>

        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email.otp') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="example@email.com" value="{{ old('email', session('email_for_otp')) }}" required autofocus>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="action-button">
                Next
            </button>
        </form>
    </div>
</body>
</html>