<?php

namespace TecLevate\Models;

use TecLevate\Utils\Database;
use PDO;
use DateTime;
use Exception;

class UsersCoursesModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users_courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users_courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        if (!$this->existsInTable('users', $data['user_id'])) {
            throw new Exception("User with ID " . $data['user_id'] . " does not exist.");
        }

        if (!$this->existsInTable('courses', $data['course_id'])) {
            throw new Exception("Course with ID " . $data['course_id'] . " does not exist.");
        }

        $enrollment = new DateTime($data['enrollment_date']);
        $expiration = isset($data['expiration_date']) ? new DateTime($data['expiration_date']) : null;

        if ($expiration && $expiration < $enrollment) {
            throw new Exception("Expiration date cannot be earlier than enrollment date.");
        }

        $stmt = $this->db->prepare("
            INSERT INTO users_courses (user_id, course_id, enrollment_date, expiration_date, completed) 
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


    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE users_courses 
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

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users_courses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function existsInTable($table, $id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
    public function createCourse($title, $description, $userId)
    {
        $stmt = $this->db->prepare('INSERT INTO users_courses (title, description, user_id) VALUES (?, ?, ?)');
        $stmt->execute([$title, $description, $userId]);
    }
}
