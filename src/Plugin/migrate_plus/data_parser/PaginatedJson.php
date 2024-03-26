<?php

namespace Drupal\reqres_users\Plugin\migrate_plus\data_parser;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate_plus\DataParserPluginBase;
use GuzzleHttp\Client;

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

    $response = $this->httpClient->get(current($this->urls));
    $data = json_decode($response->getBody(), TRUE);

    // Extract pagination information.
    $total_pages = $data['total_pages'];
    $urls = [];

    // Generate URLs for each page of data.
    for ($page = 1; $page <= $total_pages; $page++) {
      $urls[] = $url . '?page=' . $page;
    }

    if (!empty($urls)) {
      $this->urls = $urls;
    }

  }


}
