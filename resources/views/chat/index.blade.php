@extends('layouts.chat')

@section('content')
    <div style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333; margin-bottom: 20px;">Messages</h2>

        @if ($recentChats->isNotEmpty())
            <div style="margin-bottom: 20px;">
                @foreach ($recentChats as $chat)
                    <a href="{{ route('chat.show', $chat['stylist']) }}" style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee; text-decoration: none; color: #333;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; margin-right: 15px;">
                            <img src="{{ asset('storage/' . $chat['stylist']->profilepicture) }}" alt="{{ $chat['stylist']->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-weight: 600;">{{ $chat['stylist']->nama }}</div>
                            <div style="font-size: 0.9rem; color: #777;">{{ Str::limit($chat['last_message']->isiPesan ?? '', 50) }}</div>
                        </div>
                        <div style="font-size: 0.8rem; color: #999; margin-left: 15px;">{{ $chat['last_message']->waktuKirim->diffForHumans() ?? '' }}</div>
                        @if ($chat['unread_count'] > 0)
                            <div style="background-color: #ffc107; color: #fff; border-radius: 50%; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center; font-size: 0.7rem; margin-left: 10px;">{{ $chat['unread_count'] }}</div>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <p style="color: #777;">No Conversation Yet</p>
        @endif

        <h2 style="color: #333; margin-top: 30px; margin-bottom: 15px;">Our Stylist</h2>
        @include('chat.liststylist', ['stylists' => $stylists])
    </div>
@endsection
