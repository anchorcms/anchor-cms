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

Installer::check('Anchor is already installed!', function() {
	return is_readable(PATH . 'anchor/config/database.php');
});

if(count(Installer::$errors)) {
	echo View::make('halt', array('errors' => Installer::$errors))
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');

	exit(1);
}