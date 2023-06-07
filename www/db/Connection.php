<?php
namespace App\db;

use Exception;
use PDO;
use Dotenv;

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../')->load();

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
                    "mysql:host=" . getenv('HOST_NAME') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8",
                    getenv('USER_NAME'),
                    getenv('PASSWORD')
                );
            } catch (Exception $e) {
                throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
