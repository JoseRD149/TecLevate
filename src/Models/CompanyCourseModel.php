<?php
namespace TecLevate\Models;

use PDO;
use Exception;

class CompanyCourseModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO companies_courses (company_id, course_id, offer_date)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $data['company_id'],
            $data['course_id'],
            $data['offer_date']
        ]);
        return $this->getById($this->db->lastInsertId());
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM companies_courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM companies_courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update($id, $data)
{
    $stmt = $this->db->prepare("
        UPDATE companies_courses 
        SET company_id = ?, course_id = ?, sponsorship_type = ?, start_date = ?, end_date = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        $data['company_id'],
        $data['course_id'],
        $data['sponsorship_type'],
        $data['start_date'],
        $data['end_date'],
        $id
    ]);
    return $this->getById($id);
}


    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM companies_courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
