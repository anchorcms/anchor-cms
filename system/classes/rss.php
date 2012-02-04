<?php defined('IN_CMS') or die('No direct access allowed.');

class RSS {
    public static function headers() {
        //  Set the RSS header
        header('Content-Type: application/rss+xml; charset=ISO-8859-1');
        
        //  And echo the encoding
        echo '<?xml version="1.0" encoding="UTF-8" ?>';
        
    }
}
