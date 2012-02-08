<?php defined('IN_CMS') or die('No direct access allowed.');

class Comments_controller {

	public function status() {
		Response::header('Content-Type', 'application/json');

		// process post request
		if(Input::method() == 'POST') {
			Comments::update_status();
		}
	}
	
	public function update() {
		Response::header('Content-Type', 'application/json');

		// process post request
		if(Input::method() == 'POST') {
			Comments::update();
		}
	}

	public function remove() {
		Response::header('Content-Type', 'application/json');

		// process post request
		if(Input::method() == 'POST') {
			Comments::remove();
		}
	}
	
}
