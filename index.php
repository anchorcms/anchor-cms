<?php

/**
 *    Anchor CMS
 *
 *    Originally built by @visualidiot, with thanks to @kieronwilson, @spenserj and a bunch of other contributors.
 *    You're all great.
 */
 
/*
 *	XH Profiling
 */
if(function_exists('xhprof_enable')) {
	xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
}

// benchmark
define('ANCHOR_START', microtime(true));

//  Set the include path
define('PATH', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');

//  Block direct access to any PHP files
define('IN_CMS', true);

//  Anchor version
define('ANCHOR_VERSION', 0.5);

// Lets bootstrap our application and get it ready to run
require PATH . 'system/bootstrap.php';

/*
 *	XH Profiling
 */
if(function_exists('xhprof_enable')) {
	$xhprof_data = xhprof_disable();

	$path = '../../xh/xhprof_lib/';

	include $path . 'config.php';
	include $path . 'utils/xhprof_lib.php';
	include $path . 'utils/xhprof_runs.php';

	$xhprof_runs = new XHProfRuns_Default();
	$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_testing");
}
