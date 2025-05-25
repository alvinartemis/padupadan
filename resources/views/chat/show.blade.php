<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
    @foreach ($stylists as $stylist)
        <a href="{{ route('chat.profilestylist', $stylist) }}" style="text-decoration: none; color: inherit;">
            <div style="background-color: #f9f9f9; border-radius: 8px; padding: 15px; text-align: center; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);">
                <div style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; margin: 0 auto 10px;">
                    <img src="{{ asset('storage/' . $stylist->profilepicture) }}" alt="{{ $stylist->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="font-weight: 600; color: #333; margin-bottom: 5px; font-size: 0.9rem;">{{ $stylist->nama }}</div>
                <div style="color: #777; font-size: 0.8rem; margin-bottom: 5px;">{{ '@' . $stylist->username }}</div>
                <div style="color: #555; font-size: 0.8rem; margin-bottom: 8px;">{{ $stylist->job }}</div>
                @if ($stylist->speciality)
                    <div style="font-size: 0.75rem; color: #999;">
                        @foreach (explode(',', $stylist->speciality) as $speciality)
                            <span style="background-color: #e0e0e0; padding: 3px 6px; border-radius: 5px; margin-right: 5px;">{{ trim($speciality) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </a>
    @endforeach
</div>
