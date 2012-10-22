<?php

/**
	Theme functions for comments
*/

function has_comments() {
	if( ! $itm = Registry::get('article')) {
		return false;
	}

	if( ! $comments = Registry::get('comments')) {
		$comments = Comment::where('status', '=', 'approved')->where('post', '=', $itm->id)->get();

		$comments = new Items($comments);

		Registry::set('comments', $comments);
	}

	return $comments->length();
}

function total_comments() {
	if( ! has_comments()) return 0;

	$comments = Registry::get('comments');

	return $comments->length();
}

// loop comments
function comments() {
	if( ! has_comments()) return false;

	$comments = Registry::get('comments');

	if($result = $comments->valid()) {
		// register single comment
		Registry::set('comment', $comments->current());

		// move to next
		$comments->next();
	}

	return $result;
}

// single comments
function comment_id() {
	if($itm = Registry::get('comment')) {
		return $itm->id;
	}

	return '';
}

function comment_time() {
	if($itm = Registry::get('comment')) {
		return $itm->date;
	}

	return '';
}

function comment_date() {
	if($itm = Registry::get('comment')) {
		return date(Config::get('meta.date_format'), $itm->date);
	}

	return '';
}

function comment_name() {
	if($itm = Registry::get('comment')) {
		return $itm->name;
	}

	return '';
}

function comment_email() {
	if($itm = Registry::get('comment')) {
		return $itm->email;
	}

	return '';
}

function comment_text() {
	if($itm = Registry::get('comment')) {
		return $itm->text;
	}

	return '';
}

function comments_open() {
	if($itm = Registry::get('article')) {
		return $itm->comments ? true : false;
	}

	return false;
}

// form elements
function comment_form_notifications() {
	return Notify::read();
}

function comment_form_url() {
	return Uri::make(Uri::current());
}

function comment_form_input_name($extra = '') {
	return '<input name="name" id="name" type="text" ' . $extra . ' value="' . Input::old('name') . '">';
}

function comment_form_input_email($extra = '') {
	return '<input name="email" id="email" type="email" ' . $extra . ' value="' . Input::old('email') . '">';
}

function comment_form_input_text($extra = '') {
	return '<textarea name="text" id="text" ' . $extra . '>' . Input::old('text') . '</textarea>';
}

function comment_form_button($text = 'Post Comment', $extra = '') {
	return '<button class="btn" type="submit" ' . $extra . '>' . $text . '</button>';
}