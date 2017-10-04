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
   * @var array $_loadedPlugins
   * @since 1.0.0
   */
  private static $_loadedPlugins = [];

  /**
   * Loads the list of plugins.
   *
   * @param boolean $force_reload Forces reload of the loaded plugins. Also flushes cache.
   *
   * @since 1.0.0
   * @return void
   */
  private static function _initLoadedPlugins(bool $force_reload = false): void {
    if ($force_reload === true) {
      wp_cache_flush();
      self::$_loadedPlugins = [];
    }
    if (empty(self::$_loadedPlugins) === true) {
      if (function_exists('get_plugins') === false) {
        include_once ABSPATH . '/wp-admin/includes/plugin.php';
      }
      $all_plugins = get_plugins();
      $active_plugins = (array)get_option('active_plugins', []);
      foreach ($all_plugins as $k => $plugin) {
        self::$_loadedPlugins[] = array_merge(
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
   * @see   \TwinDigital\WPTools\PluginTools::_initLoadedPlugins()
   * @since 1.0.0
   * @return void
   */
  public static function refreshLoadedPlugins(): void {
    self::_initLoadedPlugins(true);
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
    if (empty(self::$_loadedPlugins) === true) {
      self::_initLoadedPlugins();
    }
    foreach (self::$_loadedPlugins as $k => $v) {
      if ($case_sensitive === true) {
        $v['Name'] = strtolower($v['Name']);
        $title = strtolower($title);
      }
      if (isset($v['Name']) === true && $v['Name'] === $title) {
        return $v;
      }
    }

    return false;
  }
}
