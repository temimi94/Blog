<?php


namespace App\Model;

use PDO;

/**
 * Class ConnectDB
 * @package App\Model
 */
class ConnectDB
{
    /**
     * Stores the Connection
     * @var null
     */
    private static $pdo = null;

    /**
     * Returns the Connection if it exists or creates it before returning it
     * @return PDO|null
     */
    public static function getPDO()
    {
        require_once '../config/database.php';
        if (self::$pdo === null) {
            self::$pdo = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
            self::$pdo->exec('SET NAMES UTF8');
        }
        return self::$pdo;
    }


}