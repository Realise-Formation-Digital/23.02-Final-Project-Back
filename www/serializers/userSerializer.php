<?php

use App\models\User;

/**
 * serializeUser
 *
 * @param  array $users
 * @return void
 */
function serializeUser(array $users)
{
   try {
      $userList = [];
      foreach ($users as $usr) {
         $usr_id = $usr->get_id();
         if (!isset($userList[$usr_id])) {
            $userList[$usr_id] = [
               "id" => $usr_id,
               "last_name" => $usr->get_last_name(),
               "first_name" => $usr->get_first_name(),
               "image" => $usr->get_image(),
            ];
         }
      }

      $user_tab = [];
      foreach ($userList as $u) {
         $user_tab[] = $u;
      }
      return $user_tab;
   } catch (Exception $e) {
      throw $e;
   }
}

/**
 * serializeOneUser
 *
 * @param  Class $user
 * @return array
 */
function serializeOneUser(User $user): array
{
    return [
        "id" => $user->get_id(),
        "last_name" => $user->get_last_name(),
        "first_name" => $user->get_first_name(),
        "image" => $user->get_image(),
    ];
}
