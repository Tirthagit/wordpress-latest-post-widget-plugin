<?php

namespace PaktolusPostWidget\Includes\Logger;
use WP_Error;
class ErrorLogger extends WP_Error
{
    public static function log($error)
    {
        if ($error instanceof WP_Error) {
            foreach ($error->get_error_message() as $msg) {
                error_log('[PaktolusPostWidget]' . $msg);
            }
        } else if (is_string($error)) {
            error_log('[PaktolusPostWidget]' . $error);
        }
    }

    public static function admin_notice($error)
    {
        if ($error->has_errrors()) {
            set_transient('paktolus_widget_errors', $error->get_error_message(), 30);
        }
    }
}