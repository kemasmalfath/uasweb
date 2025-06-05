<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Minimalis</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100 text-white">

  <aside class="w-64 bg-blue-600 flex flex-col px-6 py-8">
    <h2 class="text-2xl font-bold mb-10">Dashboard</h2>
    <div class="mb-4">
      <button id="profilToggle" class="w-full flex items-center justify-between font-semibold hover:bg-blue-700 px-4 py-3 rounded-lg">
        Profil
        <svg id="profilArrow" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform transform rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div id="profilMenu" class="hidden mt-2 ml-2 flex flex-col bg-blue-500 rounded-md overflow-hidden">
        <a href="haleditProfile.php" class="px-4 py-2 text-sm hover:bg-blue-700 border-b border-white/20">Edit Profil</a>
        <a href="gantiPw.php" class="px-4 py-2 text-sm hover:bg-blue-700">Edit Password</a>
      </div>
    </div>
    <div class="mb-4">
      <button id="formToggle" class="w-full flex items-center justify-between font-semibold hover:bg-blue-700 px-4 py-3 rounded-lg">
        <span class="text-left">
          Form<br>Pengaduan
        </span>
        <svg id="formArrow" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform transform rotate-[-45deg]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div id="formMenu" class="hidden mt-2 ml-2 flex flex-col bg-blue-500 rounded-md overflow-hidden">
        <a href="formPengaduan.php" class="px-4 py-2 text-sm hover:bg-blue-700 border-b border-white/20">Tulis Pengaduan</a>
        <a href="daftarPengaduan.php" class="px-4 py-2 text-sm hover:bg-blue-700">Daftar Pengaduan</a>
      </div>
    </div>
    <a href="logoutUser.php" class="mt-auto px-4 py-3 hover:bg-blue-700 rounded-lg font-semibold text-sm">Logout</a>
  </aside>

  <main class="flex-1 p-10 overflow-y-auto text-black">
    <h1 class="text-2xl font-bold mb-4">Selamat Datang, <span class="text-blue-600 font-semibold">
      <?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama'], ENT_QUOTES, 'UTF-8') : 'User' ?>
    </span>!</h1>

    <div class="absolute top-5 right-10 flex gap-4">
      <button onclick="location.href='notifikasi.php'" title="Notifikasi"
        class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" class="w-5 h-5">
          <path d="M12 24c1.104 0 2-.897 2-2h-4c0 1.103.896 2 2 2zm6.364-5.364l-1.423-1.423V10c0-3.314-2.686-6-6-6S5 6.686 5 10v7.213l-1.423 1.423-1.414-1.414 2.837-2.837V10c0-4.411 3.589-8 8-8s8 3.589 8 8v5.588l2.837 2.837-1.414 1.414z"/>
        </svg>
      </button>
      <button onclick="location.href='chat.php'" title="Chat / Konsultasi"
        class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700">
        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" class="w-5 h-5">
          <path d="M20 2H4c-1.103 0-2 .897-2 2v18l4-4h14c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2z"/>
        </svg>
      </button>
    </div>
  </main>

  <script>
    const profilToggle = document.getElementById('profilToggle');
    const profilMenu = document.getElementById('profilMenu');
    const profilArrow = document.getElementById('profilArrow');

    profilToggle.addEventListener('click', () => {
      const isVisible = !profilMenu.classList.contains('hidden');
      profilMenu.classList.toggle('hidden', isVisible);
      profilArrow.classList.toggle('rotate-[135deg]', !isVisible);
      profilArrow.classList.toggle('rotate-45', isVisible);
    });

    const formToggle = document.getElementById('formToggle');
    const formMenu = document.getElementById('formMenu');
    const formArrow = document.getElementById('formArrow');

    formToggle.addEventListener('click', () => {
      const isVisible = !formMenu.classList.contains('hidden');
      formMenu.classList.toggle('hidden', isVisible);
      formArrow.classList.toggle('rotate-[135deg]', !isVisible);
      formArrow.classList.toggle('rotate-[-45deg]', isVisible);
    });
  </script>
</body>
</html>