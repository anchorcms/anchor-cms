<?php defined('IN_CMS') or die('No direct access allowed.');

class Markdown_controller {

    public function __construct() {
        $this->admin_url = Config::get('application.admin_folder');
    }

	public function index() {
    	if(isset($_GET['wut'])) {
    	    $markdown = Markdown(Input::get('wut'));
    	    echo json_encode(array('html' => $markdown));
    	    exit;
    	}
	}
	
}
