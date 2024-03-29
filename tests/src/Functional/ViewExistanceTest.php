<?php

namespace Drupal\Tests\reqres_users\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the existence and output of 'reqres_users' View and its block.
 *
 * @group my_module
 */
class ViewExistanceTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['block', 'reqres_users', 'views'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'claro';

  public function testViewExistenceAndBlockOutput() {
    // This line is optional, only needed if you want to assert the entity type for some reason.
    $this->assertTrue(\Drupal::entityTypeManager()->hasDefinition('reqres_user'), 'The reqres_user entity type is available.');

    // Correctly load the View using its ID and assert it's not NULL.
    $view = \Drupal::entityTypeManager()->getStorage('view')->load('reqres_users');
    $this->assertNotEmpty($view, 'The view exists.');

    // Place the block.
    $this->drupalPlaceBlock('views_block:reqres_users-block', [
      'region' => 'content',
      'settings[label]' => 'Reqres Users views',
    ]);

    // Visit a page where the block should be visible.
    $this->drupalGet('<front>');

    // Check that the block outputs to the browser.
    $assert_session = $this->assertSession();
    $assert_session->pageTextContains('Reqres Users views');
  }

}
