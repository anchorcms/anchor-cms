<?php

return new Container([
	'config' => function() {
		return new Config(__DIR__ . '/config');
	},
	'db' => function($app) {
		$config = $app['config']->get('db');
		$dns = sprintf('%s:%s', $config['driver'], $config['dbname']);

		$pdo = new PDO($dns);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

		return $pdo;
	},
	'query' => function($app) {
		return new DB\Query($app['db']);
	},
	'dispatcher' => function($app) {
		return new Dispatcher($app);
	},
	'errors' => function() {
		return new Errors();
	},
	'events' => function() {
		return new Events();
	},
	'session' => function() {
		$s = new Session([
			'name' => 'anchor',
			'cookie_lifetime' => 0
		]);

		$s->start();

		return $s;
	},
	'installer' => function() {
		return new Services\Installer;
	},
	'csrf' => function($app) {
		$config = $app['config']->get('general');

		return new Csrf($config['nonce']);
	},
	'server' => function() {
		return new Arr($_SERVER);
	},
	'http' => function($app) {
		return new Http($app['server'], $app['config']->get('general'));
	},
	'validation' => function() {
		return new Validation\Validation;
	},
	'categories' => function($app) {
		return Mappers\Factory::create($app, 'Categories');
	},
	'meta' => function($app) {
		return Mappers\Factory::create($app, 'Meta');
	},
	'pages' => function($app) {
		return Mappers\Factory::create($app, 'Pages');
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
]);
