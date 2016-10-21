<?php

// Make sure this we have a good version of PHP
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 7) {
    http_response_code(500);
    echo file_get_contents(__DIR__.'/views/errors/requirements.html');
    return 0;
}

// Check composer is installed
if (!is_file(__DIR__.'/../vendor/autoload.php')) {
    http_response_code(500);
    echo file_get_contents(__DIR__.'/views/errors/composer-not-installed.html');
    return 0;
}

require __DIR__.'/../vendor/autoload.php';

// create the container
$app = new Pimple\Container(require __DIR__.'/container.php');

// Setup Error handling
$app['errors']->handler($app['error.handler']);

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
    $app['http.server']->append(
        new Anchorcms\Middleware\ACL(
            $app['session'],
            $app['mappers.users'],
            $app['config']->get('acl')
        )
    );
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
