<?php
require_once '../config/database.php';
requireAdmin();

$pengaduan_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$pengaduan_id) {
    header('Location: daftarPengaduan.php');
    exit;
}

try {
    $pdo = getConnection();
    
    // Ambil detail pengaduan
    $stmt = $pdo->prepare("
        SELECT p.*, u.nama as nama_user, u.username, k.nama_kategori 
        FROM pengaduan p 
        JOIN users u ON p.user_id = u.id 
        JOIN kategori k ON p.kategori_id = k.id 
        WHERE p.id = ?
    ");
    $stmt->execute([$pengaduan_id]);
    $pengaduan = $stmt->fetch();
    
    if (!$pengaduan) {
        header('Location: daftarPengaduan.php');
        exit;
    }
    
    // Ambil chat/tanggapan
    $stmt = $pdo->prepare("
        SELECT c.*, 
               CASE 
                   WHEN c.pengirim_type = 'admin' THEN a.nama 
                   ELSE u.nama 
               END as nama_pengirim
        FROM chat c 
        LEFT JOIN admin a ON c.pengirim_type = 'admin' AND c.pengirim_id = a.id
        LEFT JOIN users u ON c.pengirim_type = 'user' AND c.pengirim_id = u.id
        WHERE c.pengaduan_id = ? 
        ORDER BY c.tanggal_kirim ASC
    ");
    $stmt->execute([$pengaduan_id]);
    $chatList = $stmt->fetchAll();
    
    // Handle kirim pesan
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan'])) {
        $pesan = sanitizeInput($_POST['pesan']);
        
        if (!empty($pesan)) {
            $stmt = $pdo->prepare("INSERT INTO chat (pengaduan_id, pengirim_type, pengirim_id, pesan) VALUES (?, 'admin', ?, ?)");
            $stmt->execute([$pengaduan_id, $_SESSION['admin_id'], $pesan]);
            
            // Update status pengaduan jika belum ditanggapi
            if ($pengaduan['status'] === 'Belum Ditanggapi') {
                $stmt = $pdo->prepare("UPDATE pengaduan SET status = 'Sudah Ditanggapi' WHERE id = ?");
                $stmt->execute([$pengaduan_id]);
            }
            
            header("Location: tanggapi.php?id=$pengaduan_id");
            exit;
        }
    }
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tanggapi Pengaduan | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-8">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 border">
    <a href="daftarPengaduan.php" class="flex items-center gap-1 text-blue-600 mb-4 hover:underline">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
      Kembali
    </a>
    
    <div class="flex gap-3 mb-4 items-center">
      <div>
        <div class="bg-gray-200 w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg text-blue-700">
          <?= strtoupper(substr($pengaduan['nama_user'], 0, 2)) ?>
        </div>
      </div>
      <div>
        <p class="text-lg font-semibold text-gray-700"><?= $pengaduan['nama_user'] ?></p>
        <span class="text-gray-500 text-sm"><?= $pengaduan['username'] ?></span>
      </div>
    </div>
    
    <div class="bg-blue-50 rounded-lg p-4 mb-4 border">
      <div class="mb-3">
        <p class="text-blue-700 font-semibold text-sm mb-1">Judul Pengaduan:</p>
        <h3 class="font-bold text-blue-800"><?= htmlspecialchars($pengaduan['judul']) ?></h3>
      </div>
      
      <div class="mb-3">
        <p class="text-blue-700 font-semibold text-sm mb-1">Isi Pengaduan:</p>
        <div class="bg-white p-3 rounded border">
          <div class="text-gray-700 leading-relaxed whitespace-pre-wrap break-words">
            <?php
            $paragraphs = explode("\n", trim($pengaduan['isi_pengaduan']));
            foreach ($paragraphs as $para) {
              if (trim($para) !== '') {
                echo '<p class="mb-1 last:mb-0">' . htmlspecialchars($para) . '</p>';
              }
            }
            ?>
          </div>
        </div>
      </div>
      
      <p class="text-sm text-gray-500 mb-1">
        Kategori: <?= $pengaduan['nama_kategori'] ?> | 
        Tanggal: <?= date('Y-m-d H:i', strtotime($pengaduan['tanggal_pengaduan'])) ?>
      </p>
      
      <?php if ($pengaduan['lampiran']): ?>
      <div class="flex gap-2 mt-2">
        <a href="../uploads/<?= $pengaduan['lampiran'] ?>" target="_blank" class="text-blue-700 underline hover:text-blue-900">
          <?= $pengaduan['lampiran'] ?>
        </a>
      </div>
      <?php endif; ?>
      
      <p class="text-xs text-gray-400 mt-2">
        Status: <span class="font-semibold 
          <?= $pengaduan['status'] === 'Belum Ditanggapi' ? 'text-yellow-600' : 
             ($pengaduan['status'] === 'Sudah Ditanggapi' ? 'text-blue-600' : 'text-green-600') ?>">
          <?= $pengaduan['status'] ?>
        </span>
      </p>
    </div>

    <div class="mb-2">
      <div class="text-sm font-semibold text-gray-600 mb-2">Percakapan</div>
      <div class="space-y-2 max-h-40 overflow-y-auto pr-2 mb-3">
        <?php if (empty($chatList)): ?>
        <p class="text-gray-500 text-center">Belum ada percakapan</p>
        <?php else: ?>
        <?php foreach ($chatList as $chat): ?>
        <div class="flex <?= $chat['pengirim_type'] === 'admin' ? 'justify-end' : 'justify-start' ?>">
          <div class="<?= $chat['pengirim_type'] === 'admin' ? 'bg-blue-50' : 'bg-gray-100' ?> px-3 py-2 rounded-lg shadow max-w-xs">
            <span class="block font-semibold text-blue-700 text-sm"><?= $chat['nama_pengirim'] ?></span>
            <div class="block whitespace-pre-wrap break-words text-sm leading-relaxed"><?= htmlspecialchars($chat['pesan']) ?></div>
            <span class="block text-xs text-gray-500 mt-1"><?= date('Y-m-d H:i', strtotime($chat['tanggal_kirim'])) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
      
      <form method="POST">
        <div class="flex gap-2">
          <input type="text" name="pesan" class="flex-1 border rounded-lg p-2 focus:ring-2 focus:ring-blue-400" 
                 placeholder="Tulis tanggapan..." required>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            Kirim
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
