<?php
use PHPUnit\Framework\TestCase;
use TecLevate\Models\CoursesModel;
use PDO;
use PDOStatement;

class CoursesModelTest extends TestCase
{
    private $pdo;
    private $coursesModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->coursesModel = new CoursesModel();
        
        $this->coursesModel->db = $this->pdo;
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'Course 1', 'description' => 'Description 1'],
            ['id' => 2, 'title' => 'Course 2', 'description' => 'Description 2'],
        ]);
        $this->pdo->method('query')->willReturn($stmt);

        $courses = $this->coursesModel->getAll();
        $this->assertCount(2, $courses);  
        $this->assertEquals('Course 1', $courses[0]['title']);  
    }

    public function testGetById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(['id' => 1, 'title' => 'Course 1', 'description' => 'Description 1']);
        $this->pdo->method('prepare')->willReturn($stmt);

        $course = $this->coursesModel->getById(1);
        $this->assertIsArray($course); 
        $this->assertEquals('Course 1', $course['title']); 
    }

    public function testCreate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);
        $this->pdo->method('lastInsertId')->willReturn('1');

        $data = [
            'title' => 'Course 1',
            'description' => 'Description 1',
            'is_paid' => 1,
            'price' => 100,
            'url' => 'http://example.com',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'expiration_date' => '2025-12-31',
            'company_id' => 1,
        ];

        $courseId = $this->coursesModel->create($data);
        $this->assertEquals('1', $courseId);  
    }

    public function testUpdate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $data = [
            'title' => 'Updated Course',
            'description' => 'Updated Description',
            'is_paid' => 0,
            'price' => 50,
            'url' => 'http://updated.com',
            'start_date' => '2025-02-01',
            'end_date' => '2025-11-30',
            'expiration_date' => '2025-11-30',
            'company_id' => 1,
        ];

        $result = $this->coursesModel->update(1, $data);
        $this->assertTrue($result); 
    }

    public function testDestroy()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->coursesModel->destroy(1);
        $this->assertTrue($result);
    }

    public function testAssignToUser()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $this->coursesModel->assignToUser(1, 1);
        $this->assertTrue(true);  
    }

    public function testGetByUserId()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'Course 1', 'description' => 'Description 1'],
            ['id' => 2, 'title' => 'Course 2', 'description' => 'Description 2'],
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $courses = $this->coursesModel->getByUserId(1);
        $this->assertCount(2, $courses);  
    }
}
