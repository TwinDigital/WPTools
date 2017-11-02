<?php

namespace TwinDigital\WPTools;

/**
 * Class PluginTools
 * Collection of tools
 */
class PluginTools {

  /**
   * Keeps track of the loaded plugins
   * @var array $loadedPlugins
   */
  public static $loadedPlugins = [];

  /**
   * Loads the list of plugins.
   *
   * @since 1.0.0
   * @return void
   */
  public static function loadPluginList(): void {
    if (empty(self::$loadedPlugins) === true || count(self::$loadedPlugins) === 0) {
      include_once \ABSPATH . '/wp-admin/includes/plugin.php';
      $allPlugins    = get_plugins();
      $activePlugins = (array)get_option('active_plugins', []);
      foreach ($allPlugins as $k => $plugin) {
        self::$loadedPlugins[] = array_merge(
          $plugin,
          [
            'Path'   => $k,
            'Active' => (bool)(in_array($k, $activePlugins)),
          ]
        );
      }
    }
  }

  /**
   * Refreshes the loaded plugins.
   *
   * @see   \TwinDigital\WPTools\PluginTools::loadPluginList()
   * @since 1.0.0
   * @return void
   */
  public static function refreshLoadedPlugins(): void {
    wp_cache_flush();
    self::$loadedPlugins = [];
    self::loadPluginList();
  }

  /**
   * Returns plugin by name (case-sensitive)
   *
   * @param string $title Title of the plugin.
   *
   * @since 1.0.0
   * @return array|boolean False if not found, array otherwise.
   */
  public static function getPluginByTitle(string $title) {
    self::loadPluginList();
    foreach (self::$loadedPlugins as $v) {
      if ($v['Name'] === $title) {
        return $v;
      }
    }

    return false;
  }

  /**
   * Returns plugin by name (case-sensitive)
   *
   * @param string $title Title of the plugin.
   *
   * @since 1.0.0
   * @return array|boolean False if not found, array otherwise.
   */
  public static function getPluginByTitleCaseInsensitive(string $title) {
    self::loadPluginList();
    foreach (self::$loadedPlugins as $v) {
      $v['Name'] = strtolower($v['Name']);
      $title     = strtolower($title);
      if ($v['Name'] === $title) {
        return $v;
      }
    }

    return false;
  }

  /**
   * Checks if the plugin is installed and activated.
   * @param string $pluginName The Name of the plugin.
   *
   * @return boolean True if the plugin is active, false otherwise.
   */
  public static function isPluginActive(string $pluginName): bool {
    $return = false;
    $plugin = self::getPluginByTitle($pluginName);
    if ($plugin === false) {
      return false;
    }
    if (is_array($plugin) === true && array_key_exists('Name', $plugin) === true) {
      $return = $plugin['Active'];
    }

    return $return;
  }
}
