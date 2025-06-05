<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
    <div class="flex flex-col items-center mb-7 text-center">
  <img src="https://dashboard.sdgcenter.unila.ac.id/frontend/gambar/UNILA.png" class="w-24 h-24 mb-3" alt="FMIPA Logo" />
  <span class="text-2xl font-bold text-gray-700 mb-2 tracking-wide">
    Kritik & Saran Mahasiswa FMIPA
  </span>
    </div>
    <form id="loginForm" onsubmit="return loginDirect(event)" class="space-y-6">
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Username</label>
        <input id="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" type="text" placeholder="Masukkan username" required />
      </div>
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Password</label>
        <input id="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" type="password" placeholder="Masukkan password" required />
      </div>
      <button type="submit" class="w-full py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 transition">Login</button>
    </form>
    <div class="text-sm text-gray-600 mt-6 text-center">
      Belum punya akun? 
      <a href="register.php" class="text-blue-600 hover:underline font-semibold">Daftar di sini</a>
    </div>
  </div>

  <script>
    function loginDirect(event) {
      event.preventDefault();
      alert('Login berhasil! Mengarah ke dashboard.');
      window.location.href = 'dashboardUser.php';
      return false;
    }
  </script>
</body>
</html>