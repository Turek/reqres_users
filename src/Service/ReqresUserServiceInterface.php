<?php

namespace Drupal\reqres_users\Service;

/**
 * Interface for the Reqres User service.
 */
interface ReqresUserServiceInterface {

  /**
   * Gets Reqres users from the database based on provided vatiables.
   *
   * @param int $limit
   *   The number of items the calling code will display per page.
   * @param int $page
   *   Page number to be displayed.
   *
   * @return array
   *   Return array of user objects.
   */
  public function getUsers(int $limit, int $page): array;

  /**
   * Get the total number of rows in the reqres_user entity table.
   *
   * @return int
   *   The total number of rows.
   */
  public function getTotalRows(): int;

}
