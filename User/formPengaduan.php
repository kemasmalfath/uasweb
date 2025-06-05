<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Pengaduan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-blue-900 font-sans min-h-screen flex items-center justify-center">
  <section class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-xl">
    <div class="mb-6 text-center">
      <h2 class="text-3xl font-bold text-blue-700 flex items-center justify-center gap-2">
        📝 Form Pengaduan
      </h2>
      <p class="text-blue-500 mt-1 text-sm">Silakan isi form berikut dengan lengkap dan jelas</p>
    </div>
    <form class="space-y-5">
      <div>
        <label class="block text-sm font-semibold mb-1" for="nama">Nama Lengkap</label>
        <input type="text" id="nama" placeholder="Nama Lengkap" class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1" for="email">Email</label>
        <input type="email" id="email" placeholder="Email Aktif" class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1" for="judul">Judul Pengaduan</label>
        <input type="text" id="judul" placeholder="Judul Pengaduan" class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1" for="isi">Isi Pengaduan</label>
        <textarea id="isi" placeholder="Tulis pengaduan Anda di sini..." class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition h-32 resize-none"></textarea>
      </div>
      <div class="text-right">
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
          Kirim Pengaduan
        </button>
      </div>
    </form>
  </section>
</body>
</html>
