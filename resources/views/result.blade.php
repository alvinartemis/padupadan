<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Your Style!</title>
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
            text-align: center;
            z-index: 1;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #173F63;
            margin-bottom: 1rem;
        }

        p {
            color: #173F63;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .start-quiz-button {
            background-color: white;
            color: #173F63;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 24px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .start-quiz-button:hover,
        .start-quiz-button:focus {
            background-color: #173F63;
            color: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(23, 63, 99, 0.2);
        }
    </style>
</head>
<body>

<div class="circle left"></div>
<div class="circle right"></div>

<div class="logo">
    <img src="img/logoy.png" alt="Logo">
</div>

<div class="container">
    <div class="mb-8">
        <img src="{{ $resultImage }}" alt="{{ $resultTitle }}" class="max-w-md rounded-lg shadow-md">
        <p class="text-gray-600 mt-4">{{ $resultDescription }}</p>
    </div>
</div>

</body>
</html>

