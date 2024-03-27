<?php

/**
 * @file
 * Hooks provided by the Reqres users module.
 */

/**
 * Alter a query before executing it.
 *
 * This hook is invoked by Drupal's query builder whenever a query tagged with
 * 'reqres_users_query' is about to be executed.
 *
 * @param \Drupal\Core\Database\Query\AlterableInterface $query
 *   The query object.
 */
function hook_query_reqres_users_query_alter(\Drupal\Core\Database\Query\AlterableInterface $query) {
  // Example: Add a condition to filter users by a specific field.
  $query->condition('first_name', 'Emma', '!=');
}

/**
 * Alter the list of users.
 *
 * This hook allows modules to alter the array of user entities before they
 * are processed. For example, this hook implementation loops through all
 * users and alters their email addresses to be lowercase.
 *
 * @param \Drupal\reqres_users\Entity\ReqresUser[] $users
 *   An array of user entities that can be altered.
 */
function hook_reqres_users_data_alter(array &$users) {
  // Perform alterations to the user list as needed.
  foreach ($users as $user) {
    // Change email addresses to lowercase.
    $user->setEmail(strtolower($user->getEmail()));
  }
}
