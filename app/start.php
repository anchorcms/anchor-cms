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
$autoload = __DIR__ . '/../vendor/autoload.php';

if(false === is_file($autoload)) {
	echo 'Composer not installed';
	exit(1);
}

$composer = require $autoload;

function dd() {
	if( ! headers_sent()) {
		header('content-type: text/plain');
	}
	call_user_func_array('var_dump', func_get_args());
	exit(1);
}

function e($str) {
	return htmlspecialchars($str, ENT_COMPAT, 'UTF-8', false);
}

$app = require __DIR__ . '/container.php';

$app['errors']->handler(function($exception) use($app) {
	while(ob_get_level()) ob_end_clean();
	http_response_code(500);
	echo sprintf('<html>
			<head>
				<title>Uncaught Exception</title>
				<style>html,body { color: #333; padding: 2rem; font: 1rem/1.5rem sans-serif; }</style>
			</head>
			<body>
				<h1>Uncaught Exception</h1>
				<p>%s in %s:%d</p>
				<h3>Stack Trace</h3>
				<pre>%s</pre>
			</body>
		</html>',
		$exception->getMessage(),
		$exception->getFile(),
		$exception->getLine(),
		$exception->getTraceAsString()
	);
});

$app['errors']->register();

$fi = new FilesystemIterator($app['paths']['plugins'], FilesystemIterator::SKIP_DOTS);

foreach($fi as $file) {
	$plugin = $file->getPathname() . '/src/Plugin.php';

	if(is_file($plugin)) {
		require $plugin;

		$class = 'Plugins\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $file->getBasename())));
		$obj = new $class();
		$obj->init($app['events']);
	}
}

$app['kernel']->redirectTrailingSlash();

if(false === $app['installer']->isInstalled() || true === $app['installer']->installerRunning()) {
	$app['routes']->set(require __DIR__ . '/routes/installer.php');
}
else {
	if( ! $app['config']->get('app.debug')) {
		$app['query']->disableProfile();
	}
}

$app['events']->trigger('before_response');

$response = $app['kernel']->getResponse();

$app['events']->trigger('after_response');

if($app['session']->started()) {
	$app['session']->rotate();
	$app['session']->close();
}

$app['events']->trigger('before_output');

$app['kernel']->outputResponse($response);

$app['events']->trigger('after_output');
