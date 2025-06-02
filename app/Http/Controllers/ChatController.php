<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Stylist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ChatController extends Controller
{
    /**
     * Menampilkan halaman index chat, berisi daftar chat terakhir dan daftar stylist.
     *
     * @return \Illuminate\Contracts\View\View
     */

    public function index()
    {
        $user = Auth::user();
        $recentChats = []; // Inisialisasi sebagai array kosong

        $stylistsWithChats = Pesan::where('idPengguna', $user->idPengguna)
            ->select('idStylist')
            ->union(Pesan::where('idStylist', $user->idPengguna)->select('idPengguna as idStylist'))
            ->distinct()
            ->pluck('idStylist')
            ->toArray();

        foreach ($stylistsWithChats as $stylistId) {
            $stylist = Stylist::findOrFail($stylistId);
            $lastMessage = Pesan::where(function ($query) use ($user, $stylist) {
                $query->where('idPengguna', $user->idPengguna)
                    ->where('idStylist', $stylist->idStylist);
            })->orWhere(function ($query) use ($user, $stylist) {
                $query->where('idPengguna', $stylist->idStylist)
                    ->where('idStylist', $user->idPengguna);
            })
                ->orderBy('waktukirim', 'desc')
                ->first();

            $unreadCount = Pesan::where('idPengguna', $stylist->idStylist)
                ->where('idStylist', $user->idPengguna)
                ->where('statusBacaPengguna', 0)
                ->count();

            $recentChats[] = [ // Langsung tambahkan ke $recentChats
                'stylist' => $stylist,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        }

        $recentChats = collect($recentChats); // Sekarang buat collection dari $recentChats yang sudah terisi
        $recentChats = $recentChats->sortByDesc(function ($chat) {
            return optional($chat['last_message'])->waktukirim;
        });

        $stylists = Stylist::all();

        return view('chat.index', compact('recentChats', 'stylists'));
    }

    /**
     * Menampilkan daftar semua stylist.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getStylists()
    {
        $stylists = Stylist::all();
        return view('chat.liststylist', compact('stylists'));
    }

    /**
     * Menampilkan halaman profil stylist.
     *
     * @param  \App\Models\Stylist  $stylist
     * @return \Illuminate\Contracts\View\View
     */
    public function showChatWithStylist(Stylist $stylist)
    {
        $user = Auth::user();

        $messages = Pesan::where(function ($query) use ($user, $stylist) {
            $query->where('idPengguna', $user->idPengguna)
                ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($user, $stylist) {
            $query->where('idPengguna', $stylist->idStylist)
                ->where('idStylist', $user->idPengguna);
        })
            ->orderBy('waktukirim', 'asc')
            ->get();

        return view('chat.show', compact('stylist', 'messages'));
    }

    /**
     * Menampilkan halaman profil stylist.
     *
     * @param  \App\Models\Stylist  $stylist
     * @return \Illuminate\Contracts\View\View
     */
    public function showProfileStylist(Stylist $stylist)
    {
        return view('chat.profilestylist', compact('stylist'));
    }

    /**
     * Mengirim pesan dari pengguna ke stylist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stylist  $stylist
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(Request $request, Stylist $stylist)
    {
        $request->validate([
            'isiPesan' => 'required|string|max:1000',
            'lampiranPesan' => 'nullable|file|mimes:png,jpg,jpeg,heic|max:2048',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiranPesan')) {
            $lampiranPath = $request->file('lampiranPesan')->store('chat_attachments', 'public');
        }

        Pesan::create([
            'idPengguna' => Auth::id(),
            'idStylist' => $stylist->idStylist,
            'isiPesan' => $request->input('isiPesan'),
            'lampiranPesan' => $lampiranPath,
            'waktukirim' => Carbon::now(),
            'statusBacaPengguna' => 1,
            'statusBacaStylist' => 0,
        ]);

        return redirect()->route('chat.show', $stylist);
    }

    /**
     * Mendapatkan daftar pesan antara pengguna dan stylist.
     *
     * @param  \App\Models\Stylist  $stylist
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Stylist $stylist)
    {
        $user = Auth::user();

        $messages = Pesan::where(function ($query) use ($user, $stylist) {
            $query->where('idPengguna', $user->idPengguna)
                ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($user, $stylist) {
            $query->where('idPengguna', $stylist->idStylist)
                ->where('idStylist', $user->idPengguna);
        })
            ->orderBy('waktukirim', 'asc')
            ->get();

        return view('chat.listmessage', compact('messages'));
    }

    /**
     * Menandai pesan sebagai sudah dibaca oleh pengguna.
     *
     * @param  \App\Models\Pesan  $pesan
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Pesan $pesan)
    {
        if ($pesan->idStylist == Auth::id() && $pesan->statusBacaStylist == 0) {
            $pesan->update(['statusBacaStylist' => 1]);
            return response()->json(['success' => true]);
        } elseif ($pesan->idPengguna == Auth::id() && $pesan->statusBacaPengguna == 0) {
            $pesan->update(['statusBacaPengguna' => 1]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Pesan tidak dapat ditandai sebagai dibaca.'], 400);
    }
}
