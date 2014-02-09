<?php

$app = new Ship\Container;

$app['error'] = function() {
	return new Ship\Error;
};

$app['env'] = function() {
	return new Ship\Environment;
};

$app['events'] = function() {
	return new Ship\Events;
};

$app['config'] = function($app) {
	return new Ship\Config(__DIR__ . '/config', $app['env']->current());
};

$app['timezone'] = function($app) {
	return new DateTimeZone($app['config']->get('app.timezone', 'UTC'));
};

$app['markdown'] = function($app) {
	return new Parsedown();
};

$app['slugify'] = function($app) {
	return new Cocur\Slugify\Slugify();
};

return $app;