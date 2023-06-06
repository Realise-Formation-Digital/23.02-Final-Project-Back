<?php

use App\models\Project;


require_once("../serializers/projectSerializer.php");
require_once("./baseController.php");


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

}

function patch(int $id, stdClass $body): array
{

}


function delete(int $id): array{
    $project = new Project();
    return $project->delete($id);
}
