<?php

use App\Models\Task;

function serializeTask(Task $task): array
{
}


function deserializeTask(stdClass $body): Task
{
    $task = new Task();
    return $task;
}

