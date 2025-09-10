<?php
/**
 * 
 *@package Latest Post Widget
 */
namespace LatestPostWidget\Includes\Base;

class Activate
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}