<?php
namespace TecLevate\Controllers;

use TecLevate\Models\ProjectsModel;

class ProjectsController {
    private $model;

    public function __construct() {
        $this->model = new ProjectsModel();
    }

    public function index() {
        $projects = $this->model->getAll();
        echo json_encode($projects);
    }

    public function show($id) {
        $project = $this->model->getById($id);
        if ($project) {
            echo json_encode($project);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Project not found']);
        }
    }

    public function create($data) {
        $id = $this->model->create($data);
        echo json_encode(['message' => 'Project created', 'id' => $id]);
    }

    public function update($id, $data) {
        if ($this->model->update($id, $data)) {
            echo json_encode(['message' => 'Project updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update project']);
        }
    }

    public function destroy($id) {
        if ($this->model->destroy($id)) {
            echo json_encode(['message' => 'Project deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete project']);
        }
    }

    public function assign($userId, $projectId) {
        $this->model->assignToUser($userId, $projectId);
        echo json_encode(['message' => 'Project assigned to user']);
    }
}
