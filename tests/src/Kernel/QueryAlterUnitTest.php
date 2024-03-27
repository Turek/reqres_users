<?php

namespace Drupal\Tests\reqres_users\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\reqres_users\Entity\ReqresUser;

/**
 * Tests the alteration of user query.
 *
 * @group reqres_users
 */
class QueryAlterUnitTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'test_query_alter_module', 'reqres_users'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('reqres_user');

    // Assuming reqres_user is a custom entity, adjust as needed.
    ReqresUser::create(['first_name' => 'Emma', 'email' => 'emma@example.com'])->save();
    ReqresUser::create(['first_name' => 'John', 'email' => 'john@example.com'])->save();

    // Enable the module that contains the hook implementation.
    $this->enableModules(['reqres_users']);
  }

  /**
   * Tests the alteration of user query.
   */
  public function testQueryAlteration() {
    // Get the entity query service for reqres_user entities.
    $query = $this->container->get('entity_type.manager')->getStorage('reqres_user')->getQuery();
    // Add a tag to trigger the hook query alteration.
    $query->addTag('reqres_users_query');
    $query->accessCheck(FALSE);
    $ids = $query->execute();

    // Fetch the entities to verify the correct one is returned.
    $users = $this->container->get('entity_type.manager')->getStorage('reqres_user')->loadMultiple($ids);

    // Ensure that one row is responded.
    $this->assertEquals(1, count($users), 'Only one user returned after query alteration.');
    // Ensure that first returned user is John, not Emma.
    $user = current($users);
    $this->assertEquals('John', trim($user->getName()), "The user 'John' is returned after query alteration.");
  }

}
