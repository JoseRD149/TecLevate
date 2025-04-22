<?php
namespace TecLevate\Controllers;

use TecLevate\Models\CompanyProjectModel;
use Exception;

class CompanyProjectController
{
    private $model;

    public function __construct()
    {
        global $pdo;
        $this->model = new CompanyProjectModel($pdo);
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
}
