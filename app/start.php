<?php

require __DIR__ . '/compat.php';
require __DIR__ . '/autoloader.php';

function dd() {
	echo '<pre>';
	call_user_func_array('var_dump', func_get_args());
	echo '</pre>';
	exit;
}

ob_start();

$app = require __DIR__ . '/container.php';

$app['errors']->register();

$app['errors']->handler(function($exception) use($app) {
	while(ob_get_level()) ob_end_clean();

	http_response_code(500);

	printf('<p><code>%s</code></p>', $exception->getMessage());

	if($exception instanceof \DB\SqlException) {
		printf('<p><code>%s</code></p>', $exception->getSql());
	}

	printf('<p><code>%s:%d</code></p>', $exception->getFile(), $exception->getLine());

	printf('<pre>%s</pre>', $exception->getTraceAsString());
});

$app['kernel']->redirectTrailingSlash();

if(false === $app['installer']->isInstalled() || true === $app['installer']->installerRunning()) {
	$app['router']->setRoutes(require __DIR__ . '/installer_routes.php');
}

$response = $app['kernel']->getResponse();

$app['session']->rotate();
$app['session']->close();

$app['kernel']->outputResponse($response);
