<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md text-center">
    <img src="https://dashboard.sdgcenter.unila.ac.id/frontend/gambar/UNILA.png" alt="Logo FMIPA" class="w-24 mx-auto mb-4" />
    <h2 class="text-2xl font-semibold mb-1">Daftar Akun</h2>
    <p class="text-gray-500 text-sm mb-6">Silakan buat akun untuk mengisi kritik & saran</p>
    
    <form id="registerForm" class="space-y-4 text-left" onsubmit="return registerDirect(event)">
      <input type="text" name="nama" placeholder="Nama Lengkap" required
             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      <input type="email" name="email" placeholder="Email Aktif" required
             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      <input type="password" name="password" placeholder="Password" required
             class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
        Daftar
      </button>
    </form>

    <div class="text-sm text-gray-600 mt-6">
      Sudah punya akun? 
      <a href="loginUser.php" class="text-blue-600 hover:underline font-semibold">Login di sini</a>
    </div>
  </div>

  <script>
    function registerDirect(event) {
      event.preventDefault();
      alert('Pendaftaran berhasil! Silakan login terlebih dahulu.');
      window.location.href = 'loginUser.php';
      return false;
    }
  </script>

</body>
</html>