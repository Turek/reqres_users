<?php

namespace Drupal\reqres_users\Plugin\migrate_plus\data_parser;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use GuzzleHttp\Client;
use Drupal\migrate_plus\Plugin\migrate_plus\data_parser\Json;

/**
 * Obtain JSON data for migration.
 *
 * @DataParser(
 *   id = "paginated_json",
 *   title = @Translation("Paginated JSON")
 * )
 */
class PaginatedJson extends Json implements ContainerFactoryPluginInterface {

  /**
   * The HTTP client.
   */
  protected ?Client $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = \Drupal::httpClient();
    $base_url = current($this->urls);

    $response = $this->httpClient->get($base_url);
    $data = json_decode($response->getBody(), TRUE);

    // Extract pagination information.
    $total_pages = $data['total_pages'];
    $urls = [];

    // Generate URLs for each page of data.
    for ($page = 1; $page <= $total_pages; $page++) {
      $urls[] = $base_url . '?page=' . $page;
    }

    // Update list of URLs pased on pagination.
    if (!empty($urls)) {
      $this->urls = $urls;
    }
  }


}
