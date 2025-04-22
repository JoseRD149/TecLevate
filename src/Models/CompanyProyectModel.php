<?php
namespace TecLevate\Models;

use PDO;

class CompanyProjectModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO companies_projects (company_id, project_id, offer_date)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $data['company_id'],
            $data['project_id'],
            $data['offer_date']
        ]);
        return $this->getById($this->db->lastInsertId());
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM companies_projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM companies_projects");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM companies_projects WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function update($id, $data)
        {
            $stmt = $this->db->prepare("
                UPDATE companies_projects 
                SET company_id = ?, project_id = ?, sponsorship_type = ?, start_date = ?, end_date = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $data['company_id'],
                $data['project_id'],
                $data['sponsorship_type'],
                $data['start_date'],
                $data['end_date'],
                $id
            ]);
            return $this->getById($id);
        }
    
}
    
    
        