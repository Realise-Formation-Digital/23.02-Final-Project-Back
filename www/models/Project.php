<?php

namespace App\models;

use Exception;
use PDO;

class Project extends Database
{
   private $id;
   private $title;
   private $status;
   private $copil_list = [];

   /**
    * @return string
    */
   public function getId(): string
   {
      return $this->id;
   }

   /**
    * @param string $title
    */
   public function setId(string $id): void
   {
      $this->id = $id;
   }

   /**
    * @return string
    */
   public function getStatus(): string
   {
      return $this->id;
   }

   /**
    * @param string $title
    */
   public function setStatus(string $status): void
   {
      $this->id = $status;
   }

   /**
    * @return string
    */
   public function getTitle(): string
   {
      return $this->title;
   }

   /**
    * @param string $title
    */
   public function setTitle(string $title): void
   {
      $this->title = $title;
   }

   /**
    * @return array
    */
   public function getCopilList(): array
   {
      return $this->copil_list;
   }

   /**
    * @param string $description
    */
   public function setCopilList(array $copil_list): void
   {
      $this->copil_list = $copil_list;
   }

   public function getProjectById($id): Project | array
   {
      $stmt = $this->pdo->prepare("SELECT * FROM project WHERE id = :id");
      $stmt->execute(['id' => $id]);
      $project = $stmt->fetch(PDO::FETCH_CLASS, "App\models\Project");
      if (!$project) {
         return ['message' => "Le projet n'existe pas."];
      } else {
         return $project;
      }
   }

   public function create(Project $prjct): Project
   {
      try {
         $copil = $prjct->getCopilList();

         $stmt = $this->pdo->prepare("INSERT INTO kanban_db.project (title) VALUES (:title)");
         $stmt->execute([
            "title" => $prjct->getTitle(),
         ]);

         //get new id and add to task object
         $id = $this->pdo->lastInsertId();
         $prjct->setId($id);

         foreach ($copil as $pilot) {
            $stmt = $this->pdo->prepare("INSERT INTO kanban_db.project_user (project_id, user_id) VALUES (:project_id, :user_id)");
            $stmt->execute([
               "project_id" => $id,
               "user_id" => $pilot
            ]);
         }



         $prjct = $this->getProjectById($id);


         return $prjct;
      } catch (Exception $e) {
         throw $e;
      }
   }
}
