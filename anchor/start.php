<?php

$time_start = microtime(true);

/**
 * Setup autoloader
 */
if( ! is_file(__DIR__ . '/../vendor/autoload.php')) {
	echo 'Composer is not installed or cannot be found!';
	exit;
}

require __DIR__ . '/../vendor/autoload.php';

/**
 * Application container
 */
$app = require __DIR__ . '/container.php';

/**
 * Setup environment
 */
$app['env']->detect(function() {
	return getenv('APP_ENV') ?: 'production';
});

if($app['env']->current() == 'local') {
	ini_set('display_errors', true);
	ini_set('error_log', '/dev/null');
	error_reporting(-1);
}

/**
 * Set timezone
 */
date_default_timezone_set($app['timezone']->getName());

/**
 * Error logging
 */
$app['error']->logger($app['config']->get('error.log'));

$app['error']->register();

/**
 * Register service providers
 */
foreach($app['config']->get('app.providers', array()) as $className) {
	$provider = new $className();

	if( ! $provider instanceof Ship\Contracts\ProviderInterface) {
		throw new ErrorException(sprintf('Service provider "%s" must implement Ship\Contracts\ProviderInterface', $className));
	}

	$provider->register($app);
}

/**
 * Event listeners
 */
$app['events']->attach('beforeResponse', function() use($app, $time_start) {
	// Append elapsed time and memory usage
	$time_end = microtime(true);
	$elapsed_time = round(($time_end - $time_start) * 1000);
	$memory = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

	$tags = array('{elapsed_time}', '{memory_usage}');
	$values = array($elapsed_time, $memory);

	$body = str_replace($tags, $values, $app['response']->getBody());

	$app['response']->setBody($body);
});

$app['events']->attach('beforeResponse', function() use($app) {
	if($app['admin'] or $app['env']->current() == 'local') {
		$app['response']->setHeader('expires', gmdate('D, d M Y H:i:s', time() - 86400) . ' GMT');
	}
});

/**
 * Start session for admin only, this way we can use a
 * more aggresive caching for the site.
 */
if($app['admin']) {
	$app['session']->start();
}

/**
 * Handle the request
 */
$app['events']->trigger('beforeDispatch');

$response = $app['router']->dispatch();

$app['events']->trigger('afterDispatch');

/**
 * Close session
 */
if($app['admin']) {
	$app['session']->close();
}

/**
 * Create a Response if we only have a string
 */
if( ! $response instanceof Ship\Http\Response) {
	$response = $app['response']
		->setHeader('content-type', 'text/html; charset=' . $app['config']->get('app.encoding', 'UTF-8'))
		->setBody($response);
}

/**
 * Finish
 */
$app['events']->trigger('beforeResponse');

$response->send();

$app['events']->trigger('afterResponse');