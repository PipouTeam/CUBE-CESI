<?php

namespace App\Models;

use Core\Model;
use App\Core;
use DateTime;
use Exception;
use App\Utility;
use PDO;

/**
 * Articles Model
 */
class Articles extends Model {

    public static $db;

    public static function setDBForTests(PDO $db)
    {
        self::$db = $db;
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getAll($filter) {
        $db = static::getDB();

        $query = 'SELECT * FROM articles ';

        switch ($filter){
            case 'views':
                $query .= ' ORDER BY articles.views DESC';
                break;
            case 'data':
                $query .= ' ORDER BY articles.published_date DESC';
                break;
            case '':
                break;
        }

        $stmt = $db->query($query);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getOne($id) {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT * FROM articles
            INNER JOIN users ON articles.user_id = users.id
            WHERE articles.id = ? 
            LIMIT 1');

        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function addOneView($id) {
        $db = self::$db ?? static::getDB();

        $stmt = $db->prepare('
            UPDATE articles 
            SET articles.views = articles.views + 1
            WHERE articles.id = ?');

        $stmt->execute([$id]);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getByUser($id) {
        $db = self::$db ?? static::getDB();

        $stmt = $db->prepare('
            SELECT *, articles.id as id FROM articles
            LEFT JOIN users ON articles.user_id = users.id
            WHERE articles.user_id = ?');

        $stmt->execute([$id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function getSuggest() {
        $db = static::getDB();

        $stmt = $db->prepare('
            SELECT *, articles.id as id FROM articles
            INNER JOIN users ON articles.user_id = users.id
            ORDER BY published_date DESC LIMIT 10');

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function save($data) {
        $db = self::$db ?? static::getDB();

        $stmt = $db->prepare('INSERT INTO articles(name, description, user_id, published_date) VALUES (:name, :description, :user_id,:published_date)');

        $published_date =  new DateTime();
        $published_date = $published_date->format('Y-m-d');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':published_date', $published_date);
        $stmt->bindParam(':user_id', $data['user_id']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public static function attachPicture($articleId, $pictureName){
        $db = self::$db ?? static::getDB();

        $stmt = $db->prepare('UPDATE articles SET picture = :picture WHERE articles.id = :articleid');

        $stmt->bindParam(':picture', $pictureName);
        $stmt->bindParam(':articleid', $articleId);


        $stmt->execute();
    }

    /**
     * Get articles within a radius of a given city
     * 
     * @param int $cityId ID of the center city
     * @param float $radiusKm Radius in kilometers
     * @param string $filter Optional filter for sorting
     * @return array Array of articles within the radius
     * @throws Exception
     */
    public static function getWithinRadius($cityId, $radiusKm, $filter = '') {
        $db = static::getDB();
        
        // Get cities within the radius
        $citiesInRadius = Cities::findCitiesWithinRadius($cityId, $radiusKm);
        
        if (empty($citiesInRadius)) {
            return [];
        }
        
        // Convert array of city IDs to a comma-separated string for the IN clause
        $cityIdsString = implode(',', $citiesInRadius);
        
        $query = "SELECT * FROM articles WHERE ville_id IN ($cityIdsString)";
        
        switch ($filter){
            case 'views':
                $query .= ' ORDER BY articles.views DESC';
                break;
            case 'date':
                $query .= ' ORDER BY articles.published_date DESC';
                break;
            case '':
                break;
        }
        
        $stmt = $db->query($query);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
