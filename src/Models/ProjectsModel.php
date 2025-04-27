<?php
namespace TecLevate\Models;
use TecLevate\Utils\Database;
use PDO;

class ProjectsModel {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM projects");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO projects (title, description, is_paid, payment_amount, url, start_date, end_date, expiration_date, company_id)
            VALUES (:title, :description, :is_paid, :payment_amount, :url, :start_date, :end_date, :expiration_date, :company_id)
        ");
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $data['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE projects SET 
                title = :title,
                description = :description,
                is_paid = :is_paid,
                payment_amount = :payment_amount,
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
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function assignToUser($userId, $projectId) {
        $stmt = $this->db->prepare("INSERT INTO users_projects (user_id, project_id) VALUES (?, ?)");
        $stmt->execute([$userId, $projectId]);
    }
    public function getByUserId(int $userId): array
    {
        $sql = "
           SELECT p.id, p.title, p.description, p.is_paid, p.payment_amount, p.url, p.start_date, p.end_date, p.expiration_date, p.company_id
          FROM projects p
          JOIN users_projects up ON p.id = up.project_id
          WHERE up.user_id = :userId
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
