<?php

namespace TecLevate\Controllers;

use TecLevate\Models\UsersProjectsModel;
use TecLevate\Models\ProjectsModel;
class UsersProjectsController {
    private $model;

    public function __construct() {
        $this->model = new UsersProjectsModel();
    }

    public function index() {
        $data = $this->model->getAll();
        echo json_encode($data);
    }

    public function show($id) {
        $item = $this->model->getById($id);
        if ($item) {
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }

    public function store($data) {
        $created = $this->model->create($data);
        http_response_code(201);
        echo json_encode($created);
    }

    public function update($id, $data) {
        $existing = $this->model->getById($id);
        if ($existing) {
            $updated = $this->model->update($id, $data);
            echo json_encode($updated);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }

    public function destroy($id) {
        $existing = $this->model->getById($id);
        if ($existing) {
            $this->model->delete($id);
            echo json_encode(['message' => 'Deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }
    public function getByUser($request)
    {
        header('Content-Type: application/json');
        $userId = isset($request['user_id']) ? (int)$request['user_id'] : null;

        if (!$userId) {
            http_response_code(400);
            echo json_encode(['message' => 'Falta user_id']);
            return;
        }

        try {
            $ProjectsModel = new ProjectsModel();
            $projects = $ProjectsModel->getByUserId($userId);
            echo json_encode(['projects' => $projects]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error del servidor', 'error' => $e->getMessage()]);
        }
    }
}
