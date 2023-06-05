<?php

use App\models\StatusColumn;

require_once("../serializers/taskSerializer.php");

function serializeStatusColumn(StatusColumn $statusColumn): array
{
    $tasks = [];
    foreach ($statusColumn->getTasks() as $task) {
        $tasks[] = serializeTask($task);
    }

    return [
        'id' => $statusColumn->getId(),
        'title' => $statusColumn->getTitle(),
        'tasks' => $tasks
    ];
}


