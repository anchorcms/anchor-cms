<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme functions for comments
*/

function has_comments() {
	if(($itm = IoC::resolve('article')) === false) {
		return false;
	}
		
	if(($items = IoC::resolve('comments')) === false) {
		$items = Comments::list_all(array('status' => 'published', 'post' => $itm->id));
		IoC::instance('comments', $items, true);
	}
	
	return $items->length() > 0;
}

function total_comments() {
	if(has_comments() === false) {
		return 0;
	}
	
	$items = IoC::resolve('comments');
	return $items->length();
}

// loop comments
function comments() {
	if(has_comments() === false) {
		return false;
	}

	$items = IoC::resolve('comments');

	if($result = $items->valid()) {	
		// register single comment
		IoC::instance('comment', $items->current(), true);
		
		// move to next
		$items->next();
	}

	return $result;
}

// single comments
function comment_id() {
	if($itm = IoC::resolve('comment')) {
		return $itm->id;
	}
	
	return '';
}

function comment_time() {
	if($itm = IoC::resolve('comment')) {
		return $itm->date;
	}
	
	return '';
}

function comment_date() {
	if($itm = IoC::resolve('comment')) {
		return date(Config::get('metadata.date_format'), $itm->date);
	}
	
	return '';
}

function comment_name() {
	if($itm = IoC::resolve('comment')) {
		return $itm->name;
	}
	
	return '';
}

function comment_text() {
	if($itm = IoC::resolve('comment')) {
		return $itm->text;
	}
	
	return '';
}

function comments_open() {
	if($itm = IoC::resolve('article')) {
		return $itm->comments ? true : false;
	}
	
	return false;
}

// form elements
function comment_form_notifications() {
	return Notifications::read();
}

function comment_form_input_name($extra = '') {
	return '<input name="name" id="name" type="text" ' . $extra . '>';
}

function comment_form_input_email($extra = '') {
	return '<input name="email" id="email" type="email" ' . $extra . '>';
}

function comment_form_input_text($extra = '') {
	return '<textarea name="text" id="text" ' . $extra . '></textarea>';
}

function comment_form_button($text = 'Post Comment', $extra = '') {
	return '<button class="btn" type="submit" ' . $extra . '>' . $text . '</button>';
}