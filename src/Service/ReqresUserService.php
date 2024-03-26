<?php

namespace Drupal\reqres_users\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a service for database connectivity.
 */
class ReqresUserService {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ReqresUserService service object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
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
  public function getUsers(int $limit, int $page) {
    // Query users with pagination.
    $query = $this->entityTypeManager->getStorage('reqres_user')->getQuery();
    $query->pager($limit);
    $offset = $page * $limit;
    $query->range($offset, $limit);
    // Add tag to the query for altering.
    $query->addTag('reqres_users_query_alter');
    // Execute the query.
    $results = $query->execute();

    // Load user entities.
    $users = $this->entityTypeManager->getStorage('reqres_user')->loadMultiple($results);

    // Allow other modules to alter the user list through hook.
    \Drupal::moduleHandler()->alter('reqres_users_data', $users);

    // Dispatch an event to allow altering the user list.
    $event = new ReqresUsersAlterEvent($users);
    $this->eventDispatcher()->dispatch(ReqresUsersAlterEvent::EVENT_NAME, $event);

    // Get the altered user list and return it.
    return $event->getUsers();
  }

}
