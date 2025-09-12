<?php

defined('WP_UNINSTALL_PLUGIN') || exit;

// Remove widget instances
delete_option('widget_latest-post-widget');

// Cleanup sidebar assignments
$sidebars = get_option('sidebar_widgets');
if (is_array($sidebars)) {
    foreach ($sidebars as $sidebar => $widgets) {
        if (is_array($widget)) {
            $sidebars[$sidebar] = array_filter($widgets, function ($widget) {
                return strpos($widget, 'latest-post-widget') != 0;
            });
        }
    }
    update_option($sidebars);
}
// Clear any transients plugin might have created
delete_transient('latest_post_widget_errors');

// Clear object name
wp_cache_flush();