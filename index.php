<?php
	//	Run Anchor
	//require('core/loader.php');
require_once 'routes.php';
require_once 'core/paths.php';
require_once 'core/class.php';
require_once 'core/connect.php';
ob_start();
//include 'loader.php';
$request = str_replace($urlpath, '', $_SERVER['REQUEST_URI']);
foreach ($routes as $routeFrom => $routeTo) {
  if (preg_match('`' . $routeFrom . '`i', $request, $match) == 1) {
    $route = explode('#', $routeTo);
    require_once $path . 'controllers/' . $route[0] . '.php';
    call_user_func(implode('_', $route));
    break;
  }
}
$content = ob_get_contents();
ob_end_clean();
include $path . 'views/layouts/application.php';