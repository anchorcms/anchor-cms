<?php
$routes = array(
  'admin' => 'admin_posts#index',
  'admin/posts' => 'admin_posts#index',
  'admin/posts/new' => 'admin_posts#new',
  'admin/posts/edit/(\d+)' => 'admin_posts#edit',
  'posts/(\d+)' => 'posts#show',
  'user/login' => 'users#login',
  'admin/login' => 'users#login',
  'user/logout' => 'users#logout',
  'admin/logout' => 'users#logout',
  'latest' => 'posts#latest',
  '.*' => 'posts#show'
);
$root = 'posts#index';