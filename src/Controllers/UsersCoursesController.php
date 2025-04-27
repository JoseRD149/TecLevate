<?php

namespace TecLevate\Controllers;

use TecLevate\Models\UsersCoursesModel;
use TecLevate\Utils\Database;

class UsersCoursesController
{
    private $model;

    public function __construct()
    {
        $this->model = new UsersCoursesModel();
    }

    public function index()
    {
        echo json_encode($this->model->getAll());
    }

    public function show($id)
    {
        $record = $this->model->getById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User course not found']);
        }
    }

    public function store($data)
    {
        $created = $this->model->create($data);
        http_response_code(201);
        echo json_encode($created);
    }

    public function update($id, $data)
    {
        $updated = $this->model->update($id, $data);
        echo json_encode($updated);
    }

    public function destroy($id)
    {
        if ($this->model->delete($id)) {
            echo json_encode(['message' => 'User course deleted']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User course not found']);
        }
    }
    public function create($request)
    {
        $title = $request['title'] ?? null;
        $description = $request['description'] ?? null;
        $userId = $request['user_id'] ?? null;

        if (!$title || !$description || !$userId) {
            http_response_code(400);
            echo json_encode(['message' => 'Faltan datos']);
            return;
        }

        $this->model->createCourse($title, $description, $userId);

        echo json_encode(['message' => 'Curso creado correctamente por el usuario']);
    }
}
