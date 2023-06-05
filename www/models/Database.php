<?php

namespace App\Models;

use App\Db\Connection;
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