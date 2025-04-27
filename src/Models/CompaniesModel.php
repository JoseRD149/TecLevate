<?php
namespace TecLevate\Models;

use TecLevate\Utils\Database;
use PDO;

class CompaniesModel
{
    public $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM companies");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO companies (name, email, phone, logo, cif, url) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['logo'],
            $data['cif'],
            $data['url']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE companies SET name = ?, email = ?, phone = ?, logo = ?, cif = ?, url = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['logo'],
            $data['cif'],
            $data['url'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM companies WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getCompanyById($companyId)
{
    $stmt = $this->db->prepare("SELECT * FROM companies WHERE id = :company_id");
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
