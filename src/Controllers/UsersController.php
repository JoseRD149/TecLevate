<?php

namespace   TecLevate\Controllers;

use TecLevate\Models\UsersModel;

class UsersController
{
    private $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }
    
    public function index() {
        return $this->respondJSON($this->usersModel->getAll());
    }

    public function show($id) {
        return $this->respondJSON($this->usersModel->getById($id));
    }

    public function store($data) {
        $success = $this->usersModel->create($data);
        return $this->respondJSON(['success' => $success]);
    }

    public function update($id, $data) {
        $success = $this->usersModel->update($id, $data);
        return $this->respondJSON(['success' => $success]);
    }

    public function destroy($id) {
        $success = $this->usersModel->delete($id);
        return $this->respondJSON(['success' => $success]);
    }

    private function respondJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}


