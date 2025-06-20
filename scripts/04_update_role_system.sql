-- Update tabel admin untuk menambah role
ALTER TABLE admin ADD COLUMN role ENUM('admin', 'super_admin') DEFAULT 'admin';
UPDATE admin SET role = 'super_admin' WHERE id = 1;

-- Update tabel users untuk menambah role  
ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user';

-- Buat tabel unified untuk login (opsional, bisa tetap pakai tabel terpisah)
-- Tapi kita akan tetap pakai tabel admin dan users yang sudah ada
