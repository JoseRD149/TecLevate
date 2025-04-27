<?php

namespace TecLevate\Utils;
require_once __DIR__ . '/../../config/config.php';
use PDO;
use PDOException;

class Database{
    private static $connection;

    public static function connect(){
        if (!self::$connection) {
            $dsn = 'mysql:host=' . DB_HOST . ';port=3307;dbname=' . DB_NAME . ';charset=utf8';


            try {
                self::$connection = new PDO($dsn, DB_USER, DB_PASS);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
    public static function getConnection() {
        return self::connect();
    }
}
?>