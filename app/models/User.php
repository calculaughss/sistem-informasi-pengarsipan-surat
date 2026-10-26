<?php
if (defined('_USER_MODEL_LOADED_')) return;
define('_USER_MODEL_LOADED_', true);
class User {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT id, nama, email, role, foto, is_active, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll(int $offset = 0, int $limit = ROWS_PER_PAGE): array {
        $stmt = $this->db->prepare(
            "SELECT id, nama, email, role, is_active, created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?"
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function count(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['nama'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['role'] ?? 'anggota',
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [];
        foreach (['nama', 'email', 'role', 'is_active', 'foto'] as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "$f = ?";
                $params[] = $data[$f];
            }
        }
        if (!empty($data['password'])) {
            $fields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        if (empty($fields)) return false;
        $params[] = $id;
        $stmt = $this->db->prepare("UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($params);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }
}
