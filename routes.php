<?php
$routes = array(
  'admin' => 'admin_posts#index',
  'admin/posts' => 'admin_posts#index',
  'posts/(\d+)' => 'posts#show',
  '.*' => 'posts#show'
);
$root = 'posts#index';