<?php

namespace TecLevate\Models;

use TecLevate\Utils\Database;
use PDO;

class UsersProjectsModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM user_projects");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM user_projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO user_projects (user_id, project_id, start_date, end_date, finished) 
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
            UPDATE user_projects 
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
        $stmt = $this->db->prepare("DELETE FROM user_projects WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
