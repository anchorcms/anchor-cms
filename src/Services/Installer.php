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
		$query = new \DB\Query($pdo);

		$category = $query->table($input['prefix'].'categories')->insert([
			'title' => 'Uncategorised',
			'slug' => 'uncategorised',
			'description' => 'Ain\'t no category here.',
		]);

		$user = $query->table($input['prefix'].'users')->insert([
			'username' => $input['username'],
			'password' => password_hash($input['password'], PASSWORD_BCRYPT, ['cost' => 12]),
			'email' => $input['email'],
			'real_name' => $input['username'],
			'bio' => 'The Bouse',
			'status' => 'active',
			'role' => 'administrator',
		]);

		$page = $query->table($input['prefix'].'pages')->insert([
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

		$post = $query->table($input['prefix'].'posts')->insert([
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

		$meta = [
			'home_page' => $page,
			'posts_page' => $page,
			'posts_per_page' => 6,
			'comment_notifications' => 0,
			'comment_moderation_keys' => '',
		];

		foreach($meta as $key => $value) {
			$query->table($input['prefix'].'meta')->insert(['key' => $key, 'value' => $value]);
		}
	}
}
