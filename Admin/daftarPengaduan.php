<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pengaduan | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
     <aside class="fixed left-0 top-0 w-60 h-screen bg-blue-600 border-r border-blue-800 flex flex-col py-6 px-4 z-10 shadow-sm">
  <div class="flex flex-col items-center mb-10">
    <img src="logo.png" class="w-12 h-12 mb-2" alt="Logo">
    <span class="text-lg font-bold tracking-wide text-white">SuaraKita</span>
  </div>
  <nav class="flex flex-col gap-2 font-medium text-white">
    <a href="dashboardAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
      <!--squares2x2  -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0
   0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25
    2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 
    2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1
     13.5 18v-2.25Z" />
</svg>
      Dashboard
    </a>
    <a href="daftarPengaduan.php" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-800 text-white font-bold">
      <!--document text  -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125
   0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0
    .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
</svg>
      Daftar Pengaduan
    </a>
    <a href="profilAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
      <!--user  -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 
  0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
</svg>
      Profil Admin
    </a>
  </nav>
  <div class="flex-grow"></div>
  <a href="loginAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 text-white transition">
  <!-- arrow-left-start-on-rectangle -->  
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25
   0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
</svg>
    Logout
  </a>
</aside>

  <main class="ml-60 p-8">
    <div class="flex items-center gap-4 mb-8">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="blue" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="3"
      width="16" height="18" rx="2"/><line x1="8" y1="9" x2="16" y2="9"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="12" y2="17"/></svg>
       <h2 class="text-2xl font-bold text-gray-800">Daftar Pengaduan</h2>
    </div>
   

    <div class="flex flex-wrap gap-4 items-center mb-8">
      <select class="p-2 rounded-lg border border-gray-300 bg-white shadow focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Kategori</option>
        <option>Fasilitas Umum</option>
        <option>Pelayanan Akademik</option>
        <option>Layanan IT / Website</option>
        <option>Kebersihan</option>
        <option>Keamanan</option>
        <option>Lain-lain</option>
      </select>
      <select class="p-2 rounded-lg border border-gray-300 bg-white shadow focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Status</option>
        <option>Belum Ditanggapi</option>
        <option>Sudah Ditanggapi</option>
        <option>Selesai</option>
      </select>
    </div>
    <div class="bg-white rounded-xl shadow p-8 border">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 text-gray-700 font-semibold">
            <th class="py-2 px-3 text-left">No</th>
            <th class="py-2 px-3 text-left">Tanggal</th>
            <th class="py-2 px-3 text-left">Username</th>
            <th class="py-2 px-3 text-left">Kategori</th>
            <th class="py-2 px-3 text-left">Judul</th>
            <th class="py-2 px-3 text-left">Status</th>
            <th class="py-2 px-3 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-t hover:bg-blue-50 transition">
            <td class="py-2 px-3">1</td>
            <td class="py-2 px-3">2025-06-03</td>
            <td class="py-2 px-3">dian_pratama</td>
            <td class="py-2 px-3">Fasilitas Umum</td>
            <td class="py-2 px-3">AC Rusak di Kelas 101</td>
            <td class="py-2 px-3">
               <select class="status-select rounded-full text-xs px-2 py-1 bg-blue-400 text-blue-800">
                <option value="Belum Ditanggapi">Belum Ditanggapi</option>
                <option value="Sudah Ditanggapi"selected>Sudah Ditanggapi</option>
                <option value="Selesai">Selesai</option>
              </select></td>
            <td class="py-2 px-3"><a href="tanggapi.php" class="text-blue-600 font-semibold hover:underline">Tanggapi</a></td>
          </tr>
     
        </tbody>
      </table>
    </div>
  </main>
<script>
    // Fungsi ubah warna dropdown sesuai status
    document.querySelectorAll('.status-select').forEach(function(select) {
      select.addEventListener('change', function() {
        select.classList.remove('bg-yellow-400', 'text-yellow-800', 'bg-blue-400', 'text-blue-800', 'bg-green-400', 'text-green-800');
        if (select.value === "Belum Ditanggapi") {
          select.classList.add('bg-yellow-400', 'text-yellow-800');
        } else if (select.value === "Sudah Ditanggapi") {
          select.classList.add('bg-blue-400', 'text-white-800');
        } else if (select.value === "Selesai") {
          select.classList.add('bg-green-400', 'text-green-800');
        }
      });
    });
  </script>

</body>
</html>



