<?php
function posts_index() {
  global $path, $urlpath;
  $posts = Post::listAll();
  include $path . 'views/admin_posts/index.php';
}
?>