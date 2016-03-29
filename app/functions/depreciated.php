<?php

require __DIR__ . '/comments.php';

function set_theme_options($options, $value = null) {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function theme_option($option, $default = '') {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function bind($page, $fn) {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function receive($name = '') {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function body_class() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function slug($string, $separator = '-') {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function parse($str, $markdown = true) {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function readable_size($size) {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function is_admin() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function is_installed() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_description() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_html() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated, use article_content');
}

function article_markdown() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated, use article_content');
}

function article_css() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated, use article_custom_field');
}

function article_js() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated, use article_custom_field');
}

function article_total_comments() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function customised() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_customised() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_object() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_adjacent_url() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function related_posts() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_number() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_previous_url() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}

function article_next_url() {
	throw new DepreciatedFunctionException(__FUNCTION__ . ' function is depreciated');
}
