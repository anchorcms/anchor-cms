<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @idiot, with thanks to @kieronwilson, @spenserj and a bunch of other contributors.
 *    You're all great.
 */

// Anchor version
define('ANCHOR_VERSION', 0.7);

// Make sure the only included file is the current file
// Since we want to grab this file from the installer
if(count(get_included_files()) <= 1) {

	// benchmark
	define('ANCHOR_START', microtime(true));
	
	// Define base path
	define('PATH', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');
	
	// Block direct access to any PHP files
	define('IN_CMS', true);

	require PATH . 'system/bootstrap.php';
}