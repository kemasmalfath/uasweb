<?php
// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'kritik_saran_fmipa');

// Fungsi koneksi database
function getConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Koneksi database gagal: " . $e->getMessage());
    }
}

// Fungsi untuk memulai session
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Fungsi untuk redirect jika belum login sebagai user
function requireLogin() {
    startSession();
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
        header('Location: ../index.php');
        exit;
    }
}

// Fungsi untuk redirect jika belum login sebagai admin
function requireAdmin() {
    startSession();
    if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
        header('Location: ../index.php');
        exit;
    }
}

// Fungsi untuk logout user saja
function logoutUser() {
    startSession();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_role']);
    unset($_SESSION['username']);
    unset($_SESSION['nama']);
    unset($_SESSION['email']);
    unset($_SESSION['nim']);
    unset($_SESSION['jurusan']);
}

// Fungsi untuk logout admin saja
function logoutAdmin() {
    startSession();
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_role']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_nama']);
    unset($_SESSION['admin_email']);
}

// Fungsi untuk logout semua
function logoutAll() {
    startSession();
    $_SESSION = array();
    session_destroy();
}

// Fungsi untuk sanitasi input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Fungsi untuk validasi email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fungsi untuk hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Fungsi untuk verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>
