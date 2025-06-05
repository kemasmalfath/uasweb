<!DOCTYPE html>
<html lang="id" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Pengaduan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">

  <header class="w-full max-w-5xl mb-10">
    <h1 class="text-4xl font-extrabold text-gray-900 text-center">Daftar Pengaduan Anda</h1>
    <p class="mt-2 text-center text-gray-600">Lihat status dan detail pengaduan yang sudah Anda buat.</p>
  </header>

  <main class="w-full max-w-5xl bg-white shadow-md rounded-lg p-8">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold">No</th>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold">Judul</th>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold">Status</th>
            <th scope="col" class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr class="hover:bg-blue-50 transition-colors duration-200">
            <td class="px-6 py-4 whitespace-nowrap">1</td>
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Lampu jalan mati</td>
            <td class="px-6 py-4 whitespace-nowrap">2025-06-01</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-yellow-200 text-yellow-800">Diproses</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              <a href="#" class="text-blue-600 hover:underline font-semibold">Detail</a>
            </td>
          </tr>
          <tr class="hover:bg-blue-50 transition-colors duration-200">
            <td class="px-6 py-4 whitespace-nowrap">2</td>
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">Sampah menumpuk</td>
            <td class="px-6 py-4 whitespace-nowrap">2025-06-02</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-200 text-green-800">Selesai</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              <a href="#" class="text-blue-600 hover:underline font-semibold">Detail</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <footer class="mt-12">
    <a href="dashboardUser.php" class="text-blue-600 hover:underline font-medium">← Kembali ke Beranda</a>
  </footer>

</body>
</html>