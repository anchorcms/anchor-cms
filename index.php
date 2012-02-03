<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @visualidiot, with thanks to @spenserj and a bunch of other contributors.
 *    You're all great.
 */

//  Set the include path
define('PATH', __DIR__ . '/');

//  Block direct access to any PHP files
define('IN_CMS', true);

// Lets bootstrap our application and get it ready to run
require PATH . 'system/bootstrap.php';
