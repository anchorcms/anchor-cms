<?php defined('IN_CMS') or die('No direct access allowed.');

class Users_controller {

	public function __construct() {
		$this->admin_url = Config::get('application.admin_folder');
	}

	public function index() {
		$users = Users::list_all();
		Template::render('users/index', array('users' => $users));
	}
	
	public function login() {
		if(Input::method() == 'POST') {
			if(Users::login()) {
				return Response::redirect($this->admin_url . '/posts');
			}
		}
		Template::render('users/login');
	}
	
	public function logout() {
		Users::logout();
		return Response::redirect($this->admin_url . '/login');
	}
	
	public function amnesia() {
		if(Input::method() == 'POST') {
			if(Users::recover_password()) {
				return Response::redirect($this->admin_url . '/users/login');
			}
		}
		Template::render('users/amnesia');
	}
	
	public function reset($hash) {
		// find user
		if(($user = Users::find(array('hash' => $hash))) === false) {
			Notifications::set('error', 'User not found');
			return Response::redirect($this->admin_url . '/users');
		}

		if(Input::method() == 'POST') {
			if(Users::reset_password($user->id)) {
				return Response::redirect($this->admin_url);
			}
		}

		Template::render('users/reset', array('user' => $user));
	}
	
	public function add() {
		if(Input::method() == 'POST') {
			if(Users::add()) {
				return Response::redirect($this->admin_url . '/users/edit/' . Db::insert_id());
			}
		}
		Template::render('users/add');
	}
	
	public function edit($id) {
		// find user
		if(($user = Users::find(array('id' => $id))) === false) {
			return Response::redirect($this->admin_url . '/users');
		}

		// process post request
		if(Input::method() == 'POST') {
			if(Users::update($id)) {
				// redirect path
				return Response::redirect($this->admin_url . '/users/edit/' . $id);
			}
		}

		Template::render('users/edit', array('user' => $user));
	}
	
}
