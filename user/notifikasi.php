<?php
require_once '../config/database.php';
requireLogin();

try {
    $pdo = getConnection();
    
    // Ambil notifikasi berdasarkan pengaduan user
    $stmt = $pdo->prepare("
        SELECT p.*, k.nama_kategori,
               (SELECT COUNT(*) FROM chat WHERE pengaduan_id = p.id AND pengirim_type = 'admin' 
                AND tanggal_kirim > COALESCE(p.last_read_user, '1970-01-01')) as unread_messages
        FROM pengaduan p 
        JOIN kategori k ON p.kategori_id = k.id 
        WHERE p.user_id = ? 
        ORDER BY p.tanggal_update DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $notifikasiList = $stmt->fetchAll();
    
    // Mark as read jika ada parameter
    if (isset($_GET['mark_read']) && isset($_GET['pengaduan_id'])) {
        $pengaduan_id = (int)$_GET['pengaduan_id'];
        $stmt = $pdo->prepare("UPDATE pengaduan SET last_read_user = NOW() WHERE id = ? AND user_id = ?");
        $stmt->execute([$pengaduan_id, $_SESSION['user_id']]);
        
        header('Location: notifikasi.php');
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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notifikasi Pengaduan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(15px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp { animation: fadeInUp 0.5s ease-out; }
  </style>
</head>
<body class="bg-blue-50 min-h-screen flex flex-col items-center p-6 font-sans text-blue-900">
  <header class="mb-8 w-full max-w-3xl">
    <div class="mb-4">
      <a href="dashboardUser.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
    <h1 class="text-4xl font-bold text-center text-blue-700 mb-2">Notifikasi Pengaduan</h1>
    <p class="text-center text-blue-600">Cek status terbaru pengaduan kamu di sini.</p>
  </header>

  <main class="w-full max-w-3xl space-y-6">
    <?php if (empty($notifikasiList)): ?>
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
      <p class="text-gray-500">Belum ada notifikasi pengaduan.</p>
      <a href="formPengaduan.php" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
        Buat Pengaduan Pertama
      </a>
    </div>
    <?php else: ?>
    <?php foreach ($notifikasiList as $notifikasi): ?>
    <article class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4 animate-fadeInUp border-l-8 
      <?= $notifikasi['status'] === 'Selesai' ? 'border-green-500' : 
         ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'border-blue-500' : 'border-yellow-400') ?> 
      hover:shadow-lg transition <?= $notifikasi['unread_messages'] > 0 ? 'bg-blue-50' : '' ?>">
      
      <div class="flex-shrink-0 
        <?= $notifikasi['status'] === 'Selesai' ? 'text-green-500' : 
           ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'text-blue-500' : 'text-yellow-400') ?>">
        <?php if ($notifikasi['status'] === 'Selesai'): ?>
        <!-- Icon selesai -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <?php elseif ($notifikasi['status'] === 'Sudah Ditanggapi'): ?>
        <!-- Icon diproses -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <?php else: ?>
        <!-- Icon menunggu -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <?php endif; ?>
      </div>
      
      <div class="flex-1">
        <h2 class="text-xl font-semibold 
          <?= $notifikasi['status'] === 'Selesai' ? 'text-green-800' : 
             ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'text-blue-800' : 'text-yellow-600') ?>">
          <?= $notifikasi['status'] === 'Selesai' ? 'Pengaduan Selesai' : 
             ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'Pengaduan Ditanggapi' : 'Menunggu Tanggapan') ?>
        </h2>
        <p class="
          <?= $notifikasi['status'] === 'Selesai' ? 'text-green-700' : 
             ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'text-blue-700' : 'text-yellow-700') ?> mt-1">
          Pengaduan tentang <span class="font-medium"><?= $notifikasi['judul'] ?></span> 
          <?= $notifikasi['status'] === 'Selesai' ? 'sudah selesai ditangani' : 
             ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'sudah mendapat tanggapan' : 'sedang menunggu tanggapan') ?>.
        </p>
        <p class="text-sm 
          <?= $notifikasi['status'] === 'Selesai' ? 'text-green-500' : 
             ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'text-blue-500' : 'text-yellow-500') ?> mt-2">
          Kategori: <?= $notifikasi['nama_kategori'] ?> | 
          Tanggal: <?= date('d M Y', strtotime($notifikasi['tanggal_pengaduan'])) ?>
        </p>
        
        <?php if ($notifikasi['unread_messages'] > 0): ?>
        <p class="text-sm text-red-600 font-semibold mt-1">
          <?= $notifikasi['unread_messages'] ?> pesan baru dari admin
        </p>
        <?php endif; ?>
      </div>
      
      <div class="flex flex-col gap-2">
        <a href="detailPengaduan.php?id=<?= $notifikasi['id'] ?>" 
           class="bg-<?= $notifikasi['status'] === 'Selesai' ? 'green' : 
                      ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'blue' : 'yellow') ?>-600 
                  text-white px-4 py-2 rounded hover:bg-<?= $notifikasi['status'] === 'Selesai' ? 'green' : 
                                                          ($notifikasi['status'] === 'Sudah Ditanggapi' ? 'blue' : 'yellow') ?>-700 
                  transition font-semibold text-center">
          Lihat Detail
        </a>
        
        <?php if ($notifikasi['unread_messages'] > 0): ?>
        <a href="notifikasi.php?mark_read=1&pengaduan_id=<?= $notifikasi['id'] ?>" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition font-semibold text-center text-sm">
          Tandai Dibaca
        </a>
        <?php endif; ?>
      </div>
    </article>
    <?php endforeach; ?>
    <?php endif; ?>
  </main>
</body>
</html>
