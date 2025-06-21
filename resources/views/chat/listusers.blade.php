<div class="user-list-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 15px;">
    @forelse ($usersToChat as $user)
        {{-- Memanggil rute 'chat.showstylist' dan melewatkan ID user --}}
        <a href="{{ route('chat.showstylist', ['user' => $user->idPengguna]) }}" style="text-decoration: none; color: inherit;">
            <div style="text-align: center; background-color: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; margin: 0 auto 10px;">
                    <img src="{{ asset('user/' . $user->profilepicture) }}" alt="{{ $user->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="font-weight: 600; font-size: 0.9rem; color: #333;">{{ $user->nama }}</div>
            </div>
        </a>
    @empty
        <p style="color: #777; grid-column: 1 / -1;">No other users available to chat with at the moment.</p>
    @endforelse
</div>
