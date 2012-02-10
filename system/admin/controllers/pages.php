<?php defined('IN_CMS') or die('No direct access allowed.');

class Pages_controller {

	public function __construct() {
		$this->admin_url = Config::get('application.admin_folder');
	}

	public function index() {
		$pages = Pages::list_all();
		Template::render('pages/index', array('pages' => $pages));
	}
	
	public function add() {
		if(Input::method() == 'POST') {
			if(Pages::add()) {
				return Response::redirect($this->admin_url . '/pages/edit/' . Db::insert_id());
			}
		}
		Template::render('pages/add');
	}
	
	public function edit($id) {
		// find page
		if(($page = Pages::find(array('id' => $id))) === false) {
			return Response::redirect($this->admin_url . '/pages');
		}

		// process post request
		if(Input::method() == 'POST') {
			if(Pages::update($id)) {
				// redirect path
				return Response::redirect($this->admin_url . '/pages/edit/' . $id);
			}
		}

		Template::render('pages/edit', array('page' => $page));
	}
	
}
