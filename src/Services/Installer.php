<?php

namespace Services;

class Installer {

	protected $messages = [];

	public function run(array $input) {
		$bytes = openssl_random_pseudo_bytes(32);
		$input['nonce'] = bin2hex($bytes);

		$this->copySampleConfig($input);
		$this->runSchema($input);
		$this->setupDatabase($input);
	}

	public function copySampleConfig(array $input) {
		$path = __DIR__ . '/../../app/config';

		if(false === is_dir($path)) mkdir($path);

		foreach(['db', 'general', 'paths', 'routes'] as $file) {
			$src = $path . sprintf('/../config-samples/%s.php', $file);
			$dest = $path . sprintf('/%s.php', $file);

			// skip if already exists
			if(is_file($dest)) continue;

			$sample = file_get_contents($src);
			$keys = array_map(function($key) { return sprintf('{%s}', $key); }, array_keys($input));
			file_put_contents($dest, str_replace($keys, array_values($input), $sample));
		}
	}

	public function log($message) {
		$this->messages[] = $message;
	}

	public function buildConnectionDns(array $input) {
		if($input['driver'] == 'mysql') {
			$params = array_intersect_key($input, array_fill_keys(['host', 'dbname'], null));
			array_walk($params, function(&$value, $key) { $value = $key.'='.$value; });
		}
		else {
			$path = $input['dbname'];

			if(false === is_file($path)) touch($path);

			$params = [$path];
		}

		return $input['driver'] . ':' . implode(';', $params);
	}

	public function getConnectionError() {
		return array_pop($this->messages);
	}

	public function testConnection($dns, $user, $pass) {
		try {
			$this->getConnection($dns, $user, $pass);
		}
		catch(\PDOException $e) {
			$this->log('Connection failed: ' . $e->getMessage());

			return false;
		}

		return true;
	}

	protected function getConnection($dns, $user = null, $pass = null) {
		$pdo = new \PDO($dns, $user, $pass);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $pdo;
	}

	protected function getPdo(array $input) {
		if($input['driver'] == 'mysql') {
			$dns = $this->buildConnectionDns($input);
			$pdo = $this->getConnection($dns, $input['user'], $input['pass']);
		}
		else {
			$dns = $this->buildConnectionDns($input);
			$pdo = $this->getConnection($dns);
		}

		return $pdo;
	}

	public function runSchema(array $input) {
		$path = __DIR__ . '/../../app/storage/schema_' . $input['driver'] . '.sql';
		$schema = file_get_contents($path);

		$pdo = $this->getPdo($input);
		$pdo->beginTransaction();

		foreach(explode(';', $schema) as $sql) {
			$pdo->exec($sql);
		}

		$pdo->commit();
	}

	public function setupDatabase(array $input) {
		$pdo = $this->getPdo($input);

		$categories = new \Models\Categories($pdo, $input['prefix']);
		$category = $categories->insert([
			'title' => 'Uncategorised',
			'slug' => 'uncategorised',
			'description' => 'Ain\'t no category here.',
		]);

		$users = new \Models\Users($pdo, $input['prefix']);
		$user = $users->insert([
			'username' => $input['username'],
			'password' => password_hash($input['password'], PASSWORD_BCRYPT, ['cost' => 12]),
			'email' => $input['email'],
			'real_name' => $input['username'],
			'bio' => 'The Bouse',
			'status' => 'active',
			'role' => 'administrator',
		]);

		$pages = new \Models\Pages($pdo, $input['prefix']);
		$page = $pages->insert([
			'parent' => 0,
			'slug' => 'posts',
			'name' => 'Posts',
			'title' => 'My posts and thoughts',
			'content' => 'Welcome!',
			'status' => 'published',
			'redirect' => '',
			'show_in_menu' => 1,
			'menu_order' => 0
		]);

		$posts = new \Models\Posts($pdo, $input['prefix']);
		$posts->insert([
			'title' => 'Hello World',
			'slug' => 'hello-world',
			'description' => 'This is the first post.',
			'html' => 'Hello World!\r\n\r\nThis is the first post.',
			'css' => '',
			'js' => '',
			'created' => date('Y-m-d H:i:s'),
			'author' => $user,
			'category' => $category,
			'status' => 'published',
			'comments' => 0,
		]);

		$meta = new \Models\Meta($pdo, $input['prefix']);

		$meta->insert(['key' => 'home_page', 'value' => $page]);
		$meta->insert(['key' => 'posts_page', 'value' => $page]);

		$meta->insert(['key' => 'posts_per_page', 'value' => 6]);

		$meta->insert(['key' => 'comment_notifications', 'value' => 0]);
		$meta->insert(['key' => 'comment_moderation_keys', 'value' => '']);
	}
}
