<?php

namespace App\Models;

class User extends Database
{
   protected $first_name;
   protected $last_name;
   protected $image;

   // SEARCH ALL FUNCTION -----
   public function search_users(): array
   {
      return $this->GetAll("SELECT * FROM kanban_db.users");
   }
}
