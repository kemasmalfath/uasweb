<?php
require_once '../config/database.php';
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi input
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password baru minimal 6 karakter!';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        try {
            $pdo = getConnection();
            
            // Verifikasi password lama
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            
            if (!$user || !verifyPassword($old_password, $user['password'])) {
                $error = 'Password lama tidak benar!';
            } else {
                // Update password baru
                $hashedPassword = hashPassword($new_password);
                $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
                
                $success = 'Password berhasil diubah!';
            }
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
  <title>Ganti Sandi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 text-blue-900 min-h-screen flex items-center justify-center">
  <section class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-md">
    <div class="mb-6">
      <a href="dashboardUser.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
    
    <h2 class="text-3xl font-bold text-center text-blue-700 mb-8">Ganti Sandi</h2>
    
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
    
    <form method="POST" class="space-y-6" onsubmit="return validateForm()">
      <div>
        <label for="old_password" class="block text-lg mb-1">Sandi Lama</label>
        <input type="password" id="old_password" name="old_password" placeholder="Masukkan sandi lama" required
               class="w-full px-4 py-3 border border-blue-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label for="new_password" class="block text-lg mb-1">Sandi Baru</label>
        <input type="password" id="new_password" name="new_password" placeholder="Masukkan sandi baru (min. 6 karakter)" required
               class="w-full px-4 py-3 border border-blue-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label for="confirm_password" class="block text-lg mb-1">Konfirmasi Sandi Baru</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi sandi baru" required
               class="w-full px-4 py-3 border border-blue-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div class="flex justify-between mt-6">
        <a href="dashboardUser.php" 
           class="bg-gray-300 hover:bg-gray-400 text-blue-900 px-5 py-3 rounded-lg text-lg transition duration-300 ease-in-out">
          Kembali
        </a>
        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg transition duration-300 ease-in-out">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </section>

  <script>
    function validateForm() {
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      
      if (newPassword.length < 6) {
        alert('Password baru minimal 6 karakter!');
        return false;
      }
      
      if (newPassword !== confirmPassword) {
        alert('Konfirmasi password tidak cocok!');
        return false;
      }
      
      return true;
    }
  </script>
</body>
</html>
