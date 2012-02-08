<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for posts
*/
function has_posts() {
	if(($posts = IoC::resolve('posts')) === false) {
		$posts = Posts::list_public(array('sortby' => 'id', 'sortmode' => 'desc'));
		IoC::instance('posts', $posts, true);
	}
	
	return $posts->length() > 0;
}

function posts($params = array()) {
	if(has_posts() === false) {
		return false;
	}
	
	$posts = IoC::resolve('posts');

	if($result = $posts->valid()) {	
		// register single post
		IoC::instance('article', $posts->current(), true);
		
		// move to next
		$posts->next();
	}

	return $result;
}
