<?php defined('IN_CMS') or die('No direct access allowed.');

/**
	Theme helpers functions
*/


// Url helpers
function absolute_url($suffix = '') {
	return Url::build(array('path' => base_url() . ltrim($suffix, '/')));
}

function base_url($url = '') {
    return Url::make($url);
}

function theme_url($file = '') {
	return Config::get('application.base_url') . 'themes/' . Config::get('metadata.theme') . '/' . ltrim($file, '/');
}

function current_url() {
	return Request::uri();
}

function admin_url($url = '') {
    return Url::make(Config::get('application.admin_folder') . '/' . ltrim($url, '/'));
}

function search_url() {
	return Url::make('search');
}

function rss_url() {
    return Url::make('rss');
}

//  Custom function helpers
function bind($page, $fn) {
	Events::bind($page, $fn);
}

function receive($name = '') {
	return Events::call($name);
}

// create a alias for typo in 0.6 and below so we dont break themes
function recieve() {
	$args = func_get_args();
	return call_user_func_array('receive', $args);
}

// page type helpers
function is_homepage() {
	if($itm = IoC::resolve('page')) {
		return $itm->id == Config::get('metadata.home_page');
	}

	return false;
}

function is_postspage() {
	if($itm = IoC::resolve('page')) {
		return $itm->id == Config::get('metadata.posts_page');
	}

	return false;
}

function is_debug() {
	return Config::get('debug', false);
}

// benchmarking
function execution_time() {
	$miliseconds = microtime(true) - ANCHOR_START;
	return round($miliseconds, 4);
}

// return in mb
function memory_usage() {
	return memory_get_peak_usage(true) / 1024;
}

// database profile information
function db_profile() {
	// total query time
	$total = 0;

	$html = '<style>';
	$html .= '.debug {display: none;font-size: 13px; margin-bottom: 1em;}';
	$html .= '.debug td, .debug th {padding: 4px 6px; border-bottom: 1px solid #ddd;}';
	$html .= '.debug th {font-weight: bold; text-align: center;}';
	$html .= '.debug tfoot td:first-child {text-align: right;}';
	$html .= '</style>';

	$html .= '<table id="debug_table" class="debug">';
	$html .= '<thead><tr><th>SQL</th><th>Bindings</th><th>Rows</th><th>Time</th></th></thead>';

	$html .= '<tbody>';

	foreach(Db::profile() as $row) {
		$html .= '<tr><td>' . $row['sql'] . '</td><td>' . implode(', ', $row['binds']) . '</td><td>' . $row['rows'] . '</td><td>' . $row['time'] . '</td></tr>';
		$total += $row['time'];
	}

	$html .= '</tbody>';

	$html .= '<tfoot>';
	$html .= '<tr><td colspan="3"><strong>Query Time</strong></td><td>' . round($total, 4) . '</td></tr>';
	$html .= '<tr><td colspan="3"><strong>Execution Time</strong></td><td>' . execution_time() . '</td></tr>';
	$html .= '<tr><td colspan="3"><strong>Memory Usage</strong></td><td>' . memory_usage() . 'Kb</td></tr>';
	$html .= '</tfoot>';

	$html .= '</table>';

	return $html;
}