<?php

/*
	Pre install checks
*/
Installer::check('<code>anchor/config</code> directory needs to be writable.', function() {
	return is_writable(PATH . 'anchor/config');
});

Installer::check('Anchor requires <code>pdo_mysql</code> module to be installed.', function() {
	return in_array('mysql', PDO::getAvailableDrivers());
});

if(Uri::current() != 'complete') {
	Installer::check('Anchor is already installed!', function() {
		return file_exists(PATH . 'anchor/config/database.php') === false;
	});
}

if(count(Installer::$errors)) {
	echo View::make('halt', array('errors' => Installer::$errors))
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');

	exit(1);
}