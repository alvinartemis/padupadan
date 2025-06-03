<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Find Your Style</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/set-preference-single.css') }}">
    <style>
       body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .circle {
            position: fixed;
            background-color: #f9a825; /* Example accent color */
            border-radius: 50%;
            opacity: 0.6;
            width: 200px;
            height: 200px;
        }

        .circle.left {
            top: -100px;
            left: -100px;
        }

        .circle.right {
            bottom: -100px;
            right: -100px;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .logo img {
            height: 40px;
        }

        .progress-container {
            position: absolute;
            top: 70px;
            width: 80%;
            max-width: 600px;
            background-color: #e0e0e0;
            border-radius: 5px;
            height: 10px;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #f9a825; /* Example progress color */
            height: 10px;
            border-radius: 5px;
            width: 0%; /* Will be updated by JavaScript if needed */
        }

        #regForm {
            background-color: #ffffff;
            margin: 40px auto;
            padding: 30px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #173F63;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .tab {
            display: none;
        }

        .tab h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
            text-align: center;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .option-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .option-label:hover {
            background-color: #f0f0f0;
            border-color: #999;
        }

        .option-label img {
            max-width: 60px;
            height: auto;
            margin-bottom: 8px;
        }

        .option-label span {
            font-size: 14px;
            color: #555;
        }

        input[type="radio"] {
            display: none;
        }

        input[type="radio"]:checked + .option-label {
            background-color: #e0f7fa;
            border-color: #4dd0e1;
        }

        .buttons-container {
            overflow: auto;
            margin-top: 20px;
        }

        .buttons-container div {
            float: right;
        }

        button {
            background-color: #f9a825; /* Primary button color */
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e69920;
        }

        button#prevBtn {
            background-color: #f44336; /* Previous button color */
            margin-right: 10px;
        }

        button#prevBtn:hover {
            background-color: #d32f2f;
        }

        .step-indicator {
            text-align: center;
            margin-top: 20px;
        }

        .step {
            height: 12px;
            width: 12px;
            margin: 0 5px;
            background-color: #bbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        .step.finish {
            background-color: #4CAF50;
        }

        /* Intro Tab Styles */
        .intro-tab {
            text-align: center;
        }

        .intro-tab h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .intro-tab p {
            color: #555;
            margin-bottom: 25px;
        }

        .start-button {
            display: inline-block;
            background-color: #f9a825;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .start-button:hover {
            background-color: #e69920;
        }
    </style>
</head>
<body>
    <form id="regForm" action="{{ route('set_preference.save_all') }}" method="POST">
        @csrf
        <h1>Find Your Style!</h1>

        <div class="tab intro-tab">
            <h2>What's Your Style Profile?</h2>
            <p>Take the Personal Analysis Quiz to Find Out!</p>
            <a href="#" class="start-button" onclick="showNextTab()">Start Quiz!</a>
        </div>

        <div class="tab">
            <h3>Your Gender</h3>
            <div class="options-grid">
                @foreach ($genderOptions as $key => $option)
                    <label class="option-label">
                        <input type="radio" name="gender" value="{{ $option }}" {{ old('gender') == $option ? 'checked' : '' }} required>
                        @php
                            $genderIcons = [
                                'male' => asset('img/male.png'),
                                'female' => asset('img/female.png'),
                            ];
                        @endphp
                        <img src="{{ $genderIcons[$option] }}" alt="{{ $genderLabels[$key] }}">
                        <span>{{ $genderLabels[$key] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="tab">
            <h3>Your Body Type</h3>
            <div class="options-grid" id="bodytype-options">
                </div>
        </div>

        <div class="tab">
            <h3>Your Skin Tone</h3>
            <div class="options-grid">
                @foreach ($skintoneOptions as $key => $option)
                    <label class="option-label">
                        <input type="radio" name="skintone" value="{{ $option }}" {{ old('skintone') == $option ? 'checked' : '' }} required>
                        @php
                            $skinColors = [
                                'cool' => asset('img/cool.png'),
                                'warm' => asset('img/warm.png'),
                                'neutral' => asset('img/neutral.png'),
                                'olive' => asset('img/olive.png'),
                            ];
                        @endphp
                        <img src="{{ $skinColors[$option] ?? 'https://placehold.co/64x64/cccccc/333333?text=Skin' }}" alt="{{ $skintoneLabels[$key] }}">
                        <span>{{ $skintoneLabels[$key] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="tab">
            <h3>Your Fashion Style</h3>
            <div class="options-grid">
                @foreach ($styleOptions as $key => $option)
                    <label class="option-label">
                        <input type="radio" name="style" value="{{ $option }}" {{ old('style') == $option ? 'checked' : '' }} required>
                        @php
                            $styleImages = [
                                'casual' => asset('img/casual.png'),
                                'formal' => asset('img/formal.png'),
                                'unique' => asset('img/unique.png'),
                                'stylish' => asset('img/stylish.png'),
                            ];
                        @endphp
                        <img src="{{ $styleImages[$option] ?? 'https://placehold.co/64x64/cccccc/333333?text=Style' }}" alt="{{ $styleLabels[$key] }}">
                        <span>{{ $styleLabels[$key] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="buttons-container">
            <div style="float:right;">
                <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
            </div>
        </div>

        <div class="step-indicator">
            <span class="step active"></span>
            @for ($i = 1; $i < count($steps); $i++)
                <span class="step"></span>
            @endfor
        </div>
    </form>

    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        var tabs = document.getElementsByClassName("tab");
        var prevBtn = document.getElementById("prevBtn");
        var nextBtn = document.getElementById("nextBtn");
        var stepIndicators = document.getElementsByClassName("step");

        function showTab(n) {
            tabs[n].style.display = "block";
            if (n == 0) {
                prevBtn.style.display = "none";
                nextBtn.style.display = "none"; // Hide Next on intro
            } else {
                prevBtn.style.display = "inline";
                nextBtn.style.display = "inline";
            }
            if (n == (tabs.length - 1)) {
                nextBtn.innerHTML = "Submit";
                nextBtn.type = "submit"; // Tambahkan baris ini
            } else {
                nextBtn.innerHTML = "Next";
                nextBtn.type = "button"; // Pastikan tipenya button di langkah lain
            }
            fixStepIndicator(n);
            updateBodytypeOptions();
        }

        function showNextTab() {
            tabs[currentTab].style.display = "none";
            currentTab++;
            showTab(currentTab);
        }

        function nextPrev(n) {
            if (n == 1 && !validateForm()) return false;
            tabs[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= tabs.length) {
                document.getElementById("regForm").submit();
                return false;
            }
            showTab(currentTab);
        }

        function validateForm() {
            var x, y, i, valid = true;
            x = tabs[currentTab].getElementsByTagName("input");
            for (i = 0; i < x.length; i++) {
                if (x[i].type === "radio" && !x[i].checked && currentTab > 0) { // Basic radio button validation
                    let anyChecked = false;
                    let radioGroup = document.querySelectorAll('input[name="' + x[i].name + '"]');
                    radioGroup.forEach(radio => {
                        if (radio.checked) anyChecked = true;
                    });
                    if (!anyChecked && currentTab > 0) {
                        x[i].className += " invalid";
                        valid = false;
                    } else {
                        x[i].className = x[i].className.replace(" invalid", "");
                    }
                }
            }
            if (valid) {
                stepIndicators[currentTab].className += " finish";
            }
            return valid;
        }

        function fixStepIndicator(n) {
            for (i = 0; i < stepIndicators.length; i++) {
                stepIndicators[i].className = stepIndicators[i].className.replace(" active", "");
            }
            stepIndicators[n].className += " active";
        }

        function updateBodytypeOptions() {
            var genderRadios = document.getElementsByName('gender');
            var selectedGender = '';
            for (var i = 0; i < genderRadios.length; i++) {
                if (genderRadios[i].checked) {
                    selectedGender = genderRadios[i].value;
                    break;
                }
            }

            var bodytypeOptionsContainer = document.getElementById('bodytype-options');
            bodytypeOptionsContainer.innerHTML = ''; // Clear previous options

            var bodyTypes = [];
            var bodyLabels = [];
            if (selectedGender === 'female') {
                bodyTypes = ['hourglass', 'apple', 'pear', 'rectangle'];
                bodyLabels = ['Hourglass', 'Apple', 'Pear', 'Rectangular'];
            } else if (selectedGender === 'male') {
                bodyTypes = ['triangle', 'round', 'rectangle', 'inverted Triangle'];
                bodyLabels = ['Triangle', 'Round', 'Rectangular', 'Inverted Triangle'];
            }

            bodyTypes.forEach(function(type, index) {
                var label = document.createElement('label');
                label.className = 'option-label';

                var input = document.createElement('input');
                input.type = 'radio';
                input.name = 'bodytype';
                input.value = type;
                input.required = true;

                var img = document.createElement('img');
                var imageName = type.toLowerCase().replace(' ', '_');
                img.src = '{{ asset("img/") }}/' + imageName + '.png'; // Adjust path as needed
                img.alt = bodyLabels[index];

                var span = document.createElement('span');
                span.textContent = bodyLabels[index];

                label.appendChild(input);
                label.appendChild(img);
                label.appendChild(span);
                bodytypeOptionsContainer.appendChild(label);
            });

            // Re-initialize radio button validation for the new body type options
            var bodytypeRadios = document.querySelectorAll('input[name="bodytype"]');
            bodytypeRadios.forEach(radio => {
                radio.addEventListener('change', validateForm);
            });
        }

        // Initialize the quiz
        showTab(currentTab);
    </script>
</body>
</html>
