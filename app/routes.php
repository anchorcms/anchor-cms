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
	'/admin/posts/:id/edit' => 'controllers\\admin\\posts@edit',
	'/admin/posts/:id/update' => 'controllers\\admin\\posts@update',
	'/admin/posts/:id/delete' => 'controllers\\admin\\posts@delete',

	'/admin/pages' => 'controllers\\admin\\pages@index',
	'/admin/pages/create' => 'controllers\\admin\\pages@create',
	'/admin/pages/save' => 'controllers\\admin\\pages@save',
	'/admin/pages/:id/edit' => 'controllers\\admin\\pages@edit',
	'/admin/pages/:id/update' => 'controllers\\admin\\pages@update',
	'/admin/pages/:id/delete' => 'controllers\\admin\\pages@delete',

	'/admin/categories' => 'controllers\\admin\\categories@index',
	'/admin/categories/create' => 'controllers\\admin\\categories@create',
	'/admin/categories/save' => 'controllers\\admin\\categories@save',
	'/admin/categories/:id/edit' => 'controllers\\admin\\categories@edit',
	'/admin/categories/:id/update' => 'controllers\\admin\\categories@update',
	'/admin/categories/:id/delete' => 'controllers\\admin\\categories@delete',

	'/admin/users' => 'controllers\\admin\\users@index',
	'/admin/users/create' => 'controllers\\admin\\users@create',
	'/admin/users/save' => 'controllers\\admin\\users@save',
	'/admin/users/:id/edit' => 'controllers\\admin\\users@edit',
	'/admin/users/:id/update' => 'controllers\\admin\\users@update',
	'/admin/users/:id/delete' => 'controllers\\admin\\users@delete',

	'/admin/meta' => 'controllers\\admin\\meta@index',
	'/admin/meta/update' => 'controllers\\admin\\meta@update',

	'/admin/themes' => 'controllers\\admin\\themes@index',
	'/admin/plugins' => 'controllers\\admin\\plugins@index',
	'/admin/vars' => 'controllers\\admin\\vars@index',
	'/admin/fields' => 'controllers\\admin\\fields@index',
	'/admin/extend' => 'controllers\\admin\\extend@index',

	'/feeds/rss' => 'controllers\\feeds@rss',

	'/' => 'controllers\\page@home',
	'/category/:category' => 'controllers\\page@category',
	'/:page/:post' => 'controllers\\posts@index',
	'/:page' => 'controllers\\page@index',
];
