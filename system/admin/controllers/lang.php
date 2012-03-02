<?php defined('IN_CMS') or die('No direct access allowed.');

class lang_controller {

	public function build($file) {
		Response::header('Content-Type', 'application/json; charset=utf-8');
		Response::content(json_encode(Lang::get($file)));
	}
	
}
