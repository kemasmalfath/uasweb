<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tanggapi Pengaduan | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-8">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-8 border">
    <a href="daftarPengaduan.php" class="flex items-center gap-1 text-blue-600 mb-4 hover:underline">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline" fill="none" viewBox="0 0 24 24" 
      stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
       d="M15 19l-7-7 7-7" /></svg>
      Kembali
    </a>
    
    <div class="flex gap-4 mb-4 items-center">
      <div>
        <div class="bg-gray-200 w-14 h-14 rounded-full flex items-center justify-center font-bold
         text-xl text-blue-700">DP</div>
      </div>
      <div>
        <p class="text-lg font-semibold text-gray-700">Dian Pratama</p>
        <span class="text-gray-500 text-sm">dian_pratama</span>
      </div>
    </div>
   
    <div class="bg-blue-50 rounded-lg p-4 mb-4 border">
      <h3 class="font-bold text-blue-800 mb-1">AC Rusak di Kelas 101</h3>
      <p class="mb-1 text-gray-700">AC di kelas 101 tidak menyala sejak kemarin. Mohon segera diperbaiki.</p>
      <p class="text-sm text-gray-500 mb-1">Kategori: Fasilitas Umum | Tanggal: 2025-06-03</p>

      <div class="flex gap-2 mt-2">
        <a href="#" class="text-blue-700 underline hover:text-blue-900">ac_kelas101.jpg</a>
        <a href="#" class="text-blue-700 underline hover:text-blue-900">foto_kerusakan2.png</a>
      </div>
      <p class="text-xs text-gray-400 mt-2">Status: <span class="font-semibold text-yellow-600">Sudah Ditanggapi</span></p>
    </div>

     <div class="mb-2">
      <div class="text-sm font-semibold text-gray-600 mb-2">Tanggapan Admin</div>
      <div class="space-y-3 max-h-40 overflow-y-auto pr-2 mb-3">
     <div class="flex justify-end">
          <div class="bg-blue-50 px-4 py-2 rounded-lg shadow max-w-xs ">
            <span class="block font-semibold text-blue-700">Admin</span>
            <span class="block">Terima kasih atas laporannya, akan segera kami tindaklanjuti.</span>
            <span class="block text-xs text-gray-500 mt-1">2025-06-03 13:02</span>
          </div>
        </div>
        <div class="flex justify-start">
          <div class="bg-gray-100 px-4 py-2 rounded-lg shadow max-w-xs">
            <span class="block font-semibold text-blue-700">Dian Pratama</span>
            <span class="block">Terima kasih min.</span>
            <span class="block text-xs text-gray-500 mt-1 ">2025-06-03 13:25</span>
          </div>
        </div>
        <div class="flex justify-end">
          <div class="bg-blue-50 px-4 py-2 rounded-lg shadow max-w-xs ">
            <span class="block font-semibold text-blue-700">Admin</span>
            <span class="block">Teknisi sudah dijadwalkan, mohon tunggu.</span>
            <span class="block text-xs text-gray-500 mt-1 ">2025-06-03 14:02</span>
          </div>
        </div>
      </div>
      <form>
        <div class="flex gap-2">
          <input type="text" class="flex-1 border rounded-lg p-2 focus:ring-2 focus:ring-blue-400" 
          placeholder="Tulis tanggapan...">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold
           hover:bg-blue-700 transition">Kirim</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
