<?php

require_once("../vendor/autoload.php");
require_once("../serializers/taskSerializer.php");
require_once("./baseController.php");

use App\models\Task;


/**
 * @throws Exception
 */
function read(int $id)
{
    throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


/**
 * @throws Exception
 */
function search()
{
    throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


/**
 * @throws Exception
 */
function create(stdClass $body): array
{
    $task = deserializeTask($body);
    if (empty($body->project_id)) {
        throw new Exception("Il s'agit de quel Kanban ?", 400);
    }
    if (empty($body->pilot)) {
        throw new Exception("La tache doit être attribué à quelqu'un.", 400);
    }

    $task = $task->create($task, $body->project_id, $body->pilot);
    return serializeTask($task);
}

/**
 * update une tache
 *
 * @param int $id
 * @param stdClass $body
 * @return array
 * @throws Exception
 */
function put(int $id, stdClass $body): array
{
    $task = deserializeTask($body);
    if (empty($body->pilot)) {
        throw new Exception("La tache doit être attribué à quelqu'un.", 400);
    }

    $updateTask = $task->update($id, $task, $body->project_id, $body->pilot);
    return serializeTask($updateTask);
}

/**
 * @param int $id
 * @param stdClass $body
 * @return array
 * @throws Exception
 */
function patch(int $id, stdClass $body): array
{
    $task = new Task();
    //check if id of body task exist 
    if (!empty($body->new_status_column_id)) {
        //must be integer, if yes create new status column id
        if (gettype($body->new_status_column_id) == "integer") {
            return $task->patch($id, $body->new_status_column_id);
        } else {
            throw new Exception("L'id de la colonne status doit être un nombre entier.", 400);
        }
    } else {
        throw new Exception("L'id de la colonne status est obligatoire.", 400);
    }
}

/**
 * @param int $id
 * @return array
 * @throws Exception
 */
function delete(int $id): array
{
    $task = new Task();
    $deleteTask = $task->delete($id);
    return $deleteTask;
}
