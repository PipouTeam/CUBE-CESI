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

    //Ajouter pour les test unitaire 
    public static $db;

    public static function setDB($db) {
        self::$db = $db;
    }
    /////////////////////////////////

    /**
     * Crée un utilisateur
     */
    public static function createUser($data) {
        $db = self::$db ?? static::getDB();

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

    // Enregistre le token dans la db 
    public static function setRememberToken($userId, $token, $expiresAt)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
            INSERT INTO user_tokens (user_id, token, expires_at)
            VALUES (:user_id, :token, :expires_at)
        ");

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', $expiresAt);

        return $stmt->execute(); 
    }

    // recupere le token dans la db 
    public static function getUserByRememberToken($token)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
            SELECT users.* 
            FROM users 
            JOIN user_tokens ON user_tokens.user_id = users.id
            WHERE user_tokens.token = :token
            AND user_tokens.expires_at > NOW()
            LIMIT 1
        ");

        $stmt->bindParam(':token', $token);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user;
    }

    // Delete le token a la deconnexion 
    public static function deleteRememberToken($token)
    {
        $db = static::getDB();

        $stmt = $db->prepare("DELETE FROM user_tokens WHERE token = :token");
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
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
