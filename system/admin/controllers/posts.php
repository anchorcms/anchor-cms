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
				return Response::redirect($this->admin_url . '/posts/edit/' . Db::insert_id());
			}
		}

		Template::render('posts/add');
	}
	
	public function edit($id) {
		// find article
		if(($article = Posts::find(array('id' => $id))) === false) {
			return Response::redirect($this->admin_url . '/posts');
		}

		// process post request
		if(Input::method() == 'POST') {
			if(Posts::update($id)) {
				// redirect path
				return Response::redirect($this->admin_url . '/posts/edit/' . $id);
			}
		}
		
		// get comments
		$comments = Comments::list_all(array('post' => $id));
		$pending = array();
		
		foreach($comments as $comment) {
		    if($comment->status == 'pending') {
		        $pending[] = $comment->id;
		    }
		}
		
		$pending = count($pending);

		// get posts page
		$page = Pages::find(array('id' => Config::get('metadata.posts_page')));

		Template::render('posts/edit', array('article' => $article, 'comments' => $comments, 'page' => $page, 'pending' => $pending));
	}
	
}
