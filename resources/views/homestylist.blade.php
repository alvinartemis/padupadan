@extends('layouts.stylist')
@section('title', 'Stylist Profile - Padu Padan')
@section('content')
    <style>
        .content-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 20px;
            margin-left: 250px;
            box-sizing: border-box;
            width: calc(100% - 250px);
            box-shadow: none;
            border-radius: 0;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .content {
            background-color: transparent !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            max-width: none !important;
            width: 100% !important;
            display: block !important;
        }


        .profile-container {
            display: flex;
            width: 100%;
            max-width: 960px;
            margin-bottom: 30px;
            border-bottom: none;
            gap: 40px;
            align-items: flex-start;
            box-sizing: border-box;
        }

        .profile-left {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
        }

        .profile-right {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .profile-picture {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .profile-text-info {
            text-align: left;
            margin-bottom: 30px;
            width: 100%;
        }

        .profile-name {
            margin-top: 0;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 1.8rem;
        }

        .profile-username {
            margin-top: 0;
            margin-bottom: 12px;
            color: #777;
            font-size: 1rem;
        }

        .profile-role {
            margin-top: 0;
            margin-bottom: 0;
            color: #555;
            font-size: 1.2rem;
            line-height: 1.4;
        }

        .profile-info-grid {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
            width: 100%;
            gap: 50px;
            row-gap: 20px;
        }

        .profile-info-item {
            display: flex;
            align-items: flex-start;
            margin: 0;
            gap: 12px;
            white-space: nowrap;
        }
        .profile-info-item img {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .profile-info-item div {
            display: flex;
            flex-direction: column;
        }
        .profile-info-item div p:first-child {
            margin: 0;
            font-size: 0.85rem;
            color: #777;
        }
        .profile-info-item div p:last-child {
            margin: 2px 0 0 0;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .lookbook-section {
            padding: 0;
            width: 100%;
        }

        .lookbook-section h3 {
            margin-bottom: 25px;
            font-size: 1.4rem;
        }

        .lookbook-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .lookbook-card {
            text-align: center;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding-bottom: 0;
            transition: transform 0.2s ease-in-out;
            min-height: 280px;
            display: flex;
            flex-direction: column;
        }
        .lookbook-card:hover {
            transform: translateY(-5px);
        }

        .lookbook-card img {
            width: 100%;
            height: max-content;
            object-fit: cover;
            display: block;
            margin-bottom: 0;
        }

        .lookbook-card h4 {
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #333;
            padding: 0 10px;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            width: 100%;
        }
        .logout-button {
            background-color: #FFFFFF;
            color: #081B2C;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
            width: auto;
            min-width: 120px;
            justify-content: center;
        }
        .logout-button:hover {
            background-color: #E03F4F;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 400px;
            box-sizing: border-box;
        }

        .modal-content p {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 1.1rem;
            color: #333;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-button {
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .modal-button.yes {
            background-color: #F4F4F4;
            color: #0C2A42;
        }

        .modal-button.yes:hover {
            background-color: #F4BC43;
            color: #F4F4F4;
        }

        .modal-button.no {
            background-color: #F4F4F4;
            color: #0C2A42;
        }

        .modal-button.no:hover {
            background-color: #F4BC43;
            color: #F4F4F4;
        }
    </style>
    @if($stylist)
        <div class="profile-container">
            <div class="profile-left">
                <img src="{{ asset('stylist/' . $stylist->profilepicture) }}" alt="{{ $stylist->nama }}" class="profile-picture">
                <div class="logout-container">
                    <button type="button" class="logout-button" id="logoutTrigger">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
                        Logout
                    </button>
                </div>
            </div>
            <div class="profile-right">
                <div class="profile-text-info">
                    <h2 class="profile-name">{{ $stylist->nama }}</h2>
                    <p class="profile-username">@ {{ $stylist->username }}</p>
                    <p class="profile-role">{{ $stylist->job }}</p>
                </div>
                <div class="profile-info-grid">
                    <div class="profile-info-item">
                        <img src="{{ asset('img/spe.png') }}" alt="Speciality Icon">
                        <div>
                            <p>Speciality</p>
                            <p>{{ $stylist->speciality }}</p>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <img src="{{ asset('img/loc.png') }}" alt="Location Icon">
                        <div>
                            <p>Location</p>
                            <p>{{ $stylist->location }}</p>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <img src="{{ asset('img/gen.png') }}" alt="Gender Icon">
                        <div>
                            <p>Gender</p>
                            <p>{{ $stylist->gender }}</p>
                        </div>
                    </div>
                </div>
                <div class="lookbook-section">
                    <h3>
                        <img src="{{ asset('img/lb.png') }}" alt="Lookbook Icon">
                        Lookbook
                    </h3>
                    <div class="lookbook-grid">
                        @forelse($lookbooks as $lookbook)
                            <div class="lookbook-card">
                                <a href="{{ route('stylist.lookbook.show', $lookbook->idLookbook) }}">
                                    <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}">
                                    <h4>{{ $lookbook->nama }}</h4>
                                </a>
                            </div>
                        @empty
                            <p>No lookbook items found for this stylist.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>Stylist data not found.</p>
    @endif

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <p>Are you sure you want to log out?</p>
            <div class="modal-buttons">
                <button type="button" class="modal-button yes" id="confirmLogout">Yes</button>
                <button type="button" class="modal-button no" id="cancelLogout">No</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutTrigger = document.getElementById('logoutTrigger');
            const logoutModal = document.getElementById('logoutModal');
            const confirmLogoutButton = document.getElementById('confirmLogout');
            const cancelLogoutButton = document.getElementById('cancelLogout');

            if (logoutTrigger) {
                logoutTrigger.addEventListener('click', function() {
                    logoutModal.style.display = 'flex';
                });
            }

            if (cancelLogoutButton) {
                cancelLogoutButton.addEventListener('click', function() {
                    logoutModal.style.display = 'none';
                });
            }

            if (confirmLogoutButton) {
                confirmLogoutButton.addEventListener('click', function() {
                    const logoutForm = document.createElement('form');
                    logoutForm.action = "{{ route('stylist.logout') }}";
                    logoutForm.method = "POST";
                    logoutForm.style.display = "none";

                    const csrfToken = document.querySelector('meta[name="csrf-token"]') || { content: '' };
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken.content;
                    logoutForm.appendChild(csrfInput);

                    document.body.appendChild(logoutForm);
                    logoutForm.submit();
                });
            }

            if (logoutModal) {
                logoutModal.addEventListener('click', function(event) {
                    if (event.target === logoutModal) {
                        logoutModal.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endpush
