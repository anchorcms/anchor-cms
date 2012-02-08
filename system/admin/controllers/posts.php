<?php defined('IN_CMS') or die('No direct access allowed.');

class Posts_controller {

	public function index() {
		Template::render('posts/index');
	}
	
	public function add() {
		if(Input::method() == 'POST') {
			if(Posts::add()) {
				return Response::redirect('admin/posts');
			}
		}
		Template::render('posts/add');
	}
	
	public function edit($id) {
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
	
}
