<?php
use PHPUnit\Framework\TestCase;
use TecLevate\Models\ProjectsModel;
use PDO;
use PDOStatement;

class ProjectsModelTest extends TestCase
{
    private $pdo;
    private $projectsModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->projectsModel = new ProjectsModel();
        
        $this->projectsModel->db = $this->pdo;
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'Project 1', 'description' => 'Description 1'],
            ['id' => 2, 'title' => 'Project 2', 'description' => 'Description 2'],
        ]);
        $this->pdo->method('query')->willReturn($stmt);

        $projects = $this->projectsModel->getAll();
        $this->assertCount(2, $projects); 
        $this->assertEquals('Project 1', $projects[0]['title']);  
    }

    public function testGetById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(['id' => 1, 'title' => 'Project 1', 'description' => 'Description 1']);
        $this->pdo->method('prepare')->willReturn($stmt);

        $project = $this->projectsModel->getById(1);
        $this->assertIsArray($project);  
        $this->assertEquals('Project 1', $project['title']);  
    }

    public function testCreate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);
        $this->pdo->method('lastInsertId')->willReturn('1');

        $data = [
            'title' => 'Project 1',
            'description' => 'Description 1',
            'is_paid' => 1,
            'payment_amount' => 500,
            'url' => 'http://example.com',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'expiration_date' => '2025-12-31',
            'company_id' => 1,
        ];

        $projectId = $this->projectsModel->create($data);
        $this->assertEquals('1', $projectId);  
    }

    public function testUpdate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $data = [
            'title' => 'Updated Project',
            'description' => 'Updated Description',
            'is_paid' => 0,
            'payment_amount' => 300,
            'url' => 'http://updated.com',
            'start_date' => '2025-02-01',
            'end_date' => '2025-11-30',
            'expiration_date' => '2025-11-30',
            'company_id' => 1,
        ];

        $result = $this->projectsModel->update(1, $data);
        $this->assertTrue($result); 
    }

    public function testDestroy()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->projectsModel->destroy(1);
        $this->assertTrue($result); 
    }

    public function testAssignToUser()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $this->projectsModel->assignToUser(1, 1);
        $this->assertTrue(true);  
    }

    public function testGetByUserId()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'title' => 'Project 1', 'description' => 'Description 1'],
            ['id' => 2, 'title' => 'Project 2', 'description' => 'Description 2'],
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $projects = $this->projectsModel->getByUserId(1);
        $this->assertCount(2, $projects);
    }
}
