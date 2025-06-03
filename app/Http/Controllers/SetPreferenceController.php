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
        $genderOptions = ['male', 'female'];
        $genderLabels = ['Male', 'Female'];

        $bodytypeOptions = []; // Akan diisi oleh JavaScript berdasarkan gender
        $bodytypeLabels = [];

        $skintoneOptions = ['cool', 'warm', 'neutral', 'olive'];
        $skintoneLabels = ['Cool', 'Warm', 'Neutral', 'Olive'];

        $styleOptions = ['casual', 'formal', 'unique', 'stylish'];
        $styleLabels = ['Casual', 'Formal', 'Unique', 'Stylish'];
        $steps = $this->steps;

        return view('set-preference-single', compact(
            'genderOptions',
            'genderLabels',
            'bodytypeOptions',
            'bodytypeLabels',
            'skintoneOptions',
            'skintoneLabels',
            'styleOptions',
            'styleLabels',
            'steps'
        ));
    }

    public function saveAll(Request $request)
    {
        $validations = [
            'gender' => 'required|in:male,female',
            'bodytype' => 'required',
            'skintone' => 'required|in:warm,olive,neutral,cool',
            'style' => 'required|in:casual,formal,unique,stylish',
        ];

        $gender = $request->input('gender');
        $validBodytypes = $this->getBodytypeOptions($gender);
        $validations['bodytype'] = 'required|in:' . implode(',', $validBodytypes);

        $request->validate($validations);

        session(['quiz.gender' => $request->input('gender')]);
        session(['quiz.bodytype' => $request->input('bodytype')]);
        session(['quiz.skintone' => $request->input('skintone')]);
        session(['quiz.style' => $request->input('style')]);

        Log::info("Set session quiz:", session('quiz'));

        // Redirect ke halaman hasil setelah menyimpan semua preferensi
        return redirect()->route('set_preference.result');
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
        } else if ($quizData && isset($quizData['style'])) {
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
                    $resultImage = asset('img/fi.png');
                    break;
            }
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
            $resultDescription = '';
            $resultImage = null;

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
