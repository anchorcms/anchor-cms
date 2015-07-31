<?php

error_reporting(-1);
ini_set('display_errors', true);

// Make sure this is PHP 5.3 or later
if ( ! defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50500) {
	echo 'PHP 5.5.0 or later is required';
	exit(1);
}

// Set default timezone to UTC
if( ! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// check composer is installed
$autoload_file = __DIR__ . '/../vendor/autoload.php';

if(false === is_file($autoload_file)) {
	echo 'Composer autoloader not found';
	exit(1);
}

require $autoload_file;

function dd() {
	echo '<pre>';
	call_user_func_array('var_dump', func_get_args());
	echo '</pre>';
	exit;
}

function e($str) {
	return htmlspecialchars($str, ENT_COMPAT, 'UTF-8', false);
}

$app = require __DIR__ . '/container.php';

$app['errors']->register();

$app['errors']->handler(function($exception) use($app) {
	while(ob_get_level()) ob_end_clean();
	http_response_code(500);
	$frames = $exception->getTrace();
	array_unshift($frames, [
		'file' => $exception->getFile(),
		'line' => $exception->getLine(),
	]);
	require __DIR__ . '/views/error.phtml';
});

$app['kernel']->redirectTrailingSlash();

if(false === $app['installer']->isInstalled() || true === $app['installer']->installerRunning()) {
	$app['router']->setRoutes(require __DIR__ . '/installer_routes.php');
}
else {
	if( ! $app['config']->get('app.debug')) {
		$app['query']->disableProfile();
	}
}

$response = $app['kernel']->getResponse();

$app['session']->rotate();
$app['session']->close();

$app['kernel']->outputResponse($response);
