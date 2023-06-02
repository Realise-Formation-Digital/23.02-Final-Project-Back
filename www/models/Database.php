<?php

require_once("../db/Connection.php");

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