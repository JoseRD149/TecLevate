<?php

namespace TecLevate\Controllers;

use TecLevate\Models\UsersModel;
use TecLevate\Models\CompaniesModel;

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

    public function create($data)
    {
        $success = $this->usersModel->create($data);
        return $this->respondJSON(['success' => $success]);
    }

    public function update($id, $data)
    {
        $success = $this->usersModel->update($id, $data);
        return $this->respondJSON(['success' => $success]);
    }

    public function destroy($id)
    {
        $success = $this->usersModel->delete($id);
        return $this->respondJSON(['success' => $success]);
    }

    private function respondJSON($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    public function login($data)
    {
        session_start();

        $email  = $data['email'] ?? null;
        $pass   = $data['password'] ?? null;

        if (!$email || !$pass) {
            return $this->respondJSON(['error' => 'Email and password are required'], 400);
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


        return $this->respondJSON(['success' => true, 'user' => $user, 'company' => $_SESSION['company'] ?? null]);
    }



    public function logout()
    {
        session_start();

        session_unset();
        session_destroy();

        return $this->respondJSON(['success' => true]);
    }
}
