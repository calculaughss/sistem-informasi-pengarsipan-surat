<?php
class Klasifikasi {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM klasifikasi ORDER BY kode")->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM klasifikasi WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO klasifikasi (kode, nama_klasifikasi, keterangan) VALUES (?,?,?)"
        );
        $stmt->execute([$data['kode'], $data['nama_klasifikasi'], $data['keterangan'] ?? null]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE klasifikasi SET kode=?, nama_klasifikasi=?, keterangan=? WHERE id=?"
        );
        return $stmt->execute([$data['kode'], $data['nama_klasifikasi'], $data['keterangan'] ?? null, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM klasifikasi WHERE id = ?");
        return $stmt->execute([$id]);
    }
}