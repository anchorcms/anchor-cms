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
	return Registry::prop('comment', 'id');
}

function comment_time() {
	if($time = Registry::prop('comment', 'date')) {
		return Date::format($time,'U');
	}
}

function comment_date() {
	if($date = Registry::prop('comment', 'date')) {
		return Date::format($date);
	}
}

function comment_name() {
	return Registry::prop('comment', 'name');
}

function comment_email() {
	return Registry::prop('comment', 'email');
}

function comment_text() {
	return Registry::prop('comment', 'text');
}

function comments_open() {
	return Registry::prop('article', 'comments') ? true : false;
}

// form elements
function comment_form_notifications() {
	return Notify::read();
}

function comment_form_url() {
	return Uri::to(Uri::current());
}

function comment_form_input_name() {
	return Form::text('name', Input::previous('name'), array('id' => 'name', 'placeholder' => __('comments.name')));
}

function comment_form_input_email() {
	return Form::email('email', Input::previous('email'), array('id' => 'email', 'placeholder' => __('comments.email')));
}

function comment_form_input_text() {
    return Form::textarea('text', Input::previous('text'), array('id' => 'text', 'placeholder' => __('comments.text')));
}

function comment_form_button() {
    return Form::button(__('comments.button'), array('type' => 'submit'));
}