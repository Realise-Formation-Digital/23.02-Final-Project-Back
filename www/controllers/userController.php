<?php

require_once("../vendor/autoload.php");
require_once("../serializers/userSerializer.php");
require_once("./baseController.php");

use App\models\User;

function read(int $id)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}

/**
 * Search users in the database and converts it into JSON
 * returns an array 
 */
function search(): array
{
   $user = new User();
   $user_data = $user->search_users();
   $user_tab = serializeUsersList($user_data);

   return $user_tab;
}


function create(stdClass $body)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


function put(int $id, stdClass $body)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}

function patch(int $id, stdClass $body)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


function delete(int $id)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}
