<?php

namespace App\Models;

use App\Db\Connection;
use Exception;
use PDO;

class Database
{
    protected $pdo;

    public function __construct()
    {
        try {
            $this->pdo = Connection::getConnection();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     *    get several objects from database
     *    @param string $query
     *    return object
     */
    public function GetAll(string $query): array
    {
        try {
            // prepare statement
            $stmt = $this->pdo->prepare($query);
            // execute the statement.
            $stmt->execute();
            // returns a list of type Class User
            return $stmt->fetch(PDO::FETCH_CLASS, 'User');
        } catch (Exception $e) {
            // send an error for there was an error with the inserted query
            throw new Exception($e->getMessage());
        }
    }
}
