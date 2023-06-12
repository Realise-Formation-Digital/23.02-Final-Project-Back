<?php

namespace App\models;

use AllowDynamicProperties;
use Exception;
use PDO;
use OpenApi\Attributes as OA;

#[AllowDynamicProperties]
#[OA\Schema(
    schema: "Task",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "description", type: "string"),
        new OA\Property(property: "start_date", type: "date"),
        new OA\Property(property: "end_date", type: "date"),
        new OA\Property(property: "pilot", ref: "#/components/schemas/User"),
        new OA\Property(property: "sector", type: "string")
    ]
)]
class Task extends Database
{
    private ?int $id;

    private string $title;

    private string $description;

    private string $start_date;

    private string $end_date;

    private User $pilot;

    private ?string $sector;

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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->start_date;
    }

    /**
     * @param string $start_date
     */
    public function setStartDate(string $start_date): void
    {
        $this->start_date = $start_date;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->end_date;
    }

    /**
     * @param string $end_date
     */
    public function setEndDate(string $end_date): void
    {
        $this->end_date = $end_date;
    }

    /**
     * @return User
     */
    public function getPilot(): User
    {
        return $this->pilot;
    }

    /**
     * @param User $pilot
     */
    public function setPilot(User $pilot): void
    {
        $this->pilot = $pilot;
    }

    /**
     * @return string|null
     */
    public function getSector(): ?string
    {
        return $this->sector;
    }

    /**
     * @param string|null $sector
     */
    public function setSector(?string $sector): void
    {
        $this->sector = $sector;
    }

    /**
     * Patch task by id (change task column)
     * 
     * @param int $id
     * @param int $status_column_id
     * @throws Exception
     */
    #[OA\Patch(
        path: '/tasks/{id}',
        tags: ['Task']
    )]
    #[OA\Parameter(
        name: 'id',
        description: "Task id",
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
                    property: "new_status_column_id",
                    type: "integer",
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Patch task by id',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "message", type: "string", example: "Le tâche a bien changé de colonne")
            ]
        )
    )]
    public function patch(int $id, int $status_column_id)
    {
        try {
            // test if id exists
            $this->testIfTaskExists($id);

            /*fetch and map id from task*/
            $stmtFetch = $this->pdo->prepare("SELECT * FROM task WHERE id=:id");
            $stmtFetch->execute([
                "id" => $id,
            ]);
            $task = $stmtFetch->fetch(PDO::FETCH_OBJ);

            // test if new column not same tha old
            if ($task->status_column_id == $status_column_id) {
                throw new Exception("Vous êtes resté dans la même colonne.", 400);
            }

            // test if new status column_id exists and has  has same project
            $stmtFetch = $this->pdo->prepare("SELECT * FROM status_column WHERE id=:status_column_id");
            $stmtFetch->execute([
                "status_column_id" => $status_column_id,
            ]);
            $new_column = $stmtFetch->fetch(PDO::FETCH_OBJ);

            //test if column exists
            if(!$new_column) {
                throw new Exception("La nouvelle colonne de statut d'id $status_column_id n'existe pas.", 400);
            }

            // get project id from old column
            $stmtFetch = $this->pdo->prepare("SELECT project_id FROM status_column WHERE id=:status_column_id");
            $stmtFetch->execute([
                "status_column_id" => $task->status_column_id,
            ]);
            $old_column = $stmtFetch->fetch(PDO::FETCH_OBJ);

            //test if column is in same project
            if ($new_column->project_id != $old_column->project_id) {
                throw new Exception("La nouvelle colonne n'appartient pas au même projet que l'ancienne colonne.", 400);
            }

            $stmtUpdate = $this->pdo->prepare("UPDATE task SET status_column_id=:status_column_id WHERE id = :id");
            $stmtUpdate->execute([
                "id" => $id,
                "status_column_id" => $status_column_id
            ]);

            return ["message" => "La tâche d'id $id a bien migré vers la colonne d'id $status_column_id"];
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Create a task
     *
     * @param Task $task
     * @param int $project_id
     * @param int $user_id
     * @return Task
     * @throws Exception
     */
    #[OA\Post(
        path: '/tasks',
        tags: ['Task']
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
                    property: "description",
                    type: "string",
                ),
                new OA\Property(
                    property: "start_date",
                    type: "date",
                ),
                new OA\Property(
                    property: "end_date",
                    type: "date",
                ),
                new OA\Property(
                    property: "pilot",
                    type: "integer",
                ),
                new OA\Property(
                    property: "sector",
                    type: "string",
                ),
                new OA\Property(
                    property: "project_id",
                    type: "integer",
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Create task',
        content: new OA\JsonContent(
            ref: '#/components/schemas/Task'
        )
    )]
    public function create(Task $task, int $project_id, int $user_id): Task
    {
        try {
            // GET THE LIST OF USERS WHO WERE ASSIGNED TO THE PROJECT WE'RE TRYING TO ADD THE TASK TO
            $associatedProject = new Project;
            $projectCopil[] = $associatedProject->getUsersByProjectId($project_id);
            $userIdList = [];

            // ISOLATES THE USER IDS FROM THE COPIL LIST
            foreach ($projectCopil[0] as $user) {
                $userIdList[] = $user->get_id();
            }

            // CHECKS IF THE USERS EXIST IN THE PROJECT, IF IT DOESNT THROW AN ERROR
            if (array_search($user_id, $userIdList) === false) {
                throw new Exception("Le pilote choisi n'appartient pas à ce projet, veuillez en choisir un valide.", 400);
            } else {
                $stmtSelectColPos = $this->pdo->prepare("SELECT id FROM status_column WHERE project_id = ? AND title = 'toDo'");
                $stmtSelectColPos->execute([$project_id]);
                $col_pos = $stmtSelectColPos->fetch(PDO::FETCH_OBJ);

                $stmt = $this->pdo->prepare("INSERT INTO task (title, description, start_date, end_date, sector, status_column_id, user_id) VALUES (:title, :description, :start_date, :end_date, :sector, :status_column_id, :user_id)");
                $stmt->execute([
                    "title" => $task->getTitle(),
                    "description" => $task->getDescription(),
                    "start_date" => $task->getStartDate(),
                    "end_date" => $task->getEndDate(),
                    "sector" => $task->getSector(),
                    "status_column_id" => $col_pos->id,
                    "user_id" => $user_id
                ]);

                //get new id and add to task object
                $id = $this->pdo->lastInsertId();
                $task->setId($id);

                //get pilot
                $pilot = $this->getPilotById($user_id);

                //add pilot to task
                $task->setPilot($pilot);

                return $task;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Update task
     *
     * @param int $id
     * @param Task $task
     * @param int $project_id
     * @param int $user_id
     * @return Task
     * @throws Exception
     */
    #[OA\Put(
        path: '/tasks/{id}',
        tags: ['Task']
    )]
    #[OA\Parameter(
        name: 'id',
        description: "Task id",
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
                    property: "description",
                    type: "string",
                ),
                new OA\Property(
                    property: "start_date",
                    type: "date",
                ),
                new OA\Property(
                    property: "end_date",
                    type: "date",
                ),
                new OA\Property(
                    property: "pilot",
                    type: "integer",
                ),
                new OA\Property(
                    property: "sector",
                    type: "string",
                ),
                new OA\Property(
                    property: "project_id",
                    type: "integer",
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Update task by id',
        content: new OA\JsonContent(
            ref: '#/components/schemas/Task'
        )
    )]
    public function update(int $id, Task $task, int $project_id, int $user_id): Task
    {
        try {
            //test if task exists
            $this->testIfTaskExists($id);

            // GET THE LIST OF USERS WHO WERE ASSIGNED TO THE PROJECT WE'RE TRYING TO ADD THE TASK TO
            $associatedProject = new Project;
            $projectCopil[] = $associatedProject->getUsersByProjectId($project_id);
            $userIdList = [];

            // ISOLATES THE USER IDS FROM THE COPIL LIST
            foreach ($projectCopil[0] as $user) {
                $userIdList[] = $user->get_id();
            }

            // CHECKS IF THE USERS EXIST IN THE PROJECT, IF IT DOESNT THROW AN ERROR
            if (array_search($user_id, $userIdList) === false) {
                throw new Exception("Le pilote choisi n'appartient pas à ce projet, veuillez en choisir un valide.", 400);
            } else {
                $this->setId($id);

                $stmt = $this->pdo->prepare("UPDATE task SET title= :title, description= :description, start_date= :start_date, end_date= :end_date, sector= :sector, user_id= :user_id WHERE id= :id");
                $stmt->execute([
                    "title" => $task->getTitle(),
                    "description" => $task->getDescription(),
                    "start_date" => $task->getStartDate(),
                    "end_date" => $task->getEndDate(),
                    "sector" => $task->getSector(),
                    "user_id" => $user_id,
                    "id" => $id
                ]);

                //get pilot
                $stmt = $this->pdo->prepare('SELECT * FROM user WHERE id = :user_id');
                $stmt->execute([
                    'user_id' => $user_id
                ]);

                //add pilot to task
                $pilot = $stmt->fetchObject(User::class);

                //add pilot to task
                $task->setPilot($pilot);

                return $task;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete task
     *
     * @param $id
     * @return string[]
     * @throws Exception
     */
    #[OA\Delete(
        path: '/tasks/{id}',
        tags: ['Task']
    )]
    #[OA\Parameter(
        name: 'id',
        description: "Task id",
        in: "path",
        required: true,
        schema: new OA\Schema(
            type: "integer"
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Delete task by id',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "message", type: "string", example: "La tâche a été correctement supprimée")
            ]
        )
    )]
    public function delete($id)
    {
        try {
            //test if task exists
            $this->testIfTaskExists($id);

            $stmt = $this->pdo->prepare("DELETE FROM task WHERE id=?");
            $stmt->execute([$id]);
            return ["message" => "La tache a été correctement supprimée"];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPilotById(int $id): User {
        try {
            //get pilot
            $stmt = $this->pdo->prepare('SELECT * FROM user WHERE id = :user_id');
            $stmt->execute([
                'user_id' => $id
            ]);

            //add pilot to task
            return $stmt->fetchObject(User::class);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function testIfTaskExists(int $id): void
    {
        try {
            // get task
            $stmt = $this->pdo->prepare('SELECT * FROM task WHERE id = :id');
            $stmt->execute([
                'id' => $id
            ]);
            $task = $stmt->fetchObject(Task::class);

            if (!$task) {
                throw new Exception("La tâche d'id $id n'existe pas.", 400);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
