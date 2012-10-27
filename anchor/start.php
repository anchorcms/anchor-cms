<?php

/*
	Anchor setup
*/
define('IS_ADMIN', strpos(Uri::current(), 'admin') === 0);

// Check installation
if(is_null(Config::load('database'))) {
	echo View::make('intro')->render();

	exit(1);
}

// load database metadata
foreach(Query::table('meta')->get() as $item) {
	$meta[$item->key] = $item->value;
}

Config::set('meta', $meta);

if( ! IS_ADMIN) {
	// include global theming functions
	$fi = new FilesystemIterator(APP . 'functions', FilesystemIterator::SKIP_DOTS);

	foreach($fi as $file) {
		if($file->isFile() and $file->isReadable() and pathinfo($file->getPathname(), PATHINFO_EXTENSION) == 'php') {
			require $file->getPathname();
		}
	}

	// include theme functions
	if(is_readable($path = PATH . 'themes' . DS . Config::get('meta.theme') . DS . 'functions.php')) {
		require $path;
	}
}

// register home page
Registry::set('home_page', Page::home());

// register posts page
Registry::set('posts_page', Page::posts());

// register categories
foreach(Category::all() as $itm) {
	$categories[$itm->id] = $itm;
}

Registry::set('all_categories', $categories);

function __($line, $default = 'No language replacement') {
	return Language::line($line, $default);
}

// include admin functions
if(IS_ADMIN) {
	function asset($path) {
		return Html::asset('anchor/views/assets/' . $path);
	}

	function url($path) {
		return Uri::make('admin/' . $path);
	}

	function site($path = '') {
		return Uri::make($path);
	}
}