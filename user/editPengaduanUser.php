<?php
require_once '../config/database.php';
requireLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
  echo "<script>alert('ID pengaduan tidak ditemukan.'); window.location.href='daftarPengaduan.php';</script>";
  exit;
}

try {
    $pdo = getConnection();
    
    // Ambil data pengaduan (hanya milik user yang login)
    $stmt = $pdo->prepare("
        SELECT p.*, k.nama_kategori 
        FROM pengaduan p 
        JOIN kategori k ON p.kategori_id = k.id 
        WHERE p.id = ? AND p.user_id = ?
    ");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $data = $stmt->fetch();
    
    if (!$data) {
      echo "<script>alert('Data tidak ditemukan.'); window.location.href='daftarPengaduan.php';</script>";
      exit;
    }
    
    // Cek apakah masih bisa diedit
    if ($data['status'] !== 'Belum Ditanggapi') {
      echo "<script>alert('Pengaduan yang sudah ditanggapi tidak dapat diedit.'); window.location.href='daftarPengaduan.php';</script>";
      exit;
    }
    
    // Ambil kategori untuk dropdown
    $stmt = $pdo->query("SELECT * FROM kategori ORDER BY id");
    $kategoriList = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengaduan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center px-4 py-12">
  <div class="bg-white shadow-md p-8 rounded-xl w-full max-w-xl">
    <h2 class="text-2xl font-bold text-blue-700 mb-6">✏️ Edit Pengaduan</h2>
    <form action="updatePengaduanUser.php" method="POST" enctype="multipart/form-data" class="space-y-4">
      <input type="hidden" name="id" value="<?= $data['id'] ?>">
      
      <div>
        <label class="block text-sm font-semibold mb-1" for="kategori_id">Kategori Pengaduan</label>
        <select name="kategori_id" id="kategori_id" required class="w-full px-4 py-2 border border-blue-300 rounded-lg">
          <option value="">Pilih Kategori</option>
          <?php foreach ($kategoriList as $kategori): ?>
          <option value="<?= $kategori['id'] ?>" <?= $data['kategori_id'] == $kategori['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($kategori['nama_kategori']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div>
        <label class="block text-sm font-semibold mb-1" for="judul">Judul Pengaduan</label>
        <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required class="w-full px-4 py-2 border border-blue-300 rounded-lg" />
      </div>
      
      <div>
        <label class="block text-sm font-semibold mb-1" for="isi_pengaduan">Isi Pengaduan</label>
        <textarea id="isi_pengaduan" name="isi_pengaduan" required class="w-full px-4 py-2 border border-blue-300 rounded-lg resize-none h-28"><?= htmlspecialchars($data['isi_pengaduan']) ?></textarea>
      </div>
      
      <div>
        <label class="block text-sm font-semibold mb-1" for="lampiran">Ubah Lampiran (opsional)</label>
        <input type="file" name="lampiran" id="lampiran" accept="image/*,.pdf,.doc,.docx" class="block w-full text-sm text-gray-600" />
        <?php if (!empty($data['lampiran'])): ?>
          <p class="mt-2 text-sm text-gray-600">File saat ini: 
            <a href="../uploads/<?= htmlspecialchars($data['lampiran']) ?>" target="_blank" class="text-blue-600 hover:underline">
              <?= htmlspecialchars($data['lampiran']) ?>
            </a>
          </p>
        <?php endif; ?>
      </div>
      
      <div class="flex justify-between pt-4">
        <a href="daftarPengaduan.php" class="px-5 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold">
          Batal
        </a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</body>
</html>
