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
  include $path . 'views/layouts/403.php';
  $content = ob_get_contents();
  ob_end_clean();
  include $path . 'views/layouts/application.php';
  exit;
}

function throw404() {
  global $path, $urlpath;
  ob_start();
  include $path . 'views/layouts/404.php';
  $content = ob_get_contents();
  ob_end_clean();
  include $path . 'views/layouts/application.php';
  exit;
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
  if (!(error_reporting() & $errno)) {
    // This error code is not included in error_reporting
    return;
  }

  switch ($errno) {
    case E_USER_ERROR:
      $error_output = "<b>ERROR</b> [$errno] $errstr<br />\n" .
                      "Fatal error on line $errline in file $errfile" .
                      ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n" .
                      "Aborting...<br />\n";
      break;
  
    case E_USER_WARNING:
      echo "<b>WARNING</b> [$errno] $errstr<br />\n";
      break;
  
    case E_USER_NOTICE:
      echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
      break;
  
    default:
      echo "Unknown error type: [$errno] $errstr<br />\n";
      break;
  }

  return true;
}

$application_layout = 'application';

function layout($layout) {
  global $application_layout;
  $application_layout = $layout;
}

ob_start();
//include 'loader.php';
$request = str_replace($urlpath, '', $_SERVER['REQUEST_URI']);
if (substr($request, -1) == '/') { $request = substr($request, 0, -1); }
if ($request == '') {
  $route = explode('#', $root);
  require_once $path . 'controllers/' . $route[0] . '.php';
  if (is_callable(($requestFunction = implode('_', $route))) === false) {
    throw404();
  }
  call_user_func($requestFunction, (isset($match) ? $match : null));
} else {
  foreach ($routes as $routeFrom => $routeTo) {
    if (preg_match('`^' . $routeFrom . '$`i', $request, $match) == 1) {
      $route = explode('#', $routeTo);
      require_once $path . 'controllers/' . $route[0] . '.php';
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
include $path . 'views/layouts/' . $application_layout . '.php';