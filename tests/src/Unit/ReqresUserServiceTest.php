<?php

namespace Drupal\Tests\reqres_users\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\reqres_users\Service\ReqresUserService;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\Core\Entity\EntityInterface;

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
    // Mock dependencies
    $cacheBackend = $this->createMock(CacheBackendInterface::class);
    $entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
    $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
    $logger = $this->createMock(LoggerChannelInterface::class);
    $moduleHandler = $this->createMock(ModuleHandlerInterface::class);

    // Setup mock behavior as necessary, for example:
    $entityStorage = $this->createMock(EntityStorageInterface::class);
    $entityTypeManager->method('getStorage')->willReturn($entityStorage);

    $query = $this->createMock(QueryInterface::class);
    $entityStorage->method('getQuery')->willReturn($query);
    $query->method('execute')->willReturn(['1', '2']); // Simulating entity IDs.

    $entity = $this->createMock(EntityInterface::class);
    $entityStorage->method('loadMultiple')->willReturn([$entity, $entity]); // Simulating loaded entities.

    // Instantiate the service with mocked dependencies.
    $service = new ReqresUserService($cacheBackend, $entityTypeManager, $eventDispatcher, $logger, $moduleHandler);

    // Perform the method call
    $result = $service->getUsers(10, 1); // Adjust arguments as necessary

    // Assert the expected results
    $this->assertCount(2, $result, 'Expected two entities to be returned.');
  }
  
}