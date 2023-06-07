<?php

require_once("../vendor/autoload.php");
require_once("../serializers/userSerializer.php");

use App\models\Task;

/**
 * Function which serializes task
 *
 * @param Task $task
 * @return array
 */
function serializeTask(Task $task): array
{
    $user = serializeOneUser($task->getPilot());
    return [
        'id' => $task->getId(),
        'title' => $task->getTitle(),
        "description" => $task->getDescription(),
        "start_date" => $task->getStartDate(),
        "end_date" => $task->getEndDate(),
        "pilot" => $user,
        "sector" => $task->getSector()
    ];
}


/**
 * Function which deserializes a task (convert standard object to task object) and verifies body
 *
 * @param stdClass $body
 * @return Task
 * @throws Exception
 */
function deserializeTask(stdClass $body): Task
{
    $task = new Task();
    
    if (!empty($body->title)) {
        $task->setTitle($body->title);

        if (strlen($body->title) > 100) {
            throw new Exception("Le titre ne peut pas avoir plus que 100 caractères.", 400);
        }
    } else {
        throw new Exception("Le titre ne peut pas être nul.", 400);
    }

    if (!empty($body->description)) {
        $task->setDescription($body->description);

        if (strlen($body->description) > 250) {
            throw new Exception("La description ne peut pas avoir plus que 250 caractères.", 400);
        }
    } else {
        throw new Exception("La description ne peut pas être nulle.", 400);
    }

    if (!empty($body->start_date)) {
        $task->setStartDate($body->start_date);

        test_date($body->start_date);

    } else {
        throw new Exception("La date de début ne peut pas être nulle.", 400);
    }

    if (!empty($body->end_date)) {
        $task->setEndDate($body->end_date);

        test_date($body->end_date);

    } else {
        throw new Exception("La date de fin ne peut pas être nulle.", 400);
    }

    if (!empty($body->sector)) {
        if ($body->sector!="blanchisserie" && $body->sector!="horlogerie" && $body->sector!="jardinerie" && $body->sector!="nettoyage" && $body->sector!="administration" && $body->sector!="informatique"){
            throw new Exception("Ce secteur n'existe pas.", 400);
        }
        $task->setSector($body->sector);
    } else {
        $task->setSector(null);
    }

    return $task;
}

/**
 * Function which tests date (format and if exists)
 *
 * @param string $date
 * @return void
 * @throws Exception
 */
function test_date(string $date): void {
    $format = "Y-m-d";
    if(date($format, strtotime($date)) != date($date)) {
        throw new Exception("La date doit être valide et au format YYYY-MM-DD. Exemple: 2023-06-05", 400);
    }
}


