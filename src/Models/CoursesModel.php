<?php
namespace TecLevate\Models;
use TecLevate\Utils\Database;
use PDO;

class CoursesModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO courses (title, description, is_paid, price, url, start_date, end_date, expiration_date, company_id)
            VALUES (:title, :description, :is_paid, :price, :url, :start_date, :end_date, :expiration_date, :company_id)
        ");
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $data['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE courses SET 
                title = :title,
                description = :description,
                is_paid = :is_paid,
                price = :price,
                url = :url,
                start_date = :start_date,
                end_date = :end_date,
                expiration_date = :expiration_date,
                company_id = :company_id
            WHERE id = :id
        ");
        return $stmt->execute($data);
    }

    public function destroy($id) {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function assignToUser($userId, $courseId) {
        $stmt = $this->db->prepare("INSERT INTO users_courses (user_id, course_id) VALUES (?, ?)");
        $stmt->execute([$userId, $courseId]);
    }
}
