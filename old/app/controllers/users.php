<?php
function users_login() {
  global $path, $urlpath, $request;
  if (strpos($request, 'admin') > -1) { layout('admin'); }
  if (User::is_logged_in() === true || User::login() === true) {
    header('Location: ' . $urlpath . 'admin/');
  }
  render();
}

function users_logout() {
  global $urlpath;
  session_destroy();
  header('Location: ' . $urlpath);
}