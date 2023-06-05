<?php

use App\models\Project;

require_once("../serializers/statusColumnSerializer.php");

function serializeProjectById(Project $project): array
{
    $status_columns = [];
    foreach ($project->getStatusColumns() as $statusColumn) {
        $status_columns[] = serializeStatusColumn($statusColumn);
    }
    return [
        'id' => $project->getId(),
        'title' => $project->getTitle(),
        'status' => $project->getStatus(),
        "status_columns" => $status_columns
    ];
}


function deserializeProject(stdClass $body): Project
{
}

