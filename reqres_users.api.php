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
  // Example: Add a condition to exclude users with the first name 'Emma'.
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


/**
 * Implements hook_views_query_alter().
 *
 * This function is used to alter the query for a specific view, adding a condition
 * to exclude users with the first name 'Emma'. It checks if the current view matches
 * the specified ID and, if so, modifies the query. Ensure that the field name used
 * matches the actual database field name for the user's first name.
 *
 * @param \Drupal\views\ViewExecutable $view
 *   The view being executed, containing all data about the view.
 * @param \Drupal\views\Plugin\views\query\QueryPluginBase $query
 *   The query object that is used to query the database. Conditions and modifications
 *   can be applied to this object to change the data that is retrieved by the view.
 *
 * @see https://www.drupal.org/docs/drupal-apis/views-api/modify-view-queries
 */
function hook_views_query_alter(Drupal\views\ViewExecutable $view, Drupal\views\Plugin\views\query\QueryPluginBase $query) {
  // Check if we are altering the correct view. Replace 'your_view_id' with the actual ID of your view.
  if ($view->id() === 'reqres_users') {
    // Example: Add a condition to exclude users with the first name 'Emma'.
    $query->addWhere('your_group', 'first_name', 'Emma', '<>');
  }
}
