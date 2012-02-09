<?php defined('IN_CMS') or die('No direct access allowed.');

class Pages_controller {

	public function index() {
		$pages = Pages::list_all();
		Template::render('pages/index', array('pages' => $pages));
	}
	
	public function add() {
		if(Input::method() == 'POST') {
			if(Pages::add()) {
				return Response::redirect('admin/pages');
			}
		}
		Template::render('pages/add');
	}
	
	public function edit($id) {
		// find page
		if(($page = Pages::find(array('id' => $id))) === false) {
			Notifications::set('notice', 'Page not found');
			return Response::redirect('admin/pages');
		}

		// process post request
		if(Input::method() == 'POST') {
			if(Pages::update($id)) {
				// redirect path
				return Response::redirect('admin/pages');
			}
		}

		Template::render('pages/edit', array('page' => $page));
	}
	
}
