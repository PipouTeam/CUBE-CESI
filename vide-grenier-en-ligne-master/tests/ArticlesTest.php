<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

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

    public function testAttachingPicturesToArticles(): void
    {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        Articles::setDBForTests($mockPDO);

        $mockPDO->method('prepare')->willReturn($mockStatement);
        
        $mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $mockPDO->method('lastInsertId')->willReturn('test.jpeg');

        $articleId = 1;
        $pictureName = 'test.jpeg';
        
        Articles::attachPicture($articleId, $pictureName);

        $this->assertTrue(true);
    }

    public function testRetrievingArticlesByUser(): void
    {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        Articles::setDBForTests($mockPDO);

        $mockPDO->method('prepare')->willReturn($mockStatement);

        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        $mockStatement->method('fetchAll')
            ->willReturn([
                [
                    'id' => 1,
                    'name' => 'Article 1',
                    'description' => 'Description for article 1',
                    'user_id' => 1,
                    'picture' => 'image1.jpg'
                ],
                [
                    'id' => 2,
                    'name' => 'Article 2',
                    'description' => 'Description for article 2',
                    'user_id' => 1,
                    'picture' => 'image2.jpg'
                ]
            ]);

        $articles = Articles::getByUser(1);

        $this->assertCount(2, $articles);
        $this->assertEquals('Article 1', $articles[0]['name']);
        $this->assertEquals('Article 2', $articles[1]['name']);
    }

    public function testViewCounter(): void
    {
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        Articles::setDBForTests($mockPDO);

        $mockPDO->method('prepare')->willReturn($mockStatement);

        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        Articles::addOneView(1);
    }

}