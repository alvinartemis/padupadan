<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>The Moment of Truth!</title>
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
    </style>
</head>

<body>
    <div class="circle left"></div>
    <div class="circle right"></div>

    <div class="logo">
        <img src="{{ asset('img/logoy.png') }}" alt="Logo">
    </div>

    <div class="container">
        <h1 id="countdown-text">The moment of truth!</h1>
        <h1 id="countdown-phase-2" style="display: none;">Time to reveal your fashion identity</h1>
        <h1 id="countdown-number" style="display: none;"></h1>
    </div>
    <span id="result-url" data-url="{{ route('set_preference.result') }}"></span>


    <script>
        const countdownPhase1Element = document.getElementById('countdown-text');
        const countdownPhase2Element = document.getElementById('countdown-phase-2');
        const countdownNumberElement = document.getElementById('countdown-number');
        let countdown = 3;

        setTimeout(() => {
            countdownPhase1Element.style.display = 'none';
            countdownPhase2Element.style.display = 'block';

            setTimeout(() => {
                countdownPhase2Element.style.display = 'none';
                countdownNumberElement.style.display = 'block';
                updateCountdown();
            }, 2000);
        }, 2000);

        const updateCountdown = () => {
            countdownNumberElement.textContent = countdown > 0 ? countdown : "";
            if (countdown > 0) {
                countdown--;
                setTimeout(updateCountdown, 1000);
            } else {
                setTimeout(() => {
                    const resultUrl = document.getElementById('result-url').dataset.url;
                    window.location.href = resultUrl;
                }, 1000);
            }

        };
    </script>
</body>

</html>
