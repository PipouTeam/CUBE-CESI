<?php

namespace Tests\User;

use PHPUnit\Framework\TestCase;
use App\Models\User;

/**
 * User Login Tests
 * 
 * Tests for user login functionality
 */
class UserLoginTest extends TestCase {
    protected $mockPDO;
    protected $mockStatement;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->mockStatement = $this->createMock(\PDOStatement::class);
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
        User::setDB($this->mockPDO);
    }
    
    /**
     * Test login with valid credentials
     */
    public function testLoginWithValidCredentials() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'Zblip',
            'email' => 'Blip@bloup.com',
            'password' => hash('sha256', 'zblurp29' . 'pepper'), 
            'salt' => 'pepper'
        ]);

        $validData = [
            'email' => 'Blip@bloup.com',
            'password' => 'zblurp29'
        ];
    
        $result = User::login($validData);
    
        $this->assertEquals('Zblip', $result['username']);
        $this->assertEquals('Blip@bloup.com', $result['email']);
    }

    /**
     * Test login with invalid credentials
     */
    public function testLoginWithInvalidCredentials() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false); 

        $invalidData = [
            'email' => 'nonexistent@bloup.com',
            'password' => 'wrongpassword'
        ];
    
        $result = User::login($invalidData);
    
        $this->assertFalse($result);
    }
    
    /**
     * Test login with correct password but different case
     */
    public function testLoginWithCorrectPasswordButDifferentCase() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => hash('sha256', 'Password123' . 'randomsalt'),
            'salt' => 'randomsalt'
        ]);
        
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password123' // Different case from stored password
        ];
        
        $result = User::login($credentials);
        
        // Should fail because hash is case-sensitive
        $this->assertFalse($result);
    }
    
    /**
     * Test login with empty password
     */
    public function testLoginWithEmptyPassword() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => hash('sha256', '' . 'randomsalt'), // Empty password hash
            'salt' => 'randomsalt'
        ]);
        
        $credentials = [
            'email' => 'test@example.com',
            'password' => '' // Empty password
        ];
        
        $result = User::login($credentials);
        
        // Should match because we're comparing empty password hash
        $this->assertIsArray($result);
    }
    
    /**
     * Test getting user by login with existing user
     */
    public function testGetByLoginWithExistingUser() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => 'hashedpassword',
            'salt' => 'randomsalt'
        ]);
        
        $result = User::getByLogin('test@example.com');
        
        $this->assertIsArray($result);
        $this->assertEquals('TestUser', $result['username']);
        $this->assertEquals('test@example.com', $result['email']);
    }
    
    /**
     * Test getting user by login with non-existing user
     */
    public function testGetByLoginWithNonExistingUser() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false);
        
        $result = User::getByLogin('nonexistent@example.com');
        
        $this->assertFalse($result);
    }
    
    /**
     * Test auto-login after registration (addressing known issue)
     */
    public function testAutoLoginAfterRegistration() {
        // First test user creation
        $mockStatement1 = $this->createMock(\PDOStatement::class);
        $this->mockPDO->method('prepare')->willReturnOnConsecutiveCalls($mockStatement1, $this->mockStatement);
        $mockStatement1->method('execute')->willReturn(true);
        $this->mockPDO->method('lastInsertId')->willReturn("1");
        
        $userData = [
            'username' => 'NewUser',
            'email' => 'new@example.com',
            'password' => 'newpassword',
            'salt' => 'newsalt'
        ];
        
        $userId = User::createUser($userData);
        $this->assertEquals("1", $userId);
        
        // Then test if we can retrieve the user
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'NewUser',
            'email' => 'new@example.com',
            'password' => hash('sha256', 'newpassword' . 'newsalt'),
            'salt' => 'newsalt'
        ]);
        
        $loginData = [
            'email' => 'new@example.com',
            'password' => 'newpassword'
        ];
        
        $user = User::login($loginData);
        $this->assertIsArray($user);
        $this->assertEquals('NewUser', $user['username']);
    }
}
