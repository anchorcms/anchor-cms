<?php
	//	Run Anchor
	//require('core/loader.php');
session_start();
require_once 'routes.php';
require_once 'core/paths.php';
require_once 'core/class.php';
require_once 'core/connect.php';

function throw403() {
  global $path, $urlpath;
  ob_start();
  render('layouts/403');
  $content = ob_get_contents();
  ob_end_clean();
  render('layouts/application');
  exit;
}

function throw404() {
  global $path, $urlpath;
  ob_start();
  render('layouts/404');
  $content = ob_get_contents();
  ob_end_clean();
  render('layouts/application');
  exit;
}

$application_layout = 'application';

function layout($layout) {
  global $application_layout;
  $application_layout = $layout;
}

function render($options = null) {
  global $route, $path, $urlpath, $content;
  if (isset($options) === false || isset($options['view']) === false) {
    $view = implode('/', $route);
  } else if (is_string($options) === true) {
    $view = $options;
  }
  if (is_array($options) === true) {
    foreach ($options as $key => $value) { $$key = $value; }
  }
  include $path . 'app/views/' . str_replace('..', '', $view) . '.php';
}

ob_start();
//include 'loader.php';
$request = str_replace($urlpath, '', $_SERVER['REQUEST_URI']);
if (substr($request, -1) == '/') { $request = substr($request, 0, -1); }
if ($request == '') {
  $route = explode('#', $root);
  require_once $path . 'app/controllers/' . $route[0] . '.php';
  if (is_callable(($requestFunction = implode('_', $route))) === false) {
    throw404();
  }
  call_user_func($requestFunction, (isset($match) ? $match : null));
} else {
  foreach ($routes as $routeFrom => $routeTo) {
    if (preg_match('`^' . $routeFrom . '$`i', $request, $match) == 1) {
      $route = explode('#', $routeTo);
      require_once $path . 'app/controllers/' . $route[0] . '.php';
      if (is_callable(($requestFunction = implode('_', $route))) === false) {
        throw404();
      }
      call_user_func($requestFunction, (isset($match) ? $match : null));
      break;
    }
  }
}
$content = ob_get_contents();
ob_end_clean();
render('layouts/' . $application_layout);