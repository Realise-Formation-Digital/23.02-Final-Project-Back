<?php

require_once("../vendor/autoload.php");
require_once("../serializers/userSerializer.php");
require_once("./baseController.php");

use App\models\User;


/**
 * @throws Exception
 */
function read(int $id): array
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}

/**
 * Search users in the database and converts it into JSON
 *
 * @return array
 * @throws Exception
 */
function search(): array
{
   $user = new User();
   $user_data = $user->search_users();
   $user_tab[] = serializeUsersList($user_data);

   // SINCE WE CALL THE SERIALIZE FUNCTIONS TWICE WE HAD AN ISSUE WITH THE RETURN WHERE THERE WAS A TAB
   // SO WE NEED TO SEND THE TAB INSIDE TOWARDS THE JSON CONVERTER. THEREFOR, THE [0] IN RETURN
   return $user_tab[0];
}


/**
 * @throws Exception
 */
function create(stdClass $body)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


/**
 * @throws Exception
 */
function put(int $id, stdClass $body)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}

/**
 * @throws Exception
 */
function patch(int $id, stdClass $body)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}


/**
 * @throws Exception
 */
function delete(int $id)
{
   throw new Exception("Ce Endpoint n'est pas accessible", 404);
}
