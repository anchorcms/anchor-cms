<?php

return [
    'start_time' => microtime(true),
    'paths' => function () {
        return require __DIR__.'/paths.php';
    },
    'config' => function ($app) {
        return new Anchorcms\Config($app['paths']['config']);
    },
    'db' => function ($app) {
        $params = $app['config']->get('db');
        $config = new Doctrine\DBAL\Configuration();

        if ($app['config']->get('app.debug')) {
            $config->setSQLLogger($app['db.logger']);
        }

        return Doctrine\DBAL\DriverManager::getConnection($params, $config);
    },
    'db.logger' => function () {
        return new \Doctrine\DBAL\Logging\DebugStack();
    },
    'errors' => function () {
        return new Anchorcms\Errors();
    },
    'events' => function () {
        return new Events\EventManager();
    },
    'session' => function ($app) {
        $config = $app['config']->get('session');

        return new Anchorcms\Session\Session($app['session.cookies'], $app['session.storage'], $config);
    },
    'session.cookies' => function ($app) {
        return new Anchorcms\Session\Cookies();
    },
    'session.storage' => function ($app) {
        if (false === is_dir($app['paths']['sessions'])) {
            mkdir($app['paths']['sessions'], 0700, true);
        }

        return new Anchorcms\Session\FileStorage($app['paths']['sessions']);
    },
    'csrf' => function ($app) {
        return new Anchorcms\Csrf($app['session']);
    },
    'validation' => function () {
        return new Validation\Validation();
    },
    'messages' => function ($app) {
        return new Anchorcms\Messages($app['session']);
    },
    'markdown' => function () {
        return new League\CommonMark\CommonMarkConverter();
    },
    'mustache' => function ($app) {
        $engine = new Mustache_Engine([
            'escape' => function ($value) {
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8', false);
            },
            'charset' => 'UTF-8',
        ]);

        if (!$app['config']->get('app.debug')) {
            $cache = new Mustache_Cache_FilesystemCache($app['paths']['storage'].'/cache/mustache');
            $engine->setCache($cache);
        }

        return $engine;
    },
    'view' => function ($app) {
        return new Anchorcms\View($app['paths']['views'], 'phtml');
    },
    'slugify' => function () {
        return new Anchorcms\Slugify();
    },
    'theme' => function ($app) {
        $path = $app['paths']['themes'].'/'.$app['mappers.meta']->key('theme', 'default');

        return new Anchorcms\Services\Themes\Theme($app['mustache'], $path);
    },
    'url' => function ($app) {
        return new Anchorcms\Url($app['http.request'], new GuzzleHttp\Psr7\Uri());
    },
    'zxcvbn' => function () {
        return new ZxcvbnPhp\Zxcvbn;
    },

    /*
     * Middleware
     */
    'http.request' => function () {
        return GuzzleHttp\Psr7\ServerRequest::fromGlobals();
    },
    'http.routes' => function ($app) {
        return new Routing\RouteCollection(require __DIR__.'/routes/default.php');
    },
    'http.router' => function ($app) {
        return new Routing\UriMatcher($app['http.routes']);
    },
    'http.kernel' => function ($app) {
        return new Anchorcms\Kernel($app['http.router']);
    },
    'http.factory' => function () {
        return new Tari\Adapter\Guzzle\Factory();
    },
    'http.server' => function ($app) {
        return new Tari\Server($app['http.factory']);
    },
    'http.default' => function ($app) {
        return function ($request) use ($app) {
            return $app['http.factory']->createResponse(200, [], '');
        };
    },

    /*
     * Mappers
     */
    'mappers.categories' => function ($app) {
        $mapper = new Anchorcms\Mappers\Categories($app['db'], new Anchorcms\Models\Category());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },
    'mappers.meta' => function ($app) {
        $mapper = new Anchorcms\Mappers\Meta($app['db'], new Anchorcms\Models\Meta());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },
    'mappers.pages' => function ($app) {
        $mapper = new Anchorcms\Mappers\Pages($app['db'], new Anchorcms\Models\Page());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },
    'mappers.pagemeta' => function ($app) {
        $mapper = new Anchorcms\Mappers\PageMeta($app['db'], new Anchorcms\Models\Meta());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },
    'mappers.posts' => function ($app) {
        $mapper = new Anchorcms\Mappers\Posts($app['db'], new Anchorcms\Models\Post());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },
    'mappers.postmeta' => function ($app) {
        $mapper = new Anchorcms\Mappers\PostMeta($app['db'], new Anchorcms\Models\Meta());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },
    'mappers.users' => function ($app) {
        $mapper = new Anchorcms\Mappers\Users($app['db'], new Anchorcms\Models\User());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },
    'mappers.customFields' => function ($app) {
        $mapper = new Anchorcms\Mappers\CustomFields($app['db'], new Anchorcms\Models\Meta());
        $mapper->setTablePrefix($app['config']->get('db.table_prefix'));

        return $mapper;
    },

    /*
     * Services
     */
    'services.media' => function ($app) {
        return new Anchorcms\Services\Media($app['paths']['content']);
    },
    'services.installer' => function ($app) {
        return new Anchorcms\Services\Installer($app['paths'], $app['session']);
    },
    'services.themes' => function ($app) {
        $current = $app['mappers.meta']->key('theme');

        return new Anchorcms\Services\Themes($app['paths']['themes'], $app['mustache'], $current);
    },
    'services.plugins' => function ($app) {
        return new Anchorcms\Services\Plugins($app['paths']['plugins']);
    },
    'services.rss' => function ($app) {
        $name = $app['mappers.meta']->key('sitename');
        $description = $app['mappers.meta']->key('description');

        return new Anchorcms\Services\Rss($name, $description, $app['url']->to('/'));
    },
    'services.posts' => function ($app) {
        return new Anchorcms\Services\Posts($app['mappers.posts'], $app['mappers.postmeta'], $app['mappers.customFields'], $app['mappers.users'], $app['mappers.categories']);
    },
    'services.customFields' => function ($app) {
        return new Anchorcms\Services\CustomFields($app['mappers.customFields'], $app['mappers.postmeta'], $app['mappers.pagemeta'], $app['services.media']);
    },
    'services.postman' => function ($app) {
        return new Anchorcms\Services\Postman($app['config']->get('mail'));
    },
    'services.auth' => function ($app) {
        return new Anchorcms\Services\Auth;
    },
];
