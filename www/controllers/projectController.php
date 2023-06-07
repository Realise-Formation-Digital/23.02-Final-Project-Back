<?php

require_once("../vendor/autoload.php");
require_once("../serializers/projectSerializer.php");
require_once("./baseController.php");

use App\models\Project;
use OpenApi\Attributes as OA;


/**
 * 
 * @throws Exception
 */
#[OA\Get(
    path:"/projects/id",
    #[OA\Response(
        response:"200",
        description:"Kanban unique",
        #[OA\JsonContent(
            type:"string",
            description:"titre du projet"
        ),]
    ),]
)]
function read(int $id)
{
    $project = new Project();
    $project = $project->read($id);
    return serializeProjectById($project);
}


function search()
{
}


/**
 * argument: request body
 * returns an array
 */
function create(stdClass $body): array
{
    $prjct = deserializeProject($body);
    $prjct = $prjct->create($prjct);
    return serializeProject($prjct);
}

/**
 * update un projet 
 *
 * @param  int $id
 * @param  stdClass $body
 * @return array
 */
function put(int $id, stdClass $body): array
{
    $project = deserializeProject($body);
    $updateProject = $project->update($id, $project);
    return serializeProject($updateProject);
}

function patch(int $id, stdClass $body)
{
}


function delete(int $id): array
{
    $project = new Project();
    return $project->delete($id);
}
