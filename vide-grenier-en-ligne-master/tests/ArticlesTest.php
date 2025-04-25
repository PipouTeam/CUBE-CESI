<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Models\Articles;

final class ArticlesTest extends TestCase
{
    public function testArticlesCreationWithValidData(): void
    {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        Articles::setDBForTests($mockPDO);

        // Préparer le mock pour statement
        $mockPDO->method('prepare')->willReturn($mockStatement);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $mockPDO->method('lastInsertId')->willReturn('42');
        
        $articleData = [
            'name' => 'Test Article',
            'description' => 'This is a test article.',
            'user_id' => 1,
        ];

        $articleId = Articles::save($articleData);

        $this->assertEquals('42', $articleId);
    }

}