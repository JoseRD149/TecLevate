<?php
use PHPUnit\Framework\TestCase;
use TecLevate\Models\CompanyCourseModel;
use PDO;
use PDOStatement;

class CompanyCourseModelTest extends TestCase
{
    private $pdo;
    private $companyCourseModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->companyCourseModel = new CompanyCourseModel($this->pdo);
    }

    public function testCreate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn([
            'id' => 1, 'company_id' => 1, 'course_id' => 1, 'offer_date' => '2025-05-01'
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);
        $this->pdo->method('lastInsertId')->willReturn('1');

        $data = [
            'company_id' => 1,
            'course_id' => 1,
            'offer_date' => '2025-05-01'
        ];

        $result = $this->companyCourseModel->create($data);
        $this->assertEquals(1, $result['id']);
    }

    public function testGetById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
            'id' => 1, 'company_id' => 1, 'course_id' => 1, 'offer_date' => '2025-05-01'
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->companyCourseModel->getById(1);
        $this->assertEquals(1, $result['id']);
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'company_id' => 1, 'course_id' => 1, 'offer_date' => '2025-05-01'],
            ['id' => 2, 'company_id' => 2, 'course_id' => 2, 'offer_date' => '2025-06-01'],
        ]);
        $this->pdo->method('query')->willReturn($stmt);

        $result = $this->companyCourseModel->getAll();
        $this->assertCount(2, $result);
    }

    public function testUpdate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn([
            'id' => 1, 'company_id' => 1, 'course_id' => 1, 'sponsorship_type' => 'Basic', 'start_date' => '2025-05-01', 'end_date' => '2025-05-31'
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $data = [
            'company_id' => 1,
            'course_id' => 1,
            'sponsorship_type' => 'Basic',
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-31'
        ];

        $result = $this->companyCourseModel->update(1, $data);
        $this->assertEquals('Basic', $result['sponsorship_type']);
    }

    public function testDelete()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->companyCourseModel->delete(1);
        $this->assertTrue($result);
    }

    public function testCreateCourse()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $title = 'New Course';
        $description = 'Course description';
        $companyId = 1;

        $this->companyCourseModel->createCourse($title, $description, $companyId);
        $this->assertTrue(true);  
    }
}
