<?php

namespace Drupal\reqres_users\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Defines the event for altering Reqres users.
 */
class ReqresUsersAlterEvent extends Event {

  // Name for subscribers to reliably use this event.
  const USERS_ALTER = 'reqres_users_alter';

  /**
   * The users to be altered.
   *
   * @var array
   */
  protected $users;

  /**
   * Constructs a new ReqresUsersAlterEvent object.
   *
   * @param array $users
   *   The users to be altered.
   */
  public function __construct(array &$users) {
    $this->users = &$users;
  }

  /**
   * Gets the users to be altered.
   *
   * @return array
   *   The users to be altered.
   */
  public function &getUsers() {
    return $this->users;
  }

}
