<?php

use App\models\User;

/**
 * serializeUser
 *
 * @param  array $users
 * @return void
 */
function serializeUsersList(array $users): array
{
   $user_tab = [];
   foreach ($users as $u) {
      $user_tab[] = serializeUser($u);
   }

   return $user_tab;
}

/**
 * serializeUser
 *
 * @param  Class $user
 * @return array
 */
function serializeUser(User $user): array
{
   return [
      "id" => $user->get_id(),
      "last_name" => $user->get_last_name(),
      "first_name" => $user->get_first_name(),
      "image" => $user->get_image(),
   ];
}
