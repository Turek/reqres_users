<?php

namespace Drupal\Tests\reqres_users\Unit;

use Drupal\Tests\UnitTestCase;
use GuzzleHttp\Client;

/**
 * Tests API connectivity and response.
 *
 * @group reqres_users
 */
class ApiConnectivityTest extends UnitTestCase {

  /**
   * The HTTP client factory.
   *
   * @var \Drupal\Core\Http\ClientFactoryInterface
   */
  protected $httpClientFactory;

  /**
   * Constructs a ApiTest object.
   */
  public function setUp() {
    parent::setUp();
    $this->httpClientFactory = $this->container->get('http_client_factory');
  }

  /**
   * Tests API connectivity and response.
   */
  public function testApiConnectivity() {
    $client = $this->httpClientFactory->fromOptions([
      'base_uri' => 'https://reqres.in',
    ]);
    $response = $client->get('/api/users');
    $this->assertEquals(200, $response->getStatusCode());
    
    $data = json_decode($response->getBody(), TRUE);
    $this->assertArrayHasKey('data', $data);
  }

}
