<?php

namespace App\models;

use AllowDynamicProperties;
use Exception;
use PDO;

#[AllowDynamicProperties]
class User extends Database
{
   protected $id;
   protected $first_name;
   protected $last_name;
   protected $image;

   // GETTER FUNCTIONS
   public function get_id()
   {
      return $this->id;
   }

   public function get_first_name()
   {
      return $this->first_name;
   }

   public function get_last_name()
   {
      return $this->last_name;
   }

   public function get_image()
   {
      return $this->image;
   }

   // SETTER FUNCTIONS
   public function set_first_name($f_name)
   {
      $this->first_name = $f_name;
   }

   public function set_last_name($l_name)
   {
      $this->last_name = $l_name;
   }

   public function set_image($img)
   {
      $this->image = $img;
   }

   // SEARCH ALL FUNCTION -----
   public function search_users(): array
   {
      try {
         // prepare statement
         $stmt = $this->pdo->prepare('SELECT * FROM kanban_db.user');
         // execute the statement.
         $stmt->execute();
         // returns a list of type Class User 
         // $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
         return $stmt->fetchAll(PDO::FETCH_CLASS, "App\models\User");
      } catch (Exception $e) {
         // send an error for there was an error with the inserted query
         throw new Exception($e->getMessage());
      }
   }
}
