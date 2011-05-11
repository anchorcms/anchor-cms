<?php
layout('admin');

function admin_users_index() {
  global $path, $urlpath;
  if (User::is_logged_in() === false) { throw403(); }
  $users = User::all();
  include $path . 'views/admin_users/index.php';
}

function admin_users_edit($user) {
  global $path, $urlpath;
  $user = User::find($user[1]);
  if (isset($_POST['user']) === true) {
    if ($user->update_attributes($_POST['user']) === true) {
      echo "<h1>User updated successfully</h1>";
      return;
    }
    echo "<h1>User failed to update</h1>";
  }
  include $path . 'views/admin_users/edit.php';
}

function admin_users_new() {
  global $path, $urlpath;
  if (isset($_POST['user']) === true) {
    $user = new User($_POST['user']);
    if ($user->save() === true) {
      echo "<h1>User created successfully</h1>";
      return;
    }
    echo "<h1>User failed to create</h1>";
  }
  include $path . 'views/admin_users/new.php';
}
?>