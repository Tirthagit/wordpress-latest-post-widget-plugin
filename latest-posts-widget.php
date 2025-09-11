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

// define('THEME_VERSION', '1.0.0');

defined('ABSPATH') || die("You don't have access to this file");

if (file_exists(__DIR__ . "/vendor/autoload.php")) {
    require_once __DIR__ . "/vendor/autoload.php";
}

use LatestPostWidget\Includes\Admin\LPWAdminMenu as AdminMenu;
use \LatestPostWidget\Includes\Init as Init;
use \LatestPostWidget\Includes\Base\Activate as Activate;
use \LatestPostWidget\Includes\Base\Deactivate as Deactivate;

// Activate Plugin

if (!function_exists(('lpw_activate'))) {
    function lpw_activate()
    {
        Activate::activate();
    }
}
register_activation_hook(__FILE__, 'lpw_activate');


// Deactivate Plugin
if (!function_exists(('lpw_deactivate'))) {
    function lpw_deactivate()
    {
        Deactivate::deactivate();
    }
}
register_deactivation_hook(__FILE__, 'lpw_deactivate');


if (class_exists("LatestPostWidget\\Includes\\Init")) {
    Init::register_services();
}

if (class_exists("LatestPostWidget\\Includes\\Admin\\LPWAdminMenu")) {
    new AdminMenu();
}


if (!function_exists('load_admin_stylesheet')) {
    function load_admin_stylesheet()
    {
        wp_enqueue_style('latestpostwidgetadminstyle', plugin_dir_url(__FILE__) . '/assets/css/style.css', [], '1.0.0');
    }
}
add_action('admin_enqueue_scripts', 'load_admin_stylesheet');

if (!function_exists('load_admin_javascript')) {
    function load_admin_javascript()
    {
        wp_enqueue_script('latestpostwidgetadminscript', plugin_dir_url(__FILE__) . '/assets/js/script.js', array('jquery'), '1.0.0');
    }
}

add_action('admin_enqueue_scripts', 'load_admin_javascript');



