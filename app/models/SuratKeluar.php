<?php
class SuratKeluar {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(array $filter = [], int $offset = 0, int $limit = ROWS_PER_PAGE): array {
        [$where, $params] = $this->buildWhere($filter);
        $stmt = $this->db->prepare(
            "SELECT sk.*, k.nama_klasifikasi, u.nama AS nama_user
             FROM surat_keluar sk
             LEFT JOIN klasifikasi k ON k.id = sk.klasifikasi_id
             LEFT JOIN users u ON u.id = sk.user_id
             $where
             ORDER BY sk.tanggal_surat DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count(array $filter = []): int {
        [$where, $params] = $this->buildWhere($filter);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM surat_keluar sk $where");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT sk.*, k.nama_klasifikasi, u.nama AS nama_user
             FROM surat_keluar sk
             LEFT JOIN klasifikasi k ON k.id = sk.klasifikasi_id
             LEFT JOIN users u ON u.id = sk.user_id
             WHERE sk.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO surat_keluar
             (nomor_surat, tanggal_surat, tujuan, perihal, isi_singkat,
              klasifikasi_id, file_surat, status, user_id)
             VALUES (?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $data['nomor_surat'],
            $data['tanggal_surat'],
            $data['tujuan'],
            $data['perihal'],
            $data['isi_singkat']    ?? null,
            $data['klasifikasi_id'] ?? null,
            $data['file_surat']     ?? null,
            $data['status']         ?? 'draft',
            $data['user_id'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE surat_keluar SET
             nomor_surat=?, tanggal_surat=?, tujuan=?, perihal=?, isi_singkat=?,
             klasifikasi_id=?, file_surat=COALESCE(?,file_surat), status=?
             WHERE id=?"
        );
        return $stmt->execute([
            $data['nomor_surat'],
            $data['tanggal_surat'],
            $data['tujuan'],
            $data['perihal'],
            $data['isi_singkat']    ?? null,
            $data['klasifikasi_id'] ?? null,
            $data['file_surat']     ?? null,
            $data['status'],
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM surat_keluar WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countByStatus(): array {
        $stmt = $this->db->query(
            "SELECT status, COUNT(*) AS total FROM surat_keluar GROUP BY status"
        );
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['status']] = $row['total'];
        }
        return $result;
    }

    private function buildWhere(array $filter): array {
        $conds  = [];
        $params = [];
        if (!empty($filter['search'])) {
            $conds[]  = "(sk.nomor_surat LIKE ? OR sk.perihal LIKE ? OR sk.tujuan LIKE ?)";
            $s = '%' . $filter['search'] . '%';
            array_push($params, $s, $s, $s);
        }
        if (!empty($filter['status'])) {
            $conds[]  = "sk.status = ?";
            $params[] = $filter['status'];
        }
        if (!empty($filter['klasifikasi_id'])) {
            $conds[]  = "sk.klasifikasi_id = ?";
            $params[] = $filter['klasifikasi_id'];
        }
        if (!empty($filter['dari'])) {
            $conds[]  = "sk.tanggal_surat >= ?";
            $params[] = $filter['dari'];
        }
        if (!empty($filter['sampai'])) {
            $conds[]  = "sk.tanggal_surat <= ?";
            $params[] = $filter['sampai'];
        }
        $where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';
        return [$where, $params];
    }
}