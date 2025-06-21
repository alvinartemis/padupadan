@extends('layouts.chat')

@section('title', 'Chat dengan ' . $stylist->nama)

@section('content')
    <div style="background-color: #f9f9f9; border-radius: 15px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); max-width: 700px; margin: 30px auto; padding: 30px; display: flex; flex-direction: column; height: 600px;">
        {{-- This div now acts as a flex container to align items --}}
        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #eee; margin-bottom: 20px;">
            <a href="{{ route('chat.profilestylist', $stylist) }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; margin-right: 15px;">
                    <img src="{{ asset('stylist/' . $stylist->profilepicture) }}" alt="{{ $stylist->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 600; color: #333;">{{ $stylist->nama }}</h4>
                    <p style="margin: 5px 0 0 0; color: #777; font-size: 0.9rem;">{{ '@' . $stylist->username }}</p>
                </div>
            </a>
            <button onclick="window.location.href='{{ route('chat.index') }}'" style="background: none; border: none; color: #999; font-size: 1.5rem; cursor: pointer;">
                &times;
            </button>
        </div>
        <div style="flex-grow: 1; overflow-y: auto; padding-bottom: 20px;" id="message-container">
            @include('chat.listmessage', ['messages' => $messages])
        </div>
        <form action="{{ route('chat.send', $stylist) }}" method="POST" enctype="multipart/form-data" style="display: flex; align-items: center; padding-top: 20px; border-top: 1px solid #eee;">
            @csrf
            <button type="button" onclick="$('#lampiranPesan').click()" style="background: none; border: none; color: #777; font-size: 1.2rem; margin-right: 10px; cursor: pointer; opacity: 0.8; transition: opacity 0.2s ease;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width: 1.2em; height: 1.2em; vertical-align: middle; fill: currentColor;">
                    <path d="M364.2 83.8c-24.4-24.4-64-24.4-88.4 0l-184 184c-42.1 42.1-42.1 110.3 0 152.4s110.3 42.1 152.4 0l152-152c10.9-10.9 28.7-10.9 39.6 0s10.9 28.7 0 39.6l-152 152c-64 64-167.6 64-231.6 0s-64-167.6 0-231.6l184-184c46.3-46.3 121.3-46.3 167.6 0s46.3 121.3 0 167.6l-176 176c-28.6 28.6-75 28.6-103.6 0s-28.6-75 0-103.6l144-144c10.9-10.9 28.7-10.9 39.6 0s10.9 28.7 0 39.6l-144 144c-6.7 6.7-6.7 17.7 0 24.4s17.7 6.7 24.4 0l176-176c24.4-24.4 24.4-64 0-88.4z"/>
                </svg>
            </button>
            <input type="text" name="isiPesan" placeholder="Send a message" style="flex-grow: 1; border: 1px solid #ccc; border-radius: 25px; padding: 10px 15px; font-size: 0.95rem; margin-right: 10px;">
            <input type="file" name="lampiranPesan" style="display: none;" id="lampiranPesan">
            <img id="pratinjauLampiran" src="#" alt="Pratinjau" style="display: none; max-width: 100px; max-height: 100px; margin-top: 10px;">
            <button type="submit" style="background-color: #F4BC43; color: #fff; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1.2em; height: 1.2em; fill: currentColor;">
                    <path d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480l0-83.6c0-4 1.5-7.8 4.2-10.8L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/>
                </svg>
            </button>
        </form>
    </div>

<div id="errorPopup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; z-index: 1000;">
        <div style="background-color: #fff; border-radius: 8px; padding: 30px; text-align: center; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 80%; max-width: 400px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 3em; height: 3em; fill: #F4BC43; margin-bottom: 20px;">
                <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480L40 480c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24l0 112c0 13.3 10.7 24 24 24s24-10.7 24-24l0-112c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/>
            </svg>
            <h4 style="margin-top: 0; color: #333; margin-bottom: 20px;">
                @if ($errors->any())
                    @if ($errors->has('isiPesan'))
                        The message text must not be greater than 1000 characters.
                    @elseif ($errors->has('lampiranPesan'))
                        The attachment file format is invalid. Allowed formats: png, jpg, jpeg, heic.
                    @else
                        Oops, Something went wrong!
                    @endif
                @else
                    Oops, Something went wrong!
                @endif
            </h4>
            <div id="errorMessage" style="display: none; margin-bottom: 20px; text-align: left;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="closeErrorPopup()" style="background-color: #ccc; color: #A2A2A2; border: none; padding: 12px 25px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 5px; cursor: pointer;"
            onmouseover="this.style.backgroundColor='#F4BC43'; this.style.color='white';"
            onmouseout="this.style.backgroundColor='#ccc'; this.style.color='#333';"
            ontouchstart="this.style.backgroundColor='#F4BC43'; this.style.color='white';"
            ontouchend="this.style.backgroundColor='#ccc'; this.style.color='#333';">OK</button>
        </div>
    </div>

    <script>
        function scrollToBottom() {
            var messageContainer = document.getElementById('message-container');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
        window.onload = scrollToBottom;

        document.getElementById('lampiranPesan').addEventListener('change', function() {
            var pratinjau = document.getElementById('pratinjauLampiran');
            var file = this.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                pratinjau.src = reader.result;
                pratinjau.style.display = 'block';
            }

            if (file && file.type.match('image.*')) {
                reader.readAsDataURL(file);
            } else {
                pratinjau.src = "#";
                pratinjau.style.display = 'none';
            }
        });

        function showErrorPopup() {
            document.getElementById('errorPopup').style.display = 'flex';
        }

        function closeErrorPopup() {
            document.getElementById('errorPopup').style.display = 'none';
        }

        @if ($errors->any())
            window.onload = showErrorPopup;
        @endif
    </script>
@endsection
