<?php

define('__PARENT__', dirname(__DIR__));

return [
	'root' => __PARENT__,
	'app' => __DIR__,
	'config' => __DIR__ . '/config',
	'themes' => __PARENT__ . '/web/themes',
	'plugins' => __PARENT__ . '/web/plugins',
	'content' => __PARENT__ . '/web/content',
	'views' => __DIR__ . '/views',
	'storage' => __DIR__ . '/storage'
];
