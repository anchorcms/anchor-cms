<?php define('IN_CMS', true);

// latest version
define('ANCHOR_VERSION', 0.7);

// Define base path
define('PATH', pathinfo(dirname(__FILE__), PATHINFO_DIRNAME) . '/');

// get system classes
require PATH . 'system/classes/config.php';
require PATH . 'system/classes/db.php';
require PATH . 'system/classes/str.php';
require PATH . 'system/classes/input.php';

// upgrade classes
require PATH . 'upgrade/classes/messages.php';
require PATH . 'upgrade/classes/migrations.php';
require PATH . 'upgrade/classes/schema.php';
require PATH . 'upgrade/controller.php';

// helpers
function render($file, $data = array()) {
	extract($data, EXTR_SKIP);
	require PATH . 'upgrade/views/' . $file . '.php';
}

function redirect($action) {
	header('Location: index.php?' . http_build_query(array('action' => $action)));
}

// load current config
Config::load(PATH . 'config.php');

$controller = new Upgrade_controller;
$method = Input::get('action', 'stage1');
$reflector = new ReflectionClass($controller);

if($reflector->hasMethod($method) === false) {
	header("HTTP/1.0 404 Not Found");
	return '';
}

$reflector->getMethod($method)->invoke($controller);