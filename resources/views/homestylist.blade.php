<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Padu Padan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
            align-items: flex-start;
        }

        .sidebar .logo {
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            height: 40px;
            width: auto;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .sidebar nav ul li {
            margin-bottom: 15px;
        }

        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #A3A3A3;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            color: #173F63;
        }

        .sidebar nav ul li a svg {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            color: #A3A3A3;
        }

        .sidebar nav ul li a.active svg {
            color: #173F63;
        }

        /* --- Main Content Area --- */
        .content-area {
            flex-grow: 1;
            padding: 20px;
            margin-left: 250px; /* Offset for the fixed sidebar */
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the content box */
            justify-content: center; /* Center the content box vertically */
        }

        .content {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 700px; /* Set max-width for the content box */
            width: 100%; /* Ensure it takes full width up to max-width */
            padding: 30px; /* Consistent padding */
            box-sizing: border-box;
            display: flex;
            flex-direction: column; /* Stack sections vertically */
        }

        /* --- Profile Header Section --- */
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 30px;
        }
        /* New styles for 1:2 layout */
        .profile-container {
            display: flex;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 30px;
            gap: 30px; /* Space between the two columns */
        }

        .profile-left {
            flex: 1; /* Takes 1 part of the available space */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .profile-right {
            flex: 2; /* Takes 2 parts of the available space */
            display: flex;
            flex-direction: column;
            /* Adjustments for alignment */
            justify-content: flex-start; /* Align content to the top */
            align-items: flex-start; /* Align content to the left */
        }

        .profile-picture {
            width: 180px; /* Increased size */
            height: 180px; /* Increased size */
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px; /* Space below image */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .profile-text-info {
            /* flex-grow: 1; */ /* Removed as it's now within profile-right */
            text-align: left; /* Align text to the left */
            margin-bottom: 20px; /* Add some space below this section */
            width: 100%; /* Ensure it takes full width for alignment */
        }

        .profile-name {
            margin-top: 0;
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
            font-size: 1.8rem;
        }

        .profile-username {
            margin-top: 0;
            margin-bottom: 10px;
            color: #777;
            font-size: 1rem;
        }

        .profile-role {
            margin-top: 0;
            margin-bottom: 0;
            color: #555;
            font-size: 1.2rem;
        }

        /* --- Profile Info Grid Section --- */
        .profile-info-grid {
            display: flex;
            justify-content: flex-start; /* Align items to the left */
            flex-wrap: wrap; /* Keep wrap for responsiveness if needed */
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            width: 100%; /* Ensure it takes full width for alignment */
        }

        .profile-info-item {
            display: flex;
            align-items: center;
            margin: 10px 15px 10px 0; /* Adjust margin-left for alignment */
            gap: 8px;
        }

        .profile-info-item img {
            width: 24px;
            height: 24px;
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

        /* --- Lookbook Section --- */
        .lookbook-section {
            padding: 0;
            border-right: none;
            width: 100%; /* Ensure it takes full width within the content area */
        }

        .lookbook-section h3 {
            font-size: 1.3rem;
            color: #333;
            font-weight: 600;
            display: flex;
            align-items: center;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .lookbook-section h3 img {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .lookbook-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .lookbook-card {
            text-align: center;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            background-color: #fff;
            padding-bottom: 0;
        }

        .lookbook-card a {
            text-decoration: none;
            color: inherit;
        }

        .lookbook-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
            border-bottom: none;
            margin-bottom: 0;
        }

        .lookbook-card h4 {
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 0.9rem;
            color: #555;
            padding: 10px;
        }

        /* --- Logout Button --- */
        .logout-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .logout-button {
            background-color: #FF4B5C;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }
        .logout-button:hover {
            background-color: #E03F4F;
        }

        /* --- Modal Styles --- */
        .modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            justify-content: center;
            align-items: center;
            z-index: 1000; /* On top of everything */
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
            background-color: #FFD700; /* Yellow background */
            color: #333; /* Dark text */
        }

        .modal-button.yes:hover {
            background-color: #E6C200; /* Darker yellow on hover */
        }

        .modal-button.no {
            background-color: #e0e0e0;
            color: #333;
        }

        .modal-button.no:hover {
            background-color: #d0d0d0;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logoy.png') }}" alt="Logo Padu Padan">
        </div>
        <nav>
            <ul>
                <li>
                    <a href="{{ url('/homestylist') }}" class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464l349.5 0c-8.9-63.3-63.3-112-129-112l-91.4 0c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3z"/></svg>
                        Profile
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Lookbook
                    </a>
                </li>
                <li>
                    <a href="{{ url('/stylist/chat') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M123.6 391.3c12.9-9.4 29.6-11.8 44.6-6.4c26.5 9.6 56.2 15.1 87.8 15.1c124.7 0 208-80.5 208-160s-83.3-160-208-160S48 160.5 48 240c0 32 12.4 62.8 35.7 89.2c8.6 9.7 12.8 22.5 11.8 35.5c-1.4 18.1-5.7 34.7-11.3 49.4c17-7.9 31.1-16.7 39.4-22.7zM21.2 431.9c1.8-2.7 3.5-5.4 5.1-8.1c10-16.6 19.5-38.4 21.4-62.9C17.7 326.8 0 285.1 0 240C0 125.1 114.6 32 256 32s256 93.1 256 208s-114.6 208-256 208c-37.1 0-72.3-6.4-104.1-17.9c-11.9 8.7-31.3 20.6-54.3 30.6c-15.1 6.6-32.3 12.6-50.1 16.1c-.8 .2-1.6 .3-2.4 .5c-4.4 .8-8.7 1.5-13.2 1.9c-.2 0-.5 .1-.7 .1c-5.1 .5-10.2 .8-15.3 .8c-6.5 0-12.3-3.9-14.8-9.9c-2.5-6-1.1-12.8 3.4-17.4c4.1-4.2 7.8-8.7 11.3-13.5c1.7-2.3 3.3-4.6 4.8-6.9l.3-.5z"/></svg>
                        Chat
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="content-area">
        @if($stylist)
            <div class="profile-container">
                <div class="profile-left">
                    <img src="{{ asset('stylist/' . $stylist->profilepicture) }}" alt="{{ $stylist->nama }}" class="profile-picture">
                    <div class="logout-container">
                        {{-- Change the form submission to a button that triggers the modal --}}
                        <button type="button" class="logout-button" id="logoutTrigger">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3v-5m14-12V7a3 3 0 00-3-3H5a3 3 0 00-3 3v5" />
                            </svg>
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
                                    <a href="{{ url('/lookbook/' . $lookbook->idLookbook) }}">
                                        <img src="{{ asset('storage/' . $lookbook->image_path) }}" alt="{{ $lookbook->title }}">
                                        <h4>{{ $lookbook->title }}</h4>
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
    </div>

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <p>Are you sure you want to log out?</p>
            <div class="modal-buttons">
                <button type="button" class="modal-button yes" id="confirmLogout">Yes</button>
                <button type="button" class="modal-button no" id="cancelLogout">No</button>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutTrigger = document.getElementById('logoutTrigger');
            const logoutModal = document.getElementById('logoutModal');
            const confirmLogoutButton = document.getElementById('confirmLogout');
            const cancelLogoutButton = document.getElementById('cancelLogout');

            logoutTrigger.addEventListener('click', function() {
                logoutModal.style.display = 'flex'; // Show the modal overlay
            });

            cancelLogoutButton.addEventListener('click', function() {
                logoutModal.style.display = 'none'; // Hide the modal overlay
            });

            confirmLogoutButton.addEventListener('click', function() {
                // If "Yes" is clicked, submit the logout form
                const logoutForm = document.createElement('form');
                logoutForm.action = "{{ route('stylist.logout') }}";
                logoutForm.method = "POST";
                logoutForm.style.display = "none"; // Hide the form

                // Add CSRF token for Laravel
                const csrfToken = document.querySelector('meta[name="csrf-token"]') || { content: '' }; // Fallback
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.content; // Assuming you have a meta tag for CSRF token
                logoutForm.appendChild(csrfInput);


                // Append the form to the body and submit it
                document.body.appendChild(logoutForm);
                logoutForm.submit();
            });

            // Optional: Close modal if user clicks outside the modal content
            logoutModal.addEventListener('click', function(event) {
                if (event.target === logoutModal) {
                    logoutModal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
