<?php

namespace Drupal\reqres_users\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\reqres_users\Service\ReqresUserService;

/**
 * Provides a block with users from Reqres.in API.
 *
 * @Block(
 *   id = "reqres_users_block",
 *   admin_label = @Translation("Reqres Users"),
 * )
 */
class ReqresUsersBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Request stack that controls the lifecycle of requests.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Service for Reqres database connectivity.
   *
   * @var \Drupal\reqres_users\Service\ReqresUserService
   */
  protected $reqresUserService;

  /**
   * Constructs a new ReqresUsersBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param RequestStack $request_stack
   *   Request stack that controls the lifecycle of requests.
   * @param ReqresUserService $reqres_user_service
   *   Service for Reqres database connectivity.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RequestStack $request_stack, ReqresUserService $reqres_user_service, ClientInterface $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->requestStack = $request_stack;
    $this->reqresUserService = $reqres_user_service;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack'),
      $container->get('reqres_users.service'),
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'items_per_page' => 10,
      'email_label' => $this->t('Email'),
      'first_name_label' => $this->t('Forename'),
      'last_name_label' => $this->t('Surname'),
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['items_per_page'] = [
      '#type' => 'number',
      '#title' => $this->t('Items per page'),
      '#default_value' => $config['items_per_page'],
      '#min' => 1,
    ];

    $form['email_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email field label'),
      '#default_value' => $config['email_label'],
    ];

    $form['first_name_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Forename field label'),
      '#default_value' => $config['first_name_label'],
    ];

    $form['last_name_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname field label'),
      '#default_value' => $config['last_name_label'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();
    $this->configuration['items_per_page'] = $values['items_per_page'];
    $this->configuration['email_label'] = $values['email_label'];
    $this->configuration['first_name_label'] = $values['first_name_label'];
    $this->configuration['last_name_label'] = $values['last_name_label'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the current page from the request.
    $request = $this->requestStack->getCurrentRequest();

    // Get the configuration.
    $config = $this->getConfiguration();
    $limit = $config['items_per_page'];

    // Initialize pager manager and the pager.
    $pager_manager = \Drupal::service('pager.manager');
    $pager = $pager_manager->createPager($this->reqresUserService->getTotalRows(), $limit);
    $page = $pager->getCurrentPage();

    // Fetch users from the database using the service.
    $users = $this->reqresUserService->getUsers($limit, $page);

    // Prepare table header.
    $header = [
      ['data' => $config['email_label']],
      ['data' => $config['first_name_label']],
      ['data' => $config['last_name_label']],
    ];

    // Build rows for the table.
    $rows = [];
    foreach ($users as $user) {
      $rows[] = [
        $user->getEmail(),
        $user->getFirstName(),
        $user->getLastName(),
      ];
    }

    // Build the render array.
    $build = [
      'table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => $this->t('No users found.'),
      ],
      'pager' => [
        '#type' => 'pager',
      ],
    ];

    return $build;
  }

}
