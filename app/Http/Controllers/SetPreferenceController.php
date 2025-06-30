<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class SetPreferenceController extends Controller
{
    private $quizSteps = ['intro', 'gender', 'bodytype', 'skintone', 'style'];
    public function index(Request $request, $step = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to set your preferences.');
        }
        if (is_null($step)) {
            return redirect()->route('set_preference.index', ['step' => 'intro']);
        }
        if (!in_array($step, $this->quizSteps)) {
            abort(404);
        }


        $user = Auth::user();


        $currentQuizData = [
            'gender' => $user->gender,
            'bodytype' => $user->bodytype,
            'skintone' => $user->skintone,
            'style' => $user->style,
            'preferences' => $user->preferences,
        ];
        session()->put('quiz', $currentQuizData);
        $currentStepIndex = array_search($step, $this->quizSteps);


        if ($step !== 'intro') {
            for ($i = 1; $i < $currentStepIndex; $i++) {
                if (empty($user->{$this->quizSteps[$i]})) {
                    return redirect()->route('set_preference.index', ['step' => $this->quizSteps[$i]])
                                     ->with('error', 'Please complete the previous step first.');
                }
            }
        }


        $options = [];
        $labels = [];


        if ($step === 'intro') {
            return view('step', compact('step'));
        }


        switch ($step) {
            case 'gender':
                $options = ['male', 'female'];
                $labels = ['Male', 'Female'];
                break;
            case 'bodytype':
                $gender = $user->gender;
                if (!$gender) {
                    return redirect()->route('set_preference.index', ['step' => 'gender'])
                                     ->with('error', 'Please select your gender first.');
                }


                if ($gender === 'male') {
                    $options = ['triangle', 'round', 'inverted_triangle', 'rectangular'];
                    $labels = ['Triangle', 'Round', 'Inverted Triangle', 'Rectangular'];
                } elseif ($gender === 'female') {
                    $options = ['hourglass', 'apple', 'pear', 'rectangular'];
                    $labels = ['Hourglass', 'Apple', 'Pear', 'Rectangular'];
                }
                break;
            case 'skintone':
                $options = ['cool', 'warm', 'neutral', 'olive'];
                $labels = ['Cool', 'Warm', 'Neutral', 'Olive'];
                break;
            case 'style':
                $options = ['casual', 'formal', 'stylish', 'unique'];
                $labels = ['Kasual', 'Formal', 'Stylish', 'Unique'];
                break;
        }


        return view('step', compact('step', 'options', 'labels'));
    }


    public function saveStep(Request $request, $step)
    {
        if ($step === 'intro') {
            return redirect()->route('set_preference.index', ['step' => 'gender']);
        }
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to save your preferences.');
        }
        $request->validate([
            $step => 'required|string',
        ]);


        $user = Auth::user();
        $user->{$step} = $request->input($step);
        if ($step === 'style') {
            $preferenceMapping = [
                'casual' => 'The Easygoing Explorer',
                'formal' => 'The Authority',
                'stylish' => 'The Fashion Icon',
                'unique' => 'The Trendsetter',
            ];
            $user->preferences = $preferenceMapping[$request->input($step)] ?? 'Unknown Style';
        }


        $user->save();
        session()->put('quiz.' . $step, $request->input($step));
        $currentStepIndex = array_search($step, $this->quizSteps);
        $nextStepIndex = $currentStepIndex + 1;
        if (isset($this->quizSteps[$nextStepIndex])) {
            return redirect()->route('set_preference.index', ['step' => $this->quizSteps[$nextStepIndex]]);
        } else {
            return redirect()->route('set_preference.countdown');
        }
    }
    public function showCountdown()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view the countdown.');
        }
        $user = Auth::user();
        for ($i = 1; $i < count($this->quizSteps); $i++) {
            $quizStep = $this->quizSteps[$i];
            if (empty($user->{$quizStep})) {
                return redirect()->route('set_preference.index', ['step' => 'intro'])
                                 ->with('error', 'Please complete the quiz first.');
            }
        }
        return view('countdown');
    }


    public function showResult()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your style result.');
        }


        $user = Auth::user();
        $style = $user->style ?? 'unknown';
        $preferenceTitle = $user->preferences ?? 'Discover Your Style!';
        $resultDetailsMapping = [
            'casual' => [
                'description' => 'You are the Easygoing Explorer! Your style is all about comfort meets chic. You effortlessly blend relaxed pieces with trendy elements, making every day a fashion statement without trying too hard.',
                'image' => asset('img/exp.png'),
            ],
            'formal' => [
                'description' => 'You are The Authority! You exude confidence and professionalism. Your wardrobe is characterized by structured silhouettes, classic tailoring, and a polished appearance that commands respect.',
                'image' => asset('img/ta.png'),
            ],
            'stylish' => [
                'description' => 'You are The Fashion Icon! You have an innate ability to put together looks that are effortlessly elegant and always on point. You understand current trends and adapt them to your sophisticated taste.',
                'image' => asset('img/fi.png'),
            ],
            'unique' => [
                'description' => 'You are The Trendsetter! Your style is a vibrant reflection of your personality. You aren\'t afraid to experiment with unconventional combinations, making a statement that is truly one-of-a-kind.',
                'image' => asset('img/ts.png'),
            ],
            'unknown' => [
                'description' => 'It seems we couldn\'t pinpoint a specific style yet. Explore more to find your perfect fashion identity!',
                'image' => null,
            ]
        ];


        $result = $resultDetailsMapping[$style] ?? $resultDetailsMapping['unknown'];


        if (empty($user->gender) || empty($user->bodytype) || empty($user->skintone) || empty($user->style) || empty($user->preferences)) {
             return redirect()->route('set_preference.index', ['step' => 'intro'])
                              ->with('error', 'Your style preference was not fully set. Please complete the quiz.');
        }


        return view('result', [
            'resultTitle' => $preferenceTitle,
            'resultDescription' => $result['description'],
            'resultImage' => $result['image'],
        ]);
    }


    public function complete(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in.');
        }
        session()->forget('quiz');


        return redirect()->route('home')->with('success', 'Your preferences are set!');
    }
}



