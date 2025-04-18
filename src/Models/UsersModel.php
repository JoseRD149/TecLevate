<?php

namespace TecLevate\Models;

use PDO;
use TecLevate\Utils\Database;

class UsersModel {
    private $db;
    
        public function __construct() {
            $this->db = Database::connect();
        }
    
        public function getAll() {
            $stmt = $this->db->prepare("SELECT * FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public function getById($id) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function create($data) {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, dni, phone, password) VALUES (:name, :email, :dni, :phone, :password)");
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'dni' => $data['dni'],
                'phone' => $data['phone'],
                'password' => $hashedPassword
            ]);
        }
    
        public function update($id, $data) {
            $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, dni = :dni, phone = :phone WHERE id = :id");
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'dni' => $data['dni'],
                'phone' => $data['phone'],
                'id' => $id
            ]);
        }
    
        public function delete($id) {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        }
}