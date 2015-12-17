<?php

return new Container([
	'paths' => function() {
		return require __DIR__ . '/paths.php';
	},
	'config' => function($app) {
		return new Config($app['paths']['config']);
	},
	'db' => function($app) {
		$config = $app['config']->get('db');

		if($config['driver'] == 'sqlite') {
			$dns = sprintf('%s:%s', $config['driver'], $app['paths']['storage'] . '/' . $config['dbname']);
			$pdo = new PDO($dns);
		}

		if($config['driver'] == 'mysql') {
			$dns = sprintf('%s:host=%s;dbname=%s', $config['driver'], $config['host'], $config['dbname']);
			$pdo = new PDO($dns, $config['user'], $config['pass']);
		}

		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

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
	'session' => function() {
		$s = new Session\Session();
		$s->start();

		return $s;
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
		return new cebe\markdown\Markdown();
	},
	'view' => function($app) {
		return new View($app['paths']['views']);
	},

	/**
	 * Middleware
	 */
	'request' => function() {
		return new Http\ServerRequest($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES);
	},
	'response' => function() {
		return new Http\Response;
	},
	'routes' => function() {
		return new Routing\RouteCollection(require __DIR__ . '/routes/default.php');
	},
	'router' => function($app) {
		return new Routing\UriMatcher($app['routes']);
	},
	'kernel' => function($app) {
		return new Kernel($app['request'], $app['router'], $app);
	},

	/**
	 * Services
	 */
	'media' => function($app) {
		return new Services\Media($app['paths']['content']);
	},
	'installer' => function($app) {
		return new Services\Installer($app['paths'], $app['session']);
	},
	'customFields' => function($app) {
		return new Services\CustomFields($app['extend'], $app['postmeta'], $app['pagemeta'], $app['media']);
	},
	'themes' => function($app) {
		return new Services\Themes($app['paths']['themes']);
	},
	'plugins' => function($app) {
		return new Services\Plugins($app['paths']['plugins']);
	},
	'services' => new Container([
		'posts' => function() {
			global $app;
			return new Services\Posts($app['posts'], $app['postmeta'], $app['extend'], $app['users'], $app['categories']);
		},
	]),

	/**
	 * Mappers
	 */
	'categories' => function($app) {
		$mapper = new Mappers\Categories($app['query'], new Models\Category);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'meta' => function($app) {
		$mapper = new Mappers\Meta($app['query'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'pages' => function($app) {
		$mapper = new Mappers\Pages($app['query'], new Models\Page);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'pagemeta' => function($app) {
		$mapper = new Mappers\PageMeta($app['query'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'posts' => function($app) {
		$mapper = new Mappers\Posts($app['query'], new Models\Post);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'postmeta' => function($app) {
		$mapper = new Mappers\PostMeta($app['query'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'users' => function($app) {
		$mapper = new Mappers\Users($app['query'], new Models\User);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'extend' => function($app) {
		$mapper = new Mappers\Extend($app['query'], new DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
]);
