<?php

return [
	'/admin' => 'admin\\auth@start',

	'/admin/auth/login' => 'admin\\auth@login',
	'/admin/auth/attempt' => 'admin\\auth@attempt',
	'/admin/auth/logout' => 'admin\\auth@logout',
	'/admin/auth/amnesia' => 'admin\\auth@amnesia',

	'/admin/upload' => 'admin\\media@upload',

	'/admin/posts' => 'admin\\posts@index',
	'/admin/posts/create' => 'admin\\posts@create',
	'/admin/posts/save' => 'admin\\posts@save',
	'/admin/posts/([0-9]+)/edit' => 'admin\\posts@edit',
	'/admin/posts/([0-9]+)/update' => 'admin\\posts@update',
	'/admin/posts/([0-9]+)/delete' => 'admin\\posts@delete',

	'/admin/pages' => 'admin\\pages@index',
	'/admin/pages/create' => 'admin\\pages@create',
	'/admin/pages/save' => 'admin\\pages@save',
	'/admin/pages/([0-9]+)/edit' => 'admin\\pages@edit',
	'/admin/pages/([0-9]+)/update' => 'admin\\pages@update',
	'/admin/pages/([0-9]+)/delete' => 'admin\\pages@delete',

	'/admin/comments' => 'admin\\comments@index',
	'/admin/comments/([0-9]+)/edit' => 'admin\\comments@edit',
	'/admin/comments/([0-9]+)/update' => 'admin\\comments@update',
	'/admin/comments/([0-9]+)/delete' => 'admin\\comments@delete',

	'/admin/users' => 'admin\\users@index',
	'/admin/users/create' => 'admin\\users@create',
	'/admin/users/save' => 'admin\\users@save',
	'/admin/users/([0-9]+)/edit' => 'admin\\users@edit',
	'/admin/users/([0-9]+)/update' => 'admin\\users@update',
	'/admin/users/([0-9]+)/delete' => 'admin\\users@delete',

	'/admin/meta' => 'admin\\meta@index',
	'/admin/meta/update' => 'admin\\meta@update',

	'/admin/themes' => 'admin\\themes@index',
	'/admin/plugins' => 'admin\\plugins@index',
	'/admin/vars' => 'admin\\vars@index',
	'/admin/fields' => 'admin\\fields@index',

	'/feeds/rss' => 'feeds@rss',

	'/' => 'page@home',
	'/category/([^/]+)' => 'page@category',
	'/([^/]+)/([^/]+)' => 'posts@index',
	'/([^/]+)' => 'page@index',
];
