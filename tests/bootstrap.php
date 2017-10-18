<?php

$_tests_dir = getenv('WP_TESTS_DIR');
if ($_tests_dir === false) {
  $_tests_dir = '/tmp/wordpress-tests-lib';
}

define('PLUGIN_FILE', getenv('PLUGIN_FILE'));
define('PLUGIN_FOLDER', __DIR__ . '/../');
define('PLUGIN_PATH', PLUGIN_FOLDER . '/' . PLUGIN_FILE);

if (!@include PLUGIN_FOLDER . '/vendor/autoload.php') {
  if (!@include __DIR__ . '/../vendor/autoload.php') {
    die('You must set up the project dependencies, run the following commands:
        wget http://getcomposer.org/composer.phar
        php composer.phar install');
  }
}

$GLOBALS['wp_tests_options'] = [
  'active_plugins' => [PLUGIN_PATH],
  'template'       => 'twentysixteen',
  'stylesheet'     => 'twentysixteen',
];

require_once $_tests_dir . '/includes/functions.php';

tests_add_filter(
  'muplugins_loaded',
  function () {
    // Manually load plugin
    include PLUGIN_PATH;
  }
);

tests_add_filter('shutdown', 'drop_tables', 999999);

require $_tests_dir . '/includes/bootstrap.php';
