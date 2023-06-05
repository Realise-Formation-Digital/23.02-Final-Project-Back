<?php

require_once("../models/Task.php");
require_once("../serializers/taskSerializer.php");
require_once("./baseController.php");


function read(int $id)
{

}


function search(): array
{

}


function create(stdClass $body): array
{

}


function put(int $id, stdClass $body): array
{
    $task = deserializeTask($body);
    $updateTask = $task->updateTask($id, $task);
    return serializeTask($updateTask);
}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array
{

}

