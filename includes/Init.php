<?php
declare(strict_types=1);

namespace LatestPostWidget\Includes;

use LatestPostWidget\Includes\Api\LatestPostsWidget;
use WP_Error;

defined('ABSPATH') || exit;


class Init
{
    public function __construct()
    {
        add_action('widgets_init', [$this, 'register_widget']);
    }

    public function register_widget(): void
    {
        if (!class_exists(LatestPostsWidget::class)) {
            error_log((new \WP_Error('widget_missing', 'Widget class not found'))->get_error_message());
            return;
        }
        register_widget(LatestPostsWidget::class);
    }

    private static function instantiate($classname)
    {
        return new $classname;
    }

    public static function register_services()
    {
        return self::instantiate(self::class);
    }
}