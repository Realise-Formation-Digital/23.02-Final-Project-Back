<?php

require_once("../models/Project.php");
require_once("../serializers/projectSerializer.php");
require_once("./baseController.php");


function read(int $id)
{
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


function put(int $id, stdClass $body): array
{
}

function patch(int $id, stdClass $body): array
{
}


function delete(int $id): array
{
}
