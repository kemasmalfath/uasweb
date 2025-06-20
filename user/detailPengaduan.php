<?php
require_once '../config/database.php';
requireLogin();

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID pengaduan tidak valid.'); window.location.href='daftarPengaduan.php';</script>";
    exit;
}

try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, k.nama_kategori, u.nama, u.email
        FROM pengaduan p 
        JOIN kategori k ON p.kategori_id = k.id 
        JOIN users u ON p.user_id = u.id
        WHERE p.id = ? AND p.user_id = ?
    ");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $data = $stmt->fetch();
    
    if (!$data) {
        echo "<script>alert('Data pengaduan tidak ditemukan atau Anda tidak memiliki akses.'); window.location.href='daftarPengaduan.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    echo "<script>alert('Terjadi kesalahan sistem.'); window.location.href='daftarPengaduan.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Pengaduan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center px-4 py-12">

  <div class="bg-white border border-blue-100 shadow-lg rounded-xl w-full max-w-2xl p-6">
    <h1 class="text-2xl font-semibold text-blue-800 mb-4 border-b border-blue-200 pb-3">Detail Pengaduan</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-blue-900 text-sm sm:text-base">
      <div>
        <p class="text-blue-600 mb-1">Nama</p>
        <p class="font-medium"><?= htmlspecialchars($data['nama']) ?></p>
      </div>

      <div>
        <p class="text-blue-600 mb-1">Email</p>
        <p class="font-medium"><?= htmlspecialchars($data['email']) ?></p>
      </div>

      <div>
        <p class="text-blue-600 mb-1">Kategori</p>
        <p class="font-medium"><?= htmlspecialchars($data['nama_kategori']) ?></p>
      </div>

      <div class="sm:col-span-2">
        <p class="text-blue-600 mb-1">Judul Pengaduan</p>
        <p class="font-medium"><?= htmlspecialchars($data['judul']) ?></p>
      </div>

      <div class="sm:col-span-2">
        <p class="text-blue-600 mb-1">Isi Pengaduan</p>
        <div class="bg-gray-50 p-3 rounded border">
          <div class="text-gray-900 leading-relaxed whitespace-pre-wrap break-words">
            <?php
            $paragraphs = explode("\n", trim($data['isi_pengaduan']));
            foreach ($paragraphs as $para) {
              if (trim($para) !== '') {
                echo '<p class="mb-1 last:mb-0">' . htmlspecialchars($para) . '</p>';
              }
            }
            ?>
          </div>
        </div>
      </div>

      <?php if (!empty($data['lampiran'])): ?>
      <div class="sm:col-span-2">
        <p class="text-blue-600 mb-1">Lampiran</p>
        <?php 
        $fileExt = strtolower(pathinfo($data['lampiran'], PATHINFO_EXTENSION));
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        ?>
        
        <?php if (in_array($fileExt, $imageExts)): ?>
          <img src="../uploads/<?= htmlspecialchars($data['lampiran']) ?>" alt="Lampiran Pengaduan" 
               class="w-full max-w-xs max-h-48 object-contain border rounded shadow" />
        <?php else: ?>
          <a href="../uploads/<?= htmlspecialchars($data['lampiran']) ?>" target="_blank" 
             class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Lihat Lampiran: <?= htmlspecialchars($data['lampiran']) ?>
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <div>
        <p class="text-blue-600 mb-1">Tanggal</p>
        <p class="font-medium"><?= date('d M Y, H:i', strtotime($data['tanggal_pengaduan'])) ?></p>
      </div>

      <div>
        <p class="text-blue-600 mb-1">Status</p>
        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
          <?= $data['status'] === 'Selesai' ? 'bg-green-100 text-green-700' :
             ($data['status'] === 'Sudah Ditanggapi' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-200 text-yellow-700') ?>">
          <?= $data['status'] ?>
        </span>
      </div>
    </div>

    <div class="mt-6 text-right">
      <a href="daftarPengaduan.php" class="inline-block bg-blue-700 hover:bg-blue-600 text-white px-4 py-2 rounded transition">
        Kembali
      </a>
    </div>
  </div>

</body>
</html>
