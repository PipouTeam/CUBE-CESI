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
        // Créer des mocks pour PDO et PDOStatement
        $mockPDO = $this->createMock(PDO::class);
        $mockStatement = $this->createMock(PDOStatement::class);

        // Fixer la DB à utiliser pour ce test
        Articles::setDBForTests($mockPDO);

        // Simuler la préparation de la requête
        $mockPDO->method('prepare')->willReturn($mockStatement);

        // Simuler l'exécution de la requête
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([1]) // Vérifier que l'ID de l'utilisateur est passé à la méthode
            ->willReturn(true);

        // Simuler le retour de fetchAll pour renvoyer une liste d'articles fictifs
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

        // Appel de la méthode à tester
        $articles = Articles::getByUser(1);

        // Vérification que la méthode retourne bien les articles attendus
        $this->assertCount(2, $articles); // Il doit y avoir 2 articles
        $this->assertEquals('Article 1', $articles[0]['name']); // Vérifier le nom du premier article
        $this->assertEquals('Article 2', $articles[1]['name']); // Vérifier le nom du deuxième article
    }


}