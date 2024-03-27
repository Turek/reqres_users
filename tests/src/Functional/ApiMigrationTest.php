<?php

namespace Drupal\Tests\reqres_users\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\reqres_users\Traits\ApiTestTrait;

/**
 * Tests API data migration.
 *
 * @group reqres_users
 */
class ApiMigrationTest extends BrowserTestBase {

  use ApiTestTrait;

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
    $migration_id = 'reqres_users'; // Replace with your migration ID.
    $migration = \Drupal::service('plugin.manager.migration')->createInstance($migration_id);
    $migration->getIdMap()->prepareUpdate();
    $executable = new \Drupal\migrate\MigrateExecutable($migration, $this->getMockMigrationMessage());
    $executable->import();
    $migration->getIdMap()->saveMessage();
    
    // Assert that the migration was successful.
    $this->assertEqual($migration->getProcessedCount(), $migration->getCount(), 'Migration was successful.');
  }

  /**
   * Mocks the migration message.
   */
  protected function getMockMigrationMessage() {
    return $this->getMockBuilder(\Drupal\migrate\MigrateMessageInterface::class)
      ->disableOriginalConstructor()
      ->getMock();
  }

}
