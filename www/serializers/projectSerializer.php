<?php

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

