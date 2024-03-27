<?php

namespace Drupal\Tests\reqres_users\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\reqres_users\Service\ReqresUserService;

/**
 * Tests the existence of the ReqresUserService service.
 *
 * @group reqres_users
 */
class ServiceExistenceTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['reqres_users'];

  /**
   * The example service.
   *
   * @var \Drupal\reqres_users\Service\ReqresUserService
   */
  protected $reqresService;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Inject the example service.
    $this->reqresService = $this->container->get('reqres_users.service');
  }

  /**
   * Tests the existence of the ReqresUserService service.
   */
  public function testExampleServiceExists() {
    // Assert that the service exists.
    $this->assertInstanceOf(ReqresUserService::class, $this->reqresService);
  }

}
