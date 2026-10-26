<?php
if (defined('_DISPOSISI_MODEL_LOADED_')) return;
define('_DISPOSISI_MODEL_LOADED_', true);
class Disposisi {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getBySuratMasuk(int $suratMasukId): array {
        $stmt = $this->db->prepare(
            "SELECT d.*, u1.nama AS dari_nama, u2.nama AS kepada_nama
             FROM disposisi d
             JOIN users u1 ON u1.id = d.dari_user_id
             JOIN users u2 ON u2.id = d.kepada_user_id
             WHERE d.surat_masuk_id = ?
             ORDER BY d.created_at ASC"
        );
        $stmt->execute([$suratMasukId]);
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId, int $offset = 0, int $limit = ROWS_PER_PAGE): array {
        $stmt = $this->db->prepare(
            "SELECT d.*, sm.perihal, sm.nomor_surat, sm.asal_surat,
                    u1.nama AS dari_nama
             FROM disposisi d
             JOIN surat_masuk sm ON sm.id = d.surat_masuk_id
             JOIN users u1 ON u1.id = d.dari_user_id
             WHERE d.kepada_user_id = ?
             ORDER BY d.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([$userId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function countByUser(int $userId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM disposisi WHERE kepada_user_id = ?");
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    public function countUnread(int $userId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM disposisi WHERE kepada_user_id = ? AND status = 'belum_dibaca'"
        );
        $stmt->execute([$userId]);
        return (int)$stmt->fetchColumn();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT d.*, u1.nama AS dari_nama, u2.nama AS kepada_nama,
                    sm.perihal, sm.nomor_surat
             FROM disposisi d
             JOIN users u1 ON u1.id = d.dari_user_id
             JOIN users u2 ON u2.id = d.kepada_user_id
             JOIN surat_masuk sm ON sm.id = d.surat_masuk_id
             WHERE d.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO disposisi (surat_masuk_id, dari_user_id, kepada_user_id, catatan, tanggal)
             VALUES (?,?,?,?,?)"
        );
        $stmt->execute([
            $data['surat_masuk_id'],
            $data['dari_user_id'],
            $data['kepada_user_id'],
            $data['catatan'] ?? null,
            $data['tanggal'] ?? date('Y-m-d'),
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE disposisi SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM disposisi WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
