<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lookbook Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-[#f0f1f1] min-h-screen flex items-start justify-center p-6">
  <div class="flex w-full max-w-[1400px] gap-6">
    <!-- Sidebar -->
    <aside class="flex flex-col items-start gap-8 w-[180px]">
      <div class="text-[#f4b92f] font-extrabold text-[24px] leading-[28px] tracking-tight">
        <a href="{{ route('home') }}">
          <img src="{{ asset('img/logoy.png') }}" alt="Logo PaduPadan" class="w-24 h-auto" />
        </a>
      </div>
      <nav class="flex flex-col gap-6 w-full">
        <a href="{{ route('profile') }}" class="flex items-center gap-3 text-gray-400 text-[12px] font-semibold">
          <i class="fas fa-user text-[18px]"></i> Profile
        </a>
        <a href="{{ route('lookbook.create') }}" class="flex items-center gap-3 text-[#0a1f44] text-[12px] font-extrabold">
          <i class="fas fa-book-open text-[18px]"></i> Lookbook
        </a>
        <a href="{{ route('chat.index')}}" class="flex items-center gap-3 text-gray-400 text-[12px] font-semibold">
          <i class="fas fa-comment-alt text-[18px]"></i> Chat
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1">
      <!-- Search -->
      <div class="w-full max-w-[600px] mb-4">
        <div class="relative">
          <input id="searchInput" type="text" placeholder="search"
            class="w-full py-2 pl-4 pr-10 rounded-full bg-[#d9d9d9] text-[12px] text-gray-700 placeholder-gray-400 focus:outline-none" />
          <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-[14px]"></i>
        </div>
      </div>

      <!-- Search Results -->
      <div id="itemContainer" class="grid grid-cols-3 gap-4">
        <!-- Item cards appear here -->
      </div>
    </div>

    <!-- Chosen Panel -->
    <section class="bg-white rounded-[20px] shadow w-[400px] p-6 flex flex-col">
      <h2 class="font-extrabold text-[16px] text-black mb-4">Chosen Item</h2>
      <div id="chosenItems" class="space-y-4 flex-1 overflow-y-auto">
        <!-- Selected items appear here -->
      </div>
      <div class="flex justify-end mt-4">
        <button class="bg-[#f4b92f] text-white text-[10px] font-semibold rounded-full px-4 py-1 shadow-md hover:brightness-110 transition">
          next
        </button>
      </div>
    </section>
  </div>

  <script>
    const searchInput = document.getElementById('searchInput');
    const itemContainer = document.getElementById('itemContainer');
    const chosenItems = document.getElementById('chosenItems');
    let chosenData = [];

    searchInput.addEventListener('keyup', function () {
      const query = this.value.trim();
      if (query.length < 2) return;

      fetch(`/search?query=${encodeURIComponent(query)}&ajax=1`)
        .then(res => res.json())
        .then(data => {
          itemContainer.innerHTML = '';
          data.forEach(item => {
            const card = document.createElement('div');
            card.className = 'bg-white p-4 rounded shadow text-center';
            card.innerHTML = `
              <img src="${item.thumbnail}" alt="${item.description}" class="w-full h-[200px] object-cover mb-2 rounded" />
              <h3 class="text-sm font-bold">${item.description}</h3>
              <p class="text-xs text-gray-500 mb-2">${item.username}</p>
              <button class="bg-black text-white w-6 h-6 rounded-full" onclick='addToChosen(${JSON.stringify(item)})'>+</button>
            `;
            itemContainer.appendChild(card);
          });
        });
    });

    function addToChosen(item) {
      if (chosenData.find(i => i.id === item.id)) return;
      chosenData.push(item);

      const div = document.createElement('div');
      div.className = 'bg-[#f0f0f0] p-4 rounded flex items-center justify-between';
      div.innerHTML = `
        <div class="flex items-center gap-3">
          <img src="${item.thumbnail}" class="w-12 h-12 object-cover rounded" />
          <div>
            <p class="text-sm font-bold">${item.description}</p>
            <p class="text-xs text-gray-500">${item.username}</p>
          </div>
        </div>
        <button class="text-red-500 text-xs" onclick="removeChosen(${item.id}, this)">✕</button>
      `;
      chosenItems.appendChild(div);
    }

    function removeChosen(id, btn) {
      chosenData = chosenData.filter(i => i.id !== id);
      btn.closest('div').remove();
    }
  </script>
</body>
</html>
