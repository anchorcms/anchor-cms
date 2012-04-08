<?php


class Installer {

	/**
		Check required php modules
	*/
	public static function compat_check() {
		$compat = array();
		
		// php
		if(version_compare(PHP_VERSION, '5.3.0', '<')) {
			$compat[] = '<strong>Anchor requires PHP 5.3 or newer.</strong><br>
				<em>Your current environment is running PHP ' . PHP_VERSION . '</em>';
		}
		
		// PDO
		if(class_exists('PDO') === false) {
			$compat[] = '<strong>Anchor requires PDO (PHP Data Objects).</strong><br>
			<em>You can find more about <a href="//php.net/manual/en/book.pdo.php">installing and setting up PHP Data Objects</a> 
			on the php.net website</em>';
		} else {
			if(in_array('mysql', PDO::getAvailableDrivers()) === false) {
				$compat[] = '<strong>Anchor requires MySQL PDO Driver.</strong><br>
					<em>You can find more about <a href="//php.net/manual/en/ref.pdo-mysql.php">installing and setting up MySQL PDO Driver</a> 
					on the php.net website</em>';
			}
		}

		return $compat;
	}

	/**
		Database install
	*/
	private static function install_schema() {
		$data = $_SESSION;

		$sql = str_replace('[[now]]', time(), file_get_contents('assets/sql/anchor.sql'));

		$dsn = 'mysql:dbname=' . $data['db']['name'] . ';host=' . $data['db']['host'] . ';port=' . $data['db']['port'];
		$dbh = new PDO($dsn, $data['db']['user'], $data['db']['pass']);
		
		try {
			$dbh->beginTransaction();
			$dbh->exec($sql);
			
			// create metadata
			$sql= "INSERT INTO `meta` (`key`, `value`) VALUES ('sitename', ?), ('description', ?), ('theme', ?);";
			$statement = $dbh->prepare($sql);
			$statement->execute(array($data['site']['site_name'], $data['site']['site_description'], $data['site']['theme']));

			// create user account
			$sql= "INSERT INTO `users` (`username`, `password`, `email`, `real_name`, `bio`, `status`, `role`) VALUES (?, ?, ?, 'Administrator', 'Default account for Anchor.', 'active', 'administrator');";
			$statement = $dbh->prepare($sql);
			$statement->execute(array($data['user']['username'], crypt($data['user']['password'], $_SESSION['key']), $data['user']['email']));

			$dbh->commit();
		} catch(PDOException $e) {
			Messages::add($e->getMessage());
			
			// rollback any changes
			if($dbh->inTransaction()) {
				$dbh->rollBack();
			}
		}
	}

	/**
		Try to write config file, if not write to tmp and offer to download
	*/
	private static function install_config() {
		$errors = array();
		$data = $_SESSION;
		$template = file_get_contents('../config.default.php');
		
		$base_url = ($path = trim($data['site']['path'], '/')) == '' ? '' : $path . '/';
		$index_page = 'index.php';

		$search = array(
			"'host' => 'localhost'",
			"'port' => '3306'",
			"'username' => 'root'",
			"'password' => ''",
			"'name' => 'anchorcms'",
			
			// apllication paths
			"'base_url' => '/'",
			"'index_page' => 'index.php'",
			"'key' => ''",

			// language
			"'language' => 'en'"
		);
		$replace = array(
			"'host' => '" . $data['db']['host'] . "'",
			"'port' => '" . $data['db']['port'] . "'",
			"'username' => '" . $data['db']['user'] . "'",
			"'password' => '" . $data['db']['pass'] . "'",
			"'name' => '" . $data['db']['name'] . "'",

			// apllication paths
			"'base_url' => '/" . $base_url . "'",
			"'index_page' => '" . $index_page . "'",
			"'key' => '" . $_SESSION['key'] . "'",

			// language
			"'language' => '" . $data['lang'] . "'"
		);
		$config = str_replace($search, $replace, $template);

		if(is_real_writable('../')) {
			if(file_put_contents('../config.php', $config)) {
				// chmod config file to 0640 to be sure
				chmod('../config.php', 0640);
			}
		}

		if(file_exists('../config.php') === false) {
			// failed to create config file offer to download it
			$_SESSION['config'] = $config;

			$html = 'It looks like we could not automatically create your config file for you, 
				please download <code><a href="index.php?action=download">config.php</a></code> and upload it to your anchor 
				installation to complete the setup.';

			Messages::add($html);
		}
	}

	/**
		Put it all together
	*/
	private static function run() {
		// create a application key
		$_SESSION['key'] = random(32);

		// install database
		static::install_schema();

		// check we can create config
		static::install_config();

		return true;
	}

	/**
		Collect and validate
	*/
	public static function stage1() {
		$_SESSION = array('lang' => post('language'));
		return true;
	}

	public static function stage2() {
		$post = post(array('host', 'user', 'pass', 'name', 'port'));

		if(empty($post['host'])) {
			$errors[] = 'Please specify a database host';
		}

		if(empty($post['name'])) {
			$errors[] = 'Please specify a database name';
		}

		if(empty($post['port'])) {
			$post['port'] = 3306;
		}

		// test connection
		if(empty($errors)) {
			try {
				$dsn = 'mysql:dbname=' . $post['name'] . ';host=' . $post['host'] . ';port=' . $post['port'];
				new PDO($dsn, $post['user'], $post['pass']);
			} catch(PDOException $e) {
				$errors[] = $e->getMessage();
			}
		}

		if(count($errors)) {
			Messages::add($errors);
			return false;
		}

		// save and continue
		$_SESSION['db'] = $post;

		return true;
	}

	public static function stage3() {
		$post = post(array('site_name', 'site_description', 'site_path', 'theme'));

		if(empty($post['site_name'])) {
			$errors[] = 'Please enter a site name';
		}

		if(empty($post['site_path'])) {
			$errors[] = 'Please specify your site path';
		}

		if(count($errors)) {
			Messages::add($errors);
			return false;
		}

		// save and continue
		$_SESSION['site'] = $post;

		return true;
	}

	public static function stage4() {
		$post = post(array('username', 'email', 'password', 'confirm_password'));

		if(empty($post['username'])) {
			$errors[] = 'Please enter a username';
		}

		if(filter_var($post['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Please enter a valid email address';
		}

		if(strlen($post['password']) < 6) {
			$errors[] = 'Please enter a password, must be at least 6 characters long';
		} elseif($post['password'] != $post['confirm_password']) {
			$errors[] = 'Passwords do not match, please confirm your password';
		}

		if(count($errors)) {
			Messages::add($errors);
			return false;
		}

		// save and continue
		$_SESSION['user'] = $post;

		return static::run();
	}

}