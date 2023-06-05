<?php

require_once("../vendor/autoload.php");
require_once("../serializers/taskSerializer.php");
require_once("./baseController.php");

use App\Models\Task;


function read(int $id)
{

}


function search(): array
{

}


/**
 * @throws Exception
 */
function create(stdClass $body): array
{
    $object_task = deserializeTask($body);
    $task = new Task();
    $task = $task->create($object_task);
    return serializeTask($task);
}


function put(int $id, stdClass $body): array
{

}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array
{

}

