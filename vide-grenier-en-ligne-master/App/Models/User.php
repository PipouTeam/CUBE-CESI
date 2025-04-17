<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * User Model:
 */
class User extends Model {

    /**
     * Crée un utilisateur
     */
    public static function createUser($data) {
        $db = static::getDB();

        try {
            // AK : Ajout d'une sécurité champ vide

            $requiredFields = ['username', 'email', 'password', 'salt'];

            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    throw new \Exception("Le champ '$field' est requis.");
                }
            }

            $stmt = $db->prepare('INSERT INTO users(username, email, password, salt) VALUES (:username, :email, :password,:salt)');


            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':salt', $data['salt']);

            $stmt->execute();

            return $db->lastInsertId();
        }

        //AK : ajout message d'erreur
        catch(\Exception $e) {
            \App\Utility\Flash::danger($e->getMessage());
            return false;
        }
    }

    public static function getByLogin($login)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
            SELECT * FROM users WHERE ( users.email = :email) LIMIT 1
        ");

        $stmt->bindParam(':email', $login);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function login($data)
    {
        $db = static::getDB();

        //AK : Correction du login qui faisait une requête sur les articles
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        //AK : Vérification du mot de passe avec le hachage
        if ($user) {
            // Recalcul du hash avec le salt, sha256 c'est l'algo de hachage utilisé
            $hash = hash('sha256', $data['password'] . $user['salt']);

            if ($hash === $user['password']) {
                return $user;
            }
        }

        return false;
    }



}
