<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Proses Registrasi | Kritik & Saran FMIPA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>

  <?php if ($isSuccess): ?>
    <script>
      setTimeout(() => {
        window.location.href = "loginUser.php";
      }, 3000);
    </script>
  <?php endif; ?>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-lg rounded-xl max-w-md w-full p-8 text-center">
    <h1 class="text-2xl font-semibold mb-4 <?= $isSuccess ? 'text-blue-600' : 'text-red-500' ?>">
      <?= $isSuccess ? "Berhasil!" : "Gagal!" ?>
    </h1>
    <p class="text-gray-700 text-base mb-6">
      <?= htmlspecialchars($message) ?>
    </p>

    <?php if (!$isSuccess): ?>
      <a href="daftar.php" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition">
        Kembali ke Daftar
      </a>
    <?php else: ?>
      <a href="loginUser.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
        Login Sekarang
      </a>
    <?php endif; ?>
  </div>

</body>
</html>