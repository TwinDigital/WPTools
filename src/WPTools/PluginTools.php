<?php

namespace TwinDigital\WPTools;

/**
 * Class PluginTools
 * Collection of tools
 * @package TwinDigital\WPTools
 * @version 1.0.0
 */
class PluginTools {

  /**
   * Keeps track of the loaded plugins
   *
   * @var array $loadedPlugins
   * @since 1.0.0
   */
  public static $loadedPlugins = [];

  /**
   * Loads the list of plugins.
   *
   * @param boolean $force_reload Forces reload of the loaded plugins. Also flushes cache.
   *
   * @since 1.0.0
   * @return void
   */
  public static function loadPluginList(bool $force_reload = false): void {
    if ($force_reload === true) {
      wp_cache_flush();
      self::$loadedPlugins = [];
    }
    if (empty(self::$loadedPlugins) === true) {
      include_once \ABSPATH . '/wp-admin/includes/plugin.php';
      $all_plugins = get_plugins();
      $active_plugins = (array)get_option('active_plugins', []);
      foreach ($all_plugins as $k => $plugin) {
        self::$loadedPlugins[] = array_merge(
          $plugin,
          [
            'Path'   => $k,
            'Active' => (bool)(in_array($k, $active_plugins)),
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
    self::loadPluginList(true);
  }

  /**
   * Returns plugin by name (case-sensitive)
   *
   * @param string  $title          Title of the plugin.
   * @param boolean $case_sensitive Wether the title-search should be case-sensitive, true by default.
   *
   * @since 1.0.0
   * @return array|boolean False if not found, array otherwise.
   */
  public static function getPluginByTitle(string $title, bool $case_sensitive = true) {
    self::loadPluginList();
    foreach (self::$loadedPlugins as $k => $v) {
      if ($case_sensitive === false) {
        $v['Name'] = strtolower($v['Name']);
        $title = strtolower($title);
      }
      if ($v['Name'] === $title) {
        return $v;
      }
    }

    return false;
  }
}
