<?php

namespace App\Utility;

class Flash {
public static function danger($message) {
$_SESSION['flash_error'] = $message;
}

public static function getError() {
if (isset($_SESSION['flash_error'])) {
$msg = $_SESSION['flash_error'];
unset($_SESSION['flash_error']);
return $msg;
}
return null;
}
}