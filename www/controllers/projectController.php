<?php

require_once("../vendor/autoload.php");
require_once("../serializers/projectSerializer.php");
require_once("./baseController.php");

use App\models\Project;


/**
 * @throws Exception
 */
function read(int $id)
{
    $project = new Project();
    $project = $project->read($id);
    return serializeProjectById($project);
}


function search(): array
{
}


/**
 * argument: request body
 * returns an array
 */
function create(stdClass $body): array
{
    $project = deserializeProject($body);
    $project = $project->create($project);
    return serializeProject($project);
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
