<?php

namespace Drupal\Tests\reqres_users\Unit;

use Drupal\Tests\UnitTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Tests API connectivity and response.
 *
 * @group reqres_users
 */
class ApiConnectivityTest extends UnitTestCase {

  /**
   * Tests successful API response using Guzzle.
   */
  public function testSuccessfulApiResponse() {
    // Create a Guzzle client.
    $client = new Client();

    // API endpoint to test.
    $url = 'https://reqres.in/api/users';

    try {
      $response = $client->get($url);

      // Assert the status code is 200.
      $this->assertEquals(200, $response->getStatusCode());

      // Decode the JSON response.
      $data = json_decode($response->getBody(), TRUE);

      // Check if it's an array (assuming each user is an object
      // in the response).
      if (is_array($data['data'])) {
        // Get the first user object.
        $user = reset($data['data']);

        // Expected fields in the response.
        $expectedFields = ['id', 'first_name', 'last_name', 'email'];

        // Check if all expected fields exist in the user object.
        $missingFields = array_diff($expectedFields, array_keys($user));

        // Assert there are no missing fields.
        $this->assertEmpty($missingFields, 'Missing expected fields in the response: ' . implode(', ', $missingFields));
      }
      else {
        $this->fail('Unexpected response format. Expected an array.');
      }

    }
    catch (RequestException $e) {
      $this->fail('Failed to connect to API: ' . $e->getMessage());
    }
  }

}
