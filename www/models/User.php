<?php

namespace App\models;

use AllowDynamicProperties;
use Exception;
use PDO;
use OpenApi\Attributes as OA;

#[AllowDynamicProperties]
#[OA\Schema(
    schema: "User",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "first_name", type: "string"),
        new OA\Property(property: "last_name", type: "string"),
        new OA\Property(property: "image", type: "string")
    ]
)]
class User extends Database
{
   protected int $id;
   protected string $first_name;
   protected string $last_name;
   protected string $image;

   // GETTER FUNCTIONS
   public function get_id(): int
   {
      return $this->id;
   }

   public function get_first_name(): string
   {
      return $this->first_name;
   }

   public function get_last_name(): string
   {
      return $this->last_name;
   }

   public function get_image(): string
   {
      return $this->image;
   }

   // SETTER FUNCTIONS
   public function set_id(int $id)
   {
      $this->id = $id;
   }

   public function set_first_name(string $f_name)
   {
      $this->first_name = $f_name;
   }

   public function set_last_name(string $l_name)
   {
      $this->last_name = $l_name;
   }

   public function set_image(string $img)
   {
      $this->image = $img;
   }

    /**
     * Get all users
     *
     * @return array
     * @throws Exception
     */
    #[OA\Get(
        path: '/users',
        tags: ['User']
    )]
    #[OA\Response(
        response: 200,
        description: 'Get all users',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                ref: '#/components/schemas/User',
            )
        )
    )]
    public function search_users(): array
   {
      try {
         // prepare statement TO GET THE USERS FROM DATABASE ORDERED BY LAST NAME AND FIRST NAME
         $stmt = $this->pdo->prepare('SELECT * FROM kanban_db.user ORDER BY last_name, first_name ASC');
         // execute the statement.
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
      } catch (Exception $e) {
         // send an error for there was an error with the inserted query
          throw $e;
      }
   }
}
