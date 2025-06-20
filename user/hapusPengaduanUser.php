<?php
require_once '../config/database.php';
requireLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
  echo "<script>alert('ID tidak ditemukan.'); window.location.href='daftarPengaduan.php';</script>";
  exit;
}

try {
    $pdo = getConnection();
    
    // Ambil data pengaduan (hanya milik user yang login)
    $stmt = $pdo->prepare("SELECT lampiran, status FROM pengaduan WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $data = $stmt->fetch();
    
    if (!$data) {
      echo "<script>alert('Data tidak ditemukan.'); window.location.href='daftarPengaduan.php';</script>";
      exit;
    }
    
    // Cek apakah masih bisa dihapus
    if ($data['status'] !== 'Belum Ditanggapi') {
      echo "<script>alert('Pengaduan yang sudah ditanggapi tidak dapat dihapus.'); window.location.href='daftarPengaduan.php';</script>";
      exit;
    }
    
    // Hapus file lampiran jika ada
    if (!empty($data['lampiran']) && file_exists('../uploads/' . $data['lampiran'])) {
      unlink('../uploads/' . $data['lampiran']);
    }
    
    // Hapus data pengaduan
    $stmt = $pdo->prepare("DELETE FROM pengaduan WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    
    echo "<script>alert('Pengaduan berhasil dihapus.'); window.location.href='daftarPengaduan.php';</script>";
    exit;
    
} catch (PDOException $e) {
    echo "<script>alert('Terjadi kesalahan sistem.'); window.location.href='daftarPengaduan.php';</script>";
    exit;
}
?>
