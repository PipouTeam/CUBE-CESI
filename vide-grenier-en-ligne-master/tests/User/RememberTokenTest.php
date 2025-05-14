<?php

namespace Tests\User;

use PHPUnit\Framework\TestCase;
use App\Models\User;

/**
 * Remember Token Tests
 * 
 * Tests for "remember me" functionality
 */
class RememberTokenTest extends TestCase {
    protected $mockPDO;
    protected $mockStatement;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->mockStatement = $this->createMock(\PDOStatement::class);
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
        User::setDB($this->mockPDO);
    }
    
    /**
     * Test setting remember token successfully
     */
    public function testSetRememberTokenSuccess() {
        $this->mockStatement->method('execute')->willReturn(true);

        $userId = 1;
        $token = 'randomToken';
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);

        $result = User::setRememberToken($userId, $token, $expiresAt);

        $this->assertTrue($result);
    }

    /**
     * Test setting remember token failure
     */
    public function testSetRememberTokenFailure() {
        $this->mockStatement->method('execute')->willReturn(false);

        $userId = 1;
        $token = 'randomToken';
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);
        
        $result = User::setRememberToken($userId, $token, $expiresAt);

        $this->assertFalse($result);
    }

    /**
     * Test getting user by remember token successfully
     */
    public function testGetUserByRememberTokenSuccess() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'Zblip',
            'email' => 'Blip@bloup.com',
            'password' => 'hashed_password',
            'salt' => 'pepper'
        ]);

        $token = 'validToken';

        $user = User::getUserByRememberToken($token);

        $this->assertNotNull($user);
        $this->assertEquals('Zblip', $user['username']);
        $this->assertEquals('Blip@bloup.com', $user['email']);
    }

    /**
     * Test getting user by remember token when token not found
     */
    public function testGetUserByRememberTokenNotFound() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false); 

        $token = 'invalidToken';

        $user = User::getUserByRememberToken($token);

        $this->assertFalse($user);
    }

    /**
     * Test getting user by expired remember token
     */
    public function testGetUserByRememberTokenExpiredToken() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false);

        $token = 'expiredToken';

        $user = User::getUserByRememberToken($token);

        $this->assertFalse($user);
    }
    
    /**
     * Test getting user by expired remember token (explicit test)
     */
    public function testGetUserByExpiredRememberToken() {
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('fetch')->willReturn(false); // No user found because token expired
        
        $token = 'expiredToken';
        
        $result = User::getUserByRememberToken($token);
        
        $this->assertFalse($result);
    }

    /**
     * Test deleting remember token successfully
     */
    public function testDeleteRememberTokenSuccess() {
        $this->mockStatement->method('execute')->willReturn(true);
    
        $token = 'validToken';
    
        $result = User::deleteRememberToken($token);
    
        $this->assertTrue($result);
    }
    
    /**
     * Test deleting remember token failure
     */
    public function testDeleteRememberTokenFailure() {
        $this->mockStatement->method('execute')->willReturn(false);

        $token = 'invalidToken';

        $result = User::deleteRememberToken($token);

        $this->assertFalse($result);
    }
}
