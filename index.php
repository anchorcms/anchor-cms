<?php
	//	Run Anchor
	//require('core/loader.php');
require_once 'routes.php';
require_once 'core/paths.php';
require_once 'core/class.php';
require_once 'core/connect.php';

function throw404() {
  die('KABOOM! Its a 404');
}

ob_start();
//include 'loader.php';
$request = str_replace($urlpath, '', $_SERVER['REQUEST_URI']);
if ($request == '') {
  $route = explode('#', $root);
  require_once $path . 'controllers/' . $route[0] . '.php';
  if (is_callable(($requestFunction = implode('_', $route))) === false) {
    throw404();
  }
  call_user_func($requestFunction, $match);
} else {
  foreach ($routes as $routeFrom => $routeTo) {
    if (preg_match('`^' . $routeFrom . '$`i', $request, $match) == 1) {
      $route = explode('#', $routeTo);
      require_once $path . 'controllers/' . $route[0] . '.php';
      if (is_callable(($requestFunction = implode('_', $route))) === false) {
        throw404();
      }
      call_user_func($requestFunction, $match);
      break;
    }
  }
}
$content = ob_get_contents();
ob_end_clean();
include $path . 'views/layouts/application.php';