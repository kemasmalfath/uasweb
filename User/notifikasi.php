<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Notifikasi Pengaduan</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  /* Animasi muncul */
  @keyframes fadeInUp {
    0% {
      opacity: 0;
      transform: translateY(15px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
</head>
<body class="bg-blue-50 min-h-screen flex flex-col items-center p-6 font-sans text-blue-900">

  <header class="mb-8 w-full max-w-3xl">
    <h1 class="text-4xl font-bold text-center text-blue-700 mb-2">Notifikasi Pengaduan</h1>
    <p class="text-center text-blue-600">Cek status terbaru pengaduan kamu di sini.</p>
  </header>

  <main class="w-full max-w-3xl space-y-6">
    <!-- Contoh notifikasi -->
    <article class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4 animate-fadeInUp border-l-8 border-blue-500 hover:shadow-lg transition">
      <div class="flex-shrink-0 text-blue-500">
        <!-- Icon selesai -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div class="flex-1">
        <h2 class="text-xl font-semibold text-blue-800">Pengaduan Selesai</h2>
        <p class="text-blue-700 mt-1">Pengaduan tentang <span class="font-medium">Lampu jalan rusak</span> sudah selesai ditangani.</p>
        <p class="text-sm text-blue-500 mt-2">Tanggal: 2 Juni 2025</p>
      </div>
      <button
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition font-semibold"
        onclick="alert('Detail pengaduan: Lampu jalan rusak sudah selesai.')"
      >
        Lihat Detail
      </button>
    </article>

    <article class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4 animate-fadeInUp border-l-8 border-yellow-400 hover:shadow-lg transition">
      <div class="flex-shrink-0 text-yellow-400">
        <!-- Icon proses -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m4 4v-2a4 4 0 10-8 0v2m8 0h-8" />
        </svg>
      </div>
      <div class="flex-1">
        <h2 class="text-xl font-semibold text-yellow-600">Pengaduan Diproses</h2>
        <p class="text-yellow-700 mt-1">Pengaduan <span class="font-medium">Sampah menumpuk</span> sedang dalam proses penanganan.</p>
        <p class="text-sm text-yellow-500 mt-2">Tanggal: 4 Juni 2025</p>
      </div>
      <button
        class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500 transition font-semibold"
        onclick="alert('Detail pengaduan: Sampah menumpuk sedang diproses.')"
      >
        Lihat Detail
      </button>
    </article>

    <article class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4 animate-fadeInUp border-l-8 border-red-500 hover:shadow-lg transition">
      <div class="flex-shrink-0 text-red-500">
        <!-- Icon ditolak -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
      <div class="flex-1">
        <h2 class="text-xl font-semibold text-red-600">Pengaduan Ditolak</h2>
        <p class="text-red-700 mt-1">Pengaduan <span class="font-medium">Kebisingan malam hari</span> ditolak karena kurang informasi.</p>
        <p class="text-sm text-red-500 mt-2">Tanggal: 5 Juni 2025</p>
      </div>
      <button
        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition font-semibold"
        onclick="alert('Detail pengaduan: Kebisingan malam hari ditolak.')"
      >
        Lihat Detail
      </button>
    </article>
  </main>

</body>
</html>
