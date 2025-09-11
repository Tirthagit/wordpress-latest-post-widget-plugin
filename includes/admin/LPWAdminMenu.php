<?php

declare(strict_types=1);

namespace LatestPostWidget\Includes\Admin;
use LatestPostWidget\Includes\View\AdminView as View;
if (!defined('ABSPATH')) {
    exit;
}

class LPWAdminMenu
{
    private static $icons_url = null;
    public function __construct()
    {
        add_action('admin_menu', [self::class, 'lpw_options_page']);
    }

    public static function init()
    {
        if (self::$icons_url === null) {
            self::$icons_url = plugins_url('latest-posts-widget/assets/images/settings.svg', WP_PLUGIN_DIR . '/latest-posts-widget');
        }
    }

    public static function get_icon_url(string $class = 'dashboard-icon', string $alt = 'Dashboard')
    {
        if (self::$icons_url === null) {
            self::init();
        }
        return self::$icons_url;
    }

    public static function lpw_options_page()
    {
        /*
        add_menu_page(
            string $page_title,
            string $menu_title,
            string $capability,
            string $menu_slug,
            callable $function = '',
            string $icon_url = '',
            int $position = null
        );
         */
        add_menu_page(
            'Latest Post Widget',                   // $parent_slug → Settings menu
            'Latest Post Widget',                   // $page_title → Appears on the settings page itself
            'manage_options',                       // $capability → Only admin-level users can access   
            'latest-post-widget-settings',          // $menu_slug → URL slug: ?page=paktolus-post-widget-settings
            [new self(), 'lpw_options_page_html'],            // $function → Callback function to render the settings page
            // 'dashicons-welcome-widgets-menus',   // $icon_url → Icon to render 
            self::get_icon_url(),
            10                                      // $position = null 
        );
    }

    public function lpw_options_page_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                // output security fields for the registered setting "wporg_options"
                settings_fields('wporg_options');
                // output setting sections and their fields
                // (sections are registered for "wporg", each field is registered to a specific section)
                do_settings_sections('wporg');
                // output save settings button
                submit_button(__('Save Settings', 'latestpostwidget'));
                ?>
            </form>
        </div>
        <?php
    }
}
