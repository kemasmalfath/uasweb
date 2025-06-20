<?php
require_once '../config/database.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $kategori_id = (int)$_POST['kategori_id'];
    $judul = sanitizeInput($_POST['judul']);
    $isi_pengaduan = sanitizeInput($_POST['isi_pengaduan']);
    
    try {
        $pdo = getConnection();
        
        // Verifikasi pengaduan milik user dan belum ditanggapi
        $stmt = $pdo->prepare("SELECT lampiran FROM pengaduan WHERE id = ? AND user_id = ? AND status = 'Belum Ditanggapi'");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $data = $stmt->fetch();
        
        if (!$data) {
            echo "Data tidak ditemukan atau tidak dapat diubah.";
            exit;
        }
        
        $lampiran_lama = $data['lampiran'];
        $lampiran_baru = $lampiran_lama;
        
        // Handle upload file baru
        if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['lampiran']['name'], PATHINFO_EXTENSION);
            $nama_file = 'lampiran_' . time() . '_' . rand(100,999) . '.' . $ext;
            $tujuan = '../uploads/' . $nama_file;
            
            if (move_uploaded_file($_FILES['lampiran']['tmp_name'], $tujuan)) {
                $lampiran_baru = $nama_file;
                // Hapus file lama jika ada
                if (!empty($lampiran_lama) && file_exists('../uploads/' . $lampiran_lama)) {
                    unlink('../uploads/' . $lampiran_lama);
                }
            }
        }
        
        // Update data pengaduan
        $stmt = $pdo->prepare("UPDATE pengaduan SET 
                    kategori_id = ?,
                    judul = ?,
                    isi_pengaduan = ?,
                    lampiran = ?,
                    tanggal_update = NOW()
                  WHERE id = ? AND user_id = ?");
        
        if ($stmt->execute([$kategori_id, $judul, $isi_pengaduan, $lampiran_baru, $id, $_SESSION['user_id']])) {
            header("Location: daftarPengaduan.php");
            exit;
        } else {
            echo "Gagal mengupdate data.";
        }
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Metode tidak diperbolehkan.";
}
?>
