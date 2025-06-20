<?php
require_once '../config/database.php';
requireAdmin();

try {
    $pdo = getConnection();
    
    // Hitung statistik
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pengaduan");
    $totalPengaduan = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as belum FROM pengaduan WHERE status = 'Belum Ditanggapi'");
    $belumDitanggapi = $stmt->fetch()['belum'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as sudah FROM pengaduan WHERE status IN ('Sudah Ditanggapi', 'Selesai')");
    $sudahDitanggapi = $stmt->fetch()['sudah'];
    
    // Ambil pengaduan terbaru
    $stmt = $pdo->query("
        SELECT p.*, u.nama as nama_user, u.username, k.nama_kategori 
        FROM pengaduan p 
        JOIN users u ON p.user_id = u.id 
        JOIN kategori k ON p.kategori_id = k.id 
        ORDER BY p.tanggal_pengaduan DESC 
        LIMIT 5
    ");
    $pengaduanTerbaru = $stmt->fetchAll();
    
    // Statistik per kategori
    $stmt = $pdo->query("
        SELECT k.nama_kategori, COUNT(p.id) as jumlah 
        FROM kategori k 
        LEFT JOIN pengaduan p ON k.id = p.kategori_id 
        GROUP BY k.id, k.nama_kategori
    ");
    $statistikKategori = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <!-- Sidebar -->
  <aside class="fixed left-0 top-0 w-60 h-screen bg-blue-600 border-r border-blue-800 flex flex-col py-6 px-4 z-10 shadow-sm">
    <div class="flex flex-col items-center mb-10">
      <img src="logo.png" class="w-12 h-12 mb-2" alt="Logo">
      <span class="text-lg font-bold tracking-wide text-white">SuaraKita</span>
    </div>
    <nav class="flex flex-col gap-2 font-medium text-white">
      <a href="dashboardAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-800 text-white font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
        </svg>
        Dashboard
      </a>
      <a href="daftarPengaduan.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
        Daftar Pengaduan
      </a>
      <a href="profilAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        Profil Admin
      </a>
    </nav>
    <div class="flex-grow"></div>
    <a href="logoutAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 text-white transition">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
      </svg>
      Logout
    </a>
  </aside>

  <main class="ml-60 p-8">
    <div class="flex items-center gap-4 mb-8">
      <img src="logo2.png" class="w-10 h-10 mb-4" alt="Logo">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-800 mb-1 tracking-wide">Kritik & Saran Mahasiswa FMIPA</h1>
        <span class="text-gray-500 text-sm">Dashboard Admin - Selamat datang, <?= $_SESSION['admin_nama'] ?></span>
      </div>
    </div>
    
    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
      <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border">
        <span class="text-gray-400 text-xs mb-1">Total Pengaduan</span>
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
  
    <!-- Statistik per Kategori -->
    <div class="bg-white rounded-xl shadow p-8 mb-10 border">
      <h2 class="text-lg font-bold mb-4 text-gray-700">Statistik Pengaduan per Kategori</h2>
      <div class="flex items-end gap-6 h-48">
        <?php foreach ($statistikKategori as $stat): ?>
        <div class="flex flex-col items-center w-20">
          <div class="w-8 bg-blue-600 rounded-t" style="height:<?= max(20, $stat['jumlah'] * 20) ?>px"></div>
          <span class="text-xs mt-2 text-gray-600"><?= substr($stat['nama_kategori'], 0, 10) ?></span>
          <span class="text-xs text-gray-500"><?= $stat['jumlah'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
 
    <!-- Pengaduan Terbaru -->
    <div class="bg-white rounded-xl shadow p-8 border">
      <h2 class="text-lg font-bold mb-4 text-gray-700">Pengaduan Terbaru</h2>
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 text-gray-700 font-semibold">
            <th class="py-2 px-3 text-left">No</th>
            <th class="py-2 px-3 text-left">Tanggal</th>
            <th class="py-2 px-3 text-left">Username</th>
            <th class="py-2 px-3 text-left">Kategori</th>
            <th class="py-2 px-3 text-left">Judul</th>
            <th class="py-2 px-3 text-left">Status</th>
            <th class="py-2 px-3 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pengaduanTerbaru as $index => $pengaduan): ?>
          <tr class="border-t hover:bg-blue-50 transition">
            <td class="py-2 px-3"><?= $index + 1 ?></td>
            <td class="py-2 px-3"><?= date('Y-m-d', strtotime($pengaduan['tanggal_pengaduan'])) ?></td>
            <td class="py-2 px-3"><?= $pengaduan['username'] ?></td>
            <td class="py-2 px-3"><?= $pengaduan['nama_kategori'] ?></td>
            <td class="py-2 px-3"><?= substr($pengaduan['judul'], 0, 30) ?>...</td>
            <td class="py-2 px-3">
              <span class="px-2 py-1 rounded-full text-xs 
                <?= $pengaduan['status'] === 'Belum Ditanggapi' ? 'bg-yellow-200 text-yellow-700' : 
                   ($pengaduan['status'] === 'Sudah Ditanggapi' ? 'bg-blue-200 text-blue-700' : 'bg-green-200 text-green-700') ?> font-bold">
                <?= $pengaduan['status'] ?>
              </span>
            </td>
            <td class="py-2 px-3">
              <a href="tanggapi.php?id=<?= $pengaduan['id'] ?>" class="text-blue-600 font-semibold hover:underline">Tanggapi</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
