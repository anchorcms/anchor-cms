<?php

return [
	'paths' => function() {
		return require __DIR__ . '/paths.php';
	},
	'config' => function($app) {
		return new Config($app['paths']['config']);
	},
	'db' => function($app) {
		$params = $app['config']->get('db');
		$config = new \Doctrine\DBAL\Configuration();
		return Doctrine\DBAL\DriverManager::getConnection($params, $config);
	},
	'errors' => function() {
		return new Errors();
	},
	'events' => function() {
		return new Events\EventManager();
	},
	'session' => function($app) {
		$session = new Session\Session($app['session.cookies'], $app['session.storage']);
		$session->start();
		return $session;
	},
	'session.cookies' => function($app) {
		return new Session\Cookies;
	},
	'session.storage' => function($app) {
		if(false === is_dir($app['paths']['sessions'])) {
			mkdir($app['paths']['sessions']);
		}
		return new Session\FileStorage($app['paths']['sessions']);
	},
	'csrf' => function($app) {
		return new Csrf($app['session']);
	},
	'validation' => function() {
		return new Validation\Validation;
	},
	'messages' => function($app) {
		return new Messages($app['session'], $app['view'], 'partials/messages');
	},
	'markdown' => function() {
		return new League\CommonMark\CommonMarkConverter;
	},
	'slugify' => function() {
		return new Slugify;
	},
	'view' => function($app) {
		return new View($app['paths']['views']);
	},
	'theme' => function($app) {
		return new Theme($app['view'], $app['paths'], $app['events']);
	},
	'url' => function($app) {
		return new Url($app['http.request']);
	},

	/**
	 * Middleware
	 */
	'http.request' => function() {
		return GuzzleHttp\Psr7\ServerRequest::fromGlobals();
	},
	'http.routes' => function($app) {
		$routes = new Routing\RouteCollection(require __DIR__ . '/routes/default.php');

		$app['events']->trigger('routing', $routes);

		return $routes;
	},
	'http.router' => function($app) {
		return new Routing\UriMatcher($app['http.routes']);
	},
	'http.kernel' => function($app) {
		return new Kernel($app['http.router']);
	},
	'http.factory' => function() {
		return new Tari\Adapter\Guzzle\Factory;
	},
	'http.server' => function($app) {
		return new Tari\Server($app['http.factory']);
	},

	/**
	 * Mappers
	 */
	'mappers.categories' => function($app) {
		$mapper = new Mappers\Categories($app['db'], new Models\Category);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.meta' => function($app) {
		$mapper = new Mappers\Meta($app['db'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.pages' => function($app) {
		$mapper = new Mappers\Pages($app['db'], new Models\Page);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.pagemeta' => function($app) {
		$mapper = new Mappers\PageMeta($app['db'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.posts' => function($app) {
		$mapper = new Mappers\Posts($app['db'], new Models\Post);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.postmeta' => function($app) {
		$mapper = new Mappers\PostMeta($app['db'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.users' => function($app) {
		$mapper = new Mappers\Users($app['db'], new Models\User);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.customFields' => function($app) {
		$mapper = new Mappers\CustomFields($app['db'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},

	/*
	 * Services
	 */
	'services.media' => function($app) {
		return new Services\Media($app['paths']['content']);
	},
	'services.installer' => function($app) {
		return new Services\Installer($app['paths'], $app['session']);
	},
	'services.themes' => function($app) {
		$current = $app['mappers.meta']->select(['value'])->where('key', '=', 'theme')->column();

		return new Services\Themes($app['paths']['themes'], $current);
	},
	'services.plugins' => function($app) {
		return new Services\Plugins($app['paths']['plugins']);
	},
	'services.rss' => function($app) {
		$name = $app['mappers.meta']->key('sitename');
		$description = $app['mappers.meta']->key('description');
		$url = $app['middleware.request']->getUri();

		return new Services\Rss($name, $description, $url);
	},
	'services.posts' => function($app) {
		return new Services\Posts($app['mappers.posts'], $app['mappers.postmeta'], $app['mappers.customFields'], $app['mappers.users'], $app['mappers.categories']);
	},
	'services.customFields' => function($app) {
		return new Services\CustomFields($app['mappers.customFields'], $app['mappers.postmeta'], $app['mappers.pagemeta'], $app['services.media']);
	},
	'services.postman' => function($app) {
		return new Services\Postman($app['config']->get('mail'));
	},
	'services.auth' => function($app) {
		return new Services\Auth($app['config'], $app['mappers.users']);
	},
];
