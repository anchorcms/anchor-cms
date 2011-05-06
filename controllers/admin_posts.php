<?php
function admin_posts_index() {
  global $path, $urlpath;
  if (User::is_logged_in() === false) { throw403(); }
  $posts = Post::listAll();
  include $path . 'views/admin_posts/index.php';
}
?>