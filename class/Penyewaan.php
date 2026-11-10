<?php
class Penyewaan {
    private $conn;
    private $table = "riwayat_penyewaan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT r.id_sewa, p.nama_penyewa, l.nama_lapangan, r.tgl_sewa, r.jam_mulai, r.durasi_jam, r.total_harga, r.id_penyewa, r.id_lapangan 
                    FROM " . $this->table . " r
                    JOIN penyewa p ON r.id_penyewa = p.id_penyewa
                    JOIN lapangan l ON r.id_lapangan = l.id_lapangan
                    ORDER BY r.id_sewa ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_sewa = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($id_penyewa, $id_lapangan, $tgl_sewa, $jam_mulai, $durasi_jam) {
        // Hitung total harga otomatis berbasis harga_per_jam
        $stmt_harga = $this->conn->prepare("SELECT harga_per_jam FROM lapangan WHERE id_lapangan = ?");
        $stmt_harga->execute([$id_lapangan]);
        $lapangan = $stmt_harga->fetch(PDO::FETCH_ASSOC);
        
        $total_harga = $lapangan['harga_per_jam'] * $durasi_jam;

        $query = "INSERT INTO " . $this->table . " (id_penyewa, id_lapangan, tgl_sewa, jam_mulai, durasi_jam, total_harga) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_penyewa, $id_lapangan, $tgl_sewa, $jam_mulai, $durasi_jam, $total_harga]);
    }

    public function update($id_sewa, $id_penyewa, $id_lapangan, $tgl_sewa, $jam_mulai, $durasi_jam) {
        $stmt_harga = $this->conn->prepare("SELECT harga_per_jam FROM lapangan WHERE id_lapangan = ?");
        $stmt_harga->execute([$id_lapangan]);
        $lapangan = $stmt_harga->fetch(PDO::FETCH_ASSOC);
        
        $total_harga = $lapangan['harga_per_jam'] * $durasi_jam;

        $query = "UPDATE " . $this->table . " SET id_penyewa = ?, id_lapangan = ?, tgl_sewa = ?, jam_mulai = ?, durasi_jam = ?, total_harga = ? WHERE id_sewa = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id_penyewa, $id_lapangan, $tgl_sewa, $jam_mulai, $durasi_jam, $total_harga, $id_sewa]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_sewa = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>