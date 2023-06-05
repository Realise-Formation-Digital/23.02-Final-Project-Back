<?php
require_once("../vendor/autoload.php");

use App\models\Project;


function serializeProject(Project $project): array
{
   return [
      'id' => $project->getId(),
      'title' => $project->getTitle(),
      "status" => $project->getStatus(),
      "copil" => $project->getCopilList()
   ];
}


function deserializeProject(stdClass $body): Project
{
   $project = new Project();

   // IF THE TITLE WAS ENTERED WE SET IT IN THE DB
   if (!empty($body->title)) {
      $project->setTitle($body->title);

      if (strlen($body->title) > 100) {
         throw new Exception("Le titre ne peut pas avoir plus que 100 caractères.", 400);
      }
   } else {
      throw new Exception("Le titre ne peut pas être nul.", 400);
   }

   // IF THE LIST OF USERS IS NOT EMPTY WE SET THE LIST
   if (!empty($body->copil)) {
      $project->setCopilList($body->copil);
   }

   return $project;
}
