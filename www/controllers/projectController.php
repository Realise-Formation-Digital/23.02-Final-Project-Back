<?php

require_once("../models/Project.php");
require_once("../serializers/projectSerializer.php");
require_once("./baseController.php");

use App\models\Project;

function read(int $id)
{

}


function search(): array
{

}


function create(stdClass $body): array
{

}

/**
 * update un projet 
 *
 * @param  mixed $id
 * @param  mixed $body
 * @param  mixed $copil_list
 * @return array
 */
function put(int $id, stdClass $body, array $copil_list): array
{
    $project = deserializeTask($body);
    $updateProject = $project->update($id, $project, $copil_list);
    return serializeTask($updateProject);
}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array
{

}

