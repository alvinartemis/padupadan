<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SetPreferenceController extends Controller
{
    protected $steps = ['gender', 'bodytype', 'skintone', 'style'];

    public function index()
    {
        return view('index');
    }

    public function showStep($step)
    {
        if (!in_array($step, $this->steps)) {
            abort(404);
        }

        $options = [];
        $labels = [];

        switch ($step) {
            case 'gender':
                $options = ['male', 'female'];
                $labels = ['Male', 'Female'];
                break;
            case 'bodytype':
                $gender = session('quiz.gender') ?? Auth::user()?->gender;
                $options = $this->getBodytypeOptions($gender);
                $labels = array_map('ucfirst', $options);
                break;
            case 'skintone':
                $options = ['cool', 'warm', 'neutral', 'olive'];
                $labels = ['Cool', 'Warm', 'Neutral', 'Olive'];
                break;
            case 'style':
                $options = ['casual', 'formal', 'unique', 'stylish'];
                $labels = ['Casual', 'Formal', 'Unique', 'Stylish'];
                break;
        }

        return view('step', compact('step', 'options', 'labels'));
    }

    public function saveStep(Request $request, $step)
    {
        if (!in_array($step, $this->steps)) {
            abort(404);
        }

        $validations = [
            'gender' => 'required|in:male,female',
            'bodytype' => 'required',
            'skintone' => 'required|in:warm,olive,neutral,cool',
            'style' => 'required|in:casual,formal,unique,stylish',
        ];

        if ($step === 'bodytype') {
            $gender = session('quiz.gender');
            $validBodytypes = $this->getBodytypeOptions($gender);
            $validations['bodytype'] = 'required|in:' . implode(',', $validBodytypes);
        }

        $request->validate([$step => $validations[$step]]);

        session(['quiz.' . $step => $request->$step]);
        \Log::info("Set session quiz.{$step}: " . $request->$step);

        $currentIndex = array_search($step, $this->steps);
        if ($currentIndex < count($this->steps) - 1) {
            return redirect()->route('set_preference.step', ['step' => $this->steps[$currentIndex + 1]]);
        } else {
            return redirect()->route('set_preference.countdown');
        }
    }

    public function showResult()
    {
        $user = Auth::user();
        $quizData = session('quiz');

        $resultTitle = '';
        $resultDescription = '';
        $resultImage = null;

        if ($user && $user->result_title) {
            $resultTitle = $user->result_title;
            $resultDescription = $user->result_description ?? '';
            $resultImage = $user->result_image ?? null;
        } else if ($quizData && isset($quizData['style']))
            switch ($quizData['style']) {
                case 'casual':
                    $resultTitle = 'The Easygoing Explorer';
                    $resultDescription = 'Effortless comfort is your style signature! You are a natural at looking effortlessly cool, proving that relaxation and style can go hand in hand. Your positive energy and down-to-earth spirit shine through every look. A perfect partner for any escapade, you are always prepared with a style that is both practical and captivating. So, are you ready to embrace your best?';
                    $resultImage = asset('img/exp.png');
                    break;
                case 'formal':
                    $resultTitle = 'The Authority';
                    $resultDescription = 'The aura of a boss radiates from every fiber of your being! You are a true leader who knows exactly how to build trust and authority through your appearance. Every detail reflects meticulousness and professionalism. The world is yours to command. So, are you ready to step into your power?';
                    $resultImage = asset('img/ta.png');
                    break;
                case 'unique':
                    $resultTitle = 'The Trendsetter';
                    $resultDescription = 'Dare to be different! You are a fashion virtuoso, boldly showcasing your true self through clothing. Style is your personal palette, where you craft your singular identity. A courageous trailblazer, you defy fashion norms and forge your own path. Ready to unveil a look that will set you apart even further?';
                    $resultImage = asset('img/ts.png');
                    break;
                case 'stylish':
                    $resultTitle = 'The Fashion Icon';
                    $resultDescription = 'Look who is here! A genuine style icon graces us with their presence, consistently ahead and admired. Your style instinct is keen and fearless, expertly mixing the newest trends with your own distinct flair. Each appearance is a source of inspiration. You are a fashion luminary wherever you are! Ready to radiate even more?';
                    $resultImage = asset('img/fi.png');
                    break;
            }
        return view('result', [
            'resultTitle' => $resultTitle,
            'resultDescription' => $resultDescription,
            'resultImage' => $resultImage,
        ]);
    }

    public function completePreference(Request $request)
    {
        $user = Auth::user();
        $quizData = session('quiz');
        Log::info('Quiz session data di completePreference:', $quizData ?: []);

        if ($user && $quizData && isset($quizData['gender'], $quizData['bodytype'], $quizData['skintone'], $quizData['style'])) {
            $resultTitle = '';

            switch ($quizData['style']) {
                case 'casual':
                    $resultTitle = 'The Easygoing Explorer';
                    $resultDescription = 'Effortless comfort is your style signature! ...';
                    $resultImage = asset('img/exp.png');
                    break;
                case 'formal':
                    $resultTitle = 'The Authority';
                    $resultDescription = 'The aura of a boss radiates from every fiber of your being! ...';
                    $resultImage = asset('img/ta.png');
                    break;
                case 'unique':
                    $resultTitle = 'The Trendsetter';
                    $resultDescription = 'Dare to be different! You are a fashion virtuoso, ...';
                    $resultImage = asset('img/ts.png');
                    break;
                case 'stylish':
                    $resultTitle = 'The Fashion Icon';
                    $resultDescription = 'Look who is here! A genuine style icon graces us with ...';
                    break;
                default:
                    $resultTitle = 'Your Style Profile';
                    break;
            }

            $user->update([
                'gender' => $quizData['gender'],
                'bodytype' => $quizData['bodytype'],
                'skintone' => $quizData['skintone'],
                'style' => $quizData['style'],
                'result_title' => $resultTitle,
                'result_description' => $resultDescription,
                'result_image' => $resultImage,
            ]);

            session()->forget('quiz');

            Log::info('Data preferensi dan hasil disimpan ke database untuk user ID: ' . $user->id);

            return redirect('/home');
        } else {
            Log::warning('Gagal menyimpan data preferensi: User tidak terautentikasi atau data sesi tidak lengkap.');
            return redirect()->route('set_preference.index');
        }
    }

    public function showCountdown()
    {
        return view('countdown');
    }

    protected function getBodytypeOptions($gender)
    {
        if ($gender === 'female') {
            return ['hourglass', 'apple', 'pear', 'rectangle'];
        } elseif ($gender === 'male') {
            return ['triangle', 'round', 'rectangle', 'inverted Triangle'];
        }
        return [];
    }

}
