<?php

/**
 * Returns true if there is at least one comment approved
 *
 * Set the Registry with an array or approved comments for the
 * current article (post)
 *
 * @return bool
 */
function has_comments() {
	if( ! $article = Registry::get('article')) {
		return false;
	}

	if( ! $comments = Registry::get('comments')) {
		$comments = Comment::where('status', '=', 'approved')->where('post', '=', $article->id)->get();

		$comments = new Items($comments);

		Registry::set('comments', $comments);
	}

	return $comments->length() > 0;
}

/**
 * Returns the number of comments approved
 *
 * @return int
 */
function total_comments() {
	if( ! has_comments()) return 0;

	$comments = Registry::get('comments');

	return $comments->length();
}

/**
 * Returns true while there are still items in the array.
 *
 * Updates the current comment object in the Registry on each call.
 *
 * @return bool
 */
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
 * Returns the comment ID
 *
 * @return string
 */
function comment_id() {
	return Registry::prop('comment', 'id');
}

/**
 * Returns the comment date as a unix time stamp
 *
 * @return string
 */
function comment_time() {
	if($time = Registry::prop('comment', 'date')) {
		return Date::format($time, 'U');
	}
}

/**
 * Returns the comment date formatted
 *
 * @return string
 */
function comment_date() {
	if($date = Registry::prop('comment', 'date')) {
		return Date::format($date);
	}
}

/**
 * Returns the comment name
 *
 * @return string
 */
function comment_name() {
	return Registry::prop('comment', 'name');
}

/**
 * Returns the comment email
 *
 * @return string
 */
function comment_email() {
	return Registry::prop('comment', 'email');
}

/**
 * Returns the comment body text
 *
 * @return string
 */
function comment_text() {
	return parse(Registry::prop('comment', 'text'));
}

/**
 * Returns true if the current article has enabled comments
 *
 * @return bool
 */
function comments_open() {
	return Registry::prop('article', 'comments') ? true : false;
}

/**
 * Returns html of validation messages
 *
 * @return string
 */
function comment_form_notifications() {
	return Notify::read();
}

/**
 * Returns the url of where the comments form should be sent to
 *
 * @return string
 */
function comment_form_url() {
	return Uri::to(Uri::current());
}

/**
 * Returns the html input for the name
 *
 * @param string
 * @return string
 */
function comment_form_input_name($extra = '') {
	return '<input name="name" id="name" type="text" ' . $extra . ' value="' . Input::previous('name') . '">';
}

/**
 * Returns the html input for the email
 *
 * @param string
 * @return string
 */
function comment_form_input_email($extra = '') {
	return '<input name="email" id="email" type="email" ' . $extra . ' value="' . Input::previous('email') . '">';
}

/**
 * Returns the html input for the body text
 *
 * @param string
 * @return string
 */
function comment_form_input_text($extra = '') {
	return '<textarea name="text" id="text" ' . $extra . '>' . Input::previous('text') . '</textarea>';
}

/**
 * Returns the html input for the submit button
 *
 * @param string
 * @param string
 * @return string
 */
function comment_form_button($text = 'Post Comment', $extra = '') {
	return '<button class="btn" type="submit" ' . $extra . '>' . $text . '</button>';
}
