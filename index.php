<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @idiot, with thanks to @kieronwilson, @spenserj and a bunch of other contributors.
 *    You're all great.
 */

// Anchor version
define('ANCHOR_VERSION', 0.7);

// benchmark
define('ANCHOR_START', microtime(true));

// Define base path
define('PATH', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');

// Block direct access to any PHP files
define('IN_CMS', true);

require PATH . 'system/bootstrap.php';