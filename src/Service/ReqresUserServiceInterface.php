<?php

namespace Drupal\reqres_users\Service;

/**
 * Interface for the Reqres User service.
 */
interface ReqresUserServiceInterface {

  /**
   * Constructs a ReqresApiService object.
   *
   * @param CacheBackendInterface $cache
   *   The cache backend service.
   * @param EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   * @param EventDispatcherInterface $eventDispatcher
   *   The event dispatcher service.
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
