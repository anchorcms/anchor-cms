<?php
layout('admin');

function admin_posts_index() {
  global $path, $urlpath;
  if (User::is_logged_in() === false) { throw403(); }
  $posts = Post::all();
  include $path . 'views/admin_posts/index.php';
}

function admin_posts_edit($post) {
  global $path, $urlpath;
  $post = Post::find(intval($post[1]));
  include $path . 'views/admin_posts/edit.php';
}

function admin_posts_new() {
  global $path, $urlpath;
  include $path . 'views/admin_posts/new.php';
}
?>