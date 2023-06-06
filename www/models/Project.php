<?php

namespace App\models;

use Exception;
use PDO;

class Project extends Database{
    
    public function delete($id){
        try{
            $stmt=$this->pdo->prepare("DELETE FROM project WHERE id=?");
            $stmt->execute([$id]);
        
            return ["message"=>"Le projet a bien été supprimé"];
        }
        catch(Exception $e){
            throw $e;
        }
    }
}