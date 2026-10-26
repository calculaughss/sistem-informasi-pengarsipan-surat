<?php
if (defined('_SURAT_MASUK_MODEL_LOADED_')) return;
define('_SURAT_MASUK_MODEL_LOADED_', true);
class SuratMasuk {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(array $filter = [], int $offset = 0, int $limit = ROWS_PER_PAGE): array {
        [$where, $params] = $this->buildWhere($filter);
        $stmt = $this->db->prepare(
            "SELECT sm.*, k.nama_klasifikasi, u.nama AS nama_user
             FROM surat_masuk sm
             LEFT JOIN klasifikasi k ON k.id = sm.klasifikasi_id
             LEFT JOIN users u ON u.id = sm.user_id
             $where
             ORDER BY sm.tanggal_terima DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->execute([...$params, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count(array $filter = []): int {
        [$where, $params] = $this->buildWhere($filter);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM surat_masuk sm $where");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT sm.*, k.nama_klasifikasi, u.nama AS nama_user
             FROM surat_masuk sm
             LEFT JOIN klasifikasi k ON k.id = sm.klasifikasi_id
             LEFT JOIN users u ON u.id = sm.user_id
             WHERE sm.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int {
        // auto-generate nomor agenda
        $lastId = (int)$this->db->query("SELECT MAX(id) FROM surat_masuk")->fetchColumn();
        $data['nomor_agenda'] = nomorAgenda($lastId + 1);

        $stmt = $this->db->prepare(
            "INSERT INTO surat_masuk
             (nomor_agenda, nomor_surat, tanggal_surat, tanggal_terima, asal_surat,
              tujuan, perihal, isi_singkat, klasifikasi_id, file_surat, status, user_id)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $data['nomor_agenda'],
            $data['nomor_surat'],
            $data['tanggal_surat'],
            $data['tanggal_terima'],
            $data['asal_surat'],
            $data['tujuan'],
            $data['perihal'],
            $data['isi_singkat']    ?? null,
            $data['klasifikasi_id'] ?? null,
            $data['file_surat']     ?? null,
            $data['status']         ?? 'baru',
            $data['user_id'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE surat_masuk SET
             nomor_surat=?, tanggal_surat=?, tanggal_terima=?, asal_surat=?,
             tujuan=?, perihal=?, isi_singkat=?, klasifikasi_id=?,
             file_surat=COALESCE(?,file_surat), status=?
             WHERE id=?"
        );
        return $stmt->execute([
            $data['nomor_surat'],
            $data['tanggal_surat'],
            $data['tanggal_terima'],
            $data['asal_surat'],
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
        $stmt = $this->db->prepare("DELETE FROM surat_masuk WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countByStatus(): array {
        $stmt = $this->db->query(
            "SELECT status, COUNT(*) AS total FROM surat_masuk GROUP BY status"
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
            $conds[]  = "(sm.nomor_surat LIKE ? OR sm.perihal LIKE ? OR sm.asal_surat LIKE ?)";
            $s = '%' . $filter['search'] . '%';
            array_push($params, $s, $s, $s);
        }
        if (!empty($filter['status'])) {
            $conds[]  = "sm.status = ?";
            $params[] = $filter['status'];
        }
        if (!empty($filter['klasifikasi_id'])) {
            $conds[]  = "sm.klasifikasi_id = ?";
            $params[] = $filter['klasifikasi_id'];
        }
        if (!empty($filter['dari'])) {
            $conds[]  = "sm.tanggal_terima >= ?";
            $params[] = $filter['dari'];
        }
        if (!empty($filter['sampai'])) {
            $conds[]  = "sm.tanggal_terima <= ?";
            $params[] = $filter['sampai'];
        }
        $where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';
        return [$where, $params];
    }
}
