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
    
    /**
     * Find cities within a radius (in km) of a given city
     * 
     * @param int $cityId ID of the center city
     * @param float $radiusKm Radius in kilometers
     * @return array Array of city IDs within the radius
     * @throws Exception
     */
    public static function findCitiesWithinRadius($cityId, $radiusKm) {
        $db = static::getDB();
        
        // First, get the coordinates of the center city
        $centerCity = self::getById($cityId);
        
        if (!$centerCity) {
            return [];
        }
        
        $centerLat = $centerCity['ville_latitude_deg'];
        $centerLng = $centerCity['ville_longitude_deg'];
        
        // If no coordinates, return empty array
        if (!$centerLat || !$centerLng) {
            return [];
        }
        
        // Use the Haversine formula to calculate distances
        // Earth radius in kilometers
        $earthRadius = 6371;
        
        $sql = "
            SELECT ville_id FROM villes_france
            WHERE (
                :radius >= :earth_radius * ACOS(
                    COS(RADIANS(:center_lat)) * COS(RADIANS(ville_latitude_deg)) * COS(RADIANS(:center_lng) - RADIANS(ville_longitude_deg)) +
                    SIN(RADIANS(:center_lat)) * SIN(RADIANS(ville_latitude_deg))
                )
            )
        ";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':radius', $radiusKm, \PDO::PARAM_STR);
        $stmt->bindParam(':earth_radius', $earthRadius, \PDO::PARAM_STR);
        $stmt->bindParam(':center_lat', $centerLat, \PDO::PARAM_STR);
        $stmt->bindParam(':center_lng', $centerLng, \PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
    }
}
