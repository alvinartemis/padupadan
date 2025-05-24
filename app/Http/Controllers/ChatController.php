<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\Stylist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Menampilkan halaman utama chat (daftar pesan terakhir dan daftar stylist).
     */
    public function index()
    {
        $stylists = Stylist::all();
        $pengguna = Auth::user();
        $existingChatStylistIds = $pengguna->chatsWithStylists()->pluck('idStylist')->toArray();
        $lastMessages = Pesan::where('idPengguna', $pengguna->idPengguna)
            ->whereIn('idStylist', $existingChatStylistIds)
            ->orWhere(function ($query) use ($pengguna, $existingChatStylistIds) {
                $query->where('idStylist', $pengguna->idPengguna)
                      ->whereIn('idPengguna', $existingChatStylistIds);
            })
            ->orderBy('waktukirim', 'desc')
            ->get()
            ->unique(function ($item) {
                return ($item->idPengguna == Auth::id()) ? $item->idStylist : $item->idPengguna;
            })
            ->take(10);

        $stylists = Stylist::whereNotIn('idStylist', $existingChatStylistIds)->get();

        $recentChats = $lastMessages->map(function ($message) use ($pengguna) {
            $stylistId = ($message->idPengguna == $pengguna->idPengguna) ? $message->idStylist : $message->idPengguna;
            $stylist = Stylist::find($stylistId);
            $lastMessage = Pesan::where(function ($q) use ($pengguna, $stylistId) {
                $q->where('idPengguna', $pengguna->idPengguna)->where('idStylist', $stylistId);
            })->orWhere(function ($q) use ($pengguna, $stylistId) {
                $q->where('idStylist', $pengguna->idPengguna)->where('idPengguna', $stylistId);
            })->orderBy('waktukirim', 'desc')->first();

            $unreadCount = Pesan::where('idPengguna', $stylistId)
                ->where('idStylist', $pengguna->idPengguna)
                ->where('statusBacaPengguna', 0)
                ->count();

            return [
                'stylist' => $stylist,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        })->filter(function ($chat) {
            return $chat['stylist'] !== null;
        });

        return view('chat.index', compact('recentChats', 'stylists'));
    }

    /**
     * Mengambil daftar stylist untuk ditampilkan kepada pengguna yang belum pernah chat.
     */
    public function getStylists()
    {
        $stylists = Stylist::all();
        return response()->json($stylists);
    }

    /**
     * Menampilkan halaman percakapan dengan stylist tertentu.
     */
    public function showChatWithStylist(Stylist $stylist)
    {
        $pengguna = Auth::user();
        $messages = Pesan::where(function ($query) use ($pengguna, $stylist) {
            $query->where('idPengguna', $pengguna->idPengguna)
                  ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($pengguna, $stylist) {
            $query->where('idStylist', $pengguna->idPengguna)
                  ->where('idPengguna', $stylist->idStylist);
        })
        ->orderBy('waktukirim', 'asc')
        ->get();

        // Tandai pesan dari stylist ke pengguna sebagai sudah dibaca
        Pesan::where('idPengguna', $stylist->idStylist)
            ->where('idStylist', $pengguna->idPengguna)
            ->where('statusBacaPengguna', 0)
            ->update(['statusBacaPengguna' => 1]);

        return view('chat.show', compact('stylist', 'messages'));
    }

    /**
     * Menerima dan menyimpan pesan baru.
     */
    public function sendMessage(Request $request, Stylist $stylist)
    {
        $request->validate([
            'isiPesan' => 'required|string',
            'lampiranPesan' => 'nullable|file|max:2048', // Contoh validasi lampiran
        ]);

        $pengguna = Auth::user();

        $pesan = new Pesan();
        $pesan->idPengguna = $pengguna->idPengguna;
        $pesan->idStylist = $stylist->idStylist;
        $pesan->isiPesan = $request->input('isiPesan');
        $pesan->waktukirim = now();
        $pesan->statusBacaPengguna = 1;
        $pesan->statusBacaStylist = 0;

        if ($request->hasFile('lampiranPesan')) {
            $path = $request->file('lampiranPesan')->store('chat_attachments', 'public');
            $pesan->lampiranPesan = $path;
        }

        $pesan->save();

        return redirect()->route('chat.show', $stylist);
    }

    /**
     * Mengambil riwayat pesan dengan stylist tertentu (untuk AJAX).
     */
    public function getMessages(Stylist $stylist)
    {
        $pengguna = Auth::user();
        $messages = Pesan::where(function ($query) use ($pengguna, $stylist) {
            $query->where('idPengguna', $pengguna->idPengguna)
                  ->where('idStylist', $stylist->idStylist);
        })->orWhere(function ($query) use ($pengguna, $stylist) {
            $query->where('idStylist', $pengguna->idPengguna)
                  ->where('idPengguna', $stylist->idStylist);
        })
        ->orderBy('waktukirim', 'asc')
        ->get();

        return response()->json($messages);
    }

    /**
     * Menandai pesan sebagai sudah dibaca oleh pengguna.
     */
    public function markAsRead(Request $request, Pesan $pesan)
    {
        if ($pesan->idPengguna == $request->user()->idPengguna) {
            $pesan->statusBacaPengguna = 1;
            $pesan->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
}
