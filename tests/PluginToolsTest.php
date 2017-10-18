<?php

use TwinDigital\WPTools\PluginTools;

/**
 * Class PluginToolsTest
 */
class PluginToolsTest extends WP_UnitTestCase {

  /**
   * Plugins that come pre-installed with WordPress.
   *
   * @var array $preinstalledPlugins
   */
  public $preinstalledPlugins = [
    'Akismet Anti-Spam',
    'Hello Dolly',
  ];

  /**
   * Tests if the plugin_list is loaded.
   *
   * @covers \TwinDigital\WPTools\PluginTools::loadPluginList()
   * @return void
   */
  public function testLoadPluginList() {
    PluginTools::$loadedPlugins = [];
    PluginTools::loadPluginList();
    $this->assertNotCount(0, PluginTools::$loadedPlugins, 'Pluginlist is empty, probably failed loading the list of plugins.');
  }

  /**
   * Tests if the plugin_list is loaded.
   *
   * @covers \TwinDigital\WPTools\PluginTools::refreshLoadedPlugins()
   * @return void
   */
  public function testRefreshLoadedPlugins() {
    PluginTools::$loadedPlugins = [];
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
    $pluginDetails = null;
    foreach ($this->preinstalledPlugins as $plugin) {
      $pluginDetails = PluginTools::getPluginByTitle($plugin);
      if ($pluginDetails !== false) {
        break;
      }
    }
    $this->assertNotEmpty($pluginDetails);
    $this->assertNotCount(0, $pluginDetails);
  }

  /**
   * Tests if getting a plugin by name is working as it should.
   *
   * @covers \TwinDigital\WPTools\PluginTools::getPluginByTitleCaseInsensitive()
   * @return void
   */
  public function testGetPluginByTitleCaseInsensitive() {
    PluginTools::refreshLoadedPlugins();
    $this->assertEmpty(PluginTools::getPluginByTitleCaseInsensitive(strtolower('Non-existing-plugin')), 'Found a plugin that is non-existing? Oops');
    $pluginDetails = null;
    foreach ($this->preinstalledPlugins as $plugin) {
      $pluginDetails = PluginTools::getPluginByTitleCaseInsensitive(strtolower($plugin));
      if ($pluginDetails !== false) {
        break;
      }
    }
    $this->assertNotEmpty($pluginDetails);
    $this->assertNotCount(0, $pluginDetails);
  }
}
