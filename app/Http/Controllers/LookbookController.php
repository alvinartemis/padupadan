<?php

namespace App\Http\Controllers;

use App\Models\Lookbook;
use App\Models\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LookbookController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $loggedInUser = Auth::user();
        $stylistProfile = Stylist::where('nama', $loggedInUser->nama)->first();

        if (!$stylistProfile) {
            $lookbooks = collect();
            return view('lookbook.lookbookstylist', compact('lookbooks'));
        }

        $lookbooks = Lookbook::where('idStylist', $stylistProfile->idStylist)
                             ->latest()
                             ->get();

        return view('lookbook.lookbookstylist', compact('lookbooks'));
    }

    public function userIndex(Request $request)
    {
        $search = $request->input('search');
        $query = Lookbook::query()->with('stylist');
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kataKunci', 'like', '%' . $search . '%');
        }

        $lookbooks = $query->latest()->get();

        return view('lookbook.readlookbook', compact('lookbooks', 'search'));
    }

    public function create()
    {
        return view('lookbook.createlookbook');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'kataKunci'   => 'nullable|string',
            'imgLookbook' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('imgLookbook')->store('lookbooks', 'public');
        $loggedInUser = Auth::user();
        $stylistProfile = Stylist::where('nama', $loggedInUser->nama)->first();

        if (!$stylistProfile) {
            return back()->with('error', 'Profil stylist dengan nama "' . $loggedInUser->nama . '" tidak ditemukan.')->withInput();
        }

        Lookbook::create([
            'idStylist'   => $stylistProfile->idStylist,
            'nama'        => $request->input('nama'),
            'description' => $request->input('description'),
            'kataKunci'   => $request->input('kataKunci'),
            'imgLookbook' => $imagePath,
        ]);

        return redirect()->route('lookbook.index')->with('success', 'Lookbook berhasil disimpan!');
    }

    public function show(Lookbook $lookbook)
    {
        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = Auth::user()->wishlistItems()
    ->where('lookbook.idLookbook', $lookbook->idLookbook)
    ->exists();
        }
        $lookbook->load('stylist');
        return view('lookbook.detaillookbook', compact('lookbook', 'isBookmarked'));
    }

    public function showStylistLookbook(Lookbook $lookbook)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $loggedInUser = Auth::user();
        $stylistProfile = Stylist::where('nama', $loggedInUser->nama)->first();
        if (!$stylistProfile) {
            return redirect()->route('lookbook.index')->with('error', 'Profil stylist tidak ditemukan.');
        }
        if ($lookbook->idStylist !== $stylistProfile->idStylist) {
            return redirect()->route('lookbook.index')->with('error', 'Anda tidak memiliki akses ke lookbook ini.');
        }
        $lookbook->load('stylist');

        return view('lookbook.detaillookbookstylist', compact('lookbook'));
    }

    public function getTagSuggestions(Request $request)
    {
        $query = $request->input('query', '');

        if (empty($query)) {
            return response()->json([]);
        }

        $allKeywords = Lookbook::pluck('kataKunci')->toArray();

        $tags = collect($allKeywords)
            ->flatMap(function ($keywordString) {
                return array_map('trim', explode(',', $keywordString));
            })
            ->filter()
            ->unique();

        $suggestions = $tags->filter(function ($tag) use ($query) {
            return Str::startsWith(strtolower($tag), strtolower($query));
        })->values();

        return response()->json($suggestions);
    }
}
