<?php

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserModelTest extends TestCase {
    public function testCreateUserWithValidData() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement); //Au moment du prepare ca envoie le statement du mock

        $mockStatement->expects($this->once())  //Au moment du execute ca return true
                      ->method('execute')
                      ->willReturn(true);
    
        $mockPDO->method('lastInsertId')->willReturn("1"); //lastInsertId doit retourner "1"
        User::setDB($mockPDO);

        $validData = [
            'username' => 'Zblip',
            'email' => 'Blip@bloup.com',
            'password' => 'zblurp29',
            'salt' => 'pepper'
        ];

        $result = User::createUser($validData);
        $this->assertEquals("1", $result);
    }

    public function testCreateUserWithMissingData(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->expects($this->never())
                      ->method('execute');

        $mockPDO->method('lastInsertId')->willReturn("1"); 
        User::setDB($mockPDO);

        $invalidData = [
            'username' => 'Zblip',
            'email' => 'Blip@bloup.com',
            'salt' => 'pepper'
        ];

        $result = User::createUser($invalidData);
        $this->assertFalse($result);
    }

    public function testLoginWithValidCredentials(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'Zblip',
            'email' => 'Blip@bloup.com',
            'password' => hash('sha256', 'zblurp29' . 'pepper'), 
            'salt' => 'pepper'
        ]);

        User::setDB($mockPDO);

        $validData = [
            'email' => 'Blip@bloup.com',
            'password' => 'zblurp29'
        ];
    
        $result = User::login($validData);
    
        $this->assertEquals('Zblip', $result['username']);
        $this->assertEquals('Blip@bloup.com', $result['email']);

    }

    public function testLoginWithInvalidCredentials() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
    
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn(false); 

        User::setDB($mockPDO);
    
        $invalidData = [
            'email' => 'nonexistent@bloup.com',
            'password' => 'wrongpassword'
        ];
    
        $result = User::login($invalidData);
    
        $this->assertFalse($result);
    }

    public function testSetRememberTokenSuccess(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);

        $mockStatement->method('execute')->willReturn(true);

        User::setDB($mockPDO);

        $userId = 1;
        $token = 'randomToken';
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);

        $result = User::setRememberToken($userId, $token, $expiresAt);

        $this->assertTrue($result);
    }

    public function testSetRememberTokenFailure(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(false);
        User::setDB($mockPDO);

        $userId = 1;
        $token = 'randomToken';
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);
        
        $result = User::setRememberToken($userId, $token, $expiresAt);

        $this->assertFalse($result);
    }

    public function testGetUserByRememberTokenSuccess(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'Zblip',
            'email' => 'Blip@bloup.com',
            'password' => 'hashed_password',
            'salt' => 'pepper'
        ]);

        User::setDB($mockPDO);

        $token = 'validToken';

        $user = User::getUserByRememberToken($token);

        $this->assertNotNull($user);
        $this->assertEquals('Zblip', $user['username']);
        $this->assertEquals('Blip@bloup.com', $user['email']);
    }

    public function testGetUserByRememberTokenNotFound(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);

        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn(false); 

        User::setDB($mockPDO);

        $token = 'invalidToken';

        $user = User::getUserByRememberToken($token);

        $this->assertFalse($user);
    }

    public function testGetUserByRememberTokenExpiredToken(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn(false);

        User::setDB($mockPDO);

        $token = 'expiredToken';

        $user = User::getUserByRememberToken($token);

        $this->assertFalse($user);
    }

    public function testDeleteRememberTokenSuccess(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
    
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
    
        User::setDB($mockPDO);
    
        $token = 'validToken';
    
        $result = User::deleteRememberToken($token);
    
        $this->assertTrue($result);
    }
    
    public function testDeleteRememberTokenFailure(){
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(false);

        User::setDB($mockPDO);

        $token = 'invalidToken';

        $result = User::deleteRememberToken($token);

        $this->assertFalse($result);
    }

    public function testCreateUserWithInvalidEmail() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->expects($this->never())
                      ->method('execute');
        
        User::setDB($mockPDO);
        
        $invalidData = [
            'username' => 'TestUser',
            'email' => 'invalid-email', // Invalid email format
            'password' => 'password123',
            'salt' => 'randomsalt'
        ];
        
        $result = User::createUser($invalidData);
        $this->assertFalse($result);
    }

    public function testCreateUserWithDuplicateEmail() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willThrowException(new \PDOException('Duplicate entry'));
        
        User::setDB($mockPDO);
        
        $userData = [
            'username' => 'TestUser',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'salt' => 'randomsalt'
        ];
        
        $result = User::createUser($userData);
        $this->assertFalse($result);
    }

    public function testGetByLoginWithExistingUser() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => 'hashedpassword',
            'salt' => 'randomsalt'
        ]);
        
        User::setDB($mockPDO);
        
        $result = User::getByLogin('test@example.com');
        
        $this->assertIsArray($result);
        $this->assertEquals('TestUser', $result['username']);
        $this->assertEquals('test@example.com', $result['email']);
    }
    
    public function testGetByLoginWithNonExistingUser() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn(false);
        
        User::setDB($mockPDO);
        
        $result = User::getByLogin('nonexistent@example.com');
        
        $this->assertFalse($result);
    }

    public function testLoginWithCorrectPasswordButDifferentCase() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => hash('sha256', 'Password123' . 'randomsalt'),
            'salt' => 'randomsalt'
        ]);
        
        User::setDB($mockPDO);
        
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password123' // Different case from stored password
        ];
        
        $result = User::login($credentials);
        
        // Should fail because hash is case-sensitive
        $this->assertFalse($result);
    }

    public function testGetUserByExpiredRememberToken() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn(false); // No user found because token expired
        
        User::setDB($mockPDO);
        
        $token = 'expiredToken';
        
        $result = User::getUserByRememberToken($token);
        
        $this->assertFalse($result);
    }

    public function testCreateUserWithEmptyUsername() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->expects($this->never())
                      ->method('execute');
        
        User::setDB($mockPDO);
        
        $invalidData = [
            'username' => '', // Empty username
            'email' => 'test@example.com',
            'password' => 'password123',
            'salt' => 'randomsalt'
        ];
        
        $result = User::createUser($invalidData);
        $this->assertFalse($result);
    }
    
    public function testLoginWithEmptyPassword() {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')->willReturn([
            'id' => 1,
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => hash('sha256', '' . 'randomsalt'), // Empty password hash
            'salt' => 'randomsalt'
        ]);
        
        User::setDB($mockPDO);
        
        $credentials = [
            'email' => 'test@example.com',
            'password' => '' // Empty password
        ];
        
        $result = User::login($credentials);
        
        // Should match because we're comparing empty password hash
        $this->assertIsArray($result);
    }

    public function testAutoLoginAfterRegistration() {
        // This would test a method that should exist to handle auto-login after registration
        // Since I don't see this method in the User model, this test suggests you should implement it
        
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);
        
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->method('execute')->willReturn(true);
        $mockPDO->method('lastInsertId')->willReturn("1");
        
        User::setDB($mockPDO);
        
        $userData = [
            'username' => 'NewUser',
            'email' => 'new@example.com',
            'password' => 'newpassword',
            'salt' => 'newsalt'
        ];
        
        // This suggests implementing a method like:
        // public static function registerAndLogin($userData)
        
        // For now, we can test if the existing methods would support this functionality
        $userId = User::createUser($userData);
        $this->assertEquals("1", $userId);
        
        // Then test if we can retrieve the user
        $mockStatement2 = $this->createMock(PDOStatement::class);
        $mockPDO->method('prepare')->willReturn($mockStatement2);
        $mockStatement2->method('execute')->willReturn(true);
        $mockStatement2->method('fetch')->willReturn([
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