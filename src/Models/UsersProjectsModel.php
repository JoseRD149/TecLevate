<?php

namespace TecLevate\Models;

use TecLevate\Utils\Database;
use PDO;
use DateTime;
use Exception;
class UsersProjectsModel {
    public $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM users_projects");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users_projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (!$this->existsInTable('users', $data['user_id'])) {
            throw new Exception("User with ID " . $data['user_id'] . " does not exist.");
        }
    
        if (!$this->existsInTable('projects', $data['project_id'])) {
            throw new Exception("Project with ID " . $data['project_id'] . " does not exist.");
        }
    
        $enrollment = new DateTime($data['enrollment_date']);
        $expiration = isset($data['expiration_date']) ? new DateTime($data['expiration_date']) : null;
    
        if ($expiration && $expiration < $enrollment) {
            throw new Exception("Expiration date cannot be earlier than enrollment date.");
        }
    
        $stmt = $this->db->prepare("
            INSERT INTO users_projects (user_id, project_id, start_date, end_date, finished) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['user_id'],
            $data['project_id'],
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['finished'] ?? false
        ]);
        return $this->getById($this->db->lastInsertId());
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE users_projects 
            SET user_id = ?, project_id = ?, start_date = ?, end_date = ?, finished = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $data['user_id'],
            $data['project_id'],
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['finished'] ?? false,
            $id
        ]);
        return $this->getById($id);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users_projects WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    private function existsInTable($table, $id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
