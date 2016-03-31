<?php

return [
	'paths' => function() {
		return require __DIR__ . '/paths.php';
	},
	'config' => function($app) {
		return new Config($app['paths']['config']);
	},
	'db' => function($app) {
		$config = $app['config']->get('db');

		$pdo = new PDO($config['dns'], $config['user'], $config['pass'], [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		]);

		return $pdo;
	},
	'query' => function($app) {
		return new DB\Query($app['db']);
	},
	'errors' => function() {
		return new Errors();
	},
	'events' => function() {
		return new Events\EventManager();
	},
	'session' => function($app) {
		// try to create the folder is it does not exist
		if(false === is_dir($app['paths']['sessions'])) {
			mkdir($app['paths']['sessions']);
		}

		// use builtin file handler
		$handler = new \SessionHandler;

		$storage = new Session\NativeStorage($handler, [
			'entropy_length' => 32,
			'use_cookies' => true,
			'use_only_cookies' => true,
			'cookie_lifetime' => 0,
			'gc_probability' => 0,
			'name' => 'anchor',
			'save_path' => $app['paths']['sessions'],
			'save_handler' => 'files',
			'hash_function' => 'sha256',
		]);

		$session = new Session\Session($storage);

		return $session;
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
		return new Url($app['middleware.request']);
	},

	/**
	 * Middleware
	 */
	'middleware.request' => function() {
		return new Http\ServerRequest($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES);
	},
	'middleware.response' => function() {
		return new Http\Response;
	},
	'middleware.routes' => function($app) {
		$routes = new Routing\RouteCollection(require __DIR__ . '/routes/default.php');

		$app['events']->trigger('routing', $routes);

		return $routes;
	},
	'middleware.router' => function($app) {
		return new Routing\UriMatcher($app['middleware.routes']);
	},
	'middleware.kernel' => function($app) {
		return new Kernel($app['middleware.request'], $app['middleware.router'], $app);
	},

	/**
	 * Mappers
	 */
	'mappers.categories' => function($app) {
		$mapper = new Mappers\Categories($app['query'], new Models\Category);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.meta' => function($app) {
		$mapper = new Mappers\Meta($app['query'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.pages' => function($app) {
		$mapper = new Mappers\Pages($app['query'], new Models\Page);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.pagemeta' => function($app) {
		$mapper = new Mappers\PageMeta($app['query'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.posts' => function($app) {
		$mapper = new Mappers\Posts($app['query'], new Models\Post);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.postmeta' => function($app) {
		$mapper = new Mappers\PostMeta($app['query'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.users' => function($app) {
		$mapper = new Mappers\Users($app['query'], new Models\User);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'mappers.customFields' => function($app) {
		$mapper = new Mappers\CustomFields($app['query'], new DB\Row);
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
		return new Services\Themes($app['paths']['themes']);
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
