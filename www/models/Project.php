<?php


namespace App\models;

use AllowDynamicProperties;
use Exception;
use PDO;
use OpenApi\Attributes as OA;

#[AllowDynamicProperties]
#[OA\Schema(
    schema: "Project",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "copil_list", type: "array", items: new OA\Items("#/components/schemas/User"))
    ]
)]
#[OA\Schema(
    schema: "Project_By_Id",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "status_columns", type: "array", items: new OA\Items("#/components/schemas/StatusColumn")),
        new OA\Property(property: "copil_list", type: "array", items: new OA\Items("#/components/schemas/User"))
    ]
)]
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
     * Read a project with status columns, copil list and tasks
     *
     * @param int $id
     * @return Project
     * @throws Exception
     */
    #[OA\Get(
        path: '/projects/{id}',
    )]
    #[OA\Parameter(
        name: 'id',
        description: "Project id",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "integer"
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Get project by id',
        content: new OA\JsonContent(
            ref: '#/components/schemas/Project_By_Id'
        )
    )]
   public function read(int $id): Project
   {
      try {
         // TESTS IF THE PROJECT EXISTS IN THE DATABASE
         $project = $this->getProjectById($id);

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
         throw new Exception($e->getMessage(), 500);
      }
   }

    /**
     * Create project
     *
     * @param Project $prjct
     * @return Project
     * @throws Exception
     */
    #[OA\Post(
        path: '/projects',
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "title",
                    type: "string",
                ),
                new OA\Property(
                    property: "copil_list",
                    type: "array",
                    items: new OA\Items(type: "integer")
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Create project',
        content: new OA\JsonContent(
            ref: '#/components/schemas/Project'
        )
    )]
   public function create(Project $prjct): Project
   {
      try {
         // COPIL LIST AS ARRAY OF INTEGER
         $copil = $prjct->getCopilList();

         // INSERTION OF PROJECT INTO THE DATABASE
         $stmt = $this->pdo->prepare("INSERT INTO project (title, status) VALUES (:title, :status)");
         $stmt->execute([
            "title" => $prjct->getTitle(),
            "status" => $prjct->getStatus()
         ]);

         //get new id and add to task object
         $id = $this->pdo->lastInsertId();
         $prjct->setId($id);

         // INSERTION OF TABLE RELATIONS ON USERS <---> PROJECT
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
         throw new Exception($e->getMessage(), 500);
      }
   }


    /**
     * Get all projects
     *
     * @return array
     * @throws Exception
     */
    #[OA\Get(
        path: '/projects',
    )]
    #[OA\Response(
        response: 200,
        description: 'Get project by id',
        content: new OA\JsonContent(
            ref: '#/components/schemas/Project'
        )
    )]
    public function search(): array
    {
        try {
            $stmtGetProjects = $this->pdo->prepare("SELECT id, title FROM project WHERE status = :status");
            $stmtGetProjects->execute([
                'status' => 'inProgress'
            ]);
            //SET all attributes class 
            $projects = $stmtGetProjects->fetchAll(PDO::FETCH_CLASS, "App\models\Project");

         //loops through project table and GET users's ids for each project
         foreach ($projects as $project) {
            //obtain project and users ids
            $users = $project->getUsersByProjectId($project->getId());
            //add object user to project
            $project->setCopilList($users);
         }
         return $projects;
      } catch (Exception $e) {
         throw new Exception($e);
      }
   }


    /**
     * Update project by id
     *
     * @param int $id
     * @param Project $project
     * @return Project
     * @throws Exception
     */
    #[OA\Put(
        path: '/projects/{id}',
    )]
    #[OA\Parameter(
        name: 'id',
        description: "Project id",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "integer"
        )
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "title",
                    type: "string",
                ),
                new OA\Property(
                    property: "status",
                    type: "string",
                ),
                new OA\Property(
                    property: "copil_list",
                    type: "array",
                    items: new OA\Items(type: "integer")
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Update project by id',
        content: new OA\JsonContent(
            ref: '#/components/schemas/Project'
        )
    )]
   public function update(int $id, Project $project): Project
   {
      try {
         // TESTS IF THE PROJECT EXISTS IN THE DATABASE
         $this->getProjectById($id);

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
         $project->setCopilList($users);


         return $project;
      } catch (Exception $e) {
         throw new Exception($e->getMessage(), 500);
      }
   }

   public function getUsersByProjectId(int $projectId): array
   {
      // get users (= copil list) from project
      $stmt = $this->pdo->prepare('SELECT user.id, user.last_name, user.first_name, user.image FROM project_user JOIN user ON project_user.user_id = user.id WHERE project_user.project_id = :project_id');
      $stmt->execute([
         'project_id' => $projectId
      ]);
      return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
   }

    /**
     * Delete project by id
     *
     * @param $id
     * @return string[]
     * @throws Exception
     */
    #[OA\Delete(
        path: '/projects/{id}',
    )]
    #[OA\Parameter(
        name: 'id',
        description: "Project id",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "integer"
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Delete project by id',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "message", type: "string", example: "Le projet a bien été supprimé")
            ]
        )
    )]
   public function delete($id)
   {
      try {
         // TESTS IF THE PROJECT EXISTS IN THE DATABASE
         $this->getProjectById($id);

         $stmt = $this->pdo->prepare("DELETE FROM project WHERE id=?");
         $stmt->execute([$id]);

         return ["message" => "Le projet a bien été supprimé"];
      } catch (Exception $e) {
         throw new Exception($e->getMessage(), 500);
      }
   }

   /**
    * Get one project from the database with the ID inserted
    * arguments: project ID
    * returns an element of type Project
    */
   private function getProjectById(int $id): Project
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

         return $project;
      } catch (Exception $e) {
         throw new Exception($e->getMessage(), 500);
      }
   }
}
