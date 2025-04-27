<?php
namespace TecLevate\Models;
use TecLevate\Utils\Database;
use PDO;

class CoursesModel {
    public $db;

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
    public function getByUserId(int $userId): array
    {
        $sql = "
          SELECT c.id, c.title, c.description, c.is_paid, c.price, c.url, c.start_date, c.end_date, c.expiration_date, c.company_id
          FROM courses c
          JOIN users_courses uc ON c.id = uc.course_id
          WHERE uc.user_id = :userId
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
