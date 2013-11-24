<?php

class Installer {

	private $connection, $session, $config;

	public function __construct() {
		$this->session = Session::get('install');
		$this->support = new Support;
		$this->connection = $this->connect();
		$this->config = new Installer\Config($this->session, $this->support);
	}

	public function run() {
		// install database schema
		$this->schema();

		// write config files
		$this->config->write('app');
		$this->config->write('db');

		// install database data
		$this->data();

		// create apache rewrite file if the user requested it
		if($this->session['metadata']['rewrite']) $this->rewrite();
	}

	private function connect() {
		extract($this->session['database']);

		return DB::factory(array(
			'driver' => 'mysql',
			'database' => $name,
			'hostname' => $host,
			'port' => $port,
			'username' => $user,
			'password' => $pass,
			'charset' => 'utf8',
			'prefix' => $prefix
		));
	}

	private function schema() {
		$database = $this->session['database'];

		$sql = Braces::compile(APP . 'storage/anchor.sql', array(
			'now' => gmdate('Y-m-d H:i:s'),
			'charset' => 'utf8',
			'prefix' => $database['prefix']
		));

		$this->connection->instance()->exec($sql);
	}

	private function data() {
		$this->metadata();
		$this->categories();
		$this->pages();
		$this->posts();
		$this->user();
	}

	private function metadata() {
		$metadata = $this->session['metadata'];

		// insert basic meta data
		$meta = array(
			'auto_published_comments' => 0,
			'comment_moderation_keys' => '',
			'comment_notifications' => 0,
			'date_format' => 'jS M, Y',
			'home_page' => '1',
			'posts_page' => '1',
			'posts_per_page' => '10',
			'admin_posts_per_page' => '6',

			'sitename' => $metadata['site_name'],
			'description' => $metadata['site_description'],
			'theme' => $metadata['theme']
		);

		foreach($meta as $key => $value) {
			$query = Query::table('meta', $this->connection)->where('key', '=', $key);

			if($query->count() == 0) {
				$query->insert(compact('key', 'value'));
			}
		}
	}

	private function categories() {
		$query = Query::table('categories', $this->connection);

		if($query->count() == 0) {
			$query->insert(array(
				'title' => 'Uncategorised',
				'slug' => 'uncategorised',
				'description' => 'Ain\'t no category here.'
			));
		}

		// create the first page
		$query = Query::table('pages', $this->connection);

		if($query->count() == 0) {
			$query->insert(array(
				'slug' => 'posts',
				'name' => 'Posts',
				'title' => 'My posts and thoughts',
				'content' => 'Welcome!',
				'status' => 'published',
				'redirect' => '',
				'show_in_menu' => 1,
				'menu_order' => 0
			));
		}

		// create the first post
		$query = Query::table('posts', $this->connection);

		if($query->count() == 0) {
			$query->insert(array(
				'title' => 'Hello World',
				'slug' => 'hello-world',
				'description' => 'This is the first post.',
				'html' => "Hello World!\r\n\r\nThis is the first post.",
				'css' => '',
				'js' => '',
				'created' => gmdate('Y-m-d H:i:s'),
				'author' => 1,
				'category' => 1,
				'status' => 'published',
				'comments' => 0
			));
		}
	}

	private function pages() {
		$query = Query::table('pages', $this->connection);

		if($query->count() == 0) {
			$query->insert(array(
				'slug' => 'posts',
				'name' => 'Posts',
				'title' => 'My posts and thoughts',
				'content' => 'Welcome!',
				'status' => 'published',
				'redirect' => '',
				'show_in_menu' => 1,
				'menu_order' => 0
			));
		}

		// create the first post
		$query = Query::table('posts', $this->connection);

		if($query->count() == 0) {
			$query->insert(array(
				'title' => 'Hello World',
				'slug' => 'hello-world',
				'description' => 'This is the first post.',
				'html' => 'Hello World!\r\n\r\nThis is the first post.',
				'css' => '',
				'js' => '',
				'created' => gmdate('Y-m-d H:i:s'),
				'author' => 1,
				'category' => 1,
				'status' => 'published',
				'comments' => 0
			));
		}
	}

	private function posts() {
		$query = Query::table('posts', $this->connection);

		if($query->count() == 0) {
			$query->insert(array(
				'title' => 'Hello World',
				'slug' => 'hello-world',
				'description' => 'This is the first post.',
				'html' => 'Hello World!\r\n\r\nThis is the first post.',
				'css' => '',
				'js' => '',
				'created' => gmdate('Y-m-d H:i:s'),
				'author' => 1,
				'category' => 1,
				'status' => 'published',
				'comments' => 0
			));
		}
	}

	private function user() {
		$query = Query::table('users', $this->connection);

		if($query->count() == 0) {
			$query->insert(array(
				'username' => $this->session['account']['username'],
				'password' => password_hash($this->session['account']['password'], PASSWORD_BCRYPT),
				'email' => $this->session['account']['email'],
				'real_name' => 'Administrator',
				'bio' => 'The bouse',
				'status' => 'active',
				'role' => 'administrator'
			));
		}
	}

	private function rewrite() {
		if($this->support->has_mod_rewrite()) {
			$htaccess = Braces::compile(APP . 'storage/htaccess.distro', array(
				'base' => $this->session['metadata']['site_path'],
				'index' => ($this->support->is_cgi() ? 'index.php?/$1' : 'index.php/$1')
			));

			if(is_writable($filepath = PATH . '.htaccess')) {
				file_put_contents($filepath, $htaccess);
			}
			else {
				// stash htaccess file in session
				Session::put('htaccess', $htaccess);
			}
		}
	}

}