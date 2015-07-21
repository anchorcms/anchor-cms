<?php

require __DIR__ . '/compat.php';
require __DIR__ . '/autoloader.php';

function dd() {
	echo '<pre>';
	call_user_func_array('var_dump', func_get_args());
	echo '</pre>';
	exit;
}

function e($str) {
	return htmlspecialchars($str, ENT_COMPAT, 'UTF-8', false);
}

ob_start();

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

$response = $app['kernel']->getResponse();

$app['session']->rotate();
$app['session']->close();

$app['kernel']->outputResponse($response);
