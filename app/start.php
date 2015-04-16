<?php

require __DIR__ . '/compat.php';
require __DIR__ . '/autoloader.php';

// Run the app
$app = new App(require __DIR__ . '/container.php');
$app->registerErrorHandler();

if(true === $app->checkInstall()) {
	return $app->runInstall();
}

$app->removeTrailingSlash();
$app->loadPlugins();
$app->run();
