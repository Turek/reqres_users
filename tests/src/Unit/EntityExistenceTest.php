<?php

namespace Drupal\Tests\reqres_users\Unit;

use Drupal\reqres_users\Entity\ReqresUser;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the existence of the ReqresUser entity class.
 *
 * @group reqres_users
 */
class EntityExistenceTest extends UnitTestCase {

  /**
   * Sets up the test environment.
   */
  public function setUp(): void {
    parent::setUp();
  }

  /**
   * Tests if the custom entity class exists.
   */
  public function testEntityClassExists() {
    // Check if the class exists.
    $this->assertTrue(class_exists(ReqresUser::class), 'ReqresUser entity class exists.');
  }

}