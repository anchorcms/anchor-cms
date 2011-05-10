<?php
function posts_index() {
  global $path, $urlpath;
  $posts = Post::all();
  include $path . 'views/posts/index.php';
}

function posts_show($post) {
  global $path, $urlpath;
  if (isset($post[1]) === true) {
    $post = Post::find(intval($post[1]));
  } else {
    $post = Post::find_by_slug($post[0]);
  }
  
  if ($post === false) { throw404(); }
  include $path . 'views/posts/show.php';
}

function posts_latest() {
  global $urlpath;
  $post = Post::all(array('limit' => 1), 'slug');
  header('Location: ' . $urlpath . $post[0]->slug);
}
?>