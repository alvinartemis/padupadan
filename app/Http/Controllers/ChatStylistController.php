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

        // This query finds all unique users who have exchanged messages with the current stylist
        // Messages where stylist is recipient AND messages where stylist is sender
        $usersInvolvedIds = Pesan::where('idStylist', $stylist->idStylist)
                               ->pluck('idPengguna')
                               ->merge(
                                   Pesan::where('idPengguna', $stylist->idStylist) // Stylist is sender
                                        ->pluck('idStylist') // User is recipient
                               )
                               ->unique()
                               ->toArray();

        foreach ($usersInvolvedIds as $userId) {
            $user = User::find($userId);
            if (!$user) {
                continue;
            }

            // Get last message between this stylist and this user
            $lastMessage = Pesan::where(function ($query) use ($stylist, $user) {
                // Messages FROM user TO stylist
                $query->where('idPengguna', $user->idPengguna)
                      ->where('idStylist', $stylist->idStylist);
            })->orWhere(function ($query) use ($stylist, $user) {
                // Messages FROM stylist TO user
                $query->where('idPengguna', $stylist->idStylist)
                      ->where('idStylist', $user->idPengguna);
            })
                ->orderBy('waktukirim', 'desc')
                ->first();

            // Count unread messages sent BY this user TO the current stylist
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

        // Sort chats by the latest message time
        $recentChats = collect($recentChats)->sortByDesc(function ($chat) {
            return optional($chat['last_message'])->waktukirim;
        });

        // Get all users that the stylist is NOT already chatting with in recentChats
        $alreadyChattingUserIds = collect($recentChats)->pluck('user.idPengguna')->toArray();
        $usersToChat = User::whereNotIn('idPengguna', $alreadyChattingUserIds)
                           ->where('idPengguna', '!=', $stylist->idStylist) // Exclude the stylist themselves if they are also a 'user'
                           ->get();


        // Use the correct view path: resources/views/chat/indexstylist.blade.php
        return view('chat.indexstylist', compact('recentChats', 'usersToChat'));
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

        // Mark messages from this user to the stylist as read when stylist opens the chat
        Pesan::where('idPengguna', $user->idPengguna)
            ->where('idStylist', $stylist->idStylist)
            ->where('statusBacaStylist', 0)
            ->update(['statusBacaStylist' => 1]);

        // Use the correct view path: resources/views/chat/show.blade.php
        return view('chat.show', compact('user', 'messages'));
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'isiPesan' => 'required|string|max:1000',
            'lampiranPesan' => 'nullable|file|mimes:png,jpg,jpeg,heic|max:2048',
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
            'idPengguna' => $stylist->idStylist, // Stylist is the sender (idPengguna in this context)
            'idStylist' => $user->idPengguna,    // User is the recipient (idStylist in this context)
            'isiPesan' => $request->input('isiPesan'),
            'lampiranPesan' => $lampiranPath,
            'waktukirim' => Carbon::now(),
            'statusBacaPengguna' => 0, // Mark as unread for the user (recipient)
            'statusBacaStylist' => 1,  // Mark as read for the stylist (sender)
        ]);

        // Use the correct route name for redirect: chat.showChatUser
        return redirect()->route('chat.showChatUser', $user->idPengguna);
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

        // Use the correct view path: resources/views/chat/listmessage.blade.php
        return view('chat.listmessage', compact('messages', 'user'))->render();
    }

    public function markAsRead(Pesan $pesan)
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Mark as read ONLY if the message was sent by the user to THIS stylist,
        // AND the stylist hasn't read it yet.
        // Also, ensure the message's recipient is the current stylist.
        if ($pesan->idStylist == $stylist->idStylist && $pesan->statusBacaStylist == 0) {
            $pesan->update(['statusBacaStylist' => 1]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Pesan tidak dapat ditandai sebagai dibaca atau sudah dibaca.'], 400);
    }
}
