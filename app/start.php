<?php

// Make sure this is PHP 5.3 or later
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50500) {
	echo 'PHP 5.5.0 or later is required, but you&rsquo;re running '.PHP_VERSION.'.';
	exit(1);
}

// Check for this early because Craft uses it before the requirements checker gets a chance to run.
if ( ! extension_loaded('mbstring')) {
	echo '<a href="http://php.net/manual/en/book.mbstring.php" target="_blank">PHP multibyte string</a> extension is required in order to run.';
	exit(1);
}

// These have been deprecated in PHP 6 in favor of default_charset, which defaults to 'UTF-8'
// http://php.net/manual/en/migration56.deprecated.php
if (PHP_VERSION_ID < 60000) {
	// Set MB to use UTF-8
	mb_internal_encoding('UTF-8');
	mb_http_input('UTF-8');
	mb_http_output('UTF-8');
}

mb_detect_order('auto');

// Set default timezone to UTC
date_default_timezone_set('UTC');

function dd() {
	echo '<pre>';
	array_map('var_dump', func_get_args());
	echo '</pre>';
	exit(0);
}

// check composer is installed
if(false === is_file($autoloader = __DIR__ . '/../vendor/autoload.php')) {
	echo 'Composer not found, please <a href="https://getcomposer.org/download/" target="_blank">download</a> and run <code>composer install</code>.';
	exit(1);
}

require $autoloader;

// Run the app
$app = new App(require __DIR__ . '/container.php');
$app->registerErrorHandler();

if(true === $app->checkInstall()) {
	return $app->runInstall();
}

$app->removeTrailingSlash();
$app->loadPlugins();
$app->run();
