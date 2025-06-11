<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Stylist; // Already there
use App\Models\User;   // Already there
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
        $usersInvolved = Pesan::where('idStylist', $stylist->idStylist) // Messages where stylist is recipient
                            ->pluck('idPengguna')
                            ->merge(
                                Pesan::where('idPengguna', $stylist->idStylist) // Messages where stylist is sender
                                    ->pluck('idStylist')
                            )
                            ->unique()
                            ->toArray();

        foreach ($usersInvolved as $userId) {
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

        $recentChats = collect($recentChats)->sortByDesc(function ($chat) {
            return optional($chat['last_message'])->waktukirim;
        });

        // Use the recommended view path: resources/views/stylist/chat/index.blade.php
        return view('stylist.chat.index', compact('recentChats'));
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

        // Use the recommended view path: resources/views/stylist/chat/show.blade.php
        return view('stylist.chat.show', compact('user', 'messages'));
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
            'idPengguna' => $stylist->idStylist, // FIX: Stylist is the sender (idPengguna)
            'idStylist' => $user->idPengguna,    // FIX: User is the recipient (idStylist)
            'isiPesan' => $request->input('isiPesan'),
            'lampiranPesan' => $lampiranPath,
            'waktukirim' => Carbon::now(),
            'statusBacaPengguna' => 0, // Mark as unread for the user (recipient)
            'statusBacaStylist' => 1,  // Mark as read for the stylist (sender)
        ]);

        // Use the correct route name: stylist.chat.show
        return redirect()->route('stylist.chat.show', $user->idPengguna);
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

        // Use the recommended view path: resources/views/stylist/chat/listmessage.blade.php
        return view('stylist.chat.listmessage', compact('messages', 'user'))->render();
    }

    public function markAsRead(Pesan $pesan)
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Mark as read ONLY if the message was sent by the user to THIS stylist,
        // AND the stylist hasn't read it yet.
        if ($pesan->idPengguna == $pesan->idPengguna && $pesan->idStylist == $stylist->idStylist && $pesan->statusBacaStylist == 0) {
            $pesan->update(['statusBacaStylist' => 1]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Pesan tidak dapat ditandai sebagai dibaca atau sudah dibaca.'], 400);
    }
}
