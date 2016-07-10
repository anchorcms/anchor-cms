<?php

// Make sure this we have a good version of PHP
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 7) {
    throw new RuntimeException('PHP 7 or later is required');
}

// Check composer is installed
if (false === is_file(__DIR__.'/../vendor/autoload.php')) {
    throw new RuntimeException('Composer not installed');
}

require __DIR__.'/../vendor/autoload.php';

// create the container
$app = new Pimple\Container(require __DIR__.'/container.php');

// Setup Error handling
$app['errors']->handler(function (RuntimeDebugException $exception) {
    if(false === headers_sent()) {
        http_response_code(500);
    }
    echo sprintf('<html>
			<head>
				<title>Debug</title>
				<style>html,body { color: #333; padding: 2rem; font: 1rem/1.5rem sans-serif; }</style>
			</head>
			<body>
				<pre>%s</pre>
			</body>
		</html>',
        $exception->getMessage()
    );
});

$app['errors']->handler(function (Throwable $exception) {
    if(false === headers_sent()) {
        http_response_code(500);
    }
    echo sprintf('<html>
			<head>
				<title>Whoops</title>
				<style>html,body { color: #333; padding: 2rem; font: 1rem/1.5rem sans-serif; }</style>
			</head>
			<body>
				<h1>%s</h1>
				<p>%s in %s:%d</p>
				<h3>Stack Trace</h3>
				<pre>%s</pre>
			</body>
		</html>',
        get_class($exception),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );
});

// Register Error handler
$app['errors']->register();

// Set a default Timezone if theres nothing set
if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}
// Set Timezone from the config or use the default
date_default_timezone_set($app['config']->get('app.timezone', date_default_timezone_get()));

// check install
if (false === $app['services.installer']->isInstalled()) {
    // register router to run the installer
    $app['http.routes']->set(require __DIR__.'/routes/installer.php');

    // start the session for installer
    $app['http.server']->append(function ($request, $frame) use ($app) {
        $app['session']->start();

        return $frame->next($request);
    });
}
// middlewares to include when installed
else {
    // start the session for admin
    $app['http.server']->append(function ($request, $frame) use ($app) {
        if (strpos($request->getUri()->getPath(), '/admin') === 0) {
            $app['session']->start();
        }

        return $frame->next($request);
    });

    // protected admin pages
    $app['http.server']->append(new Anchorcms\Middleware\Auth($app['session'], [
        '/admin',
    ]));
}

$app['http.server']->append(new Anchorcms\Middleware\RedirectTrailingSlash);
$app['http.server']->append(new Anchorcms\Middleware\Session($app['session']));
$app['http.server']->append(new Anchorcms\Middleware\Kernel($app));

// append debug output
if ($app['config']->get('app.debug')) {
    $app['http.server']->prepend(new Anchorcms\Middleware\Debug($app));
}

$response = $app['http.server']->run($app['http.request'], $app['http.default']);

$app['http.kernel']->outputResponse($response);
