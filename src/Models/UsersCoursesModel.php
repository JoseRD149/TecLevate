<?php

namespace TecLevate\Models;

use TecLevate\Utils\Database;
use PDO;

class UsersCoursesModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM user_courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM user_courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO user_courses (user_id, course_id, enrollment_date, expiration_date, completed) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['user_id'],
            $data['course_id'],
            $data['enrollment_date'],
            $data['expiration_date'] ?? null,
            $data['completed'] ?? false
        ]);
        return $this->getById($this->db->lastInsertId());
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE user_courses 
            SET user_id = ?, course_id = ?, enrollment_date = ?, expiration_date = ?, completed = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $data['user_id'],
            $data['course_id'],
            $data['enrollment_date'],
            $data['expiration_date'] ?? null,
            $data['completed'] ?? false,
            $id
        ]);
        return $this->getById($id);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM user_courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
