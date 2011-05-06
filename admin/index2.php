<?php
$controller = '';
$action     = '';
ob_start();
include 'loader.php';
$request = explode('/', str_replace($urlpath, '', $_SERVER['REQUEST_URI']));
if ($request[count($request) - 1] == '') { array_pop($request); }

if ((isset($request[1]) === true && strtolower($request[1]) == 'pages') || $request == array('admin')) {
  $controller = 'pages';
  $action     = 'index';
  require_once $path . 'controllers/posts.php';
  posts_index();
}
$content = ob_get_contents();
ob_end_clean();
include $path . 'views/layouts/admin.php';
?>