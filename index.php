<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @visualidiot, with thanks to @kieronwilson, @spenserj and a bunch of other contributors.
 *    You're all great.
 */

// benchmark
define('ANCHOR_START', microtime(true));

//  Set the include path
define('PATH', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');

//  Block direct access to any PHP files
define('IN_CMS', true);

//  Anchor version
define('ANCHOR_VERSION', 0.6);

// Lets bootstrap our application and get it ready to run
require PATH . 'system/bootstrap.php';