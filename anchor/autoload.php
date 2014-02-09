<?php

if( ! is_file(__DIR__ . '/../vendor/autoload.php')) {
	echo 'Composer is not installed or cannot be found!'; exit;
}
else {
	require __DIR__ . '/../vendor/autoload.php';

	$loader = new Composer\Autoload\ClassLoader;
	$loader->register();
}