<?php

// Make sure this is PHP 5.3 or later
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50500) {
	printf('PHP 5.5.0 or later is required, but you&rsquo;re running %s.', PHP_VERSION);
	exit(1);
}

// Set default timezone to UTC
if( ! ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}
