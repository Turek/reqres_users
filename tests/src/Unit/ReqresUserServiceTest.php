<?php

namespace Drupal\Tests\reqres_users\Unit;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\reqres_users\Service\ReqresUserService;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Tests ReqresUserService.
 *
 * @coversDefaultClass \Drupal\reqres_users\Service\ReqresUserService
 */
class ReqresUserServiceTest extends UnitTestCase {

  /**
   * Tests the getUsers method.
   *
   * @covers ::getUsers
   */
  public function testGetUsers() {
    // Mock dependencies.
    $cacheBackend = $this->createMock(CacheBackendInterface::class);
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
    $logger = $this->createMock(LoggerChannelInterface::class);
    $moduleHandler = $this->createMock(ModuleHandlerInterface::class);
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityTypeManager->method('getStorage')->willReturn($entityStorage);

    $query = $this->createMock(QueryInterface::class);
    $entityStorage->method('getQuery')->willReturn($query);
    // Simulating entity IDs.
    $query->method('execute')->willReturn(['1', '2']);

    $entity = $this->createMock(EntityInterface::class);
    // Simulating loaded entities.
    $entityStorage->method('loadMultiple')->willReturn([$entity, $entity]);

    // Instantiate the service with mocked dependencies.
    $service = new ReqresUserService($cacheBackend, $entityTypeManager, $eventDispatcher, $logger, $moduleHandler);

    // Perform the method call.
    $result = $service->getUsers(10, 1);

    // Assert the expected results.
    $this->assertCount(2, $result, 'Expected two entities to be returned.');
  }

}
