<?php defined('IN_CMS') or die('No direct access allowed.');

class lang_controller {

	public function build() {
		Response::header('Content-Type', 'application/json');

		if(Input::method() == 'POST') {
			$file = Input::post('file');
			Response::content(json_encode(Lang::get($file)));
		}
	}
	
}
