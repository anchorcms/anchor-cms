<?php

return new Container([
	'start' => microtime(true),
	'benchmark' => function($app) {
		return round((microtime(true) - $app['start']) * 1000, 2);
	},
	'paths' => function() {
		return require __DIR__ . '/paths.php';
	},
	'config' => function() {
		return new Config(__DIR__ . '/config');
	},
	'db' => function($app) {
		$config = $app['config']->get('db');
		$dns = sprintf('%s:%s', $config['driver'], $app['paths']['storage'] . '/' . $config['dbname']);

		$pdo = new PDO($dns);
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
		$storage = new Session\Storage\Native(null, [
			'name' => 'anchor',
			'cookie_lifetime' => 0
		]);

		$s = new Session\Session($storage);
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
		return new Messages($app['session']);
	},
	'markdown' => function() {
		return new cebe\markdown\Markdown();
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
	'router' => function() {
		return new Routing\UriMatcher(require __DIR__ . '/routes.php');
	},
	'kernel' => function($app) {
		return new Kernel($app['request'], $app['router'], $app);
	},

	/**
	 * Services
	 */
	'media' => function() {
		return new Services\Media(__DIR__ . '/../content');
	},
	'installer' => function($app) {
		return new Services\Installer($app['paths'], $app['session']);
	},
	'customFields' => function($app) {
		return new Services\CustomFields($app['extend'], $app['postmeta'], $app['pagemeta']);
	},
	'themes' => function($app) {
		return new Services\Themes($app['paths']['themes']);
	},

	/**
	 * Mappers
	 */
	'categories' => function($app) {
		$mapper = new Mappers\Categories($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'meta' => function($app) {
		$mapper = new Mappers\Meta($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'pages' => function($app) {
		$mapper = new Mappers\Pages($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'pagemeta' => function($app) {
		$mapper = new Mappers\PageMeta($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'posts' => function($app) {
		$mapper = new Mappers\Posts($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'postmeta' => function($app) {
		$mapper = new Mappers\PostMeta($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'users' => function($app) {
		$mapper = new Mappers\Users($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
	'extend' => function($app) {
		$mapper = new Mappers\Extend($app['query'], new \DB\Row);
		$mapper->setTablePrefix($app['config']->get('db.table_prefix'));

		return $mapper;
	},
]);
