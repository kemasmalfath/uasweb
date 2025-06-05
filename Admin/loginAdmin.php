<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
    <div class="flex flex-col items-center mb-7">
      <img src="logo.png" class="w-16 h-16 mb-3" alt="FMIPA Logo">
      <span class="text-2xl font-bold text-gray-700 mb-2 tracking-wide">SuaraKita</span>
      <span class="text-gray-500">Admin Login</span>
    </div>
   <form action="dashboardAdmin.php" method="POST" class="space-y-6">
  <div>
    <label class="block text-gray-700 font-semibold mb-1">Username</label>
    <input name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" type="text" placeholder="Masukkan username" required />
  </div>
  <div>
    <label class="block text-gray-700 font-semibold mb-1">Password</label>
    <input name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" type="password" placeholder="Masukkan password" required />
  </div>
  <button type="submit" class="w-full py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 transition">Login</button>
</form>

  </div>
</body>
</html>
