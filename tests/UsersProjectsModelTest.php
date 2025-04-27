<?php
use PHPUnit\Framework\TestCase;
use TecLevate\Models\UsersProjectsModel;
use PDO;
use PDOStatement;

class UsersProjectsModelTest extends TestCase
{
    private $pdo;
    private $usersProjectsModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->usersProjectsModel = new UsersProjectsModel();
        $this->usersProjectsModel->db = $this->pdo;
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'user_id' => 1, 'project_id' => 1, 'start_date' => '2025-01-01'],
            ['id' => 2, 'user_id' => 2, 'project_id' => 2, 'start_date' => '2025-01-02'],
        ]);
        $this->pdo->method('query')->willReturn($stmt);

        $projects = $this->usersProjectsModel->getAll();
        $this->assertCount(2, $projects);
        $this->assertEquals(1, $projects[0]['user_id']);
    }

    public function testGetById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(['id' => 1, 'user_id' => 1, 'project_id' => 1, 'start_date' => '2025-01-01']);
        $this->pdo->method('prepare')->willReturn($stmt);

        $project = $this->usersProjectsModel->getById(1);
        $this->assertIsArray($project);
        $this->assertEquals(1, $project['user_id']);
    }

    public function testCreate()
    {
        $stmtUser = $this->createMock(PDOStatement::class);
        $stmtUser->method('fetch')->willReturn(['id' => 1]);
        $this->pdo->method('prepare')->willReturn($stmtUser);

        $stmtProject = $this->createMock(PDOStatement::class);
        $stmtProject->method('fetch')->willReturn(['id' => 1]);
        $this->pdo->method('prepare')->willReturn($stmtProject);

        $stmtInsert = $this->createMock(PDOStatement::class);
        $stmtInsert->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmtInsert);

        $this->pdo->method('lastInsertId')->willReturn('1');

        $data = [
            'user_id' => 1,
            'project_id' => 1,
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'finished' => false,
        ];

        $project = $this->usersProjectsModel->create($data);
        $this->assertEquals(1, $project['id']);
    }

    public function testUpdate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $data = [
            'user_id' => 1,
            'project_id' => 1,
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'finished' => true,
        ];

        $project = $this->usersProjectsModel->update(1, $data);
        $this->assertEquals(1, $project['id']);
        $this->assertTrue($project['finished']);
    }

    public function testDelete()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->usersProjectsModel->delete(1);
        $this->assertTrue($result);
    }

    public function testExistsInTable()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchColumn')->willReturn(1);
        $this->pdo->method('prepare')->willReturn($stmt);

        $exists = $this->usersProjectsModel->existsInTable('users', 1);
        $this->assertTrue($exists);
    }
}
