<?php

namespace App\models;

use AllowDynamicProperties;
use Exception;
use PDO;

#[AllowDynamicProperties]
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
     * Method that change status task
     * 
     * @param int $id
     * @param int $status_column_id
     * @throws Exception
     */
    public function patch(int $id, int $status_column_id)
    {
        try {
            /*fetch and map id from task*/
            $stmtFetch = $this->pdo->prepare("SELECT * FROM task WHERE id=:id");
            $stmtFetch->execute([
                "id" => $id,
            ]);
            $task = $stmtFetch->fetch(PDO::FETCH_OBJ);
            //error message if id doesn't exist
            if (!$task == true) {
                throw new Exception('Cette tache nexiste pas', 400);
            }

            $stmtUpdate = $this->pdo->prepare("UPDATE task SET status_column_id=:status_column_id WHERE id = :id");
            $stmtUpdate->execute([
                "id" => $id,
                "status_column_id" => $status_column_id
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Method which creates task, persists in DB and return task object
     *
     * @param Task $task
     * @param int $project_id
     * @param int $user_id
     * @return Task
     * @throws Exception
     */
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
     * Method which update task, persists in DB and return task object
     *
     * @param  mixed $id
     * @param  mixed $task
     * @param  mixed $project_id
     * @param  mixed $user_id
     * @return Task
     */
    public function update(int $id, Task $task, int $project_id, int $user_id): Task
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
     * delete task
     * @param string $id
     */
    public function delete($id)
    {
        try {

            $stmt = $this->pdo->prepare("DELETE FROM task WHERE id=?");
            $stmt->execute([$id]);
            return ["message" => "La tache a été correctement supprimée"];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
