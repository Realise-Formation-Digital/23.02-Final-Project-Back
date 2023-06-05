<?php

use App\models\Project;

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
}


function deserializeProject(stdClass $body): Project
{
}

