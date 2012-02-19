<?php defined('IN_CMS') or die('No direct access allowed.');

class Metadata_controller {

    public function __construct() {
        $this->admin_url = Config::get('application.admin_folder');
    }

	public function index() {
    	if(Input::method() == 'POST') {
    		if(Metadata::update()) {
    			return Response::redirect($this->admin_url . '/metadata');
    		}
    	}

    	// provide a list to set for our home page and posts page
    	$pages = Pages::list_all(array('status' => 'published'));

        // list valid themes
        $themes = Themes::list_all();

    	Template::render('metadata/index', array(
            'pages' => $pages, 
            'themes' => $themes,
            'metadata' => (object) Config::get('metadata')
        ));
	}
	
}
