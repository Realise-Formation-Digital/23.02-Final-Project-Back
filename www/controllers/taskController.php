<?php

require_once("../vendor/autoload.php");
require_once("../serializers/taskSerializer.php");
require_once("./baseController.php");

use App\models\Task;


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
    $task = deserializeTask($body);
    $task = $task->create($task);
    return serializeTask($task);
}


function put(int $id,stdClass $body): array
{
    $task = deserializeTask($body);
    $updateTask = $task->update($id, $task);
    return serializeTask($updateTask);
}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array{
        $task = new Task();
        $deleteTask = $task->delete($id);
        return $deleteTask;
    }


