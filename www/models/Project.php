<?php

namespace App\models;

use AllowDynamicProperties;
use Exception;
use PDO;

#[AllowDynamicProperties]
class Project extends Database
{
   private ?int $id;

   private string $title;

   private string $status = "inProgress";

   private array $status_columns = [];

   private array $copil_list = [];

   /**
    * @return int|null
    */
   public function getId(): ?int
   {
      return $this->id;
   }

   /**
    * @param int|null $id
    */
   public function setId(?int $id): void
   {
      $this->id = $id;
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
    * @return string
    */
   public function getStatus(): string
   {
      return $this->status;
   }

   /**
    * @param string $status
    */
   public function setStatus(string $status): void
   {
      $this->status = $status;
   }

   /**
    * @return array
    */
   public function getStatusColumns(): array
   {
      return $this->status_columns;
   }

   /**
    * @param array $status_columns
    */
   public function setStatusColumns(array $status_columns): void
   {
      $this->status_columns = $status_columns;
   }

   /**
    * @return array
    */
   public function getCopilList(): array
   {
      return $this->copil_list;
   }

   /**
    * @param array $copil_list
    */
   public function setCopilList(array $copil_list): void
   {
      $this->copil_list = $copil_list;
   }

   /**
    * Method which read a project with status columns, copil list and tasks
    *
    * @param int $id
    * @return Project
    * @throws Exception
    */
   public function read(int $id): Project
   {
      try {
         // get project
         $stmt = $this->pdo->prepare('SELECT * FROM project WHERE id = :id');
         $stmt->execute([
            'id' => $id
         ]);
         $project = $stmt->fetchObject(Project::class);

         if (!$project) {
            throw new Exception("Le projet d'id $id n'existe pas.", 400);
         }

         // get users (= copil list) from project
         $users = $this->getUsersByProjectId($id);

         //add users to project
         $project->setCopilList($users);

         // get columns from project
         $stmt = $this->pdo->prepare('SELECT * FROM status_column WHERE project_id = :project_id ORDER BY position ASC');
         $stmt->execute([
            'project_id' => $id
         ]);
         $status_columns = $stmt->fetchAll(PDO::FETCH_CLASS, StatusColumn::class);

         // add tasks to columns
         foreach ($status_columns as &$status_column) {
            //get tasks for 1 column
            $stmt = $this->pdo->prepare('SELECT * FROM task WHERE status_column_id = :status_column_id ORDER BY end_date ASC');
            $stmt->execute([
               'status_column_id' => $status_column->getId()
            ]);
            $tasks = $stmt->fetchAll(PDO::FETCH_CLASS, Task::class);

            // add tasks to column
            $status_column->setTasks($tasks);
         }
         // add columns to project
         $project->setStatusColumns($status_columns);
         return $project;
      } catch (Exception $e) {
         throw $e;
      }
   }

   /**
    * arguments: variable class type Project
    * returns an element type project
    */
   public function create(Project $prjct): Project
   {
      try {
         $copil = $prjct->getCopilList();

         $stmt = $this->pdo->prepare("INSERT INTO project (title, status) VALUES (:title, :status)");
         $stmt->execute([
            "title" => $prjct->getTitle(),
            "status" => $prjct->getStatus()
         ]);

         //get new id and add to task object
         $id = $this->pdo->lastInsertId();
         $prjct->setId($id);

         foreach ($copil as $pilot) {
            $stmt = $this->pdo->prepare("INSERT INTO project_user (project_id, user_id) VALUES (:project_id, :user_id)");
            $stmt->execute([
               "project_id" => $id,
               "user_id" => $pilot
            ]);
         }

         // recover users as objects
         $users = $this->getUsersByProjectId($id);
         $prjct->setCopilList($users);

         // returns the last created object
         return $prjct;
      } catch (Exception $e) {
         throw $e;
      }
   }



   /**
    * Method which update project, persists in DB and return project object
    *
    * @param int $id
    * @param Project $project
    * @param array $copil_lis
    * @return Project
    * @throws Exception
    */
   public function update(int $id, Project $project): Project
   {
      try {
         $this->setId($id);
         $stmtUpdate = $this->pdo->prepare("UPDATE project SET title= :title, status= :status WHERE id= :id");
         $stmtUpdate->execute([
            "title" => $project->getTitle(),
            "status" => $project->getStatus(),
            "id" => $id
         ]);

         $stmtDelete = $this->pdo->prepare("DELETE FROM project_user WHERE project_id= ?");
         $stmtDelete->execute([$id]);

         foreach ($project->copil_list as $pilot) {
            $stmtInsert = $this->pdo->prepare("INSERT INTO project_user (project_id, user_id) VALUES (:project_id, :user_id)");
            $stmtInsert->execute([
               "project_id" => $id,
               "user_id" => $pilot
            ]);
         }
         // recover users as objects
         $users = $this->getUsersByProjectId($id);
         $prjct->setCopilList($users);

         // returns change object
         return $project;
      } catch (Exception $e) {
         throw $e;
      }

   }

   private function getUsersByProjectId(int $projectId): array
   {
      // get users (= copil list) from project
      $stmt = $this->pdo->prepare('SELECT user.id, user.last_name, user.first_name, user.image FROM project_user JOIN user ON project_user.user_id = user.id WHERE project_user.project_id = :project_id');
      $stmt->execute([
         'project_id' => $projectId
      ]);
      return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
   }
}
