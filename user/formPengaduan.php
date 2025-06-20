<?php
require_once '../config/database.php';
requireLogin();

$error = '';
$success = '';

// Ambil kategori
try {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT * FROM kategori ORDER BY id");
    $kategoriList = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = sanitizeInput($_POST['judul']);
    $kategori_id = (int)$_POST['kategori_id'];
    $isi_pengaduan = sanitizeInput($_POST['isi_pengaduan']);
    
    // Validasi input
    if (empty($judul) || empty($kategori_id) || empty($isi_pengaduan)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($judul) < 10) {
        $error = 'Judul pengaduan minimal 10 karakter!';
    } elseif (strlen($isi_pengaduan) < 20) {
        $error = 'Isi pengaduan minimal 20 karakter!';
    } else {
        try {
            // Handle file upload jika ada
            $lampiran = null;
            if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['lampiran']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['lampiran']['tmp_name'], $uploadPath)) {
                    $lampiran = $fileName;
                }
            }
            
            // Insert pengaduan
            $stmt = $pdo->prepare("INSERT INTO pengaduan (user_id, kategori_id, judul, isi_pengaduan, lampiran) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $kategori_id, $judul, $isi_pengaduan, $lampiran]);
            
            $success = 'Pengaduan berhasil dikirim!';
            
            // Reset form
            $_POST = [];
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Pengaduan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-blue-900 font-sans min-h-screen flex items-center justify-center">
  <section class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-xl">
    <div class="mb-6 text-center">
      <h2 class="text-3xl font-bold text-blue-700 flex items-center justify-center gap-2">
        📝 Form Pengaduan
      </h2>
      <p class="text-blue-500 mt-1 text-sm">Silakan isi form berikut dengan lengkap dan jelas</p>
    </div>
    
    <?php if ($error): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= $error ?>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= $success ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <div>
        <label class="block text-sm font-semibold mb-1" for="kategori_id">Kategori Pengaduan</label>
        <select name="kategori_id" id="kategori_id" required
                class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
          <option value="">Pilih Kategori</option>
          <?php foreach ($kategoriList as $kategori): ?>
          <option value="<?= $kategori['id'] ?>" <?= (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kategori['id']) ? 'selected' : '' ?>>
            <?= $kategori['nama_kategori'] ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-semibold mb-1" for="judul">Judul Pengaduan</label>
        <input type="text" id="judul" name="judul" required placeholder="Judul Pengaduan"
               class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
               value="<?= isset($_POST['judul']) ? sanitizeInput($_POST['judul']) : '' ?>" />
      </div>
      
      <div>
        <label class="block text-sm font-semibold mb-1" for="isi_pengaduan">Isi Pengaduan</label>
        <textarea id="isi_pengaduan" name="isi_pengaduan" required placeholder="Tulis pengaduan Anda di sini..."
                  class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition h-32 resize-none"><?= isset($_POST['isi_pengaduan']) ? sanitizeInput($_POST['isi_pengaduan']) : '' ?></textarea>
      </div>
      
      <div>
        <label class="block text-sm font-semibold mb-1" for="lampiran">Upload Lampiran (Opsional)</label>
        <input type="file" id="lampiran" name="lampiran" accept="image/*,.pdf,.doc,.docx"
               class="w-full px-4 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white" />
        <p class="text-xs text-gray-500 mt-1">Format: .jpg / .jpeg / .png / .pdf / .doc / .docx | Maks: 5MB</p>
      </div>
      
      <div class="flex gap-4">
        <a href="dashboardUser.php" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200 text-center">
          Kembali
        </a>
        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
          Kirim Pengaduan
        </button>
      </div>
    </form>
  </section>
</body>
</html>
