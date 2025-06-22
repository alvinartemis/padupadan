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
use App\Models\Lookbook;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentChats = [];

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

            $recentChats[] = [
                'stylist' => $stylist,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        }

        $recentChats = collect($recentChats);
        $recentChats = $recentChats->sortByDesc(function ($chat) {
            return optional($chat['last_message'])->waktukirim;
        });

        $stylists = Stylist::all();

        return view('chat.index', compact('recentChats', 'stylists'));
    }

    public function getStylists()
    {
        $stylists = Stylist::all();
        return view('chat.liststylist', compact('stylists'));
    }

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

        $updatedRows = Pesan::where('idPengguna', $stylist->idStylist)
            ->where('idStylist', $user->idPengguna)
            ->where('statusBacaPengguna', 0)
            ->update(['statusBacaPengguna' => 1]);

        $loggedInUserId = $user->idPengguna;
        return view('chat.show', compact('stylist', 'messages', 'loggedInUserId'));
    }

    public function showProfileStylist(Stylist $stylist)
    {
        $lookbooks = Lookbook::where('idStylist', $stylist->idStylist)
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('chat.profilestylist', compact('stylist', 'lookbooks'));
    }

    public function sendMessage(Request $request, Stylist $stylist)
    {
        $request->validate([
            'isiPesan' => 'required|string|max:1000',
            'lampiranPesan' => 'nullable|file|mimes:png,jpg,jpeg,heic',
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
            'sender_type' => 'user',
        ]);

        return redirect()->route('chat.show', $stylist);
    }

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
        $loggedInUserId = $user->idPengguna;
        return view('chat.listmessage', compact('messages','loggedInUserId'))->render();
    }

    public function markAsRead(Pesan $pesan)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($pesan->idStylist == $user->idPengguna && $pesan->statusBacaPengguna == 0) {
            $pesan->update(['statusBacaPengguna' => 1]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Pesan tidak dapat ditandai sebagai dibaca atau sudah dibaca.'], 400);
    }
}
