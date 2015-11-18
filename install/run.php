<?php

/*
 * Set your applications current timezone
 */
date_default_timezone_set(Config::app('timezone', 'UTC'));

/*
 * Define the application error reporting level based on your environment
 */
switch(constant('ENV')) {
	case 'dev':
		ini_set('display_errors', true);
		error_reporting(-1);
		break;

	default:
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

/*
 * Set autoload directories to include your app models and libraries
 */
Autoloader::directory(array(
	APP . 'models',
	APP . 'libraries',
	PATH . 'anchor/libraries'
));

/**
 * Set the current uri from get
 */
if($route = Arr::get($_GET, 'route', '/')) {
	Uri::$current = trim($route, '/') ?: '/';
}

/*
	Helper functions
*/
function timezones() {
	$list = DateTimeZone::listIdentifiers();
	$data = array();
    foreach($list as $id => $zone) {
        $date= new DateTime(null, new DateTimeZone($zone));
        $ds = $date->format('I');
        $offset = $date->getOffset();
        $gmt = round(abs($offset / 3600), 2);
        $gmt = ($ds == 1 ? $gmt : $gmt-1 );
        $minutes = fmod($gmt, 1);
        if($minutes == 0) {
            $offset_label = $gmt.'&nbsp;&nbsp;';
        } elseif ($minutes == 0.5) {
            $offset_label = (int)$gmt.'.30';
        } elseif ($minutes == 0.75) {
            $offset_label = (int)$gmt.'.45';
        }
        $sign = $offset > 0 ? '+' : '-';
        if($offset == 0) {
            $sign = ' ';
            $offset = '';
        }
        $label = 'GMT' . $sign . $offset_label . '&nbsp;'  . $zone;
        $data[$offset][$zone] = array('offset'=> $offset, 'label' => $label, 'timezone_id' => $zone);
    }
    ksort($data);
	$timezones = array();
	foreach($data as $offsets) {
		ksort($offsets);
		foreach($offsets as $zone) {
			$timezones[] = $zone;
		}
	}
	return $timezones;
}

function current_timezone() {
	return Cookie::read('anchor-install-timezone', 0) * 3600;
}

function languages() {
	$languages = array();

	$path = PATH . 'anchor/language';
	$if = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

	foreach($if as $file) {
		if($file->isDir()) {
			$languages[] = $file->getBasename();
		}
	}

	return $languages;
}

function prefered_languages() {
	$preferences = array('en-GB');

	if($lang = Arr::get($_SERVER, 'HTTP_ACCEPT_LANGUAGE')) {
		$pattern = '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i';

		if(preg_match_all($pattern, $lang, $matches)) {
			$preferences = $matches[1];
		}
	}

	return array_map(function($str) {
		return str_replace('-', '_', $str);
	}, $preferences);
}

function is_apache() {
	return stripos(PHP_SAPI, 'apache') !== false;
}

function is_cgi() {
	return stripos(PHP_SAPI, 'cgi') !== false;
}

function mod_rewrite() {
	if(is_apache() and function_exists('apache_get_modules')) {
		return in_array('mod_rewrite', apache_get_modules());
	}

	return getenv('HTTP_MOD_REWRITE') ? true : false;
}

/*
	Pre install checks
*/
$GLOBALS['errors'] = array();

function check($message, $action) {
	if( ! $action()) {
		$GLOBALS['errors'][] = $message;
	}
}

check('<code>content</code> directory needs to be writable
	so we can upload your images and files.', function() {
	return is_writable(PATH . 'content');
});

check('<code>anchor/config</code> directory needs to be temporarily writable
	so we can create your application and database configuration files.', function() {
	return is_writable(PATH . 'anchor/config');
});

check('Anchor requires the php module <code>pdo_mysql</code> to be installed.', function() {
	return extension_loaded('PDO') and extension_loaded('pdo_mysql');
});

check('Anchor requires the php module <code>GD</code> to be installed.', function() {
	return extension_loaded('gd');
});

if(count($GLOBALS['errors'])) {
	$vars['errors'] = $GLOBALS['errors'];

	echo Layout::create('halt', $vars)->render();

	exit(0);
}

/**
 * Import defined routes
 */
require APP . 'routes' . EXT;
