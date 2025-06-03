<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lookbook Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
  />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-[#f0f1f1] min-h-screen flex items-center justify-center p-6">
  <div class="flex w-full max-w-[1200px] h-[600px]">
    <!-- Left Sidebar -->
    <aside class="flex flex-col items-start gap-8 w-[180px]">
      <div class="text-[#f4b92f] font-extrabold text-[24px] leading-[28px] tracking-tight">
       <a href="{{ route('home') }}"><img src="{{ asset('img/logoy.png') }}" alt="Logo PaduPadan" class="w-24 h-auto" />
      </div>
      <nav class="flex flex-col gap-6 w-full">
        <a href="{{ route('profile') }}" class="flex items-center gap-3 text-gray-400 text-[12px] font-semibold">
          <i class="fas fa-user text-gray-400 text-[18px]"></i>
          Profile
        </a>
        <a href="{{ route('lookbook.create') }}" class="flex items-center gap-3 text-[#0a1f44] text-[12px] font-extrabold">
          <i class="fas fa-book-open text-[#0a1f44] text-[18px]"></i>
          Lookbook
        </a>
        <a href="{{ route('chat.index')}}" class="flex items-center gap-3 text-gray-400 text-[12px] font-semibold">
          <i class="fas fa-comment-alt text-gray-400 text-[18px]"></i>
          Chat
        </a>
      </nav>
    </aside>

    <!-- Middle Search Bar -->
    <div class="w-full max-w-[1200px] mb-6">
    <div class="relative w-full max-w-[400px] mx-auto">
      <input
        type="text"
        placeholder="search"
        class="w-full py-2 pl-4 pr-10 rounded-full bg-[#d9d9d9] text-[12px] text-gray-400 placeholder-gray-400 focus:outline-none"
      />
      <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[14px]"></i>
    </div>
  </div>


    <!-- Right Panel -->
    <section
      class="bg-white rounded-[20px] shadow-[0_10px_20px_rgba(0,0,0,0.1)] w-[480px] p-8 flex flex-col justify-between"
    >
      <h2 class="font-extrabold text-[16px] text-black mb-4">Chosen Item</h2>
      <div class="flex justify-end">
        <button
          class="bg-[#f4b92f] text-white text-[10px] font-semibold rounded-full px-4 py-1 shadow-md hover:brightness-110 transition"
          type="button"
        >
          next
        </button>
      </div>
    </section>
  </div>
</body>
</html>
