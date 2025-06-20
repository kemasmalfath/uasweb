<?php
require_once '../config/database.php';
requireLogin();

try {
    $pdo = getConnection();
    
    // Ambil pengaduan user yang login
    $stmt = $pdo->prepare("
        SELECT p.*, k.nama_kategori 
        FROM pengaduan p 
        JOIN kategori k ON p.kategori_id = k.id 
        WHERE p.user_id = ? 
        ORDER BY p.tanggal_pengaduan DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $pengaduanList = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Pengaduan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .glass {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(0, 0, 0, 0.05);
    }
  </style>
</head>
<body class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12 px-4 sm:px-6 lg:px-8">
  <header class="max-w-7xl mx-auto text-center mb-10">
    <h1 class="text-4xl sm:text-5xl font-extrabold text-blue-900 mb-3">Daftar Pengaduan Anda</h1>
    <p class="text-blue-700 text-base sm:text-lg">Cek status dan detail laporan yang telah Anda kirimkan</p>
    <a href="formPengaduan.php" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full text-sm font-semibold shadow-md transition">
      + Tambah Pengaduan
    </a>
  </header>
  <main class="max-w-7xl mx-auto glass rounded-2xl shadow-2xl p-8 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-blue-600 text-white text-left">
          <tr>
            <th class="px-6 py-4 font-semibold">No</th>
            <th class="px-6 py-4 font-semibold">Judul Pengaduan</th>
            <th class="px-6 py-4 font-semibold">Kategori</th>
            <th class="px-6 py-4 font-semibold">Tanggal & Waktu</th>
            <th class="px-6 py-4 font-semibold">Status</th>
            <th class="px-6 py-4 text-center font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          <?php
          $no = 1;
          foreach ($pengaduanList as $data) {
            $statusClass = match ($data['status']) {
              'Sudah Ditanggapi' => 'bg-blue-100 text-blue-800',
              'Selesai'  => 'bg-green-100 text-green-800',
              'Belum Ditanggapi' => 'bg-yellow-200 text-yellow-800',
              default    => 'bg-gray-100 text-gray-800',
            };
            $tanggal = date("d/m/Y", strtotime($data['tanggal_pengaduan']));
            $waktu = date("H:i", strtotime($data['tanggal_pengaduan']));
            echo "
              <tr class='hover:bg-blue-50 transition'>
                <td class='px-6 py-5 text-gray-700 font-medium'>{$no}</td>
                <td class='px-6 py-5 text-gray-900 font-semibold'>" . htmlspecialchars($data['judul']) . "</td>
                <td class='px-6 py-5 text-gray-700'>" . htmlspecialchars($data['nama_kategori']) . "</td>
                <td class='px-6 py-5 text-gray-700'>
                  <div class='mb-1'>{$tanggal}</div>
                  <div class='text-xs text-gray-500'>Pukul {$waktu}</div>
                </td>
                <td class='px-6 py-5'>
                  <span class='inline-block px-3 py-1 text-xs font-semibold rounded-full $statusClass'>" . $data['status'] . "</span>
                </td>
                <td class='px-6 py-5 text-center space-x-2'>
                  <a href='detailPengaduan.php?id={$data['id']}' class='text-blue-600 hover:underline font-medium'>Detail</a>";
            
            // Tampilkan Edit dan Hapus untuk semua status, tapi dengan kondisi berbeda
            if ($data['status'] === 'Belum Ditanggapi') {
              // Jika belum ditanggapi, tampilkan edit dan hapus normal
              echo "
                  <a href='editPengaduanUser.php?id={$data['id']}' class='text-green-600 hover:underline font-medium'>Edit</a>
                  <a href='hapusPengaduanUser.php?id={$data['id']}' onclick='return confirm(\"Yakin ingin menghapus?\")' class='text-red-600 hover:underline font-medium'>Hapus</a>";
            } else {
              // Jika sudah ditanggapi/selesai, tampilkan tapi disabled atau dengan pesan
              echo "
                  <span class='text-gray-400 font-medium cursor-not-allowed' title='Tidak dapat diedit karena sudah ditanggapi'>Edit</span>
                  <span class='text-gray-400 font-medium cursor-not-allowed' title='Tidak dapat dihapus karena sudah ditanggapi'>Hapus</span>";
            }
            
            echo "
                </td>
              </tr>";
            $no++;
          }
          
          if (empty($pengaduanList)) {
            echo "<tr><td colspan='6' class='px-6 py-8 text-center text-gray-500'>Anda belum memiliki pengaduan. <a href='formPengaduan.php' class='text-blue-600 hover:underline'>Buat pengaduan pertama Anda</a></td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
  <footer class="mt-12 text-center text-blue-600 text-sm">
    <a href="dashboardUser.php" class="hover:underline">← Kembali ke Beranda</a>
  </footer>
</body>
</html>
