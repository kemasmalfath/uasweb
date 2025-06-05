<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Pengaduan Anda</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-blue-900 font-sans min-h-screen py-10">

  <div class="max-w-4xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-2">Daftar Pengaduan Anda</h2>
    <p class="text-center text-gray-600 mb-8">Lihat status dan detail pengaduan yang sudah Anda buat.</p>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full table-auto">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="py-3 px-4 text-left">No</th>
            <th class="py-3 px-4 text-left">Judul</th>
            <th class="py-3 px-4 text-left">Tanggal</th>
            <th class="py-3 px-4 text-left">Status</th>
            <th class="py-3 px-4 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-blue-900">
          <tr class="border-t">
            <td class="py-3 px-4">1</td>
            <td class="py-3 px-4">Lampu jalan mati</td>
            <td class="py-3 px-4">2025-06-01</td>
            <td class="py-3 px-4">
              <span class="bg-yellow-200 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">Diproses</span>
            </td>
            <td class="py-3 px-4">
              <a href="#" class="text-blue-600 hover:underline">Detail</a>
            </td>
          </tr>
          <tr class="bg-gray-100 border-t">
            <td class="py-3 px-4">2</td>
            <td class="py-3 px-4">Sampah menumpuk</td>
            <td class="py-3 px-4">2025-06-02</td>
            <td class="py-3 px-4">
              <span class="bg-green-200 text-green-800 text-sm font-medium px-3 py-1 rounded-full">Selesai</span>
            </td>
            <td class="py-3 px-4">
              <a href="#" class="text-blue-600 hover:underline">Detail</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-8 text-center">
      <a href="dashboard.html" class="text-blue-600 hover:text-blue-800 hover:underline">← Kembali ke Beranda</a>
    </div>
  </div>

</body>
</html>
