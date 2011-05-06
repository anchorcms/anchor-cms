<?php
function posts_index() {
  global $path, $urlpath;
  $posts = Post::listAll();
  include $path . 'views/posts/index.php';
}
?>