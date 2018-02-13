<?php

namespace TwinDigital\WPTools;

use WP_UnitTestCase;

/**
 * Class FilterTest
 */
class FilterTest extends WP_UnitTestCase {

  /**
   * Setup
   * @return void
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Test filter removal from classes.
   *
   * @covers \TwinDigital\WPTools\Filter::remove()
   *
   * @return void
   */
  public function testRemove() {
    $filter   = 'testfilter';
    $class    = Filter::class;
    $function = 'remove';
    $priority = 10;
    $this->assertFalse(Filter::remove($filter, $class, $function), 'Should not be able te remove a filter that is not added');
    add_filter($filter, $function, $priority, 0);
    $this->assertTrue(Filter::remove($filter, $class, $function), 'Should be able te remove a filter that is added');
    $this->assertFalse(Filter::remove($filter, $class, $function), 'Should not be able te remove a filter that is not added');
  }
}
