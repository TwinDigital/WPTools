<?php

use TwinDigital\WPTools\PluginTools;

class PluginToolsTest extends WP_UnitTestCase {

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

  /**
   * Tests if getting a plugin by name is working as it should.
   *
   * @covers \TwinDigital\WPTools\PluginTools::getPluginByTitle()
   * @return void
   */
  public function testGetPluginByTitle() {
    PluginTools::refreshLoadedPlugins();
    $this->assertEmpty(PluginTools::getPluginByTitle('Non-existing-plugin'), 'Found a plugin that is non-existing? Oops');
    $list_of_plugins_to_try = [
      'Akismet Anti-Spam',
      'Hello Dolly',
    ];
    $plugin_details = null;
    foreach ($list_of_plugins_to_try as $plugin) {
      $plugin_details = PluginTools::getPluginByTitle($plugin);
      if ($plugin_details !== false) {
        break;
      }
    }
    $this->assertNotEmpty($plugin_details, 'Current plugin is not active?');

    // Should not fail
    $list_of_plugins_to_try = [
      'Akismet Anti-Spam',
      'Hello Dolly',
    ];
    $plugin_details = null;
    $installed_plugin = null;
    foreach ($list_of_plugins_to_try as $plugin) {
      $plugin_details = PluginTools::getPluginByTitle(strtolower($plugin), false);
      if ($plugin_details !== false) {
        $installed_plugin = $plugin;
        break;
      }
    }
    $this->assertNotEmpty($plugin_details, 'Current plugin is not active?');

    // This should fail
    $plugin_details = null;
    $plugin_details = PluginTools::getPluginByTitle(strtolower($installed_plugin), false);
    $this->assertEmpty($plugin_details, 'Current plugin is not active?');
  }
}
