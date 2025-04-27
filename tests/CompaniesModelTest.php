<?php
use PHPUnit\Framework\TestCase;
use TecLevate\Models\CompaniesModel;
use TecLevate\Utils\Database;
use PDO;
use PDOStatement;

class CompaniesModelTest extends TestCase
{
    private $pdo;
    private $companiesModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->companiesModel = new CompaniesModel();
        $this->companiesModel->db = $this->pdo;
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'Company A', 'email' => 'contact@companya.com'],
            ['id' => 2, 'name' => 'Company B', 'email' => 'contact@companyb.com'],
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $companies = $this->companiesModel->getAll();
        $this->assertCount(2, $companies);
    }

    public function testGetById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
            'id' => 1, 'name' => 'Company A', 'email' => 'contact@companya.com'
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $company = $this->companiesModel->getById(1);
        $this->assertEquals('Company A', $company['name']);
    }

    public function testCreateValidCompany()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $companyData = [
            'name' => 'New Company',
            'email' => 'newcompany@example.com',
            'phone' => '555-6789',
            'logo' => 'logo.jpg',
            'cif' => 'B12345678',
            'url' => 'http://newcompany.com'
        ];

        $result = $this->companiesModel->create($companyData);
        $this->assertTrue($result);
    }

    public function testUpdateCompany()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $companyData = [
            'name' => 'Updated Company',
            'email' => 'updatedcompany@example.com',
            'phone' => '555-9876',
            'logo' => 'updated_logo.jpg',
            'cif' => 'B87654321',
            'url' => 'http://updatedcompany.com'
        ];

        $result = $this->companiesModel->update(1, $companyData);
        $this->assertTrue($result);
    }

    public function testDeleteCompany()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->companiesModel->delete(1);
        $this->assertTrue($result);
    }

    public function testGetCompanyById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
            'id' => 1, 'name' => 'Company A', 'email' => 'contact@companya.com'
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $company = $this->companiesModel->getCompanyById(1);
        $this->assertEquals('Company A', $company['name']);
    }
}
