<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Make sure to import your User model

class SetPreferenceController extends Controller
{
    // Define the order of quiz steps
    private $quizSteps = ['gender', 'bodytype', 'skintone', 'style'];

    /**
     * Show the quiz step.
     *
     * @param Request $request
     * @param string|null $step
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, $step = null)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to set your preferences.');
        }

        // If no step is provided, redirect to the first step
        if (is_null($step)) {
            return redirect()->route('set_preference.index', ['step' => $this->quizSteps[0]]);
        }

        // Validate the step
        if (!in_array($step, $this->quizSteps)) {
            abort(404); // Or redirect to an error page
        }

        $user = Auth::user();

        // Load the current quiz state from the database for the user, if available
        $currentQuizData = [
            'gender' => $user->gender,
            'bodytype' => $user->bodytype,
            'skintone' => $user->skintone,
            'style' => $user->style,
            'preferences' => $user->preferences, // Load existing preference string
        ];
        session()->put('quiz', $currentQuizData); // Update session with current DB values

        // Check if previous steps are completed (still good for flow control)
        $currentStepIndex = array_search($step, $this->quizSteps);
        for ($i = 0; $i < $currentStepIndex; $i++) {
            if (empty($user->{$this->quizSteps[$i]})) { // Check directly from user model
                return redirect()->route('set_preference.index', ['step' => $this->quizSteps[$i]])
                                 ->with('error', 'Please complete the previous step first.');
            }
        }

        $options = [];
        $labels = [];

        switch ($step) {
            case 'gender':
                $options = ['male', 'female'];
                $labels = ['Male', 'Female'];
                break;
            case 'bodytype':
                $gender = $user->gender; // Get gender from user model (already saved)
                if (!$gender) {
                    return redirect()->route('set_preference.index', ['step' => 'gender'])
                                     ->with('error', 'Please select your gender first.');
                }

                if ($gender === 'male') {
                    // Pastikan key ini sesuai dengan di step.blade.php
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

    /**
     * Save the current quiz step's answer directly to the user model.
     *
     * @param Request $request
     * @param string $step
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveStep(Request $request, $step)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to save your preferences.');
        }

        // Validate the step and selected option
        $request->validate([
            $step => 'required|string',
        ]);

        $user = Auth::user();

        // Save the preference directly to the user model
        $user->{$step} = $request->input($step);

        // If it's the 'style' step, update the 'preferences' column with the result title
        if ($step === 'style') {
            // FIX: Gunakan pemetaan judul yang sesuai dengan permintaan Anda
            $preferenceMapping = [
                'casual' => 'The Easygoing Explorer', // Changed
                'formal' => 'The Authority',
                'stylish' => 'The Fashion Icon', // Changed
                'unique' => 'The Trendsetter', // Changed
            ];
            // Store the result title directly in the 'preferences' column
            $user->preferences = $preferenceMapping[$request->input($step)] ?? 'Unknown Style';
        }

        $user->save(); // Save changes to the database

        // Store the saved value in session to maintain "checked" state if user navigates back
        session()->put('quiz.' . $step, $request->input($step));

        $currentStepIndex = array_search($step, $this->quizSteps);
        $nextStepIndex = $currentStepIndex + 1;

        // If there's a next step, redirect to it
        if (isset($this->quizSteps[$nextStepIndex])) {
            return redirect()->route('set_preference.index', ['step' => $this->quizSteps[$nextStepIndex]]);
        } else {
            // All steps completed, redirect to countdown
            return redirect()->route('set_preference.countdown');
        }
    }

    /**
     * Show the countdown page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showCountdown()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view the countdown.');
        }

        // Ensure all quiz data (from DB) is present before showing countdown
        $user = Auth::user();
        // Memastikan semua kolom wajib diisi sebelum ke countdown
        foreach ($this->quizSteps as $step) {
            if (empty($user->{$step})) {
                return redirect()->route('set_preference.index', ['step' => $this->quizSteps[0]])
                                 ->with('error', 'Please complete the quiz first.');
            }
        }
        return view('countdown');
    }

    /**
     * Show the result page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showResult()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your style result.');
        }

        $user = Auth::user();

        // Get style and preference from the user model (already saved)
        $style = $user->style ?? 'unknown';
        $preferenceTitle = $user->preferences ?? 'Discover Your Style!'; // Get the saved preference string from DB

        // FIX: Gunakan pemetaan deskripsi dan gambar yang sesuai dengan permintaan Anda
        $resultDetailsMapping = [
            'casual' => [
                'description' => 'You are the **Easygoing Explorer!** Your style is all about comfort meets chic. You effortlessly blend relaxed pieces with trendy elements, making every day a fashion statement without trying too hard.',
                'image' => asset('img/result_casual.png'),
            ],
            'formal' => [
                'description' => 'You are **The Authority!** You exude confidence and professionalism. Your wardrobe is characterized by structured silhouettes, classic tailoring, and a polished appearance that commands respect.',
                'image' => asset('img/result_formal.png'),
            ],
            'stylish' => [
                'description' => 'You are **The Fashion Icon!** You have an innate ability to put together looks that are effortlessly elegant and always on point. You understand current trends and adapt them to your sophisticated taste.',
                'image' => asset('img/result_stylish.png'),
            ],
            'unique' => [
                'description' => 'You are **The Trendsetter!** Your style is a vibrant reflection of your personality. You aren\'t afraid to experiment with unconventional combinations, making a statement that is truly one-of-a-kind.',
                'image' => asset('img/result_unique.png'),
            ],
            'unknown' => [ // Fallback for unknown style
                'description' => 'It seems we couldn\'t pinpoint a specific style yet. Explore more to find your perfect fashion identity!',
                'image' => null,
            ]
        ];

        $result = $resultDetailsMapping[$style] ?? $resultDetailsMapping['unknown'];

        // Add an explicit check for the 'style' and 'preferences' columns in the user model
        // If 'style' is empty or 'preferences' is empty, it means the quiz wasn't fully completed.
        if (empty($user->style) || empty($user->preferences)) {
             return redirect()->route('set_preference.index', ['step' => 'style']) // Redirect user back to the style step
                              ->with('error', 'Your style preference was not fully set. Please complete the quiz.');
        }

        return view('result', [
            'resultTitle' => $preferenceTitle, // Ambil dari kolom preferences (misal "The Authority")
            'resultDescription' => $result['description'],
            'resultImage' => $result['image'],
        ]);
    }

    /**
     * Complete the quiz flow.
     * This method is now primarily for redirection after the result is displayed.
     * The actual saving to DB happens in saveStep.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in.');
        }

        // Clear quiz session data as it's no longer strictly needed for persistent storage
        session()->forget('quiz');

        return redirect()->route('home')->with('success', 'Your preferences are set!');
    }
}
