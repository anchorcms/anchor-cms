<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	This class contains all routing details
	for the admin
*/
class Routes_admin {

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
	
	public function posts($action = '', $id = 0) {
		$reflector = new ReflectionClass(__CLASS__);
		$action = 'post_' . $action;
		
		if($reflector->hasMethod($action)) {
			return $reflector->getMethod($action)->invokeArgs($this, array($id));
		}

		Template::render('posts/index');
	}
	
    public function metadata() {
    	if(Input::method() == 'POST') {
    		Metadata::update();
    	}
    	Template::render('metadata/index');
    }

	public function post_add() {
		if(Input::method() == 'POST') {
			if(Posts::add()) {
				return Response::redirect('admin/posts');
			}
		}
		Template::render('posts/add');
	}
	
	public function post_edit($id = 0) {
	
		// find article
		if(($article = Posts::find(array('id' => $id))) === false) {
			Notifications::set('notice', 'Post not found');
			return Response::redirect('admin/posts');
		}
		
		// store object for template functions
		IoC::instance('article', $article, true);
		
		// process post request
		if(Input::method() == 'POST') {
			if(Posts::update($id)) {
				// redirect path
				return Response::redirect('admin/posts');
			}
		}

		Template::render('posts/edit');
	}
	
	public function pages($action = '', $id = 0) {
		$reflector = new ReflectionClass(__CLASS__);
		$action = 'page_' . $action;
		
		if($reflector->hasMethod($action)) {
			return $reflector->getMethod($action)->invokeArgs($this, array($id));
		}

		Template::render('pages/index');
	}
	
	public function page_add() {
		if(Input::method() == 'POST') {
			if(Pages::add()) {
				return Response::redirect('admin/pages');
			}
		}
		Template::render('pages/add');
	}
	
	public function page_edit($id = 0) {
	
		// find page
		if(($page = Pages::find(array('id' => $id))) === false) {
			Notifications::set('notice', 'Page not found');
			return Response::redirect('admin/pages');
		}
		
		// store object for template functions
		IoC::instance('page', $page, true);
		
		// process post request
		if(Input::method() == 'POST') {
			if(Pages::update($id)) {
				// redirect path
				return Response::redirect('admin/pages');
			}
		}

		Template::render('pages/edit');
	}
	
	public function users($action = '', $id = 0) {
		$reflector = new ReflectionClass(__CLASS__);
		$action = 'user_' . $action;
		
		if($reflector->hasMethod($action)) {
			return $reflector->getMethod($action)->invokeArgs($this, array($id));
		}

		Template::render('users/index');
	}
	
	public function user_add() {
		if(Input::method() == 'POST') {
			if(Users::add()) {
				return Response::redirect('admin/users');
			}
		}
		Template::render('users/add');
	}
	
	public function user_edit($id = 0) {
	
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
