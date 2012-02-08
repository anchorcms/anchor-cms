<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for metadata
*/
function site_name() {
	return Config::get('metadata.sitename');
}

function site_description() {
	return Config::get('metadata.description');
}

/*
	Twitter
*/
function twitter_account() {
	return Config::get('metadata.twitter');
}

function twitter_url() {
    return 'http://twitter.com/' . twitter_account();
}

