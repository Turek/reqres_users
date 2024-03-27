<?php

namespace Drupal\Tests\reqres_users\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Tests the API connectivity.
 *
 * @group reqres_users
 */
trait ApiTestTrait {

  /**
   * Performs an API call.
   */
  protected function performApiCall() {
    try {
      // Create a Guzzle HTTP client.
      $httpClient = new Client();

      // Define the API endpoint URL.
      $apiUrl = 'https://reqres.in/api/users';

      // Send a GET request to the API endpoint.
      $response = $httpClient->request('GET', $apiUrl);

      // Check if the response status code is 200.
      if ($response->getStatusCode() !== 200) {
        throw new \RuntimeException('API request failed with status code ' . $response->getStatusCode());
      }

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
      // Handle any request errors.
      throw new \RuntimeException('Failed to make request to the API: ' . $e->getMessage());
    }
  }

}
