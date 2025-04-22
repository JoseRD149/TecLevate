<?php

namespace TecLevate\Models;

use PDO;
use TecLevate\Utils\Database;

class UsersModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, dni, phone, password, company_id) VALUES (:name, :email, :dni, :phone, :password, :company_id)");

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'dni' => $data['dni'],
            'phone' => $data['phone'],
            'password' => $hashedPassword,
            'company_id' => $data['company_id'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, dni = :dni, phone = :phone  company_id = :company_id WHERE id = :id");
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'dni' => $data['dni'],
            'phone' => $data['phone'],
            'company_id' => $data['company_id'] ?? null,
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
