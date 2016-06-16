<?php

// Make sure this we have a good version of PHP
if( ! defined('PHP_VERSION_ID') || PHP_VERSION_ID < 7) {
	echo 'PHP 7 or later is required';
	return;
}

// Check composer is installed
if(false === is_file(__DIR__ . '/../vendor/autoload.php')) {
	echo 'Composer not installed :(';
	return;
}

// report all errors
error_reporting(-1);
ini_set('display_errors', true);

// Set default timezone to UTC
if( ! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

require __DIR__ . '/helpers.php';
require __DIR__ . '/../vendor/autoload.php';

$app = new Pimple\Container(require __DIR__ . '/container.php');

$app['errors']->handler(function(DB\SqlException $exception) {
	while(ob_get_level()) ob_end_clean();
	http_response_code(500);
	echo sprintf('<html>
			<head>
				<title>SqlException</title>
				<style>html,body { color: #333; padding: 2rem; font: 1rem/1.5rem sans-serif; }</style>
			</head>
			<body>
				<h1>SqlException</h1>
				<p>%s in %s:%d</p>
				<h3>SQL</h3>
				<pre>%s</pre>
				<h3>Params</h3>
				<pre>%s</pre>
			</body>
		</html>',
		$exception->getMessage(),
		$exception->getFile(),
		$exception->getLine(),
		$exception->getSql(),
		json_encode($exception->getParams())
	);
});

$app['errors']->handler(function(Throwable $exception) {
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

// check install
if(false === $app['services.installer']->isInstalled() ||
	// installation is complete but we still need to show the completed screen
	true === $app['services.installer']->installerRunning()) {
	// register router to run the installer
	$app['http.routes']->set(require __DIR__ . '/routes/installer.php');

	// start the session for installer
	$app['http.server']->append(function($request, $frame) use($app) {
		$app['session']->start();
		return $frame->next($request);
	});
}
// middlewares to include when installed
else {
	$app['http.server']->append(function($request, $frame) use($app) {
		$app['theme']->setTheme($app['mappers.meta']->key('theme', 'default'));
		return $frame->next($request);
	});

	$app['http.server']->append(new Anchorcms\Middleware\Auth($app['session'], [
		'/admin/(pages|posts)',
	]));
}

$app['http.server']->append(new Anchorcms\Middleware\Session($app['session']));
$app['http.server']->append(new Anchorcms\Middleware\Kernel($app));

$response = $app['http.server']->run($app['http.request'], function($request) use($app) {
	return $app['http.factory']->createResponse(404, [], 'Not Found');
});

$app['http.kernel']->outputResponse($response);
