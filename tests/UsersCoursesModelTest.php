<?php

use PHPUnit\Framework\TestCase;
use TecLevate\Models\UsersCoursesModel;
use PDO;
use PDOStatement;

class UsersCoursesModelTest extends TestCase
{
    private $pdo;
    private $usersCoursesModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->usersCoursesModel = new UsersCoursesModel();
        $this->usersCoursesModel->db = $this->pdo;
    }

    public function testCreate()
    {
        $stmtUser = $this->createMock(PDOStatement::class);
        $stmtUser->method('fetch')->willReturn(['id' => 1]);
        $this->pdo->method('prepare')->willReturn($stmtUser);

        $stmtInsert = $this->createMock(PDOStatement::class);
        $stmtInsert->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmtInsert);

        $this->pdo->method('lastInsertId')->willReturn('1');

        $data = [
            'user_id' => 1,
            'course_id' => 1,
            'enrollment_date' => '2025-01-01',
            'expiration_date' => '2025-12-31',
            'completed' => false,
        ];

        $result = $this->usersCoursesModel->create($data);
        $this->assertEquals('1', $result);
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['user_id' => 1, 'course_id' => 1, 'enrollment_date' => '2025-01-01'],
            ['user_id' => 2, 'course_id' => 2, 'enrollment_date' => '2025-01-02'],
        ]);
        $this->pdo->method('query')->willReturn($stmt);

        $courses = $this->usersCoursesModel->getAll();
        $this->assertCount(2, $courses);
        $this->assertEquals(1, $courses[0]['user_id']);
    }
}
