<?php

// Define base path
define('PATH', pathinfo(__FILE__, PATHINFO_DIRNAME) . '/');

// Anchor version
define('ANCHOR_VERSION', 0.7);

require PATH . 'functions.php';
require PATH . 'models/messages.php';
require PATH . 'models/installer.php';
require PATH . 'controller.php';

// start native session
session_start();

$controller = new Installation_controller;
$method = Installer::compat_check() ? 'compat' : get('action', 'stage1');
$reflector = new ReflectionClass($controller);

if($reflector->hasMethod($method) === false) {
	header("HTTP/1.0 404 Not Found");
	return '';
}

$reflector->getMethod($method)->invoke($controller);