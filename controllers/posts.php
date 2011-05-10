<?php
function posts_index() {
  global $path, $urlpath;
  $posts = Post::all(array('order' => 'id DESC'));
  include $path . 'views/posts/index.php';
}

function posts_show($post) {
  global $path, $urlpath;
  if (isset($post[1]) === true) {
    $post = Post::find($post[1]);
  } else {
    $post = Post::find_by_slug($post[0]);
  }
  
  if ($post === false) { throw404(); }
  include $path . 'views/posts/show.php';
}

function posts_latest() {
  global $urlpath;
  header('Location: ' . $urlpath . Post::find(array('select' => 'slug', 'limit' => 1, 'order' => 'id DESC'))->slug);
}

function posts_random() {
    global $urlpath;   
    header('Location: ' . $urlpath . Post::find(array('select' => 'slug', 'limit' => 1, 'order' => 'RAND()'))->slug);
}