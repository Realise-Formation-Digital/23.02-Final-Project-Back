<?php

require_once("../vendor/autoload.php");
require_once("../serializers/taskSerializer.php");
require_once("./baseController.php");

use App\models\Task;


function read(int $id)
{
}


function search()
{
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

   // date(format,timestamp)
//    echo "Today is " . date("Y/m/d") . "<br>";



    //StartDate must be previous to EndDate, if yes set date
    if ($body->getStartDate & ->getEndDate) {
    $this=start_date<$this=end_date
    } else {
    throw new Exception("La date de début de la tâche doit précéder la date de fin.", 400);
    }
    $task = $task->create($task, $body->project_id, $body->pilot);
    return serializeTask($task);
}

/**
 * update une tache
 *
 * @param  mixed $id
 * @param  stdClass $body
 * @return array
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

function patch(int $id, stdClass $body)
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
    $task = new Task();
    $deleteTask = $task->delete($id);
    return $deleteTask;
}
