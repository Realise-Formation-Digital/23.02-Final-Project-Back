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


function deserializeProject(stdClass $body): Project
{
}

