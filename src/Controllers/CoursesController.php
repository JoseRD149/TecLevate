<?php
namespace TecLevate\Controllers;

use TecLevate\Models\CoursesModel;

class CoursesController {
    private $model;

    public function __construct() {
        $this->model = new CoursesModel();
    }

    public function index() {
        $courses = $this->model->getAll();
        echo json_encode($courses);
    }

    public function show($id) {
        $course = $this->model->getById($id);
        if ($course) {
            echo json_encode($course);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
        }
    }

    public function create($data) {
        $id = $this->model->create($data);
        echo json_encode(['message' => 'Course created', 'id' => $id]);
    }

    public function update($id, $data) {
        if ($this->model->update($id, $data)) {
            echo json_encode(['message' => 'Course updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update course']);
        }
    }

    public function destroy($id) {
        if ($this->model->destroy($id)) {
            echo json_encode(['message' => 'Course deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete course']);
        }
    }

    public function assign($userId, $courseId) {
        $this->model->assignToUser($userId, $courseId);
        echo json_encode(['message' => 'Course assigned to user']);
    }
}
