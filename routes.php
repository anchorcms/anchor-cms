<?php
$routes = array(
    'admin' => 'admin_posts#index',
    'admin/posts' => 'admin_posts#index',
    'admin/posts/new' => 'admin_posts#new',
    'admin/posts/edit/(\d+)' => 'admin_posts#edit',
    'admin/settings' => 'admin#settings',
    'admin/users' => 'admin_users#index',
    'admin/users/new' => 'admin_users#new',
    'admin/users/edit/(\d+)' => 'admin_users#edit',
    'posts/(\d+)' => 'posts#show',
    'user/login' => 'users#login',
    'admin/login' => 'users#login',
    'user/logout' => 'users#logout',
    'admin/logout' => 'users#logout',
    'latest' => 'posts#latest',
    'random' => 'posts#random',
    '.*' => 'posts#show'
);

$root = 'posts#index';