<?php
require_once '../config/database.php';
requireAdmin();

try {
    $pdo = getConnection();
    
    // Handle filter dan pencarian
    $whereClause = "WHERE 1=1";
    $params = [];
    
    if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
        $whereClause .= " AND k.nama_kategori = ?";
        $params[] = $_GET['kategori'];
    }
    
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $whereClause .= " AND p.status = ?";
        $params[] = $_GET['status'];
    }
    
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $whereClause .= " AND (p.judul LIKE ? OR u.nama LIKE ? OR u.username LIKE ?)";
        $searchTerm = '%' . $_GET['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Handle update status
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
        $pengaduan_id = (int)$_POST['pengaduan_id'];
        $new_status = $_POST['status'];
        
        $stmt = $pdo->prepare("UPDATE pengaduan SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $pengaduan_id]);
        
        header('Location: daftarPengaduan.php');
        exit;
    }
    
    // Ambil data pengaduan dengan filter
    $sql = "SELECT p.*, u.nama as nama_user, u.username, k.nama_kategori 
            FROM pengaduan p 
            JOIN users u ON p.user_id = u.id 
            JOIN kategori k ON p.kategori_id = k.id 
            $whereClause 
            ORDER BY p.tanggal_pengaduan DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $pengaduanList = $stmt->fetchAll();
    
    // Ambil kategori untuk filter - urutkan berdasarkan ID
$stmt = $pdo->query("SELECT id, nama_kategori FROM kategori ORDER BY id");
$kategoriList = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pengaduan | Kritik & Saran FMIPA</title>
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
      <a href="dashboardAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
        </svg>
        Dashboard
      </a>
      <a href="daftarPengaduan.php" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-800 text-white font-bold">
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
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="blue" stroke-width="2" viewBox="0 0 24 24">
        <rect x="4" y="3" width="16" height="18" rx="2"/>
        <line x1="8" y1="9" x2="16" y2="9"/>
        <line x1="8" y1="13" x2="16" y2="13"/>
        <line x1="8" y1="17" x2="12" y2="17"/>
      </svg>
      <h2 class="text-2xl font-bold text-gray-800">Daftar Pengaduan</h2>
    </div>
    
    <!-- Filter dan Pencarian -->
    <form method="GET" class="flex flex-wrap gap-4 items-center mb-8">
      <select name="kategori" class="p-2 rounded-lg border border-gray-300 bg-white shadow focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategoriList as $kategori): ?>
        <option value="<?= $kategori['nama_kategori'] ?>" <?= (isset($_GET['kategori']) && $_GET['kategori'] === $kategori['nama_kategori']) ? 'selected' : '' ?>>
          <?= $kategori['nama_kategori'] ?>
        </option>
        <?php endforeach; ?>
      </select>
      
      <select name="status" class="p-2 rounded-lg border border-gray-300 bg-white shadow focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Status</option>
        <option value="Belum Ditanggapi" <?= (isset($_GET['status']) && $_GET['status'] === 'Belum Ditanggapi') ? 'selected' : '' ?>>Belum Ditanggapi</option>
        <option value="Sudah Ditanggapi" <?= (isset($_GET['status']) && $_GET['status'] === 'Sudah Ditanggapi') ? 'selected' : '' ?>>Sudah Ditanggapi</option>
        <option value="Selesai" <?= (isset($_GET['status']) && $_GET['status'] === 'Selesai') ? 'selected' : '' ?>>Selesai</option>
      </select>
      
      <input type="text" name="search" placeholder="Cari judul atau nama user..." 
             class="p-2 rounded-lg border border-gray-300 bg-white shadow focus:ring-2 focus:ring-blue-400"
             value="<?= isset($_GET['search']) ? sanitizeInput($_GET['search']) : '' ?>">
      
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        Cari
      </button>
      
      <a href="daftarPengaduan.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
        Reset
      </a>
    </form>

    <div class="bg-white rounded-xl shadow p-8 border">
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
          <?php if (empty($pengaduanList)): ?>
          <tr>
            <td colspan="7" class="py-4 px-3 text-center text-gray-500">Tidak ada data pengaduan</td>
          </tr>
          <?php else: ?>
          <?php foreach ($pengaduanList as $index => $pengaduan): ?>
          <tr class="border-t hover:bg-blue-50 transition">
            <td class="py-2 px-3"><?= $index + 1 ?></td>
            <td class="py-2 px-3"><?= date('Y-m-d', strtotime($pengaduan['tanggal_pengaduan'])) ?></td>
            <td class="py-2 px-3"><?= $pengaduan['username'] ?></td>
            <td class="py-2 px-3"><?= $pengaduan['nama_kategori'] ?></td>
            <td class="py-2 px-3"><?= substr($pengaduan['judul'], 0, 30) ?>...</td>
            <td class="py-2 px-3">
              <form method="POST" class="inline">
                <input type="hidden" name="pengaduan_id" value="<?= $pengaduan['id'] ?>">
                <select name="status" class="status-select rounded-full text-xs px-2 py-1 
                  <?= $pengaduan['status'] === 'Belum Ditanggapi' ? 'bg-yellow-400 text-yellow-800' : 
                     ($pengaduan['status'] === 'Sudah Ditanggapi' ? 'bg-blue-400 text-blue-800' : 'bg-green-400 text-green-800') ?>"
                  onchange="this.form.submit()">
                  <option value="Belum Ditanggapi" <?= $pengaduan['status'] === 'Belum Ditanggapi' ? 'selected' : '' ?>>Belum Ditanggapi</option>
                  <option value="Sudah Ditanggapi" <?= $pengaduan['status'] === 'Sudah Ditanggapi' ? 'selected' : '' ?>>Sudah Ditanggapi</option>
                  <option value="Selesai" <?= $pengaduan['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
                <input type="hidden" name="update_status" value="1">
              </form>
            </td>
            <td class="py-2 px-3">
              <a href="tanggapi.php?id=<?= $pengaduan['id'] ?>" class="text-blue-600 font-semibold hover:underline">Tanggapi</a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <script>
    // Fungsi ubah warna dropdown sesuai status
    document.querySelectorAll('.status-select').forEach(function(select) {
      select.addEventListener('change', function() {
        select.classList.remove('bg-yellow-400', 'text-yellow-800', 'bg-blue-400', 'text-blue-800', 'bg-green-400', 'text-green-800');
        if (select.value === "Belum Ditanggapi") {
          select.classList.add('bg-yellow-400', 'text-yellow-800');
        } else if (select.value === "Sudah Ditanggapi") {
          select.classList.add('bg-blue-400', 'text-blue-800');
        } else if (select.value === "Selesai") {
          select.classList.add('bg-green-400', 'text-green-800');
        }
      });
    });
  </script>
</body>
</html>
