<?php

/*
	Pre install checks
*/
$GLOBALS['errors'] = array();

function check($message, $action) {
	if( ! $action()) {
		$GLOBALS['errors'][] = $message;
	}
}

check('<code>anchor/config</code> directory needs to be temporary writable
	so we can create your application and database configuration files.', function() {
	return is_writable(PATH . 'anchor/config');
});

if(is_apache()) {
	check('The public root directory needs to be temporary writable
		while we try to create your htaccess file.', function() {
		return is_writable(PATH);
	});
}

check('Anchor requires <code>pdo_mysql</code> module to be installed.', function() {
	return in_array('mysql', PDO::getAvailableDrivers());
});

if(count($GLOBALS['errors'])) {
	$vars['errors'] = $GLOBALS['errors'];
	$vars['uri'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

	echo View::make('halt', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');

	exit(1);
}

/*
	Helpers
*/
function is_apache() {
	return stripos(PHP_SAPI, 'apache') !== false;
}

function is_cgi() {
	return stripos(PHP_SAPI, 'cgi') !== false;
}