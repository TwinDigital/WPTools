<?php

namespace TwinDigital\WPTools;

use WP_UnitTestCase;

/**
 * Class FilterTest
 */
class FilterTest extends WP_UnitTestCase
{

    /**
     * Setup
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test filter removal from classes.
     *
     * @return void
     */
    public function testRemove()
    {
        $filter   = 'testfilter';
        $class    = Filter::class;
        $function = 'remove';
        $priority = 10;
        $this->assertFalse(
            Filter::remove($filter, $class, $function),
            'Should not be able te remove a filter that is not added'
        );
        $filter   = 'testfilter';
        $class    = Filter::class;
        $function = 'remove';
        $priority = 10;
        add_filter($filter, [$class, $function], $priority, 0);
        $this->assertTrue(
            Filter::remove($filter, $class, $function),
            'Should be able te remove a filter that is added'
        );
        add_filter($filter, [$class, 'test'], $priority, 0);
        $this->assertFalse(
            Filter::remove($filter, $class, $function),
            'Should not be able to remove a filter of which the function doesn\'t exist'
        );
        $this->assertFalse(
            Filter::remove($filter, $class, $function),
            'Should not be able te remove a filter that is not added'
        );
    }
}
