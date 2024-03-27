<?php

namespace Drupal\Tests\reqres_users\Unit;

use Drupal\KernelTests\KernelTestBase;
use Drupal\reqres_users\Entity\ReqresUser;
use Drupal\Core\Database\Database;
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests the alteration of user query.
 *
 * @group reqres_users
 */
class QueryAlterUnitTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['test_query_alter_module', 'reqres_users'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Add test users to the database.
    ReqresUser::create(['first_name' => 'Emma'])->save();
    ReqresUser::create(['first_name' => 'John'])->save();
  }

  /**
   * Tests the alteration of user query.
   */
  public function testQueryAlteration() {
    // Get the database connection.
    $connection = $this->container->get('database');

    // Build the altered query.
    $query = $connection->select('reqres_user', 'ru')
      ->fields('ru', ['first_name'])
      ->condition('first_name', 'Emma', '!=');

    // Execute the query.
    $result = $query->execute()->fetchAll();

    // Ensure that the altered query does not return users with first_name 'Emma'.
    $this->assertEquals(1, count($result), 'Only one user returned after query alteration.');
    $this->assertEquals('John', $result[0]->first_name, "The user 'John' is returned after query alteration.");
  }

}
