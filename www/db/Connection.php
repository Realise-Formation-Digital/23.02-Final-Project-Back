<?php
namespace App\db;
require_once '../vendor/autoload.php';

use Exception;
use PDO;

// require_once("../config.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv ->  load();



class Connection
{
    private static ?PDO $connection = null;

    /**
     * Private constructor (singleton pattern)
     */
    private function __construct()
    {
    }

    /**
     * Static method to get PDO connection
     * @throws Exception
     */
    public static function getConnection(): ?PDO
    {
        if (is_null(self::$connection)) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . HOST_NAME . ";dbname=" . DB_NAME . ";charset=utf8",
                    USER_NAME,
                    PASSWORD
                );
            } catch (Exception $e) {
                throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
