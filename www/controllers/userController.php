<?php

namespace App\controllers;

use App\models\User;
use App\serializers\userSerializer;
use baseController;

/*
require_once("../serializers/userSerializer.php");
require_once("./baseController.php");
*/

function read(int $id)
{
}


function search(): array
{
   $user = new User();
   $user_data = $user->search_users();
   $user_tab = deserializeUser($user_data);
   return $user_tab;
}


// function create(stdClass $body)
// {
// }


// function put(int $id, stdClass $body)
// {
// }

// function patch(int $id, stdClass $body)
// {
// }


// function delete(int $id)
// {
// }
