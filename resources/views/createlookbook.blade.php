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
    #chosenItems::-webkit-scrollbar { width: 6px; }
    #chosenItems::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
  </style>
</head>
<body class="bg-[#f4f4f4] min-h-screen p-6 flex justify-center">
  <div class="flex w-full max-w-[1400px] gap-6">
    <!-- Sidebar -->
    <aside class="w-[180px] flex flex-col gap-8">
      <img src="{{ asset('img/logoy.png') }}" alt="Logo PaduPadan" class="w-24">
      <nav class="flex flex-col gap-4 text-gray-600 text-sm font-semibold">
        <a href="{{ route('profile') }}" class="flex items-center gap-2"><i class="fas fa-user"></i> Profile</a>
        <a href="{{ route('lookbook.create') }}" class="flex items-center gap-2 font-bold text-[#0a1f44]"><i class="fas fa-book-open"></i> Lookbook</a>
        <a href="{{ route('chat.index') }}" class="flex items-center gap-2"><i class="fas fa-comment-alt"></i> Chat</a>
      </nav>
    </aside>

    <!-- Middle Content -->
    <main class="flex-1">
      <!-- Search Bar -->
      <div class="relative mb-4 max-w-[600px]">
        <input id="searchInput" type="text" placeholder="blue jeans" class="w-full px-4 py-2 bg-[#d9d9d9] rounded-full text-sm outline-none pr-10">
        <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
      </div>

      <!-- Item Grid -->
      <div class="grid grid-cols-3 gap-4" id="itemContainer">
        <!-- Item cards injected here -->
      </div>
    </main>

    <!-- Chosen Detail Panel -->
    <section class="w-[400px] bg-white rounded-[20px] shadow p-6 flex flex-col">
      <h2 class="font-extrabold text-[16px] text-black mb-4">Chosen Item</h2>
      <div id="chosenItems" class="space-y-4 flex-1 overflow-y-auto">
        <!-- Selected items appear here -->
      </div>
      <button class="self-end bg-[#f4b92f] px-4 py-1 rounded-full text-xs font-semibold text-white mt-4 shadow-md hover:brightness-110 transition">next</button>
    </section>
  </div>

  <script>
    const items = [
      {
        id: 1,
        thumbnail: "{{ asset('img/casual.png') }}",
        description: "Wide Light Blue Jeans",
        tags: ["Jeans", "Casual", "Trendy"],
        username: "Casual"
      },
      {
        id: 2,
        thumbnail: "{{ asset('img/formal.png') }}",
        description: "Dark Wide Jeans",
        tags: ["Casual", "Denim", "NightLook"],
        username: "Formal"
      },
      {
        id: 3,
        thumbnail: "{{ asset('img/stylish.png') }}",
        description: "Straight Blue Jeans",
        tags: ["Jeans", "ChicStyle", "Minimalist", "Blue", "Timeless"],
        username: "Stylish"
      }
    ];

    const itemContainer = document.getElementById('itemContainer');
    const chosenItems = document.getElementById('chosenItems');
    let chosenData = [];

    function renderItems() {
      itemContainer.innerHTML = '';
      items.forEach(item => {
        const card = document.createElement('div');
        card.className = 'bg-white p-4 rounded shadow text-center';
        card.innerHTML = `
          <img src="${item.thumbnail}" class="w-full h-[180px] object-contain mb-2 rounded" />
          <p class="text-sm font-semibold">${item.username}</p>
          <button class="bg-black text-white w-6 h-6 rounded-full mt-2 flex items-center justify-center" onclick='addToChosen(${JSON.stringify(item)})'>+</button>
        `;
        itemContainer.appendChild(card);
      });
    }

    function addToChosen(item) {
      if (chosenData.find(i => i.id === item.id)) return;
      chosenData.push(item);

      const div = document.createElement('div');
      div.className = 'bg-white p-4 rounded-xl shadow flex gap-4 relative';
      div.innerHTML = `
        <button class="absolute top-2 left-2 text-xl text-gray-400 hover:text-red-500" onclick="removeChosen(${item.id}, this)">✕</button>
        <img src="${item.thumbnail}" class="w-20 h-20 object-cover rounded" />
        <div>
          <p class="text-md font-bold text-black leading-tight mb-1">${item.description}</p>
          <div class="text-xs text-gray-500 space-x-1 mb-1">
            ${item.tags.map(tag => `<span>#${tag}</span>`).join(' ')}
          </div>
          <p class="text-xs text-gray-500">${item.username}</p>
        </div>
      `;
      chosenItems.appendChild(div);
    }

    function removeChosen(id, btn) {
      chosenData = chosenData.filter(i => i.id !== id);
      btn.closest('div').remove();
    }

    renderItems();
  </script>
</body>
</html>
