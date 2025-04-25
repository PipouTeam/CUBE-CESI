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
    
}