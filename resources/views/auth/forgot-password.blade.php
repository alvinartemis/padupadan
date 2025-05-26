<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* ... (Style Anda dari sebelumnya tetap sama) ... */
        body { font-family: 'Poppins', sans-serif; background-image: url('{{ asset('img/bgr.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: 2rem; }
        .logo { width: 120px; height: auto; margin-bottom: 2rem; }
        .container { max-width: 450px; width: 100%; padding: 2rem; background-color: #F4F4F4; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-bottom: 2rem; }
        h2 { font-size: 2.5rem; font-weight: 700; color: #173F63; margin-bottom: 0.5rem; text-align: center; }
        p.instruction { color: #173F63; margin-bottom: 1.5rem; text-align: center; font-size: 0.9rem; line-height: 1.5; }
        label { display: block; font-size: 0.875rem; font-weight: 600; color: #173F63; margin-bottom: 0.5rem; }
        input[type="email"] { width: 100%; height: 3rem; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-size: 1rem; transition: border-color 0.2s ease-in-out; color: #1a202c; box-sizing: border-box; }
        input[type="email"]:focus { outline: none; border-color: #173F63; box-shadow: 0 0 0 3px rgba(23, 63, 99, 0.15); }
        .action-button { background-color: #173F63; color: white; font-family: 'Poppins', sans-serif; font-weight: 600; padding: 0.75rem 2rem; border-radius: 9999px; transition: background-color 0.1s ease-in-out; font-size: 1rem; margin: 0 auto; display: block; margin-top: 1.5rem; border: 1px solid #173F63; }
        .action-button:hover { background-color: #F4F4F4; color: #173F63; }
        .back-link { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #4a5568; }
        .back-link a { color: #173F63; }
        .back-link a:hover { font-weight: 600; text-decoration: underline; }
        .form-group { margin-bottom: 1.5rem; min-height: 4.5rem; position: relative; }
        .error-message { color: #e53e3e; font-size: 0.875rem; margin-top: 0.25rem; display: block; }
        .status-message { color: #38a169; background-color: #f0fff4; border: 1px solid #9ae6b4; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1rem; font-size: 0.9rem; text-align: center; }
    </style>
</head>
<body class="bg-gray-100">
    <img src="{{ asset('img/alogo.png') }}" alt="Padu Padan Logo" class="logo">
    <div class="container">
        <h2>Forgot Password?</h2>
        <p class="instruction">No worries, we'll help you reset it. Please enter the email address you used to sign up.</p>

        @if (session('status')) {{-- Ini untuk menampilkan pesan sukses setelah email valid dan "dikirim" --}}
            <div class="status-message">
                {{ session('status') }}
                {{-- Jika Anda ingin menampilkan OTP di sini setelah redirect, ini bukan tempat yang ideal.
                     Lebih baik di halaman OTP khusus. Pesan ini lebih cocok untuk "Link telah dikirim"
                     atau dalam kasus kita "Silakan masukkan kode OTP yang ditampilkan di halaman berikutnya." --}}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email.otp') }}"> {{-- ACTION DIUBAH --}}
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email', session('email_for_otp')) }}" required autofocus>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="action-button">
                Next
            </button>
        </form>
        <p class="back-link">
            <a href="{{ route('login') }}">Back to Log In</a>
        </p>
    </div>
</body>
</html>