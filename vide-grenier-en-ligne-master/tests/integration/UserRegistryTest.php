<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Models\User;

final class UserRegistryTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        // This runs before each test
        $this->db = new PDO('mysql:host=127.0.0.1;port=3307;dbname=vide-grenier', 'test', 'pass');
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
        
        $this->insertUser($userData);
        $userId = $this->db->lastInsertId();
        
        // Check user registration
        $this->assertNotEmpty($userId);
        
        $user = $this->getUserWithEmail($userData['email']);
        $this->assertNotFalse($user);
        $this->assertEquals($userData['username'], $user['username']);
        
        $this->deleteUserWithEmail($userData['email']);
    }

    public function testUserLogin(): void
    {
        $userData = [
            'username' => 'testlogin',
            'email' => 'testlogin@gmail.com',
            'password' => hash('sha256', 'test' . 'test'), // password + salt
            'salt' => 'test'
        ];
        
        $this->insertUser($userData);
        
        $user = $this->getUserWithEmail($userData['email']);
        $this->assertNotFalse($user);
        $this->assertEquals($userData['username'], $user['username']);
        
        // Verify password hash
        $hash = hash('sha256', 'test' . $user['salt']);
        $this->assertEquals($hash, $user['password']);
        
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
        
        $this->insertUser($userData);
        $userId = $this->db->lastInsertId();
        
        // Create remember token
        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $this->insertToken($userId, $token, $expiresAt);
        
        $user = $this->getUserByToken($token);
        
        // Check user was retrieved by token
        $this->assertNotFalse($user);
        $this->assertEquals($userData['email'], $user['email']);
        
        $this->deleteToken($token);

        // Check token was deleted
        $stmt = $this->db->prepare('SELECT * FROM user_tokens WHERE token = :token');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $this->assertEmpty($stmt->fetchAll());
        
        $this->deleteUserWithEmail($userData['email']);
    }

    protected function insertUser($userData): void
    {
        $stmt = $this->db->prepare('INSERT INTO users(username, email, password, salt) VALUES (:username, :email, :password, :salt)');
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $userData['password']);
        $stmt->bindParam(':salt', $userData['salt']);
        $stmt->execute();
    }

    protected function getUserWithEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function deleteUserWithEmail($email): void
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
    
    protected function insertToken($userId, $token, $expiresAt): void
    {
        $stmt = $this->db->prepare('INSERT INTO user_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', $expiresAt);
        $stmt->execute();
    }

    protected function deleteToken($token): void
    {
        $stmt = $this->db->prepare('DELETE FROM user_tokens WHERE token = :token');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    }

    protected function getUserByToken($token)
    {
        $stmt = $this->db->prepare('SELECT users.* FROM users JOIN user_tokens ON user_tokens.user_id = users.id WHERE user_tokens.token = :token AND user_tokens.expires_at > NOW() LIMIT 1');
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}