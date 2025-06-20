-- Membuat database
CREATE DATABASE IF NOT EXISTS kritik_saran_fmipa;
USE kritik_saran_fmipa;

-- Tabel untuk menyimpan data admin
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    foto_profil VARCHAR(255) DEFAULT 'default-profile.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan data user/mahasiswa
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    nim VARCHAR(20) UNIQUE,
    jurusan VARCHAR(100),
    email VARCHAR(100) NOT NULL,
    foto_profil VARCHAR(255) DEFAULT 'default-profile.png',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk kategori pengaduan
CREATE TABLE kategori (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan pengaduan
CREATE TABLE pengaduan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    kategori_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    isi_pengaduan TEXT NOT NULL,
    lampiran VARCHAR(255),
    status ENUM('Belum Ditanggapi', 'Sudah Ditanggapi', 'Selesai') DEFAULT 'Belum Ditanggapi',
    tanggal_pengaduan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE RESTRICT
);

-- Tabel untuk tanggapan admin
CREATE TABLE tanggapan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pengaduan_id INT NOT NULL,
    admin_id INT NOT NULL,
    isi_tanggapan TEXT NOT NULL,
    tanggal_tanggapan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengaduan_id) REFERENCES pengaduan(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE CASCADE
);

-- Tabel untuk chat/pesan
CREATE TABLE chat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pengaduan_id INT NOT NULL,
    pengirim_type ENUM('user', 'admin') NOT NULL,
    pengirim_id INT NOT NULL,
    pesan TEXT NOT NULL,
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengaduan_id) REFERENCES pengaduan(id) ON DELETE CASCADE
);
