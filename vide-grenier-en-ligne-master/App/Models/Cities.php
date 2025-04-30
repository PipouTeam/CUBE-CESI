<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * City Model:
 */
class Cities extends Model {

    public static function search($str) {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT ville_id FROM villes_france WHERE ville_nom_reel LIKE :query');

        $query = $str . '%';

        $stmt->bindParam(':query', $query);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    /**
     * Get city information by ID
     * 
     * @param int $id City ID
     * @return array|false City information or false if not found
     * @throws Exception
     */
    public static function getById($id) {
        $db = static::getDB();

        $stmt = $db->prepare('SELECT * FROM villes_france WHERE ville_id = :id LIMIT 1');
        
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
