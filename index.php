<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @visualidiot, with thanks to @spenserj and a bunch of other contributors.
 *    You're all great.
 */

//  Set the include path
define('PATH', __DIR__ . '/');

//  Set the current version number
define('VERSION', '0.3.0a');

//  Block direct access to any PHP files
$direct = strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)));

//  And load the system file
require_once PATH . 'system/index.php';
