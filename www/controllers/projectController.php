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


function create(stdClass $body): array
{

}


function put(int $id, stdClass $body): array
{

}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array
{

}

