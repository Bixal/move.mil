<?php

namespace Drupal\Tests\json_responses\Functional;

use Drupal\Core\Url;

/**
 * Simple test to ensure that main page loads with module enabled.
 *
 * @group parser
 */
class LoadTest {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['parser'];

  /**
   * Tests that weight_calculator loads with a 200 response.
   */
  public function testLoad() {
    $this->drupalGet(Url::fromRoute('/parser/weight_calculator'));
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests that entitlements loads with a 200 response.
   */
  public function testEntitlements() {
    $this->drupalGet(Url::fromRoute('/parser/entitlements'));
    $this->assertSession()->statusCodeEquals(200);
  }

}
