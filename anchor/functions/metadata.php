<?php

/**
	Theme functions for meta
*/
function site_name() {
	return Config::get('meta.sitename');
}

function site_description() {
	return Config::get('meta.description');
}