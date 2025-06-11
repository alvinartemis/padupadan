<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Stylist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ChatStylistController extends Controller
{
    public function index()
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return redirect()->route('stylist.login');
        }

        $recentChats = [];

        $usersInvolved = Pesan::where('idStylist', $stylist->idStylist)
                               ->orWhere('idPengguna', $stylist->idStylist)
                               ->pluck('idPengguna', 'idStylist')
                               ->toArray();

        $allInvolvedUserIds = [];
        foreach ($usersInvolved as $userIdPengguna => $userIdStylist) {
            if ($userIdPengguna != $stylist->idStylist) {
                $allInvolvedUserIds[] = $userIdPengguna;
            }
            if ($userIdStylist != $stylist->idStylist) {
                $allInvolvedUserIds[] = $userIdStylist;
            }
        }
        $allInvolvedUserIds = array_unique(array_filter($allInvolvedUserIds, function($id) use ($stylist){
            return $id !== $stylist->idStylist;
        }));


        foreach ($allInvolvedUserIds as $userId) {
            $user = User::find($userId); // Use find instead of findOrFail to handle potential missing users gracefully
            if (!$user) {
                continue; // Skip if user not found (e.g., user deleted)
            }

            // Get last message between this stylist and this user
            $lastMessage = Pesan::where(function ($query) use ($stylist, $user) {
                // Messages FROM user TO stylist
                $query->where('idPengguna', $user->idPengguna)
                      ->where('idStylist', $stylist->idStylist);
            })->orWhere(function ($query) use ($stylist, $user) {
                // Messages FROM stylist TO user
                $query->where('idPengguna', $stylist->idStylist) // Stylist is the sender (idPengguna in this case)
                      ->where('idStylist', $user->idPengguna);    // User is the recipient (idStylist in this case)
            })
                ->orderBy('waktukirim', 'desc')
                ->first();

            // Count unread messages *sent by this user to the current stylist*
            $unreadCount = Pesan::where('idPengguna', $user->idPengguna) // Message sent BY user
                ->where('idStylist', $stylist->idStylist) // Message sent TO current stylist
                ->where('statusBacaStylist', 0) // Unread by the stylist
                ->count();

            $recentChats[] = [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        }

        $recentChats = collect($recentChats)->sortByDesc(function ($chat) {
            return optional($chat['last_message'])->waktukirim;
        });

        return view('chat.indexstylist', compact('recentChats')); // Path sesuai yang kita sepakati
    }

    public function showChatWithUser(User $user)
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return redirect()->route('stylist.login');
        }

        $messages = Pesan::where(function ($query) use ($stylist, $user) {
            // Messages FROM user TO stylist
            $query->where('idPengguna', $user->idPengguna)
                  ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($stylist, $user) {
            // Messages FROM stylist TO user
            $query->where('idPengguna', $stylist->idStylist)
                  ->where('idStylist', $user->idPengguna);
        })
            ->orderBy('waktukirim', 'asc')
            ->get();

        // Mark messages from this user to the stylist as read
        Pesan::where('idPengguna', $user->idPengguna)
            ->where('idStylist', $stylist->idStylist)
            ->where('statusBacaStylist', 0)
            ->update(['statusBacaStylist' => 1]);

        return view('chat.showstylist', compact('user', 'messages')); // Path sesuai yang kita sepakati
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'isiPesan' => 'required|string|max:1000',
            'lampiranPesan' => 'nullable|file|mimes:png,jpg,jpeg,heic|max:2048', // Added max file size
        ]);

        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return redirect()->route('stylist.login');
        }

        $lampiranPath = null;
        if ($request->hasFile('lampiranPesan')) {
            $lampiranPath = $request->file('lampiranPesan')->store('chat_attachments', 'public');
        }

        Pesan::create([
            'idPengguna' => $user->idPengguna, // The user is the recipient in the record
            'idStylist' => $stylist->idStylist, // The stylist is the sender in the record
            'isiPesan' => $request->input('isiPesan'),
            'lampiranPesan' => $lampiranPath,
            'waktukirim' => Carbon::now(),
            'statusBacaPengguna' => 0, // Mark as unread for the user
            'statusBacaStylist' => 1, // Mark as read for the stylist (sender)
        ]);

        return redirect()->route('chat.showchatuser', $user->idPengguna); // Pastikan pakai idPengguna di redirect
    }

    public function getMessages(User $user)
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $messages = Pesan::where(function ($query) use ($stylist, $user) {
            // Messages FROM user TO stylist
            $query->where('idPengguna', $user->idPengguna)
                  ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($stylist, $user) {
            // Messages FROM stylist TO user
            $query->where('idPengguna', $stylist->idStylist)
                  ->where('idStylist', $user->idPengguna);
        })
            ->orderBy('waktukirim', 'asc')
            ->get();

        // Mark messages from this user to the stylist as read when fetched for refresh
        Pesan::where('idPengguna', $user->idPengguna)
            ->where('idStylist', $stylist->idStylist)
            ->where('statusBacaStylist', 0)
            ->update(['statusBacaStylist' => 1]);

        return view('chat.listmessagestylist', compact('messages', 'user'))->render(); // Gunakan render() untuk AJAX
    }

    public function markAsRead(Pesan $pesan)
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Only mark if the message is sent TO the current stylist AND currently unread by stylist
        if ($pesan->idStylist == $stylist->idStylist && $pesan->statusBacaStylist == 0) {
            $pesan->update(['statusBacaStylist' => 1]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Pesan tidak dapat ditandai sebagai dibaca atau sudah dibaca.'], 400);
    }
}
