<?php
class Penyewa {
    private $conn;
    private $table = "penyewa";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id_penyewa ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_penyewa = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nama, $no_hp, $email) {
        $query = "INSERT INTO " . $this->table . " (nama_penyewa, no_hp, email) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama, $no_hp, $email]);
    }

    public function update($id, $nama, $no_hp, $email) {
        $query = "UPDATE " . $this->table . " SET nama_penyewa = ?, no_hp = ?, email = ? WHERE id_penyewa = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama, $no_hp, $email, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_penyewa = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>