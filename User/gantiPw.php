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
    <h2 class="text-3xl font-bold text-center text-blue-700 mb-8">Ganti Sandi</h2>
    <form class="space-y-6">
      <div>
        <label for="old-password" class="block text-lg mb-1">Sandi Lama</label>
        <input type="password" id="old-password" placeholder="Masukkan sandi lama"
               class="w-full px-4 py-3 border border-blue-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label for="new-password" class="block text-lg mb-1">Sandi Baru</label>
        <input type="password" id="new-password" placeholder="Masukkan sandi baru"
               class="w-full px-4 py-3 border border-blue-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label for="confirm-password" class="block text-lg mb-1">Konfirmasi Sandi Baru</label>
        <input type="password" id="confirm-password" placeholder="Ulangi sandi baru"
               class="w-full px-4 py-3 border border-blue-300 rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>
      <div class="flex justify-between mt-6">
        <a href="javascript:history.back()" 
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
</body>
</html>
