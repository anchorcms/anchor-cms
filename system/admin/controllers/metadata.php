<?php defined('IN_CMS') or die('No direct access allowed.');

class Metadata_controller {

	public function index() {
    	if(Input::method() == 'POST') {
    		if(Metadata::update()) {
    			return Response::redirect('admin/metadata');
    		}
    	}

    	// provide a list to set for our home page and posts page
    	$pages = Pages::list_all(array('status' => 'published'));

    	Template::render('metadata/index', array('pages' => $pages, 'metadata' => (object) Config::get('metadata')));
	}
	
}
