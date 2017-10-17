<?php
require __DIR__ . '/../vendor/autoload.php';
$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
  $_tests_dir = '/tmp/wordpress-tests-lib';
}
// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';
define('TD_WPTOOLS_TESTS', true);
/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
  //TODO: replace <plugin-sample> with your plugin name
  include __DIR__ . '/../WPTools.php';
}

tests_add_filter('muplugins_loaded', '_manually_load_plugin');
tests_add_filter('wp_die_handler', function () {
  exit(1);
});
// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
