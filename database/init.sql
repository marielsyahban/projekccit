CREATE DATABASE IF NOT EXISTS db_kukusan_sport_center;
USE db_kukusan_sport_center;

CREATE TABLE IF NOT EXISTS pengguna (
    id_pengguna INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS lapangan (
    id_lapangan INT AUTO_INCREMENT PRIMARY KEY,
    nama_lapangan VARCHAR(100) NOT NULL,
    harga_per_jam DECIMAL(12, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS reservasi (
    id_reservasi INT AUTO_INCREMENT PRIMARY KEY,
    id_pengguna INT NOT NULL,
    id_lapangan INT NOT NULL,
    tanggal_reservasi DATE NOT NULL,
    status VARCHAR(50) DEFAULT 'Menunggu Pembayaran',
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna) ON DELETE CASCADE,
    FOREIGN KEY (id_lapangan) REFERENCES lapangan(id_lapangan) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS detail_reservasi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_reservasi INT NOT NULL,
    durasi_jam INT NOT NULL,
    subtotal_harga DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (id_reservasi) REFERENCES reservasi(id_reservasi) ON DELETE CASCADE
);

INSERT INTO pengguna (nama_lengkap, username, password, no_hp, email) VALUES
('Muhammad Ariel Syahban', 'ariel', '12345', '081234567890', 'ariel@kukusansport.com');

INSERT INTO lapangan (nama_lapangan, harga_per_jam) VALUES
('Lapangan Badminton A', 50000.00),
('Lapangan Badminton B', 50000.00),
('Lapangan Futsal Indoor', 150000.00),
('Lapangan Futsal Outdoor', 120000.00),
('Lapangan Mini Soccer 1', 350000.00),
('Lapangan Mini Soccer 2', 350000.00),
('Lapangan Tenis 1', 100000.00),
('Lapangan Tenis 2', 100000.00),
('Lapangan Basket Indoor', 200000.00),
('Lapangan Voli', 80000.00);
