<?php

namespace TwinDigital\WPTools;

/**
 * Class Filter
 */
class Filter {

  /**
   * Remove active filter from $wp_filter
   * @param string $hook   Hook on which the original filter was applied.
   * @param string $class  Classname.
   * @param string $method Method.
   *
   * @todo Create tests.
   *
   * @SuppressWarnings(PHPMD.Superglobals) Don't worry, I know what I'm doing.
   * @return void
   */
  public static function remove(string $hook, string $class, string $method) {
    $filters = [];
    if (array_key_exists($hook, $GLOBALS['wp_filter']) === true) {
      $filters = $GLOBALS['wp_filter'][$hook];
    }
    if (empty($filters) === true) {
      return;
    }

    foreach ($filters as $priority => $filter) {
      foreach ($filter as $function) {
        if (is_array($function) === true
            && is_a($function['function'][0], $class) === true
            && $method === $function['function'][1]
        ) {
          remove_filter(
            $hook,
            [
              $function['function'][0],
              $method,
            ],
            $priority
          );
        }
      }
    }
  }
}
