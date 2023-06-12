<?php

use App\models\Project;

require_once("../vendor/autoload.php");
require_once("../serializers/statusColumnSerializer.php");
require_once("../serializers/userSerializer.php");

/**
 * Function which serialize a project with copil list, status columns and tasks
 *
 * @param Project $project
 * @return array
 */
function serializeProjectById(Project $project): array
{
    // serialize status columns
    $status_columns = [];
    foreach ($project->getStatusColumns() as $statusColumn) {
        $status_columns[] = serializeStatusColumn($statusColumn);
    }

    // serialize copil_list
    $copil_list = serializeUsersList($project->getCopilList());

    return [
        'id' => $project->getId(),
        'title' => $project->getTitle(),
        'copil_list' => $copil_list,
        "status_columns" => $status_columns
    ];
}
/**
 * serialize un projet Project
 *
 * @param  Project $project
 * @return array
 */
function serializeProject(Project $project): array
{
    // serialize copil_list
    $copil_list = serializeUsersList($project->getCopilList());


    return [
        'id' => $project->getId(),
        'title' => $project->getTitle(),
        "copil_list" => $copil_list
    ];
}

/**
 * deserializeProject
 *
 * @param  Project $body
 * @return Project
 */
function deserializeProject(stdClass $body): Project
{
    $project = new Project();

    // IF THE TITLE IS EMPTY, THROW AN ERROR
    if (!empty($body->title)) {
        $project->setTitle($body->title);

        if (strlen($body->title) > 250) {
            throw new Exception("Le titre ne peut pas avoir plus que 250 caractères.", 400);
        }
    } else {
        throw new Exception("Le titre ne peut pas être nul.", 400);
    }

    // IF THE COPIL LIST IS EMPTY, THROW AN ERROR
    if (!empty($body->copil_list)) {
        $listUsersCheck = checkDistinctPilot($body->copil_list);
        if (arrayHasOnlyInts($body->copil_list)) {
            $project->setCopilList($listUsersCheck);
        } else {
            throw new Exception("La liste CoPil doit être des nombres.", 400);
        }
    } else {
        throw new Exception("La liste CoPil est obligatoire.", 400);
    }

    return $project;
}

/**
 * arrayHasOnlyInts
 *
 * @param  array $list
 * @return bool
 */
function arrayHasOnlyInts(array $list): int
{
    $nonints = preg_grep('/\D/', $list); // returns array of elements with non-ints
    return (count($nonints) == 0); // if array has 0 elements, there's no non-ints
}

/**
 * fonction qui control la selection des utilisateurs qu'ils soient bien distinct
 * @param array
 * @return array
 */
function checkDistinctPilot(array $array)
{
    $distinctValues = array_unique($array);
    return $distinctValues;
}
