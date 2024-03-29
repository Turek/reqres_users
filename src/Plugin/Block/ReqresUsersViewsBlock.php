<?php

namespace Drupal\reqres_users\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\reqres_users\Plugin\Block\ReqresUsersBlock;
use Drupal\reqres_users\Service\ReqresUserService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a views block with users from Reqres API.
 *
 * @Block(
 *   id = "reqres_users_views_block",
 *   admin_label = @Translation("Reqres Users views"),
 * )
 */
class ReqresUsersViewsBlock extends ReqresUsersBlock {

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a new ReqresUsersBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack that controls the lifecycle of requests.
   * @param \Drupal\reqres_users\Service\ReqresUserService $reqres_user_service
   *   The service for Reqres database connectivity.
   * @param \Drupal\Core\Pager\PagerManagerInterface $pager_manager
   *   The pager manager service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RequestStack $request_stack, ReqresUserService $reqres_user_service, PagerManagerInterface $pager_manager, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $request_stack, $reqres_user_service, $pager_manager);
    $this->state = $state;
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
      $container->get('pager.manager'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    // Save values to State API for further processing.
    $values = $form_state->getValues();
    $this->state->set('reqres_users.items_per_page', $values['items_per_page']);
    $this->state->set('reqres_users.email_label', $values['email_label']);
    $this->state->set('reqres_users.first_name_label', $values['first_name_label']);
    $this->state->set('reqres_users.last_name_label', $values['last_name_label']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    try {
      // Load and configure the view programmatically.
      $view = views_embed_view('reqres_users', 'block', $this->configuration['items_per_page']);
      return $view;
    }
    catch (\Exception $e) {
      // Log error and potentially display a user-friendly error message
      // or fallback content.
      $this->logger->error('Error fetching users: @message', ['@message' => $e->getMessage()]);
      return ['#markup' => $this->t('Unable to display users at this time.')];
    }
  }

}
