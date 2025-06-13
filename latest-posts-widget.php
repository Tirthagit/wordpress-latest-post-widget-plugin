<?php
/**
 * Latest Posts Widget
 *
 * @package           Latest Posts Widget
 * @author            Tirtharaj Pati
 * @copyright         2025 Tirtharaj Pati
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Latest Posts Widget
 * Plugin URI:        https://example.com/plugin-name
 * Description:       This is a plugin to show latest posts in a widget format and also allows to categorising the posts. Simple and flexible.
 * Version:           1.0.0
 * Requires at least: 6.4.5
 * Requires PHP:      7.4.3
 * Author:            Tirtharaj Pati
 * Author URI:        https://example.com
 * Text Domain:       latestpostswidget
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */
declare(strict_types=1);

use \PaktolusPostWidgets\Includes\Init as Init;
use PaktolusPostWidget\Includes\Base as Base;

require_once plugin_dir_path(__FILE__) . '/includes/Init.php';
require_once plugin_dir_path(__FILE__) . '/includes/api/PaktolusWidget.php';


defined('ABSPATH') || die("You don't have access to this file");

if (file_exists(dirname(__FILE__) . "/vendor/autoload.php")) {
    require_once __DIR__ . "/vendor/autoload.php";
}

// Activate Plugin

if (!function_exists(sanitize_text_field('activate_paktolus_plugin'))) {
    function activate_paktolus_plugin()
    {
        \PaktolusPostWidget\Includes\Base\Activate::activate();
    }
}
register_activation_hook(__FILE__, 'activate_paktolus_plugin');


// Deactivate Plugin
if (!function_exists(sanitize_text_field('deactivate_paktolus_plugin'))) {
    function deactivate_paktolus_plugin()
    {
        \PaktolusPostWidget\Includes\Base\Deactivate::deactivate();
    }
}
register_deactivation_hook(__FILE__, 'deactivate_paktolus_plugin');


if (class_exists("LatestPostWidget\\Includes\\Init")) {
    Init::register_services();
}

