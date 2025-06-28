<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Quiz</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/set-preference.css') }}">
</head>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

        toggleButton(); // Check on load
    });
</script>


<body>
    <div class="circle left"></div>
    <div class="circle right"></div>

    <div class="logo">
        <img src="{{ asset('img/logoy.png') }}" alt="Logo">
    </div>

    <div class="progress-container">
        {{-- The progress-bar class needs to reflect current step's width or state --}}
        {{-- You'll need CSS rules for .progress-bar.gender, .progress-bar.bodytype etc. --}}
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

                    // Male body type images
                    $maleBodytypeIcons = [
                        'triangle'          => asset('img/triangle.png'), // Male Triangle
                        'round'             => asset('img/round.png'),      // Male Round (instead of Oval, if round.png is that)
                        'inverted_triangle' => asset('img/intri.png'), // Male Inverted Triangle (FIXED KEY)
                        'rectangular'       => asset('img/mrec.png'),     // Male Rectangular
                    ];

                    // Female body type images (original names based on your image files)
                    $femaleBodytypeIcons = [
                        'hourglass' => asset('img/hourglass.png'),
                        'apple'     => asset('img/apple.png'),
                        'pear'      => asset('img/pear.png'),
                        'rectangular' => asset('img/frec.png'), // Female Rectangular
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
                                    $bodytypeKey = strtolower(str_replace(' ', '_', $option)); // Ensure key matches controller options

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
</body>
</html>
