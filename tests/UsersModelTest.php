<?php
use PHPUnit\Framework\TestCase;
use TecLevate\Models\UsersModel;
use TecLevate\Utils\Database;


class UsersModelTest extends TestCase
{
    private $pdo;
    private $usersModel;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);

        $this->usersModel = new UsersModel();
        $this->usersModel->db = $this->pdo; 
    }

    public function testGetAll()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
        ]);
        
        $this->pdo->method('prepare')->willReturn($stmt);

        $users = $this->usersModel->getAll();
        $this->assertCount(2, $users);  
    }

    public function testGetById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
            'id' => 1, 'name' => 'Prueba', 'email' => 'prueba@example.com'
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $user = $this->usersModel->getById(1);
        $this->assertEquals('Prueba', $user['name']);
    }

    public function testCreateValidUser()
    {
        $imageFile = ['error' => 0, 'name' => 'profile.jpg', 'tmp_name' => '/tmp/php1234.tmp'];
        
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true); 
        $this->pdo->method('prepare')->willReturn($stmt);

        $userData = [
            'name' => 'Juan',
            'email' => 'juan@example.com',
            'dni' => '12345678A',
            'phone' => '555-1234',
            'password' => 'password123',
            'company_id' => 1
        ];

        $result = $this->usersModel->create($userData, $imageFile);
        $this->assertTrue($result);  
    }

    public function testCreateUserWithEmptyName()
    {
        $imageFile = ['error' => 0, 'name' => 'profile.jpg', 'tmp_name' => '/tmp/php1234.tmp'];
        
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);  
        $this->pdo->method('prepare')->willReturn($stmt);

        $userData = [
            'name' => '',
            'email' => 'no-name@example.com',
            'dni' => '12345678A',
            'phone' => '555-1234',
            'password' => 'password123',
            'company_id' => 1
        ];

        $result = $this->usersModel->create($userData, $imageFile);
        $this->assertFalse($result);  
    }

    public function testUpdateUser()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $userData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'dni' => '87654321B',
            'phone' => '555-5678',
            'company_id' => 2
        ];

        $result = $this->usersModel->update(1, $userData);
        $this->assertTrue($result); 
    }

    public function testDeleteUser()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->usersModel->delete(1);
        $this->assertTrue($result);  
    }
}
