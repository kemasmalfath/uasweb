-- Insert data admin default
INSERT INTO admin (username, password, nama, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin FMIPA', 'admin@fmipa.ac.id');

-- Insert kategori pengaduan
INSERT INTO kategori (nama_kategori, deskripsi) VALUES 
('Fasilitas Umum', 'Pengaduan terkait fasilitas umum kampus'),
('Pelayanan Akademik', 'Pengaduan terkait pelayanan akademik'),
('Layanan IT / Website', 'Pengaduan terkait layanan IT dan website'),
('Kebersihan', 'Pengaduan terkait kebersihan kampus'),
('Keamanan', 'Pengaduan terkait keamanan kampus'),
('Lain-lain', 'Pengaduan lainnya');

-- Insert sample user
INSERT INTO users (username, password, nama, nim, jurusan, email) VALUES 
('dian_pratama', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dian Pratama', '2011021001', 'Ilmu Komputer', 'dian@student.unila.ac.id'),
('sari_dewi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sari Dewi', '2011021002', 'Matematika', 'sari@student.unila.ac.id');

-- Insert sample pengaduan
INSERT INTO pengaduan (user_id, kategori_id, judul, isi_pengaduan, status) VALUES 
(1, 1, 'AC Rusak di Kelas 101', 'AC di kelas 101 tidak menyala sejak kemarin. Mohon segera diperbaiki karena cuaca sangat panas.', 'Sudah Ditanggapi'),
(2, 4, 'Sampah Menumpuk di Kantin', 'Sampah di area kantin menumpuk dan menimbulkan bau tidak sedap.', 'Belum Ditanggapi');

-- Insert sample tanggapan
INSERT INTO tanggapan (pengaduan_id, admin_id, isi_tanggapan) VALUES 
(1, 1, 'Terima kasih atas laporannya, akan segera kami tindaklanjuti.');

-- Insert sample chat
INSERT INTO chat (pengaduan_id, pengirim_type, pengirim_id, pesan) VALUES 
(1, 'admin', 1, 'Terima kasih atas laporannya, akan segera kami tindaklanjuti.'),
(1, 'user', 1, 'Terima kasih min.'),
(1, 'admin', 1, 'Teknisi sudah dijadwalkan, mohon tunggu.');
