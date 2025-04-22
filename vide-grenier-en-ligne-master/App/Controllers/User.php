<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;

/**
 * User controller
 */
class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;

            //AK : ajout de la validation login et mdp
            if ($this->login($f)) {
                header('Location: /account');
                exit;
            }
        }

        $error = \App\Utility\Flash::getError();

        View::renderTemplate('User/login.html', [
            'flash' => [
                'danger' => $error
            ]
        ]);

    }

    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if (isset($_POST['submit'])) {
            $f = $_POST;

            //AK : Ajout vérification mot de passe

            if ($f['password'] !== $f['password-check']) {
                \App\Utility\Flash::danger("Les mots de passe ne correspondent pas.");
            } else {
                if ($this->register($f)) {
                    $this->login($f);
                    header('Location: /account');
                    exit;
                }
            }
        }


        $error = \App\Utility\Flash::getError();

        View::renderTemplate('User/register.html', [
            'flash' => [
                'danger' => $error
            ]
        ]);
    }


    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }

    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);

            return $userID;

        // AK : Ajout des messages d'erreur avec Flash

        } catch (Exception $ex) {
            \App\Utility\Flash::danger($ex->getMessage());
            return false;
        }
    }

    private function login($data) {
        try {

            //AK : gestion des erreurs et ajout des messages Flash

            if (empty($data['email']) || empty($data['password'])) {
                throw new \Exception("Email et mot de passe requis.");
            }

            $user = \App\Models\User::getByLogin($data['email']);

            if (!$user) {
                throw new \Exception("Utilisateur non trouvé.");
            }

            $hashedPassword = \App\Utility\Hash::generate($data['password'], $user['salt']);

            if ($hashedPassword !== $user['password']) {
                throw new \Exception("Mot de passe incorrect.");
            }

            $hashedPassword = \App\Utility\Hash::generate($data['password'], $user['salt']);

            if ($hashedPassword !== $user['password']) {
                throw new \Exception("Mot de passe incorrect.");
            }

            $rememberMe = isset($data['remember']) && $data['remember'] === "1";
            if ($rememberMe) {
                $token = bin2hex(random_bytes(32));
                $expires = time() + 60 * 60 * 24 * 30;
            
                \App\Models\User::setRememberToken($user['id'], $token, date('Y-m-d H:i:s', $expires));
                setcookie('remember_me', $token, $expires, "/", "", false, true);
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];

            return true;

        } catch (\Exception $ex) {
            \App\Utility\Flash::danger($ex->getMessage());
            return false;
        }
    }



    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction() {

        /*
        if (isset($_COOKIE[$cookie])){
            // TODO: Delete the users remember me cookie if one has been stored.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L148
        }*/
        // Destroy all data registered to the session.

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, "/");
            unset($_COOKIE['remember_me']);
        }        

        session_destroy();

        header ("Location: /");

        return true;
    }

}
