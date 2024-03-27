<?php

namespace Drupal\Tests\reqres_users\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the display of the custom block with configuration.
 *
 * @group reqres_users
 */
class CustomBlockDisplayTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['block', 'reqres_users'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'claro';

  /**
   * Tests block placement and configuration.
   */
  public function testBlockDisplay() {
    $block_id = 'reqres_users_block'; // Replace with your custom block's plugin ID.
    // Create a random title for the block.
    $title = $this->randomMachineName(8);
    // Retrieve the default theme.
    $default_theme = $this->config('system.theme')->get('default');
    $edit = [
      'id' => $this->randomMachineName(8),
      'region' => 'content',
      'settings[label]' => $title,
      'settings[label_display]' => TRUE,
      'settings[items_per_page]' => '5', // Assuming 'items_per_page' is a custom setting.
      'settings[email_label]' => 'Email Address', // Assuming 'email_label' is a custom setting.
      'settings[first_name_label]' => 'Forename', // Assuming 'first_name_label' is a custom setting.
      'settings[last_name_label]' => 'Surname', // Assuming 'last_name_label' is a custom setting.
    ];

    // Navigate to the block placement URL for the default theme.
    $this->drupalGet('admin/structure/block/add/' . $block_id . '/' . $default_theme);
    
    // Submit the form with the specified edit values.
    $this->submitForm($edit, 'Save block');

    // Assert the block configuration has been saved successfully.
    $this->assertSession()->pageTextContains('The block configuration has been saved.');

    // Navigate to a page where the block should be visible based on its configuration.
    $this->drupalGet('<front>');

    // Assert the block is present and contains the configured header values.
    $this->assertSession()->pageTextContains($title);
    $this->assertSession()->responseContains('Email Address');
    $this->assertSession()->responseContains('Forename');
    $this->assertSession()->responseContains('Surname');

    $rows = $this->getSession()->getPage()->findAll('css', '.block-reqres-users tbody tr');
    // Assertion to check for 5 rows in the table.
    $this->assertCount(5, $rows, 'The table contains 5 rows plus the header row.');
  }

}
