<?php
require_once '../config/database.php';
requireLogin();

try {
    $pdo = getConnection();
    
    // Ambil pengaduan user yang masih aktif untuk chat
    $stmt = $pdo->prepare("
        SELECT p.*, k.nama_kategori 
        FROM pengaduan p 
        JOIN kategori k ON p.kategori_id = k.id 
        WHERE p.user_id = ? AND p.status != 'Selesai'
        ORDER BY p.tanggal_pengaduan DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $pengaduanAktif = $stmt->fetchAll();
    
    // Jika ada pengaduan yang dipilih
    $selectedPengaduan = null;
    $chatList = [];
    
    if (isset($_GET['pengaduan_id'])) {
        $pengaduan_id = (int)$_GET['pengaduan_id'];
        
        // Verifikasi pengaduan milik user
        $stmt = $pdo->prepare("SELECT * FROM pengaduan WHERE id = ? AND user_id = ?");
        $stmt->execute([$pengaduan_id, $_SESSION['user_id']]);
        $selectedPengaduan = $stmt->fetch();
        
        if ($selectedPengaduan) {
            // Ambil chat untuk pengaduan ini
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
        }
    }
    
    // Handle kirim pesan
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan']) && isset($_POST['pengaduan_id'])) {
        $pesan = sanitizeInput($_POST['pesan']);
        $pengaduan_id = (int)$_POST['pengaduan_id'];
        
        if (!empty($pesan)) {
            // Verifikasi pengaduan milik user
            $stmt = $pdo->prepare("SELECT id FROM pengaduan WHERE id = ? AND user_id = ?");
            $stmt->execute([$pengaduan_id, $_SESSION['user_id']]);
            
            if ($stmt->fetch()) {
                $stmt = $pdo->prepare("INSERT INTO chat (pengaduan_id, pengirim_type, pengirim_id, pesan) VALUES (?, 'user', ?, ?)");
                $stmt->execute([$pengaduan_id, $_SESSION['user_id'], $pesan]);
                
                header("Location: chat.php?pengaduan_id=$pengaduan_id");
                exit;
            }
        }
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
  <title>Chat Interaktif</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-20px); }
      to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
      from { opacity: 0; transform: translateX(20px); }
      to { opacity: 1; transform: translateX(0); }
    }
    .animate-slideInLeft { animation: slideInLeft 0.3s ease-out; }
    .animate-slideInRight { animation: slideInRight 0.3s ease-out; }
  </style>
</head>
<body class="bg-white text-blue-900 font-sans">
  <div class="max-w-4xl mx-auto p-6">
    <div class="mb-6">
      <a href="dashboardUser.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
    
    <h2 class="text-3xl font-bold mb-6 text-blue-700 text-center">Chat dengan Admin</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Daftar Pengaduan -->
      <div class="md:col-span-1">
        <h3 class="text-lg font-semibold mb-4 text-blue-700">Pengaduan Aktif</h3>
        <div class="space-y-2">
          <?php if (empty($pengaduanAktif)): ?>
          <p class="text-gray-500 text-sm">Tidak ada pengaduan aktif. <a href="formPengaduan.php" class="text-blue-600 hover:underline">Buat pengaduan baru</a></p>
          <?php else: ?>
          <?php foreach ($pengaduanAktif as $pengaduan): ?>
          <a href="chat.php?pengaduan_id=<?= $pengaduan['id'] ?>" 
             class="block p-3 border rounded-lg hover:bg-blue-50 transition <?= (isset($_GET['pengaduan_id']) && $_GET['pengaduan_id'] == $pengaduan['id']) ? 'bg-blue-100 border-blue-300' : 'bg-white' ?>">
            <div class="font-semibold text-sm"><?= substr($pengaduan['judul'], 0, 30) ?>...</div>
            <div class="text-xs text-gray-500"><?= $pengaduan['nama_kategori'] ?></div>
            <div class="text-xs text-gray-400"><?= date('Y-m-d', strtotime($pengaduan['tanggal_pengaduan'])) ?></div>
          </a>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Chat Area -->
      <div class="md:col-span-2">
        <?php if ($selectedPengaduan): ?>
        <div class="bg-blue-50 p-4 rounded-lg mb-4">
          <h4 class="font-bold text-blue-800"><?= $selectedPengaduan['judul'] ?></h4>
          <p class="text-sm text-gray-600">Status: <?= $selectedPengaduan['status'] ?></p>
        </div>
        
        <div class="flex flex-col h-[400px]">
          <div id="chatBox" class="flex-1 border border-blue-300 rounded-lg p-4 bg-blue-50 overflow-y-auto space-y-4">
            <?php if (empty($chatList)): ?>
            <div class="text-center text-gray-500">
              <p>Belum ada percakapan. Mulai chat dengan admin!</p>
            </div>
            <?php else: ?>
            <?php foreach ($chatList as $chat): ?>
            <div class="chat-message flex items-start space-x-3 <?= $chat['pengirim_type'] === 'user' ? 'justify-end' : '' ?> animate-slideInLeft">
              <div class="<?= $chat['pengirim_type'] === 'admin' ? 'bg-blue-600 text-white' : 'bg-blue-200 text-blue-900' ?> rounded-lg px-4 py-2 max-w-[70%]">
                <p class="font-semibold"><?= $chat['nama_pengirim'] ?></p>
                <p><?= nl2br($chat['pesan']) ?></p>
                <p class="text-xs opacity-75 mt-1"><?= date('H:i', strtotime($chat['tanggal_kirim'])) ?></p>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <form method="POST" class="mt-4 flex gap-0">
            <input type="hidden" name="pengaduan_id" value="<?= $selectedPengaduan['id'] ?>">
            <input type="text" name="pesan" placeholder="Tulis pesan..." required
                   class="flex-1 border border-blue-300 rounded-l-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-r-lg font-semibold transition">
              Kirim
            </button>
          </form>
        </div>
        <?php else: ?>
        <div class="text-center text-gray-500 py-20">
          <p>Pilih pengaduan dari daftar di sebelah kiri untuk memulai chat</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    // Auto scroll ke bawah
    const chatBox = document.getElementById('chatBox');
    if (chatBox) {
      chatBox.scrollTop = chatBox.scrollHeight;
    }
    
    // HAPUS auto refresh yang menyebabkan masalah
    // Ganti dengan manual refresh button jika diperlukan
  </script>
</body>
</html>
