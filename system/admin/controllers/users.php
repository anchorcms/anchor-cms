<?php defined('IN_CMS') or die('No direct access allowed.');

class Users_controller {

	public function index() {
		Template::render('users/index');
	}
	
	public function login() {
		if(Input::method() == 'POST') {
			if(Users::login()) {
				return Response::redirect('admin/posts');
			}
		}
		Template::render('users/login');
	}
	
	public function logout() {
		Users::logout();
		return Response::redirect('admin/login');
	}
	
	public function add() {
		if(Input::method() == 'POST') {
			if(Users::add()) {
				return Response::redirect('admin/users');
			}
		}
		Template::render('users/add');
	}
	
	public function edit($id) {
		// find user
		if(($user = Users::find(array('id' => $id))) === false) {
			Notifications::set('notice', 'User not found');
			return Response::redirect('admin/users');
		}
		
		// store object for template functions
		IoC::instance('user', $user, true);
		
		// process post request
		if(Input::method() == 'POST') {
			if(Users::update($id)) {
				// redirect path
				return Response::redirect('admin/users');
			}
		}

		Template::render('users/edit');
	}
	
}
