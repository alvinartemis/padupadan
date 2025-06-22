<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Stylist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\KoleksiPakaian;
use App\Models\ItemFashion;

class ChatStylistController extends Controller
{
    public function index()
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return redirect()->route('stylist.login');
        }
        $recentChats = [];
        $usersInvolvedIds = Pesan::where('idStylist', $stylist->idStylist)
                               ->pluck('idPengguna')
                               ->merge(
                                   Pesan::where('idPengguna', $stylist->idStylist)
                                        ->pluck('idStylist')
                               )
                               ->unique()
                               ->toArray();

        foreach ($usersInvolvedIds as $userId) {
            $user = User::find($userId);
            if (!$user) {
                continue;
            }

            $lastMessage = Pesan::where(function ($query) use ($stylist, $user) {
                $query->where('idPengguna', $user->idPengguna)
                      ->where('idStylist', $stylist->idStylist);
            })->orWhere(function ($query) use ($stylist, $user) {
                $query->where('idPengguna', $stylist->idStylist)
                      ->where('idStylist', $user->idPengguna);
            })
                ->orderBy('waktukirim', 'desc')
                ->first();

            $unreadCount = Pesan::where('idPengguna', $user->idPengguna)
                ->where('idStylist', $stylist->idStylist)
                ->where('statusBacaStylist', 0)
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

        $alreadyChattingUserIds = $recentChats->pluck('user.idPengguna')->toArray();
        $usersToChat = User::whereNotIn('idPengguna', $alreadyChattingUserIds)->get();

        return view('chat.indexstylist', compact('recentChats', 'usersToChat'));
    }

    public function showChatWithUser(User $user)
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return redirect()->route('stylist.login');
        }

        $messages = Pesan::where(function ($query) use ($stylist, $user) {
            $query->where('idPengguna', $user->idPengguna)
                  ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($stylist, $user) {
            $query->where('idPengguna', $stylist->idStylist)
                  ->where('idStylist', $user->idPengguna);
        })
            ->orderBy('waktukirim', 'asc')
            ->get();

        Pesan::where('idPengguna', $user->idPengguna)
            ->where('idStylist', $stylist->idStylist)
            ->where('statusBacaStylist', 0)
            ->update(['statusBacaStylist' => 1]);

        $loggedInStylistId = $stylist->idStylist;
        return view('chat.showstylist', compact('user', 'messages', 'loggedInStylistId'));
    }

    public function showProfileUser(Request $request, User $user)
    {
        $koleksiOutfits = $user->koleksiPakaian()
                               ->where('visibility', 'Public')
                               ->get();
        $sections = ['items', 'outfits'];
        $categories = [
            'All', 'Top', 'Bottom', 'Outerwear', 'Dress', 'Accessories', 'Footwear'
        ];
        $selectedSection = 'outfits';
        $selectedCategory = 'All';

        return view('chat.profileuser', compact(
            'user',
            'sections',
            'categories',
            'selectedSection',
            'selectedCategory',
            'koleksiOutfits'
        ));
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'isiPesan' => 'required|string|max:1000',
            'lampiranPesan' => 'nullable|file|mimes:png,jpg,jpeg,heic',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiranPesan')) {
            $lampiranPath = $request->file('lampiranPesan')->store('chat_attachments', 'public');
        }

        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return redirect()->route('stylist.login')->with('error', 'Anda harus login sebagai stylist untuk mengirim pesan.');
        }
        $stylistId = $stylist->idStylist;

        Pesan::create([
            'idStylist' => $stylistId,
            'idPengguna' => $user->idPengguna,
            'isiPesan' => $request->input('isiPesan'),
            'lampiranPesan' => $lampiranPath,
            'waktukirim' => Carbon::now(),
            'statusBacaPengguna' => 0,
            'statusBacaStylist' => 1,
            'sender_type' => 'stylist',
        ]);

        return redirect()->route('chat.showstylist', ['user' => $user->idPengguna]);
    }

    public function getMessages(User $user)
    {
        $stylist = Auth::guard('stylist')->user();
        $messages = Pesan::where(function ($query) use ($stylist, $user) {
            $query->where('idPengguna', $user->idPengguna)
                  ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($stylist, $user) {
            $query->where('idPengguna', $stylist->idStylist)
                  ->where('idStylist', $user->idPengguna);
        })
            ->orderBy('waktukirim', 'asc')
            ->get();
        $loggedInStylistId = $stylist->idStylist;
        return view('chat.listmessage', compact('messages', 'user', 'loggedInStylistId'))->render();
    }

    public function markAsRead(Pesan $pesan)
    {
        $stylist = Auth::guard('stylist')->user();
        if (!$stylist) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($pesan->idStylist == $stylist->idStylist && $pesan->statusBacaStylist == 0) {
            $pesan->update(['statusBacaStylist' => 1]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Pesan tidak dapat ditandai sebagai dibaca atau sudah dibaca.'], 400);
    }
}
