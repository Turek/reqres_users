<?php

namespace Drupal\Tests\reqres_users\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * Tests the migration of ReqRes API data into the ReqresUsers entity.
 *
 * @group reqres_users
 */
class ReqresUsersMigrationTest extends KernelTestBase {

  /**
   * Modules to enable for the test.
   *
   * @var array
   */
  public static $modules = ['system', 'migrate', 'migrate_plus', 'migrate_tools', 'reqres_users'];

  /**
   * Set up the test environment.
   */
  protected function setUp(): void {
    parent::setUp();
    // Install schema for 'reqres_users' table/entity.
    $this->installEntitySchema('reqres_user');
  }

  /**
   * Tests the 'reqres_users' migration for success.
   */
  public function testReqresUsersMigration() {
    // Attempt to load the migration.
    $migration = $this->container->get('plugin.manager.migration')->createInstance('reqres_users');

    // Check if the migration could be loaded.
    $this->assertNotFalse($migration, 'The reqres_users migration could not be loaded.');

    // Proceed with creating the MigrateExecutable only if the migration is successfully loaded.
    $executable = new MigrateExecutable($migration, new MigrateMessage());

    // Execute the migration.
    $result = $executable->import();

    // Assert that the migration executed successfully.
    $this->assertSame(MigrationInterface::RESULT_COMPLETED, $result, 'The reqres_users migration completed successfully.');

    // Example assertion: Check the count of migrated ReqresUsers entities.
    $expectedCount = 12;
    $actualCount = \Drupal::entityQuery('reqres_users')->count()->execute();
    $this->assertEquals($expectedCount, $actualCount, 'The expected number of ReqresUsers entities have been migrated.');
  }
}
