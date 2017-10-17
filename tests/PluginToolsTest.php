<?php

use PHPUnit\Framework\TestCase;
use TwinDigital\WPTools\PluginTools;

class PluginToolsTest extends TestCase {

  /**
   * Instance of the class.
   *
   * @var \TwinDigital\WPTools\PluginTools $class
   */
  public $class;

  /**
   * Tests if the plugin_list is loaded.
   *
   * @covers PluginTools::loadPluginList()
   * @return void
   */
  public function testLoadPluginList() {
    $this->assertNotCount(0, PluginTools::$loadedPlugins, 'Pluginlist is empty, probably failed loading the list of plugins.');
  }
  /**
   * Tests if the plugin_list is loaded.
   *
   * @covers PluginTools::loadPluginList()
   * @return void
   */
  public function testLoadPluginListForced() {
    $this->assertNotCount(0, PluginTools::$loadedPlugins, 'Pluginlist is empty, probably failed loading the list of plugins.');
  }

  public function testRefreshLoadedPlugins() {
    PluginTools::$loadedPlugins = null;
    PluginTools::refreshLoadedPlugins();
    $this->assertNotCount(0, PluginTools::$loadedPlugins, 'Pluginlist is empty, probably failed loading the list of plugins.');
  }

  public function setUp() {
    $this->class = PluginTools::class;
  }

  public function tearDown() {
    parent::tearDown();
  }
}
