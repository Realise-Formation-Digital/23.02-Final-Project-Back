<?php

namespace App\Models;

use Exception;
use PDO;

class User extends Database
{
   protected $first_name;
   protected $last_name;
   protected $image;

   // GETTER FUNCTIONS
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
      $this->image = $f_name;
   }

   public function set_last_name($l_name)
   {
      $this->image = $l_name;
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
         return $stmt->fetch(PDO::FETCH_CLASS, 'User');
      } catch (Exception $e) {
         // send an error for there was an error with the inserted query
         throw new Exception($e->getMessage());
      }
   }
}
