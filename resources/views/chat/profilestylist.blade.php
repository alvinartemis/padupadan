@extends('layouts.app')

@section('title', $stylist->nama . ' Profile')

@section('content')
    <div style="background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); max-width: 900px; margin: 20px auto; position: relative;">
        <a href="{{ route('chat.index') }}" style="position: absolute; top: 20px; right: 20px; color: #999; text-decoration: none; font-size: 1.5rem;">
            &times;
        </a>

        <div style="display: flex; align-items: center; margin-bottom: 30px;">
            <div style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; margin-right: 25px; flex-shrink: 0;">
                <img src="{{ asset('stylist/' . $stylist->profilepicture) }}" alt="{{ $stylist->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="flex-grow: 1;">
                <h2 style="margin: 0; font-size: 1.8rem; color: #333; font-weight: 700;">{{ $stylist->nama }}</h2>
                <p style="margin: 5px 0 8px 0; color: #777; font-size: 1rem;">{{ '@' . $stylist->username }}</p>
                <p style="margin: 0; color: #555; font-size: 0.9rem;">{{ $stylist->job }}</p>
                <a href="{{ route('chat.show', $stylist) }}" style="background-color: #007bff; color: #fff; border: none; padding: 10px 25px; border-radius: 25px; text-decoration: none; font-size: 1rem; margin-top: 15px; display: inline-block; font-weight: 600; transition: background-color 0.2s ease;">Chat</a>
            </div>
        </div>

        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
            <div style="text-align: center; margin: 10px 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: #007bff; margin-bottom: 5px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <p style="margin: 0; font-size: 0.85rem; color: #777;">Speciality</p>
                <p style="margin: 5px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $stylist->speciality ?? '-' }}</p>
            </div>
            <div style="text-align: center; margin: 10px 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: #007bff; margin-bottom: 5px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p style="margin: 0; font-size: 0.85rem; color: #777;">Location</p>
                <p style="margin: 5px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $stylist->location ?? '-' }}</p>
            </div>
            <div style="text-align: center; margin: 10px 15px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: #007bff; margin-bottom: 5px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <p style="margin: 0; font-size: 0.85rem; color: #777;">Gender</p>
                <p style="margin: 5px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $stylist->gender ?? '-' }}</p>
            </div>
        </div>

        <div>
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.4rem; color: #333; font-weight: 700;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: #007bff; vertical-align: middle; margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Lookbook
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                <div style="text-align: center;">
                    <img src="https://placehold.co/150x150/e0f7fa/007bff?text=Outfit+1" alt="Outfit 1" style="width: 100%; height: auto; border-radius: 8px; object-fit: cover; margin-bottom: 8px;">
                    <p style="margin: 0; font-size: 0.9rem; color: #555;">Playful Fits</p>
                </div>
                <div style="text-align: center;">
                    <img src="https://placehold.co/150x150/e0f7fa/007bff?text=Outfit+2" alt="Outfit 2" style="width: 100%; height: auto; border-radius: 8px; object-fit: cover; margin-bottom: 8px;">
                    <p style="margin: 0; font-size: 0.9rem; color: #555;">College fits</p>
                </div>
                <div style="text-align: center;">
                    <img src="https://placehold.co/150x150/e0f7fa/007bff?text=Outfit+3" alt="Outfit 3" style="width: 100%; height: auto; border-radius: 8px; object-fit: cover; margin-bottom: 8px;">
                    <p style="margin: 0; font-size: 0.9rem; color: #555;">Summer outfits</p>
                </div>
                <div style="text-align: center;">
                    <img src="https://placehold.co/150x150/e0f7fa/007bff?text=Outfit+4" alt="Outfit 4" style="width: 100%; height: auto; border-radius: 8px; object-fit: cover; margin-bottom: 8px;">
                    <p style="margin: 0; font-size: 0.9rem; color: #555;">Basic Fits</p>
                </div>
                </div>
        </div>
    </div>
@endsection
