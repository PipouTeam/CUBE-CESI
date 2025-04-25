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
}