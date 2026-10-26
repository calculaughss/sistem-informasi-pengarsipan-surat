<?php
class LogAktivitas {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function catat(int|null $userId, string $aksi, string $keterangan = ''): void {
        $stmt = $this->db->prepare(
            "INSERT INTO log_aktivitas (user_id, aksi, keterangan, ip_address) VALUES (?,?,?,?)"
        );
        $stmt->execute([
            $userId,
            $aksi,
            $keterangan,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }

    public function getAll(int $offset = 0, int $limit = 20): array {
        $stmt = $this->db->prepare(
            "SELECT la.*, u.nama AS nama_user
             FROM log_aktivitas la
             LEFT JOIN users u ON u.id = la.user_id
             ORDER BY la.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM log_aktivitas")->fetchColumn();
    }
}