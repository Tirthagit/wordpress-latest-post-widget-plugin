<?php

namespace LatestPostWidget\Includes\Logger;
use WP_Error;

/**
 * ErrorLogger utility class for handling and displaying plugin errors.
 *
 * This class provides methods to:
 * - Log errors to WordPress/PHP error log
 * - Store error messages in a transient
 * - Display them as admin notices
 *
 * @package LatestPostWidget\Includes\Logger;
 */
class ErrorLogger extends WP_Error
{
    /**
     * Logs an error to WordPress debug log or PHP debug log
     * @param string|WP_Error $error Error message or WP_Error instance
     * @return void
     */
    public static function log($error): void
    {
        if ($error instanceof WP_Error) {
            foreach ($error->get_error_message() as $msg) {
                error_log('[LatestPostWidget]' . $msg);
            }
        } else if (is_string($error)) {
            error_log('[LatestPostWidget]' . $error);
        } else {
            error_log('[LatestPostWidget] Unknow error type passed to logger');
        }
    }

    /**
     * Stores WP_Error message in a transient and registers admin notice rendering
     * @param WP_Error $error A WP_Error object containing one or more error messages
     * @return void
     */
    public static function admin_notice(WP_Error $error): void
    {
        if ($error->has_errrors()) {
            set_transient('latest_post_widget_errors', $error->get_error_message(), 30);
            add_action('admin_notices', [self::class, 'render_admin_notice']);
        }
    }

    /**
     * Renders admin notice for stored error messages
     * @return void
     */
    public function render_admin_notice(): void
    {
        $messages = get_transient('latest_post_widget_errors');

        if ($messages && is_array($messages)) {
            echo '<div class="notice notice-error is-dismissible">';
            foreach ($messages as $msg) {
                echo '<p>' . esc_html($msg) . '</p>';
            }
            echo '</div>';
        }

        // Clear after displaying once
        delete_transient('paktolus_widget_errors');
    }
}