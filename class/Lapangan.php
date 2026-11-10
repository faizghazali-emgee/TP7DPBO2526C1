<?php
class Lapangan {
    private $conn;
    private $table = "lapangan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_lapangan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_lapangan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nama, $jenis, $harga) {
        $query = "INSERT INTO " . $this->table . " (nama_lapangan, jenis_olahraga, harga_per_jam) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama, $jenis, $harga]);
    }

    public function update($id, $nama, $jenis, $harga) {
        $query = "UPDATE " . $this->table . " SET nama_lapangan = ?, jenis_olahraga = ?, harga_per_jam = ? WHERE id_lapangan = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama, $jenis, $harga, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_lapangan = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>