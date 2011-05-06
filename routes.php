<?php
$routes = array(
  'admin' => 'admin_posts#index',
  'admin/posts' => 'admin_posts#index',
  '.*' => 'posts#index'
);
$root = 'posts#index';