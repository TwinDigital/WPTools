<?php
/**
 * WPTools
 * @package    TwinDigital
 * @subpackage WPTools
 * @author     Twin Digital <info@twindigital.nl>
 * @copyright  2017 Twin Digital
 * Plugin Name: WPTools
 * Plugin URI: https://twindigital.nl/
 * Description: WPTools
 * Text Domain: twindigital-wptools
 * Author: Twin Digital
 * Version: 0.5.0-RC2
 * Author URI: https://twindigital.nl/
 */

use TwinDigital\WPTools\PluginTools;

if (defined('ABSPATH') === false) {
    exit;
}
require __DIR__ . '/vendor/autoload.php';
add_action('plugins_loaded', PluginTools::loadPluginList());
