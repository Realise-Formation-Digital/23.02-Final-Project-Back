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


function search(): array {
    $project = new Project();
    $projects = $project->search();
    //create empty array to fill with every JSON projects
    $serializedProjects = [];
    //Loops through project to get every projects
    foreach($projects as $project){
        $serializedProjects[] = serializeProject($project);
      }
      return $serializedProjects;
   
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
    throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


function delete(int $id): array
{
    $project = new Project();
    return $project->delete($id);
}
