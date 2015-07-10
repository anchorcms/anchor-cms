<?php

return [
	'/admin' => 'controllers\\admin\\auth@start',

	'/admin/auth/login' => 'controllers\\admin\\auth@login',
	'/admin/auth/attempt' => 'controllers\\admin\\auth@attempt',
	'/admin/auth/logout' => 'controllers\\admin\\auth@logout',
	'/admin/auth/amnesia' => 'controllers\\admin\\auth@amnesia',

	'/admin/upload' => 'controllers\\admin\\media@upload',
	'/admin/media' => 'controllers\\admin\\media@fetch',

	'/admin/posts' => 'controllers\\admin\\posts@index',
	'/admin/posts/create' => 'controllers\\admin\\posts@create',
	'/admin/posts/save' => 'controllers\\admin\\posts@save',
	'/admin/posts/([0-9]+)/edit' => 'controllers\\admin\\posts@edit',
	'/admin/posts/([0-9]+)/update' => 'controllers\\admin\\posts@update',
	'/admin/posts/([0-9]+)/delete' => 'controllers\\admin\\posts@delete',

	'/admin/pages' => 'controllers\\admin\\pages@index',
	'/admin/pages/create' => 'controllers\\admin\\pages@create',
	'/admin/pages/save' => 'controllers\\admin\\pages@save',
	'/admin/pages/([0-9]+)/edit' => 'controllers\\admin\\pages@edit',
	'/admin/pages/([0-9]+)/update' => 'controllers\\admin\\pages@update',
	'/admin/pages/([0-9]+)/delete' => 'controllers\\admin\\pages@delete',

	'/admin/comments' => 'controllers\\admin\\comments@index',
	'/admin/comments/([0-9]+)/edit' => 'controllers\\admin\\comments@edit',
	'/admin/comments/([0-9]+)/update' => 'controllers\\admin\\comments@update',
	'/admin/comments/([0-9]+)/delete' => 'controllers\\admin\\comments@delete',

	'/admin/users' => 'controllers\\admin\\users@index',
	'/admin/users/create' => 'controllers\\admin\\users@create',
	'/admin/users/save' => 'controllers\\admin\\users@save',
	'/admin/users/([0-9]+)/edit' => 'controllers\\admin\\users@edit',
	'/admin/users/([0-9]+)/update' => 'controllers\\admin\\users@update',
	'/admin/users/([0-9]+)/delete' => 'controllers\\admin\\users@delete',

	'/admin/meta' => 'controllers\\admin\\meta@index',
	'/admin/meta/update' => 'controllers\\admin\\meta@update',

	'/admin/themes' => 'controllers\\admin\\themes@index',
	'/admin/plugins' => 'controllers\\admin\\plugins@index',
	'/admin/vars' => 'controllers\\admin\\vars@index',
	'/admin/fields' => 'controllers\\admin\\fields@index',

	'/feeds/rss' => 'controllers\\feeds@rss',

	'/' => 'controllers\\page@home',
	'/category/:category' => 'controllers\\page@category',
	'/:page/:post' => 'controllers\\posts@index',
	'/:page' => 'controllers\\page@index',
];
