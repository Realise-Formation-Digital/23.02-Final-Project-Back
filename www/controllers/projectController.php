<?php

require_once("../vendor/autoload.php");
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
 * @param  int $id
 * @param  Class $body
 * @return array
 */
function put(int $id, stdClass $body): array
{
    $project = deserializeProject($body);
    $updateProject = $project->update($id, $project);
    return serializeProject($updateProject);
}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array
{

}

