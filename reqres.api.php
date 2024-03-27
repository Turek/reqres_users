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
 *
 * @see \Drupal\Core\Database\Query\AlterableInterface::addTag()
 */
function hook_query_reqres_users_query_alter(\Drupal\Core\Database\Query\AlterableInterface $query) {
  // Example: Add a condition to filter users by a specific field.
  $query->condition('first_name', 'Emma', '!=');
}
