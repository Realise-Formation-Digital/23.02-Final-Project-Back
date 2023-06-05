<?php

use App\models\Project;

function serializeProjectById(Project $project): array
{
    dump($project);
    $status_columns = [];
    foreach ($project->getStatusColumns() as $statusColumn) {

    }
    return [];
}


function deserializeProject(stdClass $body): Project
{
}

