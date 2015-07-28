<?php

return new Container([
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
		return new \cebe\markdown\Markdown();
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

	/**
	 * Mappers
	 */
	'categories' => function($app) {
		return Mappers\Factory::create($app, 'Categories');
	},
	'meta' => function($app) {
		return Mappers\Factory::create($app, 'Meta');
	},
	'pages' => function($app) {
		return Mappers\Factory::create($app, 'Pages');
	},
	'pagemeta' => function($app) {
		return Mappers\Factory::create($app, 'PageMeta');
	},
	'posts' => function($app) {
		return Mappers\Factory::create($app, 'Posts');
	},
	'postmeta' => function($app) {
		return Mappers\Factory::create($app, 'PostMeta');
	},
	'users' => function($app) {
		return Mappers\Factory::create($app, 'Users');
	},
	'extend' => function($app) {
		return Mappers\Factory::create($app, 'Extend');
	},
]);
