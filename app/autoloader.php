<?php

// check composer is installed
if(false === is_file($autoloader = __DIR__ . '/../vendor/autoload.php')) {
	echo 'Composer not found, please <a href="https://getcomposer.org/download/" target="_blank">download</a> and run <code>composer install</code>.';
	exit(1);
}

require $autoloader;
