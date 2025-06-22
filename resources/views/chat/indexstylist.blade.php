@extends('layouts.stylist')

@section('content')
    <div style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333; margin-bottom: 20px;">Messages</h2>

        @if ($recentChats->isNotEmpty())
            <div id="recent-chats-container" style="margin-bottom: 20px;">
                @foreach ($recentChats as $chat)
                    <div style="background-color: white; border-radius: 8px; margin-bottom: 10px; padding: 10px; display: flex; align-items: flex-start; position: relative;">
                        <a href="{{ route('chat.profileuser', ['user' => $chat['user']->idPengguna]) }}" style="display: block; flex-shrink: 0; margin-right: 15px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden">
                                <img src="{{ asset('user/' . $chat['user']->profilepicture) }}" alt="{{ $chat['user']->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </a>
                        <a href="{{ route('chat.showstylist', ['user' => $chat['user']->idPengguna]) }}" style="display: flex; flex-direction: column; flex-grow: 1; text-decoration: none; color: #333; cursor: pointer;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                                <div style="font-weight: 600; flex-grow: 1; margin-right: 10px;">{{ $chat['user']->nama }}</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-top: 2px;">
                                <div style="font-size: 0.9rem; color: #777;flex-grow: 1; margin-right: 10px;">{{ Str::limit(optional($chat['last_message'])->isiPesan ?? '', 50) }}</div>
                                <div style="font-size: 0.8rem; color: #999;flex-shrink: 0;">{{ optional(optional($chat['last_message'])->waktukirim)->diffForHumans() ?? '' }}</div>
                                @if ($chat['unread_count'] > 0)
                                    <div style="background-color: #ffc107; color: #fff; border-radius: 50%; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center; font-size: 0.7rem; margin-top: 5px;">{{ $chat['unread_count'] }}</div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #777;">No Conversation Yet</p>
        @endif

        <h2 style="color: #333; margin-top: 30px; margin-bottom: 15px;">Users Available to Chat</h2>
        @include('chat.listusers', ['usersToChat' => $usersToChat])
    </div>
@endsection
