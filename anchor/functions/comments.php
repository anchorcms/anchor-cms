<?php

/**
*	Theme functions for comments
*/

/**
 * Are there really any comments?
 * @return boolean
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

/**
 * How many comments are there?
 * @return int
 */
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

/**
 * Grab the id of the current comment
 * @return int
 */
function comment_id() {
	return Registry::prop('comment', 'id');
}

/**
 * Grab the id of the current comment
 * @return int
 */
function comment_time() {
	if($time = Registry::prop('comment', 'date')) {
		return Date::format($time,'U');
	}
}

/**
 * Grab the date that this comment was created
 * @return String
 */
function comment_date() {
	if($date = Registry::prop('comment', 'date')) {
		return Date::format($date);
	}
}

/**
 * Grab the name of the person who posted the comment
 * @return String
 */
function comment_name() {
	return Registry::prop('comment', 'name');
}

/**
 * What's the commentor's email?
 * @return String
 */
function comment_email() {
	return Registry::prop('comment', 'email');
}

/**
 * What does the comment say?
 * @return String
 */
function comment_text() {
	return Registry::prop('comment', 'text');
}

/**
 * Are we allowed to comment?
 * @return boolean
 */
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

/**
 * Quickly make the HTML for an input box for the name
 * @return String
 */
function comment_form_input_name($extra = '') {
	return '<input name="name" id="name" type="text" ' . $extra . ' value="' . Input::previous('name') . '">';
}

/**
 * Quickly make the HTML for an input box for the email
 * @return String
 */
function comment_form_input_email($extra = '') {
	return '<input name="email" id="email" type="email" ' . $extra . ' value="' . Input::previous('email') . '">';
}

/**
 * Quickly make the HTML for an input box for the actual comment
 * @return String
 */
function comment_form_input_text($extra = '') {
	return '<textarea name="text" id="text" ' . $extra . '>' . Input::previous('text') . '</textarea>';
}

/**
 * Quickly make the HTML for a submit button to post the comment.
 * @return String
 */
function comment_form_button($text = 'Post Comment', $extra = '') {
	return '<button class="btn" type="submit" ' . $extra . '>' . $text . '</button>';
}