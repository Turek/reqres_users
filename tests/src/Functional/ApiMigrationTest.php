<?php

namespace Drupal\Tests\reqres_users\Functional;

use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessageInterface;
use Drupal\migrate\Plugin\MigrationPluginManagerInterface;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\reqres_users\Traits\ApiTestTrait;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * Tests API data migration.
 *
 * @group reqres_users
 */
class ApiMigrationTest extends BrowserTestBase {

  use ApiTestTrait;

  protected static $modules = ['migrate_plus', 'reqres_users', 'migrate'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Perform API call before migration.
    $this->performApiCall();
  }

  /**
   * Tests the migration process.
   */
  public function testMigration() {
    // Trigger the migration programmatically.
    $migration = \Drupal::service('plugin.manager.migration')->createInstance('reqres_users');
    $migration->getIdMap()->prepareUpdate();
    $executable = new MigrateExecutable($migration, $this->getMockMigrationMessage());
    $executable->import();

    // Use the migration status to assert success, for example:
    $this->assertTrue($migration->getStatus() == MigrationInterface::STATUS_IDLE, 'Migration completed.');

    // Check processed counts.
    $map_table = $migration->getIdMap()->mapTableName();
    $query = $this->container->get('database')->select($map_table, 'map')
      ->fields('map', ['sourceid1']);
    // Adjust the field name 'sourceid1' based on your source ID structure.
    $result = $query->execute()->fetchAll();
    $processedCount = count($result); 
    $this->assertGreaterThan(0, $processedCount, 'Expected at least one item to be processed.');
  }

  /**
   * Mocks the migration message.
   */
  protected function getMockMigrationMessage() {
    return $this->getMockBuilder(MigrateMessageInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
  }

}
