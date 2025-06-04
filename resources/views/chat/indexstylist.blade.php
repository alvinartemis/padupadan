@extends('layouts.chat') {{-- Adjust this based on your actual layout file --}}

@section('title', 'Stylist Chat')

@section('content')
<div class="chat-container">
    <div class="chat-sidebar">
        <h3>Recent Chats</h3>
        <ul class="recent-chats-list">
            @forelse($recentChats as $chat)
                <li class="{{ request()->routeIs('stylist.chat.show') && $chat['user']->idPengguna == optional(request()->route('user'))->idPengguna ? 'active' : '' }}">
                    <a href="{{ route('stylist.chat.show', $chat['user']->idPengguna) }}">
                        <div class="chat-info">
                            <img src="{{ asset('user/' . $chat['user']->profilepicture) }}" alt="{{ $chat['user']->nama }}" class="chat-avatar">
                            <div class="chat-details">
                                <span class="chat-name">{{ $chat['user']->nama }}</span>
                                @if($chat['last_message'])
                                    <span class="last-message">{{ Str::limit($chat['last_message']->isiPesan, 30) }}</span>
                                @else
                                    <span class="last-message">Start a conversation</span>
                                @endif
                            </div>
                        </div>
                        @if($chat['unread_count'] > 0)
                            <span class="unread-count">{{ $chat['unread_count'] }}</span>
                        @endif
                    </a>
                </li>
            @empty
                <p class="no-chats">No recent chats yet.</p>
            @endforelse
        </ul>

        {{-- You might want a button here to start a new chat if there are no recent ones, or to view all users --}}
        {{-- <div class="start-new-chat">
            <a href="{{ route('stylist.chat.listuser') }}" class="button">Start New Chat</a>
        </div> --}}
    </div>

    <div class="chat-main-content">
        @if(isset($user))
            @include('stylist.chat.showstylist')
        @else
            <div class="no-chat-selected">
                <p>Select a user from the sidebar to view the chat.</p>
            </div>
        @endif
    </div>
</div>

<style>
    /* Add specific styles for stylist chat if needed, otherwise use general chat styles */
    .chat-container {
        display: flex;
        height: calc(100vh - 40px); /* Adjust based on header/footer */
        margin: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .chat-sidebar {
        width: 300px;
        border-right: 1px solid #eee;
        padding: 20px;
        box-sizing: border-box;
        overflow-y: auto;
    }

    .chat-sidebar h3 {
        margin-top: 0;
        margin-bottom: 25px;
        color: #333;
        font-size: 1.4rem;
    }

    .recent-chats-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .recent-chats-list li {
        margin-bottom: 10px;
    }

    .recent-chats-list li a {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        color: #555;
        transition: background-color 0.2s ease;
        position: relative;
    }

    .recent-chats-list li a:hover,
    .recent-chats-list li.active a {
        background-color: #f0f0f0;
    }

    .chat-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }

    .chat-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }

    .chat-details {
        display: flex;
        flex-direction: column;
    }

    .chat-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: #333;
    }

    .last-message {
        font-size: 0.9rem;
        color: #777;
        margin-top: 3px;
    }

    .unread-count {
        background-color: #FFD700;
        color: #173F63;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 15px;
        margin-left: 10px;
    }

    .no-chats {
        text-align: center;
        color: #888;
        padding: 20px;
    }

    .chat-main-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .no-chat-selected {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        color: #aaa;
        font-size: 1.2rem;
    }
</style>
@endsection
