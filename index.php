<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @visualidiot, with thanks to @kieronwilson, @spenserj and a bunch of other contributors.
 *    You're all great.
 */

//  Set the include path
define('PATH', __DIR__ . '/');

//  Set a URL path, in case Anchor gets installed on a subpage
$url = str_ireplace('index.php', '', $_SERVER['SCRIPT_NAME']);
if(substr($url, 0, 1) !== '/') $url = '/' . $url;
define('URL_PATH', $url);

//  Block direct access to any PHP files
define('IN_CMS', true);

// Lets bootstrap our application and get it ready to run
require PATH . 'system/bootstrap.php';
