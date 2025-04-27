<?php

namespace TecLevate\Controllers;

use TecLevate\Models\UsersModel;
use TecLevate\Models\CompaniesModel;
use Exception;

class UsersController
{
    private $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function index()
    {
        return $this->respondJSON($this->usersModel->getAll());
    }

    public function show($id)
    {
        return $this->respondJSON($this->usersModel->getById($id));
    }
    public function create()
    {
        $data = [
            'name'       => $_POST['name']       ?? null,
            'email'      => $_POST['email']      ?? null,
            'dni'        => $_POST['dni']        ?? null,
            'phone'      => $_POST['phone']      ?? null,
            'password'   => $_POST['password']   ?? null,
            'company_id' => $_POST['company_id'] ?? null,
        ];
    
        $imageFile = (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0)
            ? $_FILES['profile_image']
            : null;
    
        try {
            $success = $this->usersModel->create($data, $imageFile);
            return $this->respondJSON([
                'success' => $success,
                'message' => $success
                    ? 'Usuario creado correctamente.'
                    : 'No se pudo crear el usuario.'
            ]);
        } catch (Exception $e) {
            return $this->respondJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    



    public function update($id, $data)
    {
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $imageFile = $_FILES['profile_image'];
            $imagePath = 'uploads/' . basename($imageFile['name']);
            move_uploaded_file($imageFile['tmp_name'], $imagePath);
            $data['profile_image'] = $imagePath;
        } else {
            $data['profile_image'] = $data['current_image'] ?? null;
        }

        $success = $this->usersModel->update($id, $data);
        return $this->respondJSON(['success' => $success]);
    }


    public function destroy($id)
    {
        $success = $this->usersModel->delete($id);
        return $this->respondJSON(['success' => $success]);
    }

    public function respondJSON($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    public function login($data)
    {
        session_start();

        $email  = $data['email'] ?? null;
        $pass   = $data['password'] ?? null;

        if (!$email || !$pass) {
            return $this->respondJSON(['error' => 'Email and password are required'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->respondJSON(['error' => 'Invalid email format'], 400);
        }

        $user = $this->usersModel->getByEmail($email);
        if (!$user) {
            return $this->respondJSON(['error' => 'Invalid email or password'], 401);
        }

        if (!password_verify($pass, $user['password'])) {
            return $this->respondJSON(['error' => 'Invalid email or password'], 401);
        }

        $_SESSION['id_user'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        if ($user['company_id'] !== null) {
            $companiesModel = new CompaniesModel();
            $company = $companiesModel->getCompanyById($user['company_id']);
            $_SESSION['company'] = $company;
        }

        return $this->respondJSON([
            'success' => true,
            'user' => $user,
            'company' => $_SESSION['company'] ?? null
        ]);
    }




    public function logout()
    {
        session_start();

        session_unset();
        session_destroy();

        return $this->respondJSON(['success' => true]);
    }
}
