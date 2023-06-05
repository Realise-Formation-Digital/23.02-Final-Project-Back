<?php

use App\models\Project;

require_once("../vendor/autoload.php");
require_once("../serializers/statusColumnSerializer.php");
require_once("../serializers/userSerializer.php");

/**
 * Function which serialize a project with copil list, status columns and tasks
 *
 * @param Project $project
 * @return array
 */
function serializeProjectById(Project $project): array
{
    // serialize status columns
    $status_columns = [];
    foreach ($project->getStatusColumns() as $statusColumn) {
        $status_columns[] = serializeStatusColumn($statusColumn);
    }

    // serialize copil_list
    $copil_list = [];
    foreach ($project->getCopilList() as $copil_user) {
        $copil_list[] = serializeOneUser($copil_user);
    }

    return [
        'id' => $project->getId(),
        'title' => $project->getTitle(),
        'copil_list' => $copil_list,
        "status_columns" => $status_columns
    ];
    return [
        'id' => $project->getId(),
        'title' => $project->getTitle(),
        "status" => $project->getStatus(),
        "copil_list" => $project->getCopilList(),
    ];
}
/**
 * serialize un projet Project
 *
 * @param  Project $project
 * @return array
 */
function serializeProject(Project $project): array
{
    return [
        'id' => $project->getId(),
        'title' => $project->getTitle(),
        "status" => $project->getStatus(),
        "copil_list" => $project->getCopilList(),
    ];
}

/**
 * deserializeProject
 *
 * @param  Project $body
 * @return Project
 */
function deserializeProject(stdClass $body): Project
{
    $project = new Project();

    if (!empty($body->title)) {
        $project->setTitle($body->title);

        if (strlen($body->title) > 250) {
            throw new Exception("Le titre ne peut pas avoir plus que 100 caractères.", 400);
        }
    } else {
        throw new Exception("Le titre ne peut pas être nul.", 400);
    }

    if (!empty($body->status)) {
        $project->setStatus($body->status);
    } else {
        throw new Exception("Le status ne peut pas être nul.", 400);
    }

    if (!empty($body->copil_list)){
        $project->setCopilList($body->copil_list);
    } else {
        throw new Exception(("La liste ne peut être vide"));
    }

    return $project;

}

