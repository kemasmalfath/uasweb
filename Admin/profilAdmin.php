<?php
require_once '../config/database.php';
requireAdmin();

$error = '';
$success = '';

// Ambil data admin saat ini
try {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $adminData = $stmt->fetch();
    
    if (!$adminData) {
        header('Location: loginAdmin.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update profil
        $nama = sanitizeInput($_POST['nama']);
        $email = sanitizeInput($_POST['email']);
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (empty($nama) || empty($email)) {
            $error = 'Nama dan email harus diisi!';
        } elseif (!isValidEmail($email)) {
            $error = 'Format email tidak valid!';
        } else {
            try {
                // Cek email sudah digunakan admin lain
                $stmt = $pdo->prepare("SELECT id FROM admin WHERE email = ? AND id != ?");
                $stmt->execute([$email, $_SESSION['admin_id']]);
                if ($stmt->fetch()) {
                    $error = 'Email sudah digunakan admin lain!';
                } else {
                    // Handle upload foto profil
                    $foto_profil = $adminData['foto_profil'];
                    
                    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = '../uploads/admin_profiles/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        $fileType = $_FILES['foto_profil']['type'];
                        
                        if (in_array($fileType, $allowedTypes)) {
                            $fileName = 'admin_' . $_SESSION['admin_id'] . '_' . time() . '.' . pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
                            $uploadPath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $uploadPath)) {
                                if ($adminData['foto_profil'] !== 'default-profile.png' && file_exists($uploadDir . $adminData['foto_profil'])) {
                                    unlink($uploadDir . $adminData['foto_profil']);
                                }
                                $foto_profil = $fileName;
                            }
                        } else {
                            $error = 'Format file tidak didukung! Gunakan JPG, PNG, atau GIF.';
                        }
                    }
                    
                    // Handle hapus foto
                    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] === '1') {
                        if ($adminData['foto_profil'] !== 'default-profile.png' && file_exists($uploadDir . $adminData['foto_profil'])) {
                            unlink($uploadDir . $adminData['foto_profil']);
                        }
                        $foto_profil = 'default-profile.png';
                    }
                    
                    if (empty($error)) {
                        // Jika ada password baru
                        if (!empty($new_password)) {
                            if (empty($old_password)) {
                                $error = 'Password lama harus diisi untuk mengganti password!';
                            } elseif (strlen($new_password) < 6) {
                                $error = 'Password baru minimal 6 karakter!';
                            } elseif ($new_password !== $confirm_password) {
                                $error = 'Konfirmasi password tidak cocok!';
                            } elseif (!verifyPassword($old_password, $adminData['password'])) {
                                $error = 'Password lama tidak benar!';
                            } else {
                                // Update dengan password baru
                                $hashedPassword = hashPassword($new_password);
                                $stmt = $pdo->prepare("UPDATE admin SET nama = ?, email = ?, password = ?, foto_profil = ?, updated_at = NOW() WHERE id = ?");
                                $stmt->execute([$nama, $email, $hashedPassword, $foto_profil, $_SESSION['admin_id']]);
                                $success = 'Profil dan password berhasil diperbarui!';
                            }
                        } else {
                            // Update tanpa password
                            $stmt = $pdo->prepare("UPDATE admin SET nama = ?, email = ?, foto_profil = ?, updated_at = NOW() WHERE id = ?");
                            $stmt->execute([$nama, $email, $foto_profil, $_SESSION['admin_id']]);
                            $success = 'Profil berhasil diperbarui!';
                        }
                        
                        if (empty($error)) {
                            // Update session
                            $_SESSION['admin_nama'] = $nama;
                            $_SESSION['admin_email'] = $email;
                            
                            // Refresh data
                            $adminData['nama'] = $nama;
                            $adminData['email'] = $email;
                            $adminData['foto_profil'] = $foto_profil;
                            
                            $success = 'Profil berhasil diperbarui!';
                            
                            // Tambahkan script untuk kembali ke view mode
                            echo "<script>
                                setTimeout(function() {
                                    document.getElementById('biodataEdit').style.display = 'none';
                                    document.getElementById('biodataView').style.display = 'block';
                                    
                                    // Update foto profil di view
                                    const fotoView = document.getElementById('fotoProfilView');
                                    if (fotoView) {
                                        if ('$foto_profil' !== 'default-profile.png') {
                                            fotoView.outerHTML = '<img id=\"fotoProfilView\" src=\"../uploads/admin_profiles/$foto_profil\" class=\"w-24 h-24 rounded-full mb-2 object-cover\" alt=\"Foto Profil\">';
                                        } else {
                                            const initials = '$nama'.substring(0, 2).toUpperCase();
                                            fotoView.outerHTML = '<div id=\"fotoProfilView\" class=\"w-24 h-24 rounded-full mb-2 bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold\">' + initials + '</div>';
                                        }
                                    }
                                    
                                    // Update nama di view
                                    const namaElements = document.querySelectorAll('.admin-nama');
                                    namaElements.forEach(function(el) {
                                        el.textContent = '$nama';
                                    });
                                    
                                    // Update email di view
                                    const emailElements = document.querySelectorAll('.admin-email');
                                    emailElements.forEach(function(el) {
                                        el.textContent = '$email';
                                    });
                                }, 1500);
                            </script>";
                        }
                    }
                }
            } catch (PDOException $e) {
                $error = 'Terjadi kesalahan sistem!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Admin | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <!-- Sidebar -->
  <aside class="fixed left-0 top-0 w-60 h-screen bg-blue-600 border-r border-blue-800 flex flex-col py-6 px-4 z-10 shadow-sm">
    <div class="flex flex-col items-center mb-10">
      <img src="logo.png" class="w-12 h-12 mb-2" alt="Logo">
      <span class="text-lg font-bold tracking-wide text-white">SuaraKita</span>
    </div>
    <nav class="flex flex-col gap-2 font-medium text-white">
      <a href="dashboardAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
        </svg>
        Dashboard
      </a>
      <a href="daftarPengaduan.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
        Daftar Pengaduan
      </a>
      <a href="profilAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-800 text-white font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        Profil Admin
      </a>
    </nav>
    <div class="flex-grow"></div>
    <a href="logoutAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 text-white transition">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
      </svg>
      Logout
    </a>
  </aside>

  <main class="ml-60 p-8 flex justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 border w-full max-w-lg">
      
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
      
      <div id="biodataView" <?= isset($_POST['update_profile']) ? 'style="display: none;"' : '' ?>>
        <div class="flex flex-col items-center mb-6">
            <?php if ($adminData['foto_profil'] && $adminData['foto_profil'] !== 'default-profile.png'): ?>
            <img id="fotoProfilView" src="../uploads/admin_profiles/<?= $adminData['foto_profil'] ?>" class="w-24 h-24 rounded-full mb-2 object-cover" alt="Foto Profil">
            <?php else: ?>
            <div id="fotoProfilView" class="w-24 h-24 rounded-full mb-2 bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">
                <?= strtoupper(substr($adminData['nama'], 0, 2)) ?>
            </div>
            <?php endif; ?>
            <h2 class="text-xl font-bold text-gray-700 mb-1 admin-nama"><?= $adminData['nama'] ?></h2>
            <span class="text-gray-400 text-sm">Super Admin</span>
        </div>
        <div class="mb-3">
            <label class="text-gray-600 text-sm">Nama</label>
            <div class="font-semibold text-gray-800 admin-nama"><?= $adminData['nama'] ?></div>
        </div>
        <div class="mb-3">
            <label class="text-gray-600 text-sm">Email</label>
            <div class="font-semibold text-gray-800 admin-email"><?= $adminData['email'] ?></div>
        </div>
        <div class="mb-3">
            <label class="text-gray-600 text-sm">Username</label>
            <div class="font-semibold text-gray-800"><?= $adminData['username'] ?></div>
        </div>
        <div class="mb-3">
            <label class="text-gray-600 text-sm">Password</label>
            <div class="font-semibold text-gray-800">••••••••</div>
        </div>
        <button onclick="editProfil()" class="mt-6 w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            Edit Profil
        </button>
    </div>

      <form id="biodataEdit" method="POST" enctype="multipart/form-data" class="<?= isset($_POST['update_profile']) ? '' : 'hidden' ?>" onsubmit="return validateForm()">
        <div class="flex flex-col items-center mb-6">
          <?php if ($adminData['foto_profil'] && $adminData['foto_profil'] !== 'default-profile.png'): ?>
          <img id="previewFoto" src="../uploads/admin_profiles/<?= $adminData['foto_profil'] ?>" class="w-24 h-24 rounded-full mb-2 object-cover" alt="Preview Foto">
          <?php else: ?>
          <div id="previewFoto" class="w-24 h-24 rounded-full mb-2 bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">
            <?= strtoupper(substr($adminData['nama'], 0, 2)) ?>
          </div>
          <?php endif; ?>
          <div class="flex gap-2 mb-3">
            <label class="cursor-pointer bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
              Ganti Foto
              <input id="fileFoto" name="foto_profil" type="file" accept="image/*" class="hidden" onchange="previewImage(event)">
            </label>
            <button type="button" onclick="hapusFoto()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
              Hapus Foto
            </button>
          </div>
          <input type="hidden" id="hapusFotoFlag" name="hapus_foto" value="0">
        </div>

        <div class="mb-3">
          <label class="text-gray-600 text-sm">Nama</label>
          <input type="text" name="nama" id="nama" class="w-full border rounded-lg p-2 mt-1" value="<?= $adminData['nama'] ?>" required>
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Email</label>
          <input type="email" name="email" id="email" class="w-full border rounded-lg p-2 mt-1" value="<?= $adminData['email'] ?>" required>
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Username</label>
          <input type="text" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" value="<?= $adminData['username'] ?>" disabled>
          <p class="text-xs text-gray-500 mt-1">Username tidak dapat diubah</p>
        </div>
        
        <!-- Section Password -->
        <div class="border-t pt-4 mt-4">
          <h3 class="text-gray-700 font-semibold mb-3">Ganti Password (Opsional)</h3>
          <div class="mb-3">
            <label class="text-gray-600 text-sm">Password Lama</label>
            <input type="password" name="old_password" id="old_password" class="w-full border rounded-lg p-2 mt-1" placeholder="Kosongkan jika tidak ingin mengganti password">
          </div>
          <div class="mb-3">
            <label class="text-gray-600 text-sm">Password Baru</label>
            <input type="password" name="new_password" id="new_password" class="w-full border rounded-lg p-2 mt-1" placeholder="Password baru (min. 6 karakter)">
          </div>
          <div class="mb-3">
            <label class="text-gray-600 text-sm">Konfirmasi Password Baru</label>
            <input type="password" name="confirm_password" id="confirm_password" class="w-full border rounded-lg p-2 mt-1" placeholder="Ulangi password baru">
          </div>
        </div>
        
        <div class="flex gap-2 mt-6">
          <button type="button" onclick="batalEdit()" class="flex-1 bg-gray-200 text-gray-600 py-2 rounded-lg font-semibold hover:bg-gray-300">
            Batal
          </button>
          <button type="submit" name="update_profile" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    function editProfil() {
      document.getElementById('biodataView').style.display = 'none';
      document.getElementById('biodataEdit').style.display = 'block';
    }
    
    function batalEdit() {
      document.getElementById('biodataEdit').style.display = 'none';
      document.getElementById('biodataView').style.display = 'block';
      
      // Reset form
      document.getElementById('fileFoto').value = '';
      document.getElementById('hapusFotoFlag').value = '0';
      document.getElementById('old_password').value = '';
      document.getElementById('new_password').value = '';
      document.getElementById('confirm_password').value = '';
      
      // Reset preview foto
      const originalFoto = document.getElementById('fotoProfilView');
      const previewFoto = document.getElementById('previewFoto');
      if (originalFoto.tagName === 'IMG') {
        previewFoto.outerHTML = '<img id="previewFoto" src="' + originalFoto.src + '" class="w-24 h-24 rounded-full mb-2 object-cover" alt="Preview Foto">';
      } else {
        previewFoto.outerHTML = originalFoto.outerHTML.replace('fotoProfilView', 'previewFoto');
      }
    }
    
    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function() {
        const previewContainer = document.getElementById('previewFoto');
        previewContainer.outerHTML = '<img id="previewFoto" src="' + reader.result + '" class="w-24 h-24 rounded-full mb-2 object-cover" alt="Preview Foto">';
      };
      reader.readAsDataURL(event.target.files[0]);
      document.getElementById('hapusFotoFlag').value = '0';
    }
    
    function hapusFoto() {
      const previewContainer = document.getElementById('previewFoto');
      const nama = document.getElementById('nama').value;
      const initials = nama.substring(0, 2).toUpperCase();
      previewContainer.outerHTML = '<div id="previewFoto" class="w-24 h-24 rounded-full mb-2 bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">' + initials + '</div>';
      document.getElementById('fileFoto').value = '';
      document.getElementById('hapusFotoFlag').value = '1';
    }
    
    function validateForm() {
      const nama = document.getElementById('nama').value.trim();
      const email = document.getElementById('email').value.trim();
      const oldPassword = document.getElementById('old_password').value;
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      
      if (nama.length < 2) {
        alert('Nama minimal 2 karakter!');
        return false;
      }
      
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        alert('Format email tidak valid!');
        return false;
      }
      
      // Validasi password jika ada yang diisi
      if (newPassword || confirmPassword || oldPassword) {
        if (!oldPassword) {
          alert('Password lama harus diisi untuk mengganti password!');
          return false;
        }
        if (newPassword.length < 6) {
          alert('Password baru minimal 6 karakter!');
          return false;
        }
        if (newPassword !== confirmPassword) {
          alert('Konfirmasi password tidak cocok!');
          return false;
        }
      }
      
      return true;
    }
    
    // Update initials saat nama berubah
    document.getElementById('nama').addEventListener('input', function() {
      const previewFoto = document.getElementById('previewFoto');
      if (previewFoto.tagName === 'DIV') {
        const initials = this.value.substring(0, 2).toUpperCase();
        previewFoto.textContent = initials;
      }
    });
  </script>
</body>
</html>
