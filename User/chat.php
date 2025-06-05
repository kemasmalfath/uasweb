<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat Interaktif</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Animasi masuk pesan */
    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-20px); }
      to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
      from { opacity: 0; transform: translateX(20px); }
      to { opacity: 1; transform: translateX(0); }
    }
  </style>
</head>
<body class="bg-white text-blue-900 font-sans">
  <section class="p-6 max-w-xl mx-auto flex flex-col h-[600px]">
    <h2 class="text-3xl font-bold mb-6 text-blue-700 text-center">Chat dengan Admin</h2>

    <div id="chatBox" class="flex-1 border border-blue-300 rounded-lg p-4 bg-blue-50 overflow-y-auto space-y-4">
      <div class="chat-message admin flex items-start space-x-3 animate-slideInLeft">
        <div class="bg-blue-600 text-white rounded-lg px-4 py-2 max-w-[70%]">
          <p class="font-semibold">Admin</p>
          <p>Halo, ada yang bisa kami bantu?</p>
        </div>
      </div>
      <div class="chat-message user flex justify-end animate-slideInRight">
        <div class="bg-blue-200 text-blue-900 rounded-lg px-4 py-2 max-w-[70%]">
          <p class="font-semibold">Anda</p>
          <p>Saya ingin menanyakan status pengaduan.</p>
        </div>
      </div>
    </div>

    <form id="chatForm" class="mt-4 flex gap-0">
      <input
        type="text"
        id="messageInput"
        placeholder="Tulis pesan..."
        class="flex-1 border border-blue-300 rounded-l-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
        autocomplete="off"
        required
      />
      <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-r-lg font-semibold transition"
      >
        Kirim
      </button>
    </form>
  </section>

  <script>
    const chatBox = document.getElementById('chatBox');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');

    // Fungsi buat elemen pesan baru
    function addMessage(sender, text) {
      const wrapper = document.createElement('div');
      wrapper.classList.add('chat-message', 'flex', 'items-start', 'space-x-3', 'animate-slideInLeft');

      if (sender === 'user') {
        wrapper.classList.remove('items-start', 'space-x-3', 'animate-slideInLeft');
        wrapper.classList.add('justify-end', 'animate-slideInRight');
      }

      const messageBubble = document.createElement('div');
      messageBubble.classList.add('rounded-lg', 'px-4', 'py-2', 'max-w-[70%]');

      if (sender === 'admin') {
        messageBubble.classList.add('bg-blue-600', 'text-white');
      } else {
        messageBubble.classList.add('bg-blue-200', 'text-blue-900');
      }

      const senderElem = document.createElement('p');
      senderElem.classList.add('font-semibold', 'mb-1');
      senderElem.textContent = sender === 'admin' ? 'Admin' : 'Anda';

      const textElem = document.createElement('p');
      textElem.textContent = text;

      messageBubble.appendChild(senderElem);
      messageBubble.appendChild(textElem);
      wrapper.appendChild(messageBubble);
      chatBox.appendChild(wrapper);

      // Scroll ke bawah otomatis
      chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Simulasi balasan admin setelah user kirim pesan
    chatForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const userMsg = messageInput.value.trim();
      if (!userMsg) return;

      addMessage('user', userMsg);
      messageInput.value = '';

      // Simulasi balasan admin setelah 1.5 detik
      setTimeout(() => {
        addMessage('admin', 'Terima kasih sudah menghubungi, pengaduan Anda sedang kami proses.');
      }, 1500);
    });
  </script>
</body>
</html>
