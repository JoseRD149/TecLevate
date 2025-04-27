<?php

namespace TecLevate\Controllers;

use TecLevate\Models\CompanyCourseModel;
use Exception;
use TecLevate\Utils\Database;


class CompanyCourseController
{
    private $model;

    public function __construct()
    {
        global $pdo;
        $this->model = new CompanyCourseModel($pdo);
    }

    public function index()
    {
        echo json_encode($this->model->getAll());
    }

    public function show($id)
    {
        echo json_encode($this->model->getById($id));
    }

    public function store($data)
    {
        try {
            $created = $this->model->create($data);
            echo json_encode($created);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->model->delete($id);
        echo json_encode(['message' => 'Deleted']);
    }
    public function create($request)
    {
        $title = $request['title'] ?? null;
        $description = $request['description'] ?? null;
        $companyId = $request['company_id'] ?? null;

        if (!$title || !$description || !$companyId) {
            http_response_code(400);
            echo json_encode(['message' => 'Faltan datos']);
            return;
        }

        $this->model->createCourse($title, $description, $companyId);

        echo json_encode(['message' => 'Curso creado correctamente']);
    }


    public function update($id, $data)
    {
        try {
            $updated = $this->model->update($id, $data);
            echo json_encode($updated);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
