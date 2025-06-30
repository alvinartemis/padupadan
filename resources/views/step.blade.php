<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Quiz</title>


    <link rel="icon" href="{{ asset('img/logoy.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/set-preference.css') }}">
    <style>
        .intro-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            text-align: center;
            padding: 20px;
        }
        .intro-container h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #173F63;
            margin-bottom: 0.5rem;
        }
        .intro-container p {
            font-size: 1.2rem;
            color: #173F63;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        .intro-button {
            background-color: white;
            color: #F4BC43;
            font-weight: 700;
            font-size: 1.2rem;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.15);
            border: none;
            cursor: pointer;
        }
        .intro-button:hover {
            background-color: #173F63;
            color: white;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }


        .intro-step .progress-container {
            display: none;
        }
    </style>
</head>


<body class="{{ $step === 'intro' ? 'intro-step' : '' }}">
    <div class="circle left"></div>
    <div class="circle right"></div>


    <div class="logo">
        <img src="{{ asset('img/logoy.png') }}" alt="Logo">
    </div>


    @if ($step === 'intro')
        <div class="intro-container">
            <h1>Find Your Style!</h1>
            <p>What's Your Style Profile? Take the Personal Analysis Quiz to Find Out!</p>
            <form method="POST" action="{{ route('set_preference.save_step', ['step' => 'intro']) }}">
                @csrf
            <button type="submit" class="intro-button">Start Quiz!</button>
    </form>
        </div>
    @else
        <div class="progress-container">
            <div class="progress-bar {{ $step }}"></div>
        </div>


        <div class="container">
            <h1>Your {{ ucfirst($step) }}</h1>
            <p>Please select your {{ $step }}</p>


            <form method="POST" action="{{ route('set_preference.save_step', ['step' => $step]) }}" class="flex flex-col items-center">
                @csrf


                <div class="{{ $step === 'gender' ? 'grid-cols-2' : 'grid-cols-4' }} gap-6 mb-8">
                    @php
                        $genderIcons = [
                            'male' => asset('img/male.png'),
                            'female' => asset('img/female.png'),
                        ];


                        $maleBodytypeIcons = [
                            'triangle'          => asset('img/triangle.png'),
                            'round'             => asset('img/round.png'),
                            'inverted_triangle' => asset('img/intri.png'),
                            'rectangular'       => asset('img/mrec.png'),
                        ];


                        $femaleBodytypeIcons = [
                            'hourglass' => asset('img/hourglass.png'),
                            'apple'     => asset('img/apple.png'),
                            'pear'      => asset('img/pear.png'),
                            'rectangular' => asset('img/frec.png'),
                        ];


                        $skinColors = [
                            'cool' => asset('img/cool.png'),
                            'warm' => asset('img/warm.png'),
                            'neutral' => asset('img/neutral.png'),
                            'olive' => asset('img/olive.png'),
                        ];
                        $styleImages = [
                            'casual' => asset('img/casual.png'),
                            'formal' => asset('img/formal.png'),
                            'unique' => asset('img/unique.png'),
                            'stylish' => asset('img/stylish.png'),
                        ];
                    @endphp


                    @foreach ($options as $key => $option)
                        <label class="label-option">
                            <input type="radio" name="{{ $step }}" value="{{ $option }}" class="radio-input" {{ session('quiz.' . $step) == $option ? 'checked' : '' }}>
                            <div class="option-circle">
                                @if ($step === 'gender')
                                    <img src="{{ $genderIcons[$option] }}" alt="{{ $labels[$key] }}" class="option-circle-image">
                                @elseif ($step === 'skintone')
                                    <img src="{{ $skinColors[$option] ?? 'https://placehold.co/64x64/cccccc/333333?text=Skin' }}" alt="{{ $labels[$key] }}" class="option-circle-image">
                                @elseif ($step === 'style')
                                    <img src="{{ $styleImages[$option] ?? 'https://placehold.co/64x64/cccccc/333333?text=Style' }}" alt="{{ $labels[$key] }}" class="option-circle-image">
                                @elseif ($step === 'bodytype')
                                    @php
                                        $isMale = session('quiz.gender') === 'male';
                                        $bodytypeKey = strtolower(str_replace(' ', '_', $option));


                                        $icon = '';
                                        if ($isMale) {
                                            $icon = $maleBodytypeIcons[$bodytypeKey] ?? 'https://placehold.co/64x64/cccccc/333333?text=Male+Body';
                                        } else {
                                            $icon = $femaleBodytypeIcons[$bodytypeKey] ?? 'https://placehold.co/64x64/cccccc/333333?text=Female+Body';
                                        }
                                    @endphp
                                    <img src="{{ $icon }}" alt="{{ $labels[$key] }}" class="option-circle-image">
                                @endif
                            </div>
                            <span class="option-label">{{ $labels[$key] }}</span>
                        </label>
                    @endforeach
                </div>


                <button type="submit" class="next-button">Next</button>
            </form>
        </div>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($step !== 'intro')
                const nextButton = document.querySelector('.next-button');
                const radios = document.querySelectorAll('.radio-input');


                function toggleButton() {
                    const anyChecked = Array.from(radios).some(radio => radio.checked);
                    nextButton.disabled = !anyChecked;
                    nextButton.style.opacity = anyChecked ? '1' : '0.5';
                    nextButton.style.cursor = anyChecked ? 'pointer' : 'not-allowed';
                }


                radios.forEach(radio => {
                    radio.addEventListener('change', toggleButton);
                });


                toggleButton();
            @endif
        });
    </script>
</body>
</html>



