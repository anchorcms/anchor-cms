<?php

/*
	Pre upgrade checks
*/
$GLOBALS['errors'] = array();

function check($message, $action) {
	if( ! $action()) {
		$GLOBALS['errors'][] = $message;
	}
}

check('<code>upgrade/storage</code> directory needs to be writable.', function() {
	return is_writable(PATH . 'upgrade/storage');
});

check('<code>anchor/config</code> directory needs to be writable.', function() {
	return is_writable(PATH . 'anchor/config');
});

check('Anchor requires <code>pdo_mysql</code> module to be installed.', function() {
	return in_array('mysql', PDO::getAvailableDrivers());
});

if(Uri::current() != 'complete') {
	check('Anchor is already installed!', function() {
		return file_exists(PATH . 'anchor/config/database.php') === false;
	});
}

if(count($GLOBALS['errors'])) {
	$vars['errors'] = $GLOBALS['errors'];
	$vars['uri'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

	echo View::make('halt', $vars)
		->nest('header', 'partials/header')
		->nest('footer', 'partials/footer');

	exit(1);
}