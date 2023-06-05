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

    private string $status = "toDo";

    private array $status_columns = [];

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

    public function read(int $id): Project {
        try {
            // get project
            $stmt = $this->pdo->prepare('SELECT * FROM project WHERE id = :id');
            $stmt->execute([
                'id' => $id
            ]);
            $project = $stmt->fetchObject( Project::class);

            if (!$project) {
                throw new Exception("Le projet d'id $id n'existe pas.", 400);
            }

            // get columns from project
            $stmt = $this->pdo->prepare('SELECT * FROM status_column WHERE project_id = :project_id ORDER BY position ASC');
            $stmt->execute([
                'project_id' => $id
            ]);
            $status_columns = $stmt->fetchAll( PDO::FETCH_CLASS, StatusColumn::class);

            // add tasks to columns
            foreach($status_columns as &$status_column) {
                //get tasks for 1 column
                $stmt = $this->pdo->prepare('SELECT * FROM task WHERE status_column_id = :status_column_id ORDER BY end_date ASC');
                $stmt->execute([
                    'status_column_id' => $status_column->getId()
                ]);
                $tasks = $stmt->fetchAll( PDO::FETCH_CLASS, Task::class);

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
}
