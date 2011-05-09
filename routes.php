<?php
$routes = array(
  'admin' => 'admin_posts#index',
  'admin/posts' => 'admin_posts#index',
  'posts/(\d+)' => 'posts#show',
  'user/login' => 'users#login',
  'admin/login' => 'users#login',
  'user/logout' => 'users#logout',
  'admin/logout' => 'users#logout',
  '.*' => 'posts#show'
);
$root = 'posts#index';