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
}
