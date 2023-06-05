<?php
namespace App\models;

use Exception;

class Project extends Database

{
    private int $id;

    private string $title;

    private string $status;

    private array $copil_list;

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
     * @return array|null
     */
    public function getCopilList(): ?array
    {
        return $this->copil_list;
    }

    /**
     * @param array|null $copil_list
     */
    public function setCopilList(array $copil_list): void
    {
        $this->copil_list = $copil_list;
    }

    /**
     * Method which update project, persists in DB and return project object
     *
     * @param int $id
     * @param Project $project
     * @param array $copil_list
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
            foreach($project->copil_list as $pilot){   
                $stmtInsert = $this->pdo->prepare("INSERT INTO project_user (project_id, user_id) VALUES (:project_id, :user_id)");
                $stmtInsert->execute([
                    "project_id" => $id,
                    "user_id" => $pilot
                ]);
            }
            return $project;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
