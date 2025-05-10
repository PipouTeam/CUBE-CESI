<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Models\Cities;
use \Core\View;
use Exception;

/**
 * API controller
 */
class Api extends \Core\Controller
{

    /**
     * Affiche la liste des articles / produits pour la page d'accueil
     *
     * @throws Exception
     */
    public function ProductsAction()
    {
        $query = $_GET['sort'];

        $articles = Articles::getAll($query);
        
        // Add city information to each article
        foreach ($articles as &$article) {
            if (isset($article['ville_id'])) {
                $city = Cities::getById($article['ville_id']);
                if ($city) {
                    $article['city'] = $city;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($articles);
    }

    /**
     * Recherche dans la liste des villes
     *
     * @throws Exception
     */
    public function CitiesAction(){

        $cities = Cities::search($_GET['query']);

        header('Content-Type: application/json');
        echo json_encode($cities);
    }
    
    /**
     * Récupère les informations d'une ville par son ID
     *
     * @throws Exception
     */
    public function CityAction(){
        if (!isset($_GET['id'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Missing city ID']);
            return;
        }
        
        $cityId = (int)$_GET['id'];
        $city = Cities::getById($cityId);
        
        if (!$city) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'City not found']);
            return;
        }
        
        header('Content-Type: application/json');
        echo json_encode($city);
    }
    
    /**
     * Récupère les articles dans un rayon autour d'une ville
     *
     * @throws Exception
     */
    public function ProductsNearbyAction(){
        if (!isset($_GET['city_id']) || !isset($_GET['radius'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Missing city_id or radius parameter']);
            return;
        }
        
        $cityId = (int)$_GET['city_id'];
        $radius = (float)$_GET['radius'];
        $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
        
        // Validate radius (between 1 and 100 km)
        $radius = max(1, min(100, $radius));
        
        $articles = Articles::getWithinRadius($cityId, $radius, $sort);
        
        // Add city information to each article
        foreach ($articles as &$article) {
            if (isset($article['ville_id'])) {
                $city = Cities::getById($article['ville_id']);
                if ($city) {
                    $article['city'] = $city;
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($articles);
    }
    
    /**
     * Récupère tous les articles avec leurs coordonnées géographiques pour la carte
     *
     * @throws Exception
     */
    public function ProductsMapAction(){
        $articles = Articles::getAll('');
        $articlesWithCoordinates = [];
        
        // Add city information with coordinates to each article
        foreach ($articles as $article) {
            if (isset($article['ville_id'])) {
                $city = Cities::getById($article['ville_id']);
                if ($city && $city['ville_latitude_deg'] && $city['ville_longitude_deg']) {
                    $article['coordinates'] = [
                        'lat' => (float)$city['ville_latitude_deg'],
                        'lng' => (float)$city['ville_longitude_deg']
                    ];
                    $article['city'] = $city;
                    $articlesWithCoordinates[] = $article;
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($articlesWithCoordinates);
    }
    
    /**
     * Récupère les limites géographiques d'une ville
     * 
     * Note: Pour obtenir les vraies frontières des villes françaises, il faudrait:
     * 1. Utiliser une API externe comme l'API Geo du gouvernement français
     * 2. Ou télécharger les données GeoJSON des communes françaises depuis data.gouv.fr
     * 3. Ou stocker les polygones des frontières dans notre base de données
     *
     * @throws Exception
     */
    public function CityBoundaryAction(){
        $cityId = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$cityId) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'City ID is required']);
            return;
        }
        
        $city = Cities::getById($cityId);
        
        if (!$city) {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => 'City not found']);
            return;
        }
        
        // Get city center coordinates
        $lat = (float)$city['ville_latitude_deg'];
        $lng = (float)$city['ville_longitude_deg'];
        
        // Pour une implémentation complète, nous aurions besoin d'utiliser:
        // 1. L'API Geo (https://geo.api.gouv.fr/communes) pour obtenir les contours des communes
        // 2. Ou stocker les données GeoJSON des communes dans notre base de données
        
        // Check if this is Paris (special case with hardcoded boundary approximation)
        if (strtolower($city['ville_nom_reel']) === 'paris') {
            // Approximate Paris boundary (simplified version of the périphérique)
            // These are rough coordinates of points around Paris's border
            $points = [
                [2.224, 48.815], // South-West
                [2.224, 48.840],
                [2.255, 48.865],
                [2.270, 48.880],
                [2.300, 48.900], // North-West
                [2.340, 48.910],
                [2.380, 48.905],
                [2.410, 48.895], // North-East
                [2.435, 48.875],
                [2.445, 48.850],
                [2.420, 48.820], // South-East
                [2.380, 48.810],
                [2.340, 48.810],
                [2.300, 48.810],
                [2.260, 48.810],
                [2.224, 48.815]  // Back to start
            ];
        } else {
            // For other cities, create a more natural boundary based on population
            $points = [];
            
            // Adjust radius based on city population (larger cities have larger boundaries)
            $population = isset($city['ville_population_2012']) ? (int)$city['ville_population_2012'] : 10000;
            $baseRadius = 0.01; // Base radius (about 1km)
            
            // Scale radius based on population (logarithmic scale)
            $radius = $baseRadius * (1 + log10(max($population, 1000) / 1000) * 0.5);
            
            // Create a slightly irregular shape to look more natural
            $steps = 36; // number of points
            for ($i = 0; $i <= $steps; $i++) {
                $angle = ($i / $steps) * 2 * M_PI;
                // Add some randomness to make the shape irregular
                $radiusVariation = $radius * (0.9 + 0.2 * ((sin($angle * 3) + cos($angle * 5)) / 2));
                $pointLng = $lng + $radiusVariation * cos($angle);
                $pointLat = $lat + $radiusVariation * sin($angle);
                $points[] = [$pointLng, $pointLat];
            }
        }
        
        $geoJson = [
            'type' => 'Feature',
            'properties' => [
                'id' => $city['ville_id'],
                'name' => $city['ville_nom_reel'],
                'postal_code' => $city['ville_code_postal']
            ],
            'geometry' => [
                'type' => 'Polygon',
                'coordinates' => [$points]
            ]
        ];
        
        header('Content-Type: application/json');
        echo json_encode($geoJson);
    }
}
