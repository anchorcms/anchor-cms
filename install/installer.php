<?php

/*
	Helper functions
*/
function random($length = 16) {
	$pool = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 1);
	$value = '';

	for ($i = 0; $i < $length; $i++)  {
		$value .= $pool[mt_rand(0, 61)];
	}

	return $value;
}
	
/*
	Installer
*/

$fields = array('host', 'user', 'pass', 'db', 'name', 'description', 'theme', 'email', 'path', 'clean_urls');
$post = array();
$warnings = array();
$errors = array();

foreach($fields as $field) {
	$post[$field] = isset($_POST[$field]) ? $_POST[$field] : false;
}

if(empty($post['db'])) {
	$errors[] = 'Please specify a database name';
}

if(empty($post['host'])) {
	$errors[] = 'Please specify a database host';
}

if(empty($post['name'])) {
	$errors[] = 'Please enter a site name';
}

if(empty($post['theme'])) {
	$errors[] = 'Please select a theme';
}

if(filter_var($post['email'], FILTER_VALIDATE_EMAIL) === false) {
	$errors[] = 'Please enter a valid email address';
}

if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	$errors[] = 'Anchor requires PHP 5.3 or newer, your current environment is running PHP ' . PHP_VERSION;
}

// test database
if(empty($errors)) {
	try {
		$dsn = 'mysql:dbname=' . $post['db'] . ';host=' . $post['host'];
		$dbh = new PDO($dsn, $post['user'], $post['pass']);
	} catch(PDOException $e) {
		$errors[] = $e->getMessage();
	}
}

// create config file
if(empty($errors)) {
	$template = file_get_contents('../config.default.php');
	
	$base_url = ($path = trim($post['path'], '/')) == '' ? '' : $path . '/';
	$index_page = ($post['clean_urls'] === false ? 'index.php' : '');

	$search = array(
		"'host' => 'localhost'",
		"'username' => 'root'",
		"'password' => ''",
		"'name' => 'anchorcms'",
		
		// apllication paths
		"'base_url' => '/'",
		"'index_page' => 'index.php'",
		"'key' => ''"
	);
	$replace = array(
		"'host' => '" . $post['host'] . "'",
		"'username' => '" . $post['user'] . "'",
		"'password' => '" . $post['pass'] . "'",
		"'name' => '" . $post['db'] . "'",

		// apllication paths
		"'base_url' => '/" . $base_url . "'",
		"'index_page' => '" . $index_page . "'",
		"'key' => '" . random(32) . "'"
	);
	$config = str_replace($search, $replace, $template);

	if(file_put_contents('../config.php', $config) === false) {
		$errors[] = 'Failed to create config file';
	}
	
	// if we have clean urls enabled let setup a 
	// basic htaccess file is there isnt one
	if($post['clean_urls']) {
		// dont overwrite existing htaccess file
		if(file_exists('../.htaccess') === false) {
			$htaccess = file_get_contents('../htaccess.txt');	
			$htaccess = str_replace('# RewriteBase /', 'RewriteBase /' . $base_url, $htaccess);
	
			if(file_put_contents('../.htaccess', $htaccess) === false) {
				$errors[] = 'Unable to create .htaccess file. Make to create one to enable clean urls.';
			}
		} else {
			$warnings[] = 'It looks like you already have a htaccess file in place, to use clean URLs please copy and paste our sample htaccess.txt file, remember to update the RewriteBase option if you have installed Anchor in a subfolder.';
		}
	}
}

// create db
if(empty($errors)) {
	// create a unique password for our installation
	$password = random(8);

	$sql = str_replace('[[now]]', time(), file_get_contents('anchor.sql'));
	$sql = str_replace('[[password]]', crypt($password), $sql);
	$sql = str_replace('[[email]]', strtolower(trim($post['email'])), $sql);
	
	try {
		$dbh->beginTransaction();
		$dbh->exec($sql);
		
		$sql= "INSERT INTO `meta` (`key`, `value`) VALUES ('sitename', ?), ('description', ?), ('theme', ?);";
		$statement = $dbh->prepare($sql);
		$statement->execute(array($post['name'], $post['description'], $post['theme']));

		$dbh->commit();
	} catch(PDOException $e) {
		$errors[] = $e->getMessage();
		
		// rollback any changes
		if($dbh->inTransaction()) {
			$dbh->rollBack();
		}
	}
}

// output response
header('Content-Type: application/json');

if(empty($errors)) {
	//no errors we're all gooood
	$response['installed'] = true;
	$response['password'] = $password;
	$response['warnings'] = $warnings;
} else {
	$response['installed'] = false;
	$response['errors'] = $errors;
	$response['warnings'] = $warnings;
}

// output json formatted string
echo json_encode($response);
