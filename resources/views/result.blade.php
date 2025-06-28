<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Style Result!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background-color: white;
            z-index: 0;
        }

        .circle.left {
            width: 400px;
            height: 400px;
            bottom: -100px;
            left: -100px;
        }

        .circle.right {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 2;
        }

        .logo img {
            height: 60px;
        }

        .container {
            position: relative;
            z-index: 1;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .result-image {
            width: 300px; /* Adjust as needed */
            height: auto;
            margin-bottom: 2rem;
            border-radius: 8px; /* Optional: add some rounded corners */
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #173F63;
            margin-bottom: 1.5rem;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 2.5rem;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background-color: #FFA500; /* Your orange color */
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            background-color: #FF8C00; /* Darker orange on hover */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            p {
                font-size: 1rem;
            }
            .result-image {
                width: 250px;
            }
            .button {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.8rem;
            }
            p {
                font-size: 0.95rem;
            }
            .result-image {
                width: 200px;
            }
            .button {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
            .circle.left, .circle.right {
                width: 250px;
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="circle left"></div>
    <div class="circle right"></div>

    <div class="logo">
        <img src="{{ asset('img/logoy.png') }}" alt="Logo">
    </div>

    <div class="container">
        @if ($resultImage)
            <img src="{{ $resultImage }}" alt="{{ $resultTitle }}" class="result-image">
        @else
            <img src="{{ asset('img/default_result.png') }}" alt="Default Style" class="result-image">
        @endif
        <h1>{{ $resultTitle }}</h1>
        <p>{!! nl2br(e($resultDescription)) !!}</p> {{-- Use {!! !!} for HTML content if needed, and nl2br for line breaks --}}
        <a href="{{ route('home') }}" class="button">I'm Ready!</a>
    </div>
</body>
</html>
