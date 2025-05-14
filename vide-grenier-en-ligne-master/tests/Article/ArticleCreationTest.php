<?php
declare(strict_types=1);

namespace Tests\Article;

use PHPUnit\Framework\TestCase;
use App\Models\Articles;

/**
 * Article Creation Tests
 * 
 * Tests for article creation and updating functionality
 */
class ArticleCreationTest extends TestCase {
    protected $mockPDO;
    protected $mockStatement;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->mockStatement = $this->createMock(\PDOStatement::class);
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
        Articles::setDBForTests($this->mockPDO);
    }
    
    /**
     * Test creating an article with valid data
     */
    public function testArticlesCreationWithValidData(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockPDO->method('lastInsertId')->willReturn('42');
        
        $articleData = [
            'name' => 'Test Article',
            'description' => 'This is a test article.',
            'user_id' => 1,
        ];

        $articleId = Articles::save($articleData);

        $this->assertEquals('42', $articleId);
    }
    
    /**
     * Test attaching pictures to articles
     */
    public function testAttachingPicturesToArticles(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $articleId = 1;
        $pictureName = 'test.jpeg';
        
        Articles::attachPicture($articleId, $pictureName);
        
        // Simple assertion to verify the test ran
        $this->assertTrue(true);
    }
    
    /**
     * Test creating an article with missing required fields
     */
    public function testCreateArticleWithMissingRequiredFields(): void {
        // The Articles model doesn't validate missing fields before executing the query
        // It will try to bind the parameters and fail with a PDO error
        $this->mockStatement->method('execute')
            ->willThrowException(new \PDOException('SQLSTATE[HY000]: General error: binding parameter'));
        
        // Missing description field
        $articleData = [
            'name' => 'Test Article',
            'user_id' => 1,
            // No description
        ];
        
        // We expect the PDOException to bubble up
        $this->expectException(\PDOException::class);
        Articles::save($articleData);
    }
    
    /**
     * Test creating an article with special characters
     */
    public function testCreateArticleWithSpecialCharacters(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockPDO->method('lastInsertId')->willReturn('43');
        
        $articleData = [
            'name' => 'Special Characters: éèêë"\'\'&<>',
            'description' => 'Description with special characters: éèêë"\'\'&<>',
            'user_id' => 1,
        ];
        
        $articleId = Articles::save($articleData);
        
        $this->assertEquals('43', $articleId);
    }
    
    /**
     * Test creating an article with long content
     */
    public function testCreateArticleWithLongContent(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockPDO->method('lastInsertId')->willReturn('44');
        
        $longDescription = str_repeat('This is a very long description. ', 100); // Creates a very long string
        
        $articleData = [
            'name' => 'Long Content Article',
            'description' => $longDescription,
            'user_id' => 1,
        ];
        
        $articleId = Articles::save($articleData);
        
        $this->assertEquals('44', $articleId);
    }
    
    /**
     * Test attaching a picture to a non-existent article
     */
    public function testAttachPictureToNonExistentArticle(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(false); // Simulate failure when article doesn't exist
        
        $nonExistentArticleId = 999;
        $pictureName = 'test.jpeg';
        
        Articles::attachPicture($nonExistentArticleId, $pictureName);
        
        // Since the method doesn't return anything, we're just verifying the mock expectations
        $this->assertTrue(true);
    }
}
