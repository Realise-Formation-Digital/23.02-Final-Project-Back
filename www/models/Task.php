<?php

namespace App\models;

use AllowDynamicProperties;
use Exception;

#[AllowDynamicProperties]
class Task extends Database
{
    private ?int $id;

    private string $title;

    private string $description;

    private string $start_date;

    private string $end_date;

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
     * Method which creates task, persists in DB and return task object
     *
     * @param Task $task
     * @return Task
     * @throws Exception
     */
    public function create(Task $task): Task
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO task (title, description, start_date, end_date, sector, status_column_id, user_id) VALUES (:title, :description, :start_date, :end_date, :sector, :status_column_id, :user_id)");
            $stmt->execute([
                "title" => $task->getTitle(),
                "description" => $task->getDescription(),
                "start_date" => $task->getStartDate(),
                "end_date" => $task->getEndDate(),
                "sector" => $task->getSector(),
                "status_column_id" => 1,
                "user_id" => 1
            ]);

            //get new id and add to task object
            $id = $this->pdo->lastInsertId();
            $task->setId($id);

            return $task;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method which update task, persists in DB and return task object
     *
     * @param int $id
     * @param Task $task
     * @return Task
     * @throws Exception
     */
    public function update(int $id, Task $task): Task
    {
        try {
            $this->setId($id);
            $stmt = $this->pdo->prepare("UPDATE task SET title= :title, description= :description, start_date= :start_date, end_date= :end_date, sector= :sector, status_column_id= :status_column_id, user_id= :user_id WHERE id= :id");
            $stmt->execute([
                "title" => $task->getTitle(),
                "description" => $task->getDescription(),
                "start_date" => $task->getStartDate(),
                "end_date" => $task->getEndDate(),
                "sector" => $task->getSector(),
                "status_column_id" => 1,
                "user_id" => 1,
                "id" => $id
            ]);
            
            return $task;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

?>