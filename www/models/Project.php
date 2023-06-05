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
    public function setCopilList(?string $copil_list): void
    {
        $this->copil_list = $copil_list;
    }

}
