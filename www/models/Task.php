<?php

namespace App\Models;

class Task extends Database
{

    /**
     * mettre à jour une tache
     * @param(int) correspond à l'id de la tache
     */
    public function updateTask(int $id, array $array){
        try{
            $stmt= $this->pdo->prepare("UPDATE task SET title=?, description=?, start_date=?, end_date=?, pilot=?, sector=? WHERE id=?");
            $stmt->execute();
            $task = $stmt->fetchObject();
        }
        catch(Exception $e){
            throw $e;
        }

    }
}

?>