<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Upload;
use \Core\View;

/**
 * Product controller
 */
class Product extends \Core\Controller
{

    /**
     * Affiche la page d'ajout
     * @return void
     * @throws \Exception
     */
    public function indexAction()
    {
        if (isset($_POST['submit'])) {
            try {
                // AK : vérification si il y a une image avant de POST
                $pictureName = Upload::uploadFile($_FILES['picture'], uniqid());

                $f = $_POST;

                $f['user_id'] = $_SESSION['user']['id'];
                $id = Articles::save($f);

                Articles::attachPicture($id, $pictureName);

                header('Location: /product/' . $id);
                exit;

            } catch (\Exception $e) {
                // Gestion de l'erreur : log, affichage, redirection...
                echo 'Erreur : ' . $e->getMessage();
            }
        }

        View::renderTemplate('Product/Add.html');
    }


    /**
     * Affiche la page d'un produit
     * @return void
     */
    public function showAction()
    {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
        } catch(\Exception $e){
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions
        ]);
    }
}
