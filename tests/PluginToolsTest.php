<?php

use PHPUnit\Framework\TestCase;
use TwinDigital\WPTools\PluginTools;

class PluginToolsTest extends TestCase {

  /**
   * Tests if the plugin_list is loaded.
   *
   * @covers \TwinDigital\WPTools\PluginTools::loadPluginList()
   * @return void
   */
  public function testLoadPluginList() {
    $this->assertNotCount(0, PluginTools::$loadedPlugins, 'Pluginlist is empty, probably failed loading the list of plugins.');
  }

  /**
   * Tests if the plugin_list is loaded.
   *
   * @covers \TwinDigital\WPTools\PluginTools::loadPluginList()
   * @return void
   */
  public function testLoadPluginListForced() {
    PluginTools::loadPluginList(true);
    $this->assertNotCount(0, PluginTools::$loadedPlugins, 'Pluginlist is empty, probably failed loading the list of plugins.');
  }

  /**
   * Tests if the plugin_list is loaded.
   *
   * @covers \TwinDigital\WPTools\PluginTools::refreshLoadedPlugins()
   * @return void
   */
  public function testRefreshLoadedPlugins() {
    PluginTools::$loadedPlugins = null;
    PluginTools::refreshLoadedPlugins();
    $this->assertNotCount(0, PluginTools::$loadedPlugins, 'Pluginlist is empty, probably failed loading the list of plugins.');
  }
}
