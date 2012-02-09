<?php defined('IN_CMS') or die('No direct access allowed.');

class Posts_controller {

	public function __construct() {
		$this->admin_url = Config::get('application.admin_folder');
	}

	public function index() {
		$data['posts'] = Posts::list_all(array('sortby' => 'id', 'sortmode' => 'desc'));
		Template::render('posts/index', $data);
	}
	
	public function add() {
		if(Input::method() == 'POST') {
			if(Posts::add()) {
				return Response::redirect($this->admin_url . '/posts');
			}
		}

		Template::render('posts/add');
	}
	
	public function edit($id) {
		// find article
		if(($article = Posts::find(array('id' => $id))) === false) {
			Notifications::set('notice', 'Post not found');
			return Response::redirect($this->admin_url . '/posts');
		}

		// process post request
		if(Input::method() == 'POST') {
			if(Posts::update($id)) {
				// redirect path
				return Response::redirect($this->admin_url . '/posts');
			}
		}
		
		// get comments
		$comments = Comments::list_all(array('post' => $id));

		Template::render('posts/edit', array('article' => $article, 'comments' => $comments));
	}
	
}
