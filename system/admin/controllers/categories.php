<?php defined('IN_CMS') or die('No direct access allowed.');

class Categories_controller {

	public function __construct() {
		$this->admin_url = Config::get('application.admin_folder');
	}

	public function index() {
		$data['categories'] = Categories::list_all();
		Template::render('categories/index', $data);
	}
	
	public function add() {
		if(Input::method() == 'POST') {
			if(Categories::add()) {
				return Response::redirect($this->admin_url . '/categories/edit/' . Db::insert_id());
			}
		}

		Template::render('categories/add');
	}
	
	public function edit($id) {
		// find article
		if(($categories = Categories::find(array('id' => $id))) === false) {
			return Response::redirect($this->admin_url . '/categories');
		}

		// process post request
		if(Input::method() == 'POST') {
			if(Categories::update($id)) {
				// redirect path
				return Response::redirect($this->admin_url . '/categories/edit/' . $id);
			}
		}
	
		Template::render('categories/edit', array('category' => $categories));
	}
	
}
