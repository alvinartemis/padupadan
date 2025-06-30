@extends('layouts.chat')

@section('title', $stylist->nama . ' Profile')

@section('content')
    <div style="background-color: #ffffff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); max-width: 700px; margin: 30px auto; padding: 30px;">
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 25px;">
            <button onclick="window.history.back()"style="background: none; border: none; color: #999; font-size: 1.5rem; cursor: pointer;">
                &times;
            </button>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 30px;">
            <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; margin-right: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);">
                <img src="{{ asset('stylist/' . $stylist->profilepicture) }}" alt="{{ $stylist->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
                <h2 style="margin-top: 0; margin-bottom: 5px; color: #333; font-weight: 600; font-size: 1.8rem;">{{ $stylist->nama }}</h2>
                <p style="margin-top: 0; margin-bottom: 10px; color: #777; font-size: 1rem;">{{ '@' . $stylist->username }}</p>
                <p style="margin-top: 0; margin-bottom: 15px; color: #555; font-size: 1.2rem;">{{ $stylist->job }}</p>
            </div>
            <a href="{{ route('chat.show', $stylist) }}" style="background-color: #F4BC43; color: #fff; border: none; padding: 12px 28px; border-radius: 25px; text-decoration: none; font-size: 1rem; font-weight: 500; transition: background-color 0.3s ease; align-self: center; margin-left: 170px; margin-right: 50px;">
                Chat
            </a>
        </div>

       <div style="display: flex; justify-content: space-around; flex-wrap: wrap; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
            <div style="display: flex; align-items: center; margin: 10px 15px;">
                <img src="{{ asset('img/spe.png') }}" alt="Speciality Icon" style="width: 24px; height: 24px; margin-right: 8px;">
                <div>
                    <p style="margin: 0; font-size: 0.85rem; color: #777;">Speciality</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $stylist->speciality ?? '-' }}</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 15px;">
                <img src="{{ asset('img/loc.png') }}" alt="Location Icon" style="width: 24px; height: 24px; margin-right: 8px;">
                <div>
                    <p style="margin: 0; font-size: 0.85rem; color: #777;">Location</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $stylist->location ?? '-' }}</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 15px;">
                <img src="{{ asset('img/gen.png') }}" alt="Gender Icon" style="width: 24px; height: 24px; margin-right: 8px;">
                <div>
                    <p style="margin: 0; font-size: 0.85rem; color: #777;">Gender</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $stylist->gender ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div>
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.3rem; color: #333; font-weight: 600; display: flex; align-items: center;">
                <img src="{{ asset('img/lb.png') }}" alt="Lookbook Icon" style="width: 24px; height: 24px; margin-right: 8px;">
                Lookbook
            </h3>
            @if($lookbooks->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    @foreach($lookbooks as $lookbook)
                        <a href="{{ route('lookbook.show', $lookbook->idLookbook) }}" style="text-decoration: none; color: inherit;">
                            <div style="text-align: center; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); background-color: #f9f9f9;">
                                <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}" style="width: 100%; height: auto; max-height: 200px; object-fit: contain; display: block; padding: 5px; box-sizing: border-box;">
                                <p style="margin-top: 8px; margin-bottom: 0; font-size: 0.9rem; color: #555;">{{ $lookbook->nama }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 20px; color: #777; font-style: italic;">
                    <p>Stylist ini belum mengunggah lookbook.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
