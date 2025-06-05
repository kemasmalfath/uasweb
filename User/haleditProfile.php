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
      <a href="dashboard.html" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg text-md">
        ← Kembali ke Dashboard
      </a>
    </div>

    <h2 class="text-4xl font-bold mb-8 text-blue-700">Profil</h2>
    <div class="flex flex-col md:flex-row items-center md:items-start space-y-8 md:space-y-0 md:space-x-12">
      <div class="text-center">
        <div class="w-36 h-36 rounded-full border-4 border-blue-300 flex items-center justify-center text-base text-blue-500">
          Gambar Avatar
        </div>
        <button class="mt-4 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg text-lg">Ganti Foto</button>
      </div>
      <div class="w-full">
        <form class="space-y-6">
          <div>
            <label class="block text-lg mb-1" for="nama">Nama Lengkap</label>
            <input id="nama" type="text" placeholder="Nama Lengkap" class="w-full border border-blue-300 px-4 py-3 rounded text-lg" />
          </div>
          <div>
            <label class="block text-lg mb-1" for="nim">NIM</label>
            <input id="nim" type="text" placeholder="Nomor Induk Mahasiswa" class="w-full border border-blue-300 px-4 py-3 rounded text-lg" />
          </div>
          <div>
            <label class="block text-lg mb-1" for="jurusan">Jurusan</label>
            <input id="jurusan" type="text" placeholder="Jurusan" class="w-full border border-blue-300 px-4 py-3 rounded text-lg" />
          </div>
          <div>
            <label class="block text-lg mb-1" for="email">E-mail</label>
            <input id="email" type="email" placeholder="E-mail" class="w-full border border-blue-300 px-4 py-3 rounded text-lg" />
          </div>
          <div class="text-right">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</body>
</html>
