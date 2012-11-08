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

check('<code>anchor/config</code> directory needs to be writable.', function() {
	return is_writable(PATH . 'anchor/config');
});

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
	return getenv('SERVER_SOFTWARE') === 'Apache';
}

function is_cgi() {
	if(getenv('FCGI_SERVER_VERSION')) {
		return true;
	}

	if($sign = getenv('SERVER_SIGNATURE')) {
		return stripos($sign, 'cgi') !== false;
	}

	return false;
}