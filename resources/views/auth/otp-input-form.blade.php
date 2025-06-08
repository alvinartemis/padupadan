<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="{{ asset('img/logoy.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-image: url('{{ asset('img/bgr.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: 2rem; }
        .logo { width: 120px; height: auto; margin-bottom: 2rem; }
        .container { max-width: 450px; width: 100%; padding: 2rem; background-color: #F4F4F4; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-bottom: 2rem; }
        h2 { font-size: 2.5rem; font-weight: 700; color: #173F63; margin-bottom: 0.5rem; text-align: center; }
        p.instruction { color: #173F63; margin-bottom: 1.5rem; text-align: center; font-size: 0.9rem; line-height: 1.5; }
        label { display: block; font-size: 0.875rem; font-weight: 600; color: #173F63; margin-bottom: 0.5rem; }
        input[type="text"], input[type="number"] { width: 100%; height: 3rem; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-size: 1rem; transition: border-color 0.2s ease-in-out; color: #1a202c; box-sizing: border-box; text-align: center; letter-spacing: 0.5em; }
        input[type="text"]:focus, input[type="number"]:focus { outline: none; border-color: #173F63; box-shadow: 0 0 0 3px rgba(23, 63, 99, 0.15); }
        .action-button { background-color: #173F63; color: white; font-family: 'Poppins', sans-serif; font-weight: 600; padding: 0.75rem 2rem; border-radius: 9999px; transition: background-color 0.1s ease-in-out; font-size: 1rem; margin: 0 auto; display: block; margin-top: 1.5rem; border: 1px solid #173F63; }
        .action-button:hover { background-color: #F4F4F4; color: #173F63; }
        .form-group { margin-bottom: 1.5rem; min-height: 4.5rem; position: relative; }
        .error-message { color: #e53e3e; font-size: 0.875rem; margin-top: 0.25rem; display: block; }
        .status-message { color: #38a169; background-color: #f0fff4; border: 1px solid #9ae6b4; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1rem; font-size: 0.9rem; text-align: center; }
        .otp-display-popup {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.1rem;
        }
        .otp-display-popup strong {
            font-size: 1.5rem;
            letter-spacing: 0.1em;
            display: block;
            margin-top: 0.5rem;
        }
        .resend-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        .resend-link a {
            color: #6B7280; 
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease, text-decoration 0.2s ease;
        }
        .resend-link a:hover {
            color: #0C2842;
            text-decoration: underline; 
        }

    </style>
</head>
<body class="bg-gray-100">
    <img src="{{ asset('img/alogo.png') }}" alt="Padu Padan Logo" class="logo">
    <div class="container">
        <h2>Enter OTP</h2>
        <p class="instruction">An OTP has been generated. Please enter the code below to proceed.</p>

        @if (session('otp_to_display'))
            <div class="otp-display-popup">
                Your One-Time Password (OTP) is:
                <strong>{{ session('otp_to_display') }}</strong>
                <p style="font-size:0.8em; margin-top:10px; margin-bottom:0;">This OTP is valid for 10 minutes.</p>
            </div>
        @endif

        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.otp.verify') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
            <div class="form-group">
                <label for="otp">One-Time Password (OTP)</label>
                <input type="text" id="otp" name="otp" placeholder="_ _ _ _ _ _" maxlength="6" pattern="\d{6}" inputmode="numeric" required>
                @error('otp')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="action-button">
                Verify OTP
            </button>
        </form>
         
        <p class="resend-link">
            <a href="{{ route('password.request') }}">Resend OTP / Change Email</a>
        </p>
    </div>
</body>
</html>