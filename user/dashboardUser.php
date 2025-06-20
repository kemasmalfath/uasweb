<?php
require_once '../config/database.php';
requireLogin();

try {
    $pdo = getConnection();
    
    // Hitung statistik user
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM pengaduan WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalPengaduan = $stmt->fetch()['total'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as belum FROM pengaduan WHERE user_id = ? AND status = 'Belum Ditanggapi'");
    $stmt->execute([$_SESSION['user_id']]);
    $belumDitanggapi = $stmt->fetch()['belum'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as sudah FROM pengaduan WHERE user_id = ? AND status IN ('Sudah Ditanggapi', 'Selesai')");
    $stmt->execute([$_SESSION['user_id']]);
    $sudahDitanggapi = $stmt->fetch()['sudah'];
    
} catch (PDOException $e) {
    $totalPengaduan = 0;
    $belumDitanggapi = 0;
    $sudahDitanggapi = 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100 text-white">
  <!-- Sidebar -->
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
        <a href="lihatProfil.php" class="px-4 py-2 text-sm hover:bg-blue-700 border-b border-white/20">Lihat Profil</a>
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

  <!-- Main Content -->
  <main class="flex-1 p-10 overflow-y-auto text-black">
    <h1 class="text-2xl font-bold mb-4">
      Selamat Datang, <span class="text-blue-600 font-semibold"><?= $_SESSION['nama'] ?></span>!
    </h1>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border">
        <span class="text-gray-400 text-xs mb-1">Total Pengaduan Anda</span>
        <span class="text-3xl font-bold text-blue-800 mb-1"><?= $totalPengaduan ?></span>
      </div>
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border">
        <span class="text-gray-400 text-xs mb-1">Belum Ditanggapi</span>
        <span class="text-3xl font-bold text-yellow-500 mb-1"><?= $belumDitanggapi ?></span>
      </div>
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border">
        <span class="text-gray-400 text-xs mb-1">Sudah Ditanggapi</span>
        <span class="text-3xl font-bold text-green-600 mb-1"><?= $sudahDitanggapi ?></span>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow p-6 border mb-8">
      <h2 class="text-lg font-bold mb-4 text-gray-700">Aksi Cepat</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="formPengaduan.php" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition">
          <div class="text-2xl mb-2">📝</div>
          <div class="font-semibold">Buat Pengaduan Baru</div>
        </a>
        <a href="daftarPengaduan.php" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition">
          <div class="text-2xl mb-2">📋</div>
          <div class="font-semibold">Lihat Pengaduan Saya</div>
        </a>
      </div>
    </div>

    <!-- Floating Action Buttons -->
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
