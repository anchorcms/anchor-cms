<?php

require __DIR__ . '/comments.php';

function set_theme_options($options, $value = null) {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function theme_option($option, $default = '') {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function bind($page, $fn) {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function receive($name = '') {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function body_class() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function slug($string, $separator = '-') {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function parse($str, $markdown = true) {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function readable_size($size) {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function is_admin() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function is_installed() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_description() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_html() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated, use article_content');
}

function article_markdown() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated, use article_content');
}

function article_css() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated, use article_custom_field');
}

function article_js() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated, use article_custom_field');
}

function article_total_comments() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function customised() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_customised() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_object() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_adjacent_url() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function related_posts() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_number() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_previous_url() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}

function article_next_url() {
	throw new ErrorException(__FUNCTION__ . ' function is depreciated');
}
