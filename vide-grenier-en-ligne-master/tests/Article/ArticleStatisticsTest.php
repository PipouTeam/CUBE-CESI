<?php
declare(strict_types=1);

namespace Tests\Article;

use PHPUnit\Framework\TestCase;
use App\Models\Articles;

/**
 * Article Statistics Tests
 * 
 * Tests for article statistics functionality like view counting
 */
class ArticleStatisticsTest extends TestCase {
    protected $mockPDO;
    protected $mockStatement;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->mockStatement = $this->createMock(\PDOStatement::class);
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
        Articles::setDBForTests($this->mockPDO);
    }
    
    /**
     * Test incrementing view counter for an article
     */
    public function testViewCounter(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willReturn(true);

        Articles::addOneView(1);
        
        // Since there's no return value to assert, we're just verifying the mock expectations
        $this->assertTrue(true);
    }
    
    /**
     * Test incrementing view counter for an invalid article ID
     */
    public function testViewCounterWithInvalidArticleId(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([0]) // Invalid ID
            ->willReturn(false); // Simulate failure

        Articles::addOneView(0);
        
        // Since the method doesn't return anything, we're just verifying the mock expectations
        $this->assertTrue(true);
    }
    
    /**
     * Test handling database error when incrementing view counter
     */
    public function testViewCounterWithDatabaseError(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([1])
            ->willThrowException(new \PDOException('Database error'));
        
        // Since the Articles model doesn't have internal exception handling,
        // we expect the exception to bubble up
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('Database error');
        
        Articles::addOneView(1);
    }
    
    /**
     * Test multiple view increments for the same article
     */
    public function testMultipleViewIncrements(): void {
        // For this test, we'll verify that the execute method is called with the same article ID
        $this->mockStatement->expects($this->exactly(2))
            ->method('execute')
            ->with([1])
            ->willReturn(true);
        
        // Increment view twice
        Articles::addOneView(1);
        Articles::addOneView(1);
        
        // The assertions are handled by the mock expectations
        $this->assertTrue(true);
    }
}
