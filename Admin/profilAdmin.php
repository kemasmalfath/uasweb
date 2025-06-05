<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Admin | Kritik & Saran FMIPA</title>
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
     2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
</svg>
      Dashboard
    </a>
    <a href="daftarPengaduan.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
      <!--document text  -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375
   3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 
   1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
</svg>
      Daftar Pengaduan
    </a>
    <a href="profilAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-800 text-white font-bold">
      <!--user  -->
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 
  0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
</svg>
      Profil Admin
    </a>
  </nav>
  <div class="flex-grow"></div>
  <a href="loginAdmin.php" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 text-white transition">
  <!-- arrow-left-start-on-rectangle -->  
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0
   0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
</svg>
    Logout
  </a>
</aside>

  <main class="ml-60 p-8 flex justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 border w-full max-w-lg">
      <div id="biodataView">
        <div class="flex flex-col items-center mb-6">
          <img id="fotoProfilView" src="default-profile.png" class="w-24 h-24 rounded-full mb-2 object-cover" alt="Foto Profil">
          <h2 class="text-xl font-bold text-gray-700 mb-1">Admin FMIPA</h2>
          <span class="text-gray-400 text-sm">Super Admin</span>
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Nama</label>
          <div class="font-semibold text-gray-800">Admin FMIPA</div>
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Email</label>
          <div class="font-semibold text-gray-800">admin@fmipa.ac.id</div>
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Username</label>
          <div class="font-semibold text-gray-800">admin</div>
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Password</label>
          <div class="font-semibold text-gray-800">••••••••</div>
        </div>
        <button onclick="editProfil()" class="mt-6 w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
          Edit Profil
        </button>
      </div>

      <form id="biodataEdit" class="hidden" enctype="multipart/form-data">
        <div class="flex flex-col items-center mb-6">
          <img id="previewFoto" src="default-profile.png" class="w-24 h-24 rounded-full mb-2 object-cover" alt="Preview Foto">
          <div class="flex gap-2 mb-3">
            <label class="cursor-pointer bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
              Ganti Foto
              <input id="fileFoto" type="file" accept="image/*" class="hidden" onchange="previewImage(event)">
            </label>
            <button type="button" onclick="hapusFoto()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
              Hapus Foto
            </button>
          </div>
        </div>

        <div class="mb-3">
          <label class="text-gray-600 text-sm">Nama</label>
          <input type="text" name="nama" class="w-full border rounded-lg p-2 mt-1" value="Admin FMIPA">
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Email</label>
          <input type="email" name="email" class="w-full border rounded-lg p-2 mt-1" value="admin@fmipa.ac.id">
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Username</label>
          <input type="text" name="username" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" value="admin" disabled>
        </div>
        <div class="mb-3">
          <label class="text-gray-600 text-sm">Password</label>
          <input type="password" name="password" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" value="admin123" disabled>
        </div>
        <div class="flex gap-2 mt-6">
          <button type="button" onclick="batalEdit()" class="flex-1 bg-gray-200 text-gray-600 py-2 rounded-lg font-semibold hover:bg-gray-300">
            Batal
          </button>
          <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700">
            Simpan
          </button>
        </div>
      </form>

      <script>
        function editProfil() {
          document.getElementById('biodataView').style.display = 'none';
          document.getElementById('biodataEdit').style.display = 'block';
        }
        function batalEdit() {
          document.getElementById('biodataEdit').style.display = 'none';
          document.getElementById('biodataView').style.display = 'block';
          document.getElementById('previewFoto').src = document.getElementById('fotoProfilView').src;
          document.getElementById('fileFoto').value = '';
        }
        function previewImage(event) {
          const reader = new FileReader();
          reader.onload = function() {
            document.getElementById('previewFoto').src = reader.result;
          };
          reader.readAsDataURL(event.target.files[0]);
        }
        function hapusFoto() {
          document.getElementById('previewFoto').src = 'default-profile.png';
          document.getElementById('fileFoto').value = '';
        }
        document.addEventListener('DOMContentLoaded', function() {
          const initialSrc = document.getElementById('fotoProfilView').src;
          document.getElementById('previewFoto').src = initialSrc;
        });
      </script>
    </div>
  </main>
</body>
</html>
