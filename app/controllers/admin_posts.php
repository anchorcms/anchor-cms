<?php
layout('admin');

function admin_posts_index() {
  global $path, $urlpath;
  if (User::is_logged_in() === false) { throw403(); }
  $posts = Post::all(array('order' => 'id'));
  render(array('posts' => $posts));
}

function admin_posts_edit($post) {
  global $path, $urlpath;
  $post = Post::find($post[1]);
  if (isset($_POST['post']) === true) {
    if ($post->update_attributes($_POST['post']) === true) {
      echo "<h1>Post updated successfully</h1>";
      return;
    }
    echo "<h1>Post failed to update</h1>";
  }
  render(array('post' => $post));
}

function admin_posts_new() {
  global $path, $urlpath;
  if (isset($_POST['post']) === true) {
    $post = new Post($_POST['post']);
    if ($post->save() === true) {
      echo "<h1>Post created successfully</h1>";
      return;
    }
    echo "<h1>Post failed to create</h1>";
  }
  render();
}