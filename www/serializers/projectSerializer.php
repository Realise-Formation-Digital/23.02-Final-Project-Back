<?php

require_once("../vendor/autoload.php");

use App\models\Project;

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
        $project->setTitle($body->status);
    } else {
        throw new Exception("Le status ne peut pas être nul.", 400);
    }

    if (!empty($body->copil_list)){
        $project->setCopilList($body->copil_list);
    } else {
        throw new Exception(("La liste ne peut être vide"));
    }

}

