<?php
require_once 'config/database.php';

startSession();

$error = '';
$success = '';
$showRegister = isset($_GET['register']);

// Handle logout
if (isset($_GET['logout'])) {
    $logoutType = $_GET['logout'];
    if ($logoutType === 'admin') {
        logoutAdmin();
    } elseif ($logoutType === 'user') {
        logoutUser();
    } elseif ($logoutType === 'all') {
        logoutAll();
    }
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Handle Login
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        $role = $_POST['role'];
        
        if (empty($username) || empty($password) || empty($role)) {
            $error = 'Semua field harus diisi!';
        } else {
            try {
                $pdo = getConnection();
                
                if ($role === 'admin') {
                    // Login sebagai admin
                    $stmt = $pdo->prepare("SELECT id, username, password, nama, email FROM admin WHERE username = ?");
                    $stmt->execute([$username]);
                    $user = $stmt->fetch();
                    
                    if ($user && verifyPassword($password, $user['password'])) {
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_username'] = $user['username'];
                        $_SESSION['admin_nama'] = $user['nama'];
                        $_SESSION['admin_email'] = $user['email'];
                        $_SESSION['admin_role'] = 'admin';
                        
                        header('Location: admin/dashboardAdmin.php');
                        exit;
                    } else {
                        $error = 'Username atau password admin salah!';
                    }
                } else {
                    // Login sebagai user
                    $stmt = $pdo->prepare("SELECT id, username, password, nama, email, nim, jurusan FROM users WHERE username = ? AND status = 'aktif'");
                    $stmt->execute([$username]);
                    $user = $stmt->fetch();
                    
                    if ($user && verifyPassword($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['nama'] = $user['nama'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['nim'] = $user['nim'];
                        $_SESSION['jurusan'] = $user['jurusan'];
                        $_SESSION['user_role'] = 'user';
                        
                        header('Location: user/dashboardUser.php');
                        exit;
                    } else {
                        $error = 'Username atau password user salah!';
                    }
                }
            } catch (PDOException $e) {
                $error = 'Terjadi kesalahan sistem!';
            }
        }
    } elseif (isset($_POST['register'])) {
        // Handle Register
        $nama = sanitizeInput($_POST['nama']);
        $email = sanitizeInput($_POST['email']);
        $username = sanitizeInput($_POST['username']);
        $nim = sanitizeInput($_POST['nim']);
        $jurusan = sanitizeInput($_POST['jurusan']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $role = $_POST['role'];
        
        if (empty($nama) || empty($email) || empty($username) || empty($password) || empty($role)) {
            $error = 'Field nama, email, username, password, dan role harus diisi!';
        }
        elseif (!isValidEmail($email)) {
            $error = 'Format email tidak valid!';
        } elseif (strlen($password) < 6) {
            $error = 'Password minimal 6 karakter!';
        } elseif ($password !== $confirm_password) {
            $error = 'Konfirmasi password tidak cocok!';
        } else {
            try {
                $pdo = getConnection();
                
                if ($role === 'admin') {
                    $stmt = $pdo->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
                    $stmt->execute([$username, $email]);
                    if ($stmt->fetch()) {
                        $error = 'Username atau email admin sudah digunakan!';
                    } else {
                        $hashedPassword = hashPassword($password);
                        $stmt = $pdo->prepare("INSERT INTO admin (username, password, nama, email, role) VALUES (?, ?, ?, ?, 'admin')");
                        $stmt->execute([$username, $hashedPassword, $nama, $email]);
                        $success = 'Pendaftaran admin berhasil! Silakan login.';
                    }
                } else {
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                    $stmt->execute([$username, $email]);
                    if ($stmt->fetch()) {
                        $error = 'Username atau email user sudah digunakan!';
                    } else {
                        $hashedPassword = hashPassword($password);
                        $nim = empty($nim) ? null : $nim;
                        $jurusan = empty($jurusan) ? null : $jurusan;

                        $stmt = $pdo->prepare("INSERT INTO users (username, password, nama, nim, jurusan, email, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");
                        $stmt->execute([$username, $hashedPassword, $nama, $nim, $jurusan, $email]);
                        $success = 'Pendaftaran user berhasil! Silakan login.';
                    }
                }
            } catch (PDOException $e) {
                $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $showRegister ? 'Daftar' : 'Login' ?> | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
    <div class="flex flex-col items-center mb-7 text-center">
      <img src="https://dashboard.sdgcenter.unila.ac.id/frontend/gambar/UNILA.png" class="w-24 h-24 mb-3" alt="FMIPA Logo" />
      <span class="text-2xl font-bold text-gray-700 mb-2 tracking-wide">
        Kritik & Saran Mahasiswa FMIPA
      </span>
      <span class="text-gray-500"><?= $showRegister ? 'Daftar Akun Baru' : 'Masuk ke Sistem' ?></span>
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
    
    <!-- Toggle Buttons -->
    <div class="flex mb-6 bg-gray-100 rounded-lg p-1">
      <button onclick="showLogin()" id="loginBtn" class="<?= !$showRegister ? 'bg-white shadow' : '' ?> flex-1 py-2 px-4 rounded-md text-sm font-medium transition">
        Login
      </button>
      <button onclick="showRegister()" id="registerBtn" class="<?= $showRegister ? 'bg-white shadow' : '' ?> flex-1 py-2 px-4 rounded-md text-sm font-medium transition">
        Daftar
      </button>
    </div>
    
    <!-- Login Form -->
    <form id="loginForm" method="POST" class="space-y-4 <?= $showRegister ? 'hidden' : '' ?>">
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Role</label>
        <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
          <option value="">Pilih Role</option>
          <option value="user">User/Mahasiswa</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Username</label>
        <input name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
               type="text" placeholder="Masukkan username" required />
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Password</label>
        <input name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
               type="password" placeholder="Masukkan password" required />
      </div>
      <button type="submit" name="login" class="w-full py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 transition">
        Login
      </button>
    </form>
    
    <!-- Register Form -->
    <form id="registerForm" method="POST" class="space-y-4 <?= !$showRegister ? 'hidden' : '' ?>">
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Role</label>
        <select name="role" id="registerRole" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="toggleFields()">
          <option value="">Pilih Role</option>
          <option value="user">User/Mahasiswa</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Nama Lengkap" required 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Email</label>
        <input type="email" name="email" placeholder="Email Aktif" required 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Username</label>
        <input type="text" name="username" placeholder="Username" required 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div id="userFields" class="space-y-4">
        <div>
          <label class="block text-gray-700 font-semibold mb-1">NIM <span class="text-gray-400">(Opsional untuk User)</span></label>
          <input type="text" name="nim" placeholder="NIM" 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block text-gray-700 font-semibold mb-1">Jurusan <span class="text-gray-400">(Opsional untuk User)</span></label>
          <input type="text" name="jurusan" placeholder="Jurusan" 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
        </div>
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Password</label>
        <input type="password" name="password" placeholder="Password (min. 6 karakter)" required 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Konfirmasi Password</label>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <button type="submit" name="register" class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
        Daftar
      </button>
    </form>
  </div>

  <script>
    function showLogin() {
      document.getElementById('loginForm').classList.remove('hidden');
      document.getElementById('registerForm').classList.add('hidden');
      document.getElementById('loginBtn').classList.add('bg-white', 'shadow');
      document.getElementById('registerBtn').classList.remove('bg-white', 'shadow');
      
      const url = new URL(window.location);
      url.searchParams.delete('register');
      window.history.pushState({}, '', url);
    }
    
    function showRegister() {
      document.getElementById('registerForm').classList.remove('hidden');
      document.getElementById('loginForm').classList.add('hidden');
      document.getElementById('registerBtn').classList.add('bg-white', 'shadow');
      document.getElementById('loginBtn').classList.remove('bg-white', 'shadow');
      
      const url = new URL(window.location);
      url.searchParams.set('register', '1');
      window.history.pushState({}, '', url);
    }
    
    function toggleFields() {
      const role = document.getElementById('registerRole').value;
      const userFields = document.getElementById('userFields');
      
      if (role === 'admin') {
        userFields.style.display = 'none';
        document.querySelector('input[name="nim"]').value = '';
        document.querySelector('input[name="jurusan"]').value = '';
      } else {
        userFields.style.display = 'block';
      }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      toggleFields();
    });
  </script>
</body>
</html>
