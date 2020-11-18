<?php

namespace TwinDigital\WPTools\Router;

use WP_UnitTestCase;

class RouterTest extends WP_UnitTestCase
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
     * @return void
     */
    public function testSetupRoutes()
    {
        $this->set_permalink_structure('/%postname%/');
        add_action('testhook', '__return_true');
        add_filter(
            'td_wptools_route_routes',
            function () {
                return
                    [
                        'testroute'          =>
                            (new Route(
                                'testhookurl',
                                'testhook',
                                'page'
                            )),
                        'testroute_fullpath' =>
                            (new Route(
                                'testhookurl_fullpath',
                                'testhook',
                                __DIR__ . '/bootstrap.php'
                            )),
                    ];
            },
            10,
            1
        );

        $router = Router::init();
        $this->assertFalse(
            get_option($router->getRouteVariable() . '_hash'),
            'router-compile should not be saved yet'
        );

        $router->registerRoutes();
        $this->assertNotEmpty(get_option($router->getRouteVariable() . '_hash'));

        flush_rewrite_rules(true);
        $this->assertEquals('a', $router->loadRouteTemplate('a'));

        $this->go_to(site_url('/'));
        $this->assertTrue(is_home());
        $this->go_to(site_url('/testhookurl'));
        $this->assertFalse(is_404());
        $this->assertEquals(1, did_action('init'));
        $this->assertEquals(1, did_action('testhook'));
        $this->assertStringEndsWith('page.php', $router->loadRouteTemplate('post'));
        $this->go_to(site_url('/testhookurl_fullpath'));
        $this->assertStringEndsWith('bootstrap.php', $router->loadRouteTemplate('post'));

        $this->go_to(site_url('/'));
        $router->deleteRoute('testroute');
        $this->expectException('WPDieException');
        $this->go_to(site_url('/testhookurl'));
    }
}
