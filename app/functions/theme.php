<?php

namespace Anchorcms;

function theme_url($url = '') {
	global $app;

	$url = sprintf('/themes/%s/%s', $app['theme']->getTheme(), $url);

	return full_url($url);
}

function theme_include($file) {
	global $app;

	$name = $app['theme']->getTheme();

	$path = sprintf('%s/%s/%s', $app['paths']['themes'], $app['theme']->getTheme(), $file);

	require $path;
}
