<?php

namespace Drupal\reqres_users\EventSubscriber;

use Drupal\reqres_users\Event\ReqresUsersAlterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An example subscriber for altering Reqres users output.
 *
 * Updates emails to lowercase.
 */
class ReqresUsersAlterSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ReqresUsersAlterEvent::USERS_ALTER][] = ['alterUsers'];
    return $events;
  }

  /**
   * Alters the user list.
   *
   * @param \Drupal\reqres_users\Event\ReqresUsersAlterEvent $event
   *   The event object.
   */
  public function alterUsers(ReqresUsersAlterEvent $event) {
    $users = $event->getUsers();
    // Perform alterations to the user list as needed.
    foreach ($users as $user) {
      // Change email addresses to lowercase.
      $user->setEmail(strtolower($user->getEmail()));
    }
  }

}
