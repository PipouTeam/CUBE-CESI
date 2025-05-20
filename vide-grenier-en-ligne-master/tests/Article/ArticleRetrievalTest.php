<?php
declare(strict_types=1);

namespace Tests\Article;

use PHPUnit\Framework\TestCase;
use App\Models\Articles;
use PDO;

/**
 * Test version of Articles class that overrides the database connection
 */
class TestArticles extends Articles {
    protected static $testDB;
    
    public static function setTestDB(PDO $db) {
        self::$testDB = $db;
    }
    
    protected static function getDB() {
        return self::$testDB;
    }
}

/**
 * Article Retrieval Tests
 * 
 * Tests for retrieving articles functionality
 */
class ArticleRetrievalTest extends TestCase {
    protected $mockPDO;
    protected $mockStatement;

    protected function setUp(): void {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->mockStatement = $this->createMock(\PDOStatement::class);
        $this->mockPDO->method('prepare')->willReturn($this->mockStatement);
        TestArticles::setTestDB($this->mockPDO);
    }
    
    /**
     * Test retrieving articles by user
     */
    public function testRetrievingArticlesByUser(): void {
        // For getByUser we need to mock the prepare method differently
        // since it uses self::$db ?? static::getDB()
        $mockPDO = $this->createMock(\PDO::class);
        $mockStatement = $this->createMock(\PDOStatement::class);
        
        $mockPDO->method('prepare')
            ->with($this->stringContains('WHERE articles.user_id = ?'))
            ->willReturn($mockStatement);
        
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
        
        // Set the static property directly
        $reflectionClass = new \ReflectionClass(Articles::class);
        $reflectionProperty = $reflectionClass->getProperty('db');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null, $mockPDO);

        $articles = Articles::getByUser(1);
        
        // Reset the static property after the test
        $reflectionProperty->setValue(null, null);

        $this->assertCount(2, $articles);
        $this->assertEquals('Article 1', $articles[0]['name']);
        $this->assertEquals('Article 2', $articles[1]['name']);
    }
    
    /**
     * Test retrieving articles for a non-existent user
     */
    public function testGetArticlesByNonExistentUser(): void {
        // For getByUser we need to mock the prepare method differently
        // since it uses self::$db ?? static::getDB()
        $mockPDO = $this->createMock(\PDO::class);
        $mockStatement = $this->createMock(\PDOStatement::class);
        
        $mockPDO->method('prepare')
            ->with($this->stringContains('WHERE articles.user_id = ?'))
            ->willReturn($mockStatement);
        
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([999]) // Non-existent user ID
            ->willReturn(true);
        
        $mockStatement->method('fetchAll')
            ->willReturn([]); // Empty result for non-existent user
        
        // Set the static property directly
        $reflectionClass = new \ReflectionClass(Articles::class);
        $reflectionProperty = $reflectionClass->getProperty('db');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null, $mockPDO);
        
        $articles = Articles::getByUser(999);
        
        // Reset the static property after the test
        $reflectionProperty->setValue(null, null);
        
        $this->assertEmpty($articles);
    }
    
    /**
     * Test retrieving all articles with no filter
     */
    public function testGetAllArticlesNoFilter(): void {
        // Create a mock PDO and query statement
        $mockQuery = $this->createMock(\PDOStatement::class);
        
        // Set up the mock to return our mock statement
        $this->mockPDO->method('query')
            ->with('SELECT * FROM articles ')
            ->willReturn($mockQuery);
        
        $mockQuery->method('fetchAll')
            ->willReturn([
                [
                    'id' => 1,
                    'name' => 'Article 1',
                    'description' => 'Description 1',
                    'user_id' => 1
                ],
                [
                    'id' => 2,
                    'name' => 'Article 2',
                    'description' => 'Description 2',
                    'user_id' => 2
                ]
            ]);
        
        // Now we can test the getAll method
        $articles = TestArticles::getAll('');
        
        // Assert the results
        $this->assertCount(2, $articles);
        $this->assertEquals('Article 1', $articles[0]['name']);
        $this->assertEquals('Article 2', $articles[1]['name']);
    }
    
    /**
     * Test retrieving all articles with views filter
     */
    public function testGetAllArticlesWithViewsFilter(): void {
        // Create a mock query statement
        $mockQuery = $this->createMock(\PDOStatement::class);
        
        // Set up the mock to return our mock statement
        $this->mockPDO->method('query')
            ->with('SELECT * FROM articles  ORDER BY articles.views DESC')
            ->willReturn($mockQuery);
        
        $mockQuery->method('fetchAll')
            ->willReturn([
                [
                    'id' => 2,
                    'name' => 'Popular Article',
                    'description' => 'Description 2',
                    'user_id' => 2,
                    'views' => 100
                ],
                [
                    'id' => 1,
                    'name' => 'Less Popular Article',
                    'description' => 'Description 1',
                    'user_id' => 1,
                    'views' => 50
                ]
            ]);
        
        // Now we can test the getAll method with views filter
        $articles = TestArticles::getAll('views');
        
        // Assert the results
        $this->assertCount(2, $articles);
        $this->assertEquals('Popular Article', $articles[0]['name']);
        $this->assertEquals('Less Popular Article', $articles[1]['name']);
    }
    
    /**
     * Test retrieving all articles with date filter
     */
    public function testGetAllArticlesWithDateFilter(): void {
        // Create a mock query statement
        $mockQuery = $this->createMock(\PDOStatement::class);
        
        // Set up the mock to return our mock statement
        $this->mockPDO->method('query')
            ->with('SELECT * FROM articles  ORDER BY articles.published_date DESC')
            ->willReturn($mockQuery);
        
        $mockQuery->method('fetchAll')
            ->willReturn([
                [
                    'id' => 2,
                    'name' => 'Newer Article',
                    'description' => 'Description 2',
                    'user_id' => 2,
                    'published_date' => '2025-05-14'
                ],
                [
                    'id' => 1,
                    'name' => 'Older Article',
                    'description' => 'Description 1',
                    'user_id' => 1,
                    'published_date' => '2025-05-10'
                ]
            ]);
        
        // Now we can test the getAll method with date filter
        $articles = TestArticles::getAll('data');
        
        // Assert the results
        $this->assertCount(2, $articles);
        $this->assertEquals('Newer Article', $articles[0]['name']);
        $this->assertEquals('Older Article', $articles[1]['name']);
    }
    
    /**
     * Test retrieving a single article by ID
     */
    public function testGetOneArticle(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([5])
            ->willReturn(true);
        
        $this->mockStatement->method('fetchAll')
            ->willReturn([
                [
                    'id' => 5,
                    'name' => 'Single Article',
                    'description' => 'This is a single article',
                    'user_id' => 3,
                    'username' => 'TestUser'
                ]
            ]);
        
        // Now we can test the getOne method
        $article = TestArticles::getOne(5);
        
        // Assert the results
        $this->assertCount(1, $article);
        $this->assertEquals('Single Article', $article[0]['name']);
        $this->assertEquals('TestUser', $article[0]['username']);
    }
    
    /**
     * Test retrieving a non-existent article
     */
    public function testGetOneNonExistentArticle(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->with([999])
            ->willReturn(true);
        
        $this->mockStatement->method('fetchAll')
            ->willReturn([]); // Empty result for non-existent article
        
        // Now we can test the getOne method with non-existent article
        $article = TestArticles::getOne(999);
        
        // Assert the results
        $this->assertEmpty($article);
    }
    
    /**
     * Test retrieving suggested articles
     */
    public function testGetSuggestedArticles(): void {
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->method('fetchAll')
            ->willReturn([
                [
                    'id' => 1,
                    'name' => 'Suggested Article 1',
                    'description' => 'Description 1',
                    'user_id' => 1,
                    'username' => 'User1'
                ],
                [
                    'id' => 2,
                    'name' => 'Suggested Article 2',
                    'description' => 'Description 2',
                    'user_id' => 2,
                    'username' => 'User2'
                ]
            ]);
        
        // Now we can test the getSuggest method
        $articles = TestArticles::getSuggest();
        
        // Assert the results
        $this->assertCount(2, $articles);
        $this->assertEquals('Suggested Article 1', $articles[0]['name']);
        $this->assertEquals('Suggested Article 2', $articles[1]['name']);
    }
}
