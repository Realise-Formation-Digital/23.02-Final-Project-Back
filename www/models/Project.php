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
    public function update(int $id, Project $project, array $copil_list): Project
    {
        try {
            $this->setId($id);
            $stmt = $this->pdo->prepare("UPDATE project SET title= :title, status= :status WHERE id= :id");
            $stmt->execute([
                "title" => $project->getTitle(),
                "status" => $project->getStatus(),
                "id" => $id
            ]);
            
            foreach($copil_list as $pilot){
                $stmt = $this->pdo->prepare("UPDATE project_user SET user_id= :pilot WHERE project_id= :id");
                $stmt->execute([
                    "user_id" => $pilot,
                    "project_id" => $id
                ]); 
            }

            return $project;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
