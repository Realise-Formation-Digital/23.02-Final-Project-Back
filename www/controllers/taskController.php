<?php

require_once("../models/Task.php");
require_once("../serializers/taskSerializer.php");
require_once("./baseController.php");

$task = new Task();

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
    try{
        $result = $task->updateTask(int $id, $body);
        return $result;
    }
    catch (ErrorException $e){
        throw ($e);
    }
}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array
{

}

