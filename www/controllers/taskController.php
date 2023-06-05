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
    $task = new Task();
    //check if id of body task exist 
    if (!empty($body->new_status_column_id)) {
        //must be integer, if yes create new status column id
        if (gettype($body->new_status_column_id) == "integer") {
            $task->patch($id, $body->new_status_column_id);
        } else {
            throw new Exception("L'id de la colonne status doit être un nombre entier.", 400);
        }
        
    } else {
        throw new Exception("L'id de la colonne status est obligatoire.", 400);
    }    
}


function delete(int $id): array
{

}

