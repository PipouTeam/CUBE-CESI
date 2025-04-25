<?php

namespace App\Utility;

class Flash {

    //Danger() stocke le message d'erreur dans la session
    public static function danger($message) {
        $_SESSION['flash_error'] = $message;
    }

    //getError affiche le message d'erreur stocké dans une page html, puis le supprime de la session
    public static function getError() {
        if (isset($_SESSION['flash_error'])) {
            $msg = $_SESSION['flash_error'];
            unset($_SESSION['flash_error']);
            return $msg;
        }
        return null;
    }
}