<?php

namespace Drupal\reqres_users\Service;

use Drupal\reqres_users\Service\ReqresUserServiceInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\reqres_users\Event\ReqresUsersAlterEvent;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Exception;

/**
 * Provides a service for database connectivity.
 */
class ReqresUserService implements ReqresUserServiceInterface {

  /**
   * The cache backend instance.
   *
   * @var CacheBackendInterface
   */
  protected $cache;

  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The event dispatcher.
   *
   * @var EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The logger service.
   *
   * @var LoggerChannelInterface
   */
  protected $logger;

  /**
   * The module handler.
   *
   * @var ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a new ReqresUserService object.
   *
   * @param CacheBackendInterface $cache
   *   The cache backend service.
   * @param EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   * @param EventDispatcherInterface $eventDispatcher
   *   The event dispatcher service.
   * @param LoggerChannelInterface $logger
   *   The logger service.
   * @param ModuleHandlerInterface $moduleHandler
   *   The module handler service.
   */
  public function __construct(CacheBackendInterface $cache, EntityTypeManagerInterface $entityTypeManager, EventDispatcherInterface $eventDispatcher, LoggerChannelInterface $logger, ModuleHandlerInterface $moduleHandler) {
    $this->cache = $cache;
    $this->entityTypeManager = $entityTypeManager;
    $this->eventDispatcher = $eventDispatcher;
    $this->logger = $logger;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * Gets Reqres users from the database based on provided vatiables.
   *
   * @param int $limit
   *   The number of items the calling code will display per page.
   * @param int $page
   *   Page number to be displayed.
   * @return array
   *   Return array of user objects.
   */
  public function getUsers(int $limit, int $page): array {
    $cid = 'reqres_users:page_' . $page . ':limit_' . $limit;

    // Attempt to retrieve from cache first.
    if ($cache = $this->cache->get($cid)) {
      return $cache->data;
    }

    try {
      // Query users with pagination.
      $query = $this->entityTypeManager->getStorage('reqres_user')->getQuery();
      $offset = $page === 0 ? 0 : $page * $limit;
      $query->range($offset, $limit);
      // Add tag to the query for altering.
      $query->addTag('reqres_users_query');
      $query->accessCheck(TRUE);
      // Execute the query.
      $results = $query->execute();

      // Load user entities.
      $users = $this->entityTypeManager->getStorage('reqres_user')->loadMultiple($results);

      // Allow other modules to alter the user list through hook.
      $this->moduleHandler->alter('reqres_users_data', $users);

      // Dispatch an event to allow altering the user list.
      $event = new ReqresUsersAlterEvent($users);
      $this->eventDispatcher->dispatch($event, ReqresUsersAlterEvent::USERS_ALTER);

      // Cache successful API response.
      $this->cache->set($cid, $event->getUsers(), CacheBackendInterface::CACHE_PERMANENT, ['reqres_users_data']);

      // Get the altered user list and return it.
      return $event->getUsers();
    }
    catch (Exception $e) {
      $this->logger->error('Failed fetching users from Reqres API: @message', ['@message' => $e->getMessage()]);

      return [];
    }
  }

  /**
   * Get the total number of rows in the reqres_user entity table.
   *
   * @return int
   *   The total number of rows.
   */
  public function getTotalRows(): int {
    $cid = 'reqres_users:total_rows';

    // Attempt to retrieve from cache first.
    if ($cache = $this->cache->get($cid)) {
      return $cache->data;
    }
    try {
      // Get the entity query for the reqres_user entity.
      $query = $this->entityTypeManager->getStorage('reqres_user')->getQuery();
      // Add tag to the query for altering.
      $query->addTag('reqres_users_query');
      $query->accessCheck(TRUE);

      // Count the total number of rows.
      $total_rows = $query->count()->execute();
      // Cache successful response.
      $this->cache->set($cid, $total_rows, CacheBackendInterface::CACHE_PERMANENT, ['reqres_users_data']);

      return $total_rows;
    }
    catch (Exception $e) {
      $this->logger->error('Failed fetching users from Reqres API: @message', ['@message' => $e->getMessage()]);

      return 0;
    }
  }

}
