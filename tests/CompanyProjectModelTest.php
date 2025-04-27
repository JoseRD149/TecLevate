<?php
use PHPUnit\Framework\TestCase;
use TecLevate\Models\CompanyProjectModel;

class CompanyProjectModelTest extends TestCase
{
    private $pdo;
    private $companyProjectModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->companyProjectModel = new CompanyProjectModel($this->pdo);
    }

    public function testCreate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);
        $this->pdo->method('lastInsertId')->willReturn('1'); 

        $this->pdo->method('prepare')
                  ->willReturnCallback(function() use ($stmt) {
                      $stmt->method('fetch')->willReturn(['id' => 1, 'company_id' => 1, 'project_id' => 1, 'offer_date' => '2025-05-01']);
                      return $stmt;
                  });

        $data = [
            'company_id' => 1,
            'project_id' => 1,
            'offer_date' => '2025-05-01'
        ];

        $companyProject = $this->companyProjectModel->create($data);
        $this->assertIsArray($companyProject); 
        $this->assertArrayHasKey('id', $companyProject); 
    }

    public function testGetById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
            'id' => 1, 'company_id' => 1, 'project_id' => 1, 'offer_date' => '2025-05-01'
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $companyProject = $this->companyProjectModel->getById(1);

        $this->assertIsArray($companyProject); 
        $this->assertEquals(1, $companyProject['company_id']);
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'company_id' => 1, 'project_id' => 1, 'offer_date' => '2025-05-01']
        ]);
        $this->pdo->method('query')->willReturn($stmt);

        $companyProjects = $this->companyProjectModel->getAll();

        $this->assertIsArray($companyProjects);  
        $this->assertCount(1, $companyProjects);  
    }

    public function testDelete()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->companyProjectModel->delete(1);

        $this->assertTrue($result);
    }

    public function testUpdate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);
        $this->pdo->method('lastInsertId')->willReturn('1'); 

        $this->pdo->method('prepare')
                  ->willReturnCallback(function() use ($stmt) {
                      $stmt->method('fetch')->willReturn(['id' => 1, 'company_id' => 1, 'project_id' => 1, 'offer_date' => '2025-05-01']);
                      return $stmt;
                  });

        $data = [
            'company_id' => 1,
            'project_id' => 1,
            'sponsorship_type' => 'Gold',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31'
        ];

        $companyProject = $this->companyProjectModel->update(1, $data);
        $this->assertIsArray($companyProject); 
        $this->assertArrayHasKey('id', $companyProject);  
    }
}
