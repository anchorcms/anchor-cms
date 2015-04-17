<?php

require __DIR__ . '/compat.php';
require __DIR__ . '/autoloader.php';

// Run the app
$app = new App(require __DIR__ . '/container.php');
$app->registerErrorHandler();

if(false === $app->isInstalled()) {
	return $app->runInstall();
}

$app->removeTrailingSlash();
$app->loadPlugins();
$app->run();
