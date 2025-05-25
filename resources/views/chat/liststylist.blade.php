<style>
    .stylist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        row-gap: 50px;
        margin-top: 20px;
        margin-bottom: 40px;
    }
    .stylist-card {
        background-color: #fdfdfd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }
    .stylist-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .stylist-image {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 10px;
    }
    .stylist-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .stylist-name {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }
    .stylist-username {
        color: #777;
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
    .stylist-job {
        color: #555;
        font-size: 0.8rem;
        margin-bottom: 8px;
        line-height: 1.2;
    }
    .stylist-tags {
        margin-top: 8px;
    }
    .stylist-tags span {
        background-color: #f0f0f0;
        padding: 3px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        margin: 2px 3px;
        display: inline-block;
        color: #666;
    }
</style>

<div class="stylist-grid">
    @foreach ($stylists as $stylist)
        <a href="{{ route('chat.profilestylist', $stylist) }}" style="text-decoration: none; color: inherit;">
            <div class="stylist-card">
                <div class="stylist-image">
                    <img src="{{ asset('stylist/' . $stylist->profilepicture) }}" alt="{{ $stylist->nama }}">
                </div>
                <div class="stylist-name">{{ $stylist->nama }}</div>
                <div class="stylist-username">{{ '@' . $stylist->username }}</div>
                <div class="stylist-job" style="font-size: 0.75rem; line-height: 1.1;">{{ $stylist->job }}</div>
                @if ($stylist->speciality)
                    <div class="stylist-tags">
                        @foreach (explode(',', $stylist->speciality) as $speciality)
                            <span>{{ trim($speciality) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </a>
    @endforeach
</div>
