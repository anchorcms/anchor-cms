<?php

/********************************
 *  Theme functions for comments
 ********************************/

use System\input;
use System\uri;

/**
 * Checks whether the current article has comments
 *
 * @return bool|int false if invalid article, number of comments otherwise
 * @throws \Exception
 */
function has_comments()
{
    if ( ! $itm = Registry::get('article')) {
        return false;
    }

    if ( ! $comments = Registry::get('comments')) {
        $comments = Comment::where('status', '=', 'approved')
                           ->where('post', '=', $itm->id)
                           ->get();

        $comments = new Items($comments);

        Registry::set('comments', $comments);
    }

    return $comments->length();
}

/**
 * Retrieves the amount of comments the current article has
 *
 * @return int number of comments
 * @throws \Exception
 */
function total_comments()
{
    if ( ! has_comments()) {
        return 0;
    }

    $comments = Registry::get('comments');

    return $comments->length();
}

/**
 * Loops through all comments
 *
 * @return bool
 * @throws \Exception
 */
function comments()
{
    if ( ! has_comments()) {
        return false;
    }

    $comments = Registry::get('comments');

    if ($result = $comments->valid()) {

        // register single comment
        Registry::set('comment', $comments->current());

        // move to next
        $comments->next();
    }

    return $result;
}

/**
 * Retrieves the current comment ID
 *
 * @return int
 */
function comment_id()
{
    return Registry::prop('comment', 'id');
}

/**
 * Retrieves the current comment creation time
 *
 * @return string
 */
function comment_time()
{
    if ($time = Registry::prop('comment', 'date')) {
        return Date::format($time, 'U');
    }
}

/**
 * Retrieves the current comment creation date
 *
 * @return string
 */
function comment_date()
{
    if ($date = Registry::prop('comment', 'date')) {
        return Date::format($date);
    }
}

/**
 * Retrieves the current comment's author name
 *
 * @return string
 */
function comment_name()
{
    return Registry::prop('comment', 'name');
}

/**
 * Retrieves the current comment's author email address
 *
 * @return string
 */
function comment_email()
{
    return Registry::prop('comment', 'email');
}

/**
 * Retrieves the current comment text
 *
 * @return string
 */
function comment_text()
{
    return Registry::prop('comment', 'text');
}

/**
 * Checks whether the comments are open for the current article
 *
 * @return bool
 */
function comments_open()
{
    return Registry::prop('article', 'comments') ? true : false;
}

/**
 * Retrieves all comment form notifications
 *
 * @return string
 */
function comment_form_notifications()
{
    return Notify::read();
}

/**
 * Retrieves the comment form action URI
 *
 * @return string
 * @throws \ErrorException
 * @throws \OverflowException
 */
function comment_form_url()
{
    return Uri::to(Uri::current());
}

/**
 * Retrieves the current comment form name input field
 *
 * @param string $extra (optional) additional input attributes
 *
 * @return string
 */
function comment_form_input_name($extra = '')
{
    return '<input name="name" id="name" type="text" ' . $extra . ' value="' . Input::previous('name') . '">';
}

/**
 * Retrieves the current comment form email input field
 *
 * @param string $extra (optional) additional input attributes
 *
 * @return string
 */
function comment_form_input_email($extra = '')
{
    return '<input name="email" id="email" type="email" ' . $extra . ' value="' . Input::previous('email') . '">';
}

/**
 * Retrieves the current comment form text input field
 *
 * @param string $extra (optional) additional input attributes
 *
 * @return string
 */
function comment_form_input_text($extra = '')
{
    return '<textarea name="text" id="text" ' . $extra . '>' . Input::previous('text') . '</textarea>';
}

/**
 * Retrieves the current comment form submit button
 *
 * @param string $text  submit button label
 * @param string $extra (optional) additional input attributes
 *
 * @return string
 */
function comment_form_button($text = 'Post Comment', $extra = '')
{
    return '<button type="submit" ' . $extra . '>' . $text . '</button>';
}
