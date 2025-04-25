<?php

namespace App\Utility;

/**
 * Hash:
 */
class Hash {

    /**
     * Génère et retourne un hash
     */
    public static function generate($string, $salt = "") {
        if (trim($string) === '') {
            throw new \Exception("Un string est nécessaire.");
        }
        return(hash("sha256", $string . $salt));
    }

    /**
     * Génère et retourne un salt
     */
    public static function generateSalt($length) {
        if (!is_int($length) || $length < 1) {
            throw new \Exception("La longueur du salt doit être un entier positif.");
        }
        $salt = "";
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'\";:?.>,<!@#$%^&*()-_=+|";
        for ($i = 0; $i < $length; $i++) {
            $salt .= $charset[mt_rand(0, strlen($charset) - 1)];
        }
        return $salt;
    }

    /**
     * Génère et retourne un UID
     */
    public static function generateUnique() {
        return(self::generate(uniqid()));
    }

}
