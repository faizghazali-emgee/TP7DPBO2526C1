CREATE DATABASE IF NOT EXISTS db_peminjaman;
USE db_peminjaman;

CREATE TABLE IF NOT EXISTS penyewa (
    id_penyewa INT AUTO_INCREMENT PRIMARY KEY,
    nama_penyewa VARCHAR(100) NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS lapangan (
    id_lapangan INT AUTO_INCREMENT PRIMARY KEY,
    nama_lapangan VARCHAR(100) NOT NULL,
    jenis_olahraga VARCHAR(50) NOT NULL,
    harga_per_jam DECIMAL(10, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS riwayat_penyewaan (
    id_sewa INT AUTO_INCREMENT PRIMARY KEY,
    id_penyewa INT NOT NULL,
    id_lapangan INT NOT NULL,
    tgl_sewa DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    durasi_jam INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_penyewa) REFERENCES penyewa(id_penyewa) ON DELETE CASCADE,
    FOREIGN KEY (id_lapangan) REFERENCES lapangan(id_lapangan) ON DELETE CASCADE
);