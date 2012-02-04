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
// @todo: move this into the config file
$url = trim($_SERVER['SCRIPT_NAME'], basename(__FILE__));
define('URL_PATH', $url);

//  Block direct access to any PHP files
define('IN_CMS', true);

// Lets bootstrap our application and get it ready to run
require PATH . 'system/bootstrap.php';
