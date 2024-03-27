<?php

namespace Drupal\reqres_users\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Drupal\Core\Cache\Cache;

/**
 * Class PostMigrationSubscriber.
 */
class PostMigrationSubscriber implements EventSubscriberInterface {

  /**
   * Post migration event subscriber to invalidate caches when new content
   * was added or updated.
   *
   * @param MigrateImportEvent $event
   *   The migration event.
   */
  public function onPostImport(MigrateImportEvent $event) {
    $migration = $event->getMigration();
    $id_map = $migration->getIdMap();

    // Check if there were newly imported or updated items.
    $imported = $id_map->importedCount();
    $updated = $id_map->updateCount();

    // Only invalidate cache if there were updates or new imports.
    if ($imported > 0 || $updated > 0) {
      // Specify the cache tags you want to invalidate
      $tags = ['reqres_users_migrate'];

      // Invalidate cache tags
      Cache::invalidateTags($tags);

      // Optionally, log this action.
      \Drupal::logger('custom_migration')->info('Cache tags invalidated after migration.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Subscribe to the post-import event.
    return [
      MigrateEvents::POST_IMPORT => ['onPostImport'],
    ];
  }

}
