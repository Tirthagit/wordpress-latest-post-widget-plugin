<?php
/**
 * 
 *@package Paktolus Post Widget
 */
namespace PaktolusPostWidget\Includes\Base;

class Deactivate
{
    public static function deactivate()
    {
        
        flush_rewrite_rules();
    }
}