<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{
    /**
     * Custom PDO connection for testing
     */
    protected static $testDB = null;

    /**
     * Set a custom PDO connection for testing
     * 
     * @param PDO $db PDO connection
     * @return void
     */
    public static function setTestDB(PDO $db)
    {
        static::$testDB = $db;
    }

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        // If a test database connection is set, use it
        if (static::$testDB !== null) {
            return static::$testDB;
        }

        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}
