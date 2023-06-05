<?php

namespace App\models;

use App\db\Connection;
use Exception;

class Database
{
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = Connection::getConnection();
        } catch(Exception $e) {
            throw $e;
        }
    }
}