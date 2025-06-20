<?php
require_once '../config/database.php';
requireLogin();

$error = '';
$success = '';

// Ambil data user saat ini
try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userData = $stmt->fetch();
    
    if (!$userData) {
        header('Location: loginUser.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitizeInput($_POST['nama']);
    $nim = sanitizeInput($_POST['nim']);
    $jurusan = sanitizeInput($_POST['jurusan']);
    $email = sanitizeInput($_POST['email']);
    
    // Validasi input
    if (empty($nama) || empty($email)) {
        $error = 'Nama dan email harus diisi!';
    } elseif (!isValidEmail($email)) {
        $error = 'Format email tidak valid!';
    } else {
        try {
            // Cek email sudah digunakan user lain
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                $error = 'Email sudah digunakan user lain!';
            } else {
                // Handle upload foto profil
                $foto_profil = $userData['foto_profil']; // Keep existing photo
                
                if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../uploads/profiles/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $fileType = $_FILES['foto_profil']['type'];
                    
                    if (in_array($fileType, $allowedTypes)) {
                        $fileName = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $uploadPath)) {
                            // Hapus foto lama jika bukan default
                            if ($userData['foto_profil'] !== 'default-profile.png' && file_exists($uploadDir . $userData['foto_profil'])) {
                                @unlink($uploadDir . $userData['foto_profil']);
                            }
                            $foto_profil = $fileName;
                        } else {
                            $error = 'Gagal mengupload file. Error: ' . error_get_last()['message'];
                        }
                    } else {
                        $error = 'Format file tidak didukung! Gunakan JPG, PNG, atau GIF.';
                    }
                }
                
                if (empty($error)) {
                    // Update data user
                    $stmt = $pdo->prepare("UPDATE users SET nama = ?, nim = ?, jurusan = ?, email = ?, foto_profil = ?, updated_at = NOW() WHERE id = ?");
                    if (!$stmt->execute([$nama, $nim, $jurusan, $email, $foto_profil, $_SESSION['user_id']])) {
                        $error = 'Gagal mengupdate profil: ' . implode(', ', $stmt->errorInfo());
                    } else {
                        // Update session
                        $_SESSION['nama'] = $nama;
                        $_SESSION['email'] = $email;
                        $_SESSION['nim'] = $nim;
                        $_SESSION['jurusan'] = $jurusan;
                        
                        // Refresh data
                        $userData['nama'] = $nama;
                        $userData['nim'] = $nim;
                        $userData['jurusan'] = $jurusan;
                        $userData['email'] = $email;
                        $userData['foto_profil'] = $foto_profil;
                        
                        $success = 'Profil berhasil diperbarui!';
                    }
                }
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 text-blue-900 font-sans min-h-screen flex items-center justify-center">
  <section class="bg-white p-10 rounded-2xl shadow-lg w-full max-w-4xl">
    <!-- Tombol kembali ke dashboard -->
    <div class="mb-6">
      <a href="dashboardUser.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg text-md">
        ← Kembali ke Dashboard
      </a>
    </div>

    <h2 class="text-4xl font-bold mb-8 text-blue-700">Edit Profil</h2>
    
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

    <div class="flex flex-col md:flex-row items-center md:items-start space-y-8 md:space-y-0 md:space-x-12">
      <div class="text-center">
        <div class="w-36 h-36 rounded-full border-4 border-blue-300 overflow-hidden mb-4">
          <?php if ($userData['foto_profil'] && $userData['foto_profil'] !== 'default-profile.png'): ?>
          <img src="../uploads/profiles/<?= $userData['foto_profil'] ?>" alt="Foto Profil" class="w-full h-full object-cover">
          <?php else: ?>
          <div class="w-full h-full bg-gray-200 flex items-center justify-center text-blue-500 text-2xl font-bold">
            <?= strtoupper(substr($userData['nama'], 0, 2)) ?>
          </div>
          <?php endif; ?>
        </div>
        <p class="text-sm text-gray-600 mb-2">Foto Profil</p>
      </div>
      
      <div class="w-full">
        <form method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="return validateForm()">
          <div>
            <label class="block text-lg mb-1" for="foto_profil">Ganti Foto Profil</label>
            <input id="foto_profil" name="foto_profil" type="file" accept="image/*" 
                   class="w-full border border-blue-300 px-4 py-3 rounded text-lg" />
            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
          </div>
          <div>
            <label class="block text-lg mb-1" for="nama">Nama Lengkap</label>
            <input id="nama" name="nama" type="text" placeholder="Nama Lengkap" required
                   class="w-full border border-blue-300 px-4 py-3 rounded text-lg" 
                   value="<?= $userData['nama'] ?>" />
          </div>
          <div>
            <label class="block text-lg mb-1" for="nim">NIM</label>
            <input id="nim" name="nim" type="text" placeholder="Nomor Induk Mahasiswa" 
                   class="w-full border border-blue-300 px-4 py-3 rounded text-lg"
                   value="<?= $userData['nim'] ?>" />
          </div>
          <div>
            <label class="block text-lg mb-1" for="jurusan">Jurusan</label>
            <input id="jurusan" name="jurusan" type="text" placeholder="Jurusan" 
                   class="w-full border border-blue-300 px-4 py-3 rounded text-lg"
                   value="<?= $userData['jurusan'] ?>" />
          </div>
          <div>
            <label class="block text-lg mb-1" for="email">E-mail</label>
            <input id="email" name="email" type="email" placeholder="E-mail" required
                   class="w-full border border-blue-300 px-4 py-3 rounded text-lg"
                   value="<?= $userData['email'] ?>" />
          </div>
          <div>
            <label class="block text-lg mb-1">Username</label>
            <input type="text" class="w-full border border-gray-300 px-4 py-3 rounded text-lg bg-gray-100" 
                   value="<?= $userData['username'] ?>" disabled />
            <p class="text-sm text-gray-500 mt-1">Username tidak dapat diubah</p>
          </div>
          <div class="text-right">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <script>
    function validateForm() {
      const nama = document.getElementById('nama').value.trim();
      const email = document.getElementById('email').value.trim();
      
      if (nama.length < 2) {
        alert('Nama minimal 2 karakter!');
        return false;
      }
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        alert('Format email tidak valid!');
        return false;
      }
      
      return true;
    }
    
    // Preview foto sebelum upload
    document.getElementById('foto_profil').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          // Bisa ditambahkan preview foto di sini
          console.log('File selected:', file.name);
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
