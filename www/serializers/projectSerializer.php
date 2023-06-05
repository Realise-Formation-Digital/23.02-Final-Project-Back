<?php

use App\models\Project;

require_once("../serializers/statusColumnSerializer.php");
require_once("../serializers/userSerializer.php");

function serializeProjectById(Project $project): array
{
    $status_columns = [];
    foreach ($project->getStatusColumns() as $statusColumn) {
        $status_columns[] = serializeStatusColumn($statusColumn);
    }

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

