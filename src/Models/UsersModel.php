<?php

namespace TecLevate\Models;

use PDO;
use Exception;
use TecLevate\Utils\Database;

class UsersModel
{
    public $db;

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

    public function create($data, $imageFile)
    {
        if (empty($data['name'])) {
            error_log('Name is empty!');
            return false;
        }

        if (empty($data['email']) || empty($data['password'])) {
            error_log('Email or password is empty!');
            return false;
        }

        $imagePath = null;
        if ($imageFile && $imageFile['error'] === 0) {
            $uploadDir = __DIR__ . '/../uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagePath = $uploadDir . basename($imageFile['name']);

            if (!move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
                error_log('Failed to move uploaded file.');
                $imagePath = null;
            }
        } else {
            error_log('File upload error: ' . ($imageFile ? $imageFile['error'] : 'No file uploaded.'));
        }

        $stmt = $this->db->prepare("INSERT INTO users (name, email, dni, phone, password, company_id, profile_image) VALUES (:name, :email, :dni, :phone, :password, :company_id, :profile_image)");

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'dni' => $data['dni'],
            'phone' => $data['phone'],
            'password' => $hashedPassword,
            'company_id' => $data['company_id'] ?? null,
            'profile_image' => $imagePath
        ]);
    }


    public function update($id, $data, $imageFile = null)
    {
        $imagePath = null;
        if ($imageFile && $imageFile['error'] === 0) {
            $uploadDir = __DIR__ . '/../uploads/';
    
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            $imagePath = $uploadDir . basename($imageFile['name']);
    
            if (!move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
                error_log('Failed to move uploaded file.');
                $imagePath = null;
            }
        }
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, dni = :dni, phone = :phone, company_id = :company_id, profile_image = :profile_image WHERE id = :id");

        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'dni' => $data['dni'],
            'phone' => $data['phone'],
            'company_id' => $data['company_id'] ?? null,
            'profile_image' => $imagePath,
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
