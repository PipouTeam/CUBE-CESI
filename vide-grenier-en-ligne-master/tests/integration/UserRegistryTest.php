<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Models\User;
use Core\Model;

final class UserRegistryTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        // This runs before each test
        $this->db = new PDO('mysql:host=127.0.0.1;port=3307;dbname=vide-grenier', 'test', 'pass');
        
        // Set the test database connection for the Model class
        Model::setTestDB($this->db);
    }

    protected function tearDown(): void
    {
        // This runs after each test
        $this->db = null;
    }

    public function testUserRegistration(): void
    {
        $userData = [
            'username' => 'test',
            'email' => 'test@gmail.com',
            'password' => hash('sha256', 'test' . 'test'), // password + salt
            'salt' => 'test'
        ];

        // Now we can use the User model directly
        $userId = User::createUser($userData);
        
        // Check user registration
        $this->assertNotEmpty($userId);
        
        $user = User::getByLogin($userData['email']);
        $this->assertNotFalse($user);
        $this->assertEquals($userData['username'], $user['username']);
        
        $this->deleteUserWithEmail($userData['email']);
    }

    public function testRememberMeFunction(): void
    {
        $userData = [
            'username' => 'testremember',
            'email' => 'testremember@gmail.com',
            'password' => hash('sha256', 'test' . 'test'), // password + salt
            'salt' => 'test'
        ];
        
        $userId = User::createUser($userData);
        
        // Create remember token
        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        User::setRememberToken($userId, $token, $expiresAt);
        
        $user = User::getUserByRememberToken($token);
        
        // Check user was retrieved by token
        $this->assertNotFalse($user);
        $this->assertEquals($userData['email'], $user['email']);
        
        User::deleteRememberToken($token);

        // Check token was deleted
        $stmt = $this->db->prepare('SELECT * FROM user_tokens WHERE token = :token');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $this->assertEmpty($stmt->fetchAll());
        
        $this->deleteUserWithEmail($userData['email']);
    }

    protected function deleteUserWithEmail($email): void
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
}