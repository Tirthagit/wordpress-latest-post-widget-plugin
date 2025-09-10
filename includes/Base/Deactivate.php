<?php
/**
 * 
 *@package Latest Post Widget
 */
namespace LatestPostWidget\Includes\Base;

class Deactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}