<?php

namespace Drupal\Tests\reqres_users\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\reqres_users\Entity\ReqresUser;

/**
 * Test creating data in a kernel test.
 *
 * @ingroup reqres_users
 */
class EntityCreationTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   *
   * @var string[]
   */
  protected static $modules = ['reqres_users'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Install *entity* schema for the reqres_user entity.
    $this->installEntitySchema('reqres_user');
  }

  /**
   * Create a user and check if it was created.
   */
  public function testEntityCreation() {
    /** @var \Drupal\reqres_users\Entity\ReqresUser $user */
    $user = ReqresUser::create([
      'id' => 1,
      'email' => 'test@email.com',
      'first_name' => 'Emma',
      'last_name' => 'Wong',
    ]);

    // Assert that the user created has the name we expect.
    $this->assertEquals('Emma', $user->getFirstName());
  }

}
