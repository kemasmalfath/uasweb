<?php
require_once '../config/database.php';
requireLogin();

// Ambil data user terbaru dari database
try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userData = $stmt->fetch();
    
    if (!$userData) {
        header('Location: dashboardUser.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Saya</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">
  
  <div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
      <a href="dashboardUser.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow p-8">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya</h1>
      
      <!-- Avatar, Nama, Username - Centered -->
      <div class="text-center mb-8">
        <div class="w-24 h-24 rounded-full border-2 border-gray-300 overflow-hidden mx-auto mb-4">
          <?php if ($userData['foto_profil'] && $userData['foto_profil'] !== 'default-profile.png'): ?>
          <img src="../uploads/profiles/<?= $userData['foto_profil'] ?>" alt="Foto Profil" class="w-full h-full object-cover">
          <?php else: ?>
          <div class="w-full h-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
            <?= strtoupper(substr($userData['nama'], 0, 2)) ?>
          </div>
          <?php endif; ?>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 mb-1"><?= htmlspecialchars($userData['nama']) ?></h2>
        <p class="text-gray-600">@<?= htmlspecialchars($userData['username']) ?></p>
      </div>

      <!-- Informasi Profil -->
      <div class="space-y-4">
        <div class="border-b pb-3">
          <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
          <p class="text-gray-800"><?= htmlspecialchars($userData['email']) ?></p>
        </div>
        
        <div class="border-b pb-3">
          <label class="block text-sm font-medium text-gray-600 mb-1">NIM</label>
          <p class="text-gray-800"><?= $userData['nim'] ? htmlspecialchars($userData['nim']) : '-' ?></p>
        </div>
        
        <div class="border-b pb-3">
          <label class="block text-sm font-medium text-gray-600 mb-1">Jurusan</label>
          <p class="text-gray-800"><?= $userData['jurusan'] ? htmlspecialchars($userData['jurusan']) : '-' ?></p>
        </div>
        
        <div class="border-b pb-3">
          <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
          <span class="inline-block px-2 py-1 rounded text-sm
            <?= $userData['status'] === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= ucfirst($userData['status']) ?>
          </span>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Bergabung Sejak</label>
          <p class="text-gray-800"><?= date('d M Y', strtotime($userData['created_at'])) ?></p>
        </div>
      </div>

      <!-- Tombol Aksi -->
      <div class="mt-8 flex gap-3">
        <a href="haleeditProfile.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Edit Profil
        </a>
        <a href="gantiPw.php" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
          Ganti Password
        </a>
      </div>
    </div>
  </div>
</body>
</html>
