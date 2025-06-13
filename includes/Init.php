<?php
declare(strict_types=1);

namespace PaktolusPostWidget\Includes;

use PaktolusPostWidget\Includes\Api\PaktolusWidget;

defined('ABSPATH') || exit;


class Init
{
    public function __construct()
    {
        add_action('widgets_init', [$this, 'register_widget']);
    }

    public function register_widget(): void
    {
        if (!class_exists(PaktolusWidget::class)) {
            error_log((new \WP_Error('widget_missing', 'Widget class not found'))->get_error_message());
            return;
        }
        register_widget(PaktolusWidget::class);
    }

    private static function instantiate($classname)
    {
        return new $classname;
    }

    public static function register_services()
    {
        return self::instantiate(Init::class);
    }
}