<?php

namespace Drupal\reqres_users\Plugin\migrate_plus\data_parser;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate_plus\DataParserPluginBase;
use Drupal\migrate_plus\Plugin\migrate_plus\data_parser\Json;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a PaginatedJson object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \GuzzleHttp\ClientInterface $httpClient
   *   The HTTP client service.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ClientInterface $httpClient, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $httpClient;
    $this->logger = $logger;
    $this->initializeUrls();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): DataParserPluginBase {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client'),
      $container->get('logger.channel.reqres_users')
    );
  }

  /**
   * Initializes the URLs based on pagination data from the first request.
   */
  protected function initializeUrls() {
    $base_url = current($this->urls);
    try {
      $response = $this->httpClient->get($base_url);
      $data = json_decode((string) $response->getBody(), TRUE);

      // Extract pagination information and update URLs.
      if (isset($data['total_pages'])) {
        $this->updateUrlsBasedOnPagination($base_url, $data['total_pages']);
      }
    }
    catch (\Exception $e) {
      $this->logger->error('Failed to fetch initial data from @url: @message', [
        '@url' => $base_url,
        '@message' => $e->getMessage(),
      ]);
    }
  }

  /**
   * Updates the URLs based on the total number of pages.
   *
   * @param string $base_url
   *   The base URL.
   * @param int $total_pages
   *   The total number of pages.
   */
  protected function updateUrlsBasedOnPagination($base_url, $total_pages) {
    $urls = [];
    for ($page = 1; $page <= $total_pages; $page++) {
      $urls[] = $base_url . '?page=' . $page;
    }
    $this->urls = $urls;
  }

}
