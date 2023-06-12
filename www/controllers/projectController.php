<?php

require_once("../vendor/autoload.php");
require_once("../serializers/projectSerializer.php");
require_once("./baseController.php");

use App\models\Project;


/**
 * @param int $id
 * @return array
 * @throws Exception
 */
function read(int $id): array
{
    $project = new Project();
    $project = $project->read($id);
    return serializeProjectById($project);
}

/**
 * @return array
 * @throws Exception
 */
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
 * @throws Exception
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
 * @param int $id
 * @param stdClass $body
 * @return array
 * @throws Exception
 */
function put(int $id, stdClass $body): array
{
    $project = deserializeProject($body);
    $updateProject = $project->update($id, $project);
    return serializeProject($updateProject);
}

/**
 * @throws Exception
 */
function patch()
{
    throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


/**
 * @throws Exception
 */
function delete(int $id): array
{
    $project = new Project();
    return $project->delete($id);
}
