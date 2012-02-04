<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @visualidiot, with thanks to @kieronwilson, @spenserj and a bunch of other contributors.
 *    You're all great.
 */

//  Set the include path
define('PATH', __DIR__ . '/');

//  Check if the .htaccess file is present
if(file_exists('.htaccess')) {
    //  Set a URL path, in case Anchor gets installed on a subpage
    // @todo: move this into the config file
    define('URL_PATH', trim($_SERVER['SCRIPT_NAME'], basename(__FILE__)));
} else {
    //  Set a fallback include path
    define('URL_PATH', $_SERVER['SCRIPT_NAME'] . '/');
}

//  Block direct access to any PHP files
define('IN_CMS', true);

//  Anchor version
define('ANCHOR_VERSION', 0.4);

// Lets bootstrap our application and get it ready to run
require PATH . 'system/bootstrap.php';
