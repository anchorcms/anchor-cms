<?php defined('IN_CMS') or die('No direct access allowed.');

class Metadata_controller {

	public function index() {
    	if(Input::method() == 'POST') {
    		if(Metadata::update()) {
    			return Response::redirect('admin/metadata');
    		}
    	}
    	Template::render('metadata/index', array('metadata' => (object) Config::get('metadata')));
	}
	
}
