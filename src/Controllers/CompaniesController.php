<?php
namespace TecLevate\Controllers;

use TecLevate\Models\CompaniesModel;

class CompaniesController
{
    private $model;

    public function __construct()
    {
        $this->model = new CompaniesModel();
    }

    public function index()
    {
        $companies = $this->model->getAll();
        echo json_encode($companies);
    }

    public function show($id)
    {
        $company = $this->model->getById($id);
        if ($company) {
            echo json_encode($company);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Company not found']);
        }
    }

    public function store($data)
    {
        if ($this->model->create($data)) {
            http_response_code(201);
            echo json_encode(['message' => 'Company created']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to create company']);
        }
    }

    public function update($id, $data)
    {
        if ($this->model->update($id, $data)) {
            echo json_encode(['message' => 'Company updated']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update company']);
        }
    }

    public function destroy($id)
    {
        if ($this->model->delete($id)) {
            echo json_encode(['message' => 'Company deleted']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to delete company']);
        }
    }
}
