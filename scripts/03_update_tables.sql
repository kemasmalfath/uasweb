-- Tambah kolom untuk tracking read status
ALTER TABLE pengaduan ADD COLUMN last_read_user TIMESTAMP NULL;
ALTER TABLE pengaduan ADD COLUMN last_read_admin TIMESTAMP NULL;

-- Update existing data
UPDATE pengaduan SET last_read_user = tanggal_pengaduan WHERE last_read_user IS NULL;
UPDATE pengaduan SET last_read_admin = tanggal_pengaduan WHERE last_read_admin IS NULL;
