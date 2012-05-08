<?php defined('IN_CMS') or die('No direct access allowed.');

class Plugins_controller {
	public function __construct() {
		$this->admin_url = Config::get('application.admin_folder');
	}

	public function index() {
		$data['plugins'] = Plugins::get_hooks();
		$data['files'] = Plugins::$plugins;
		$data['names'] = Plugins::$files;
		Template::render('plugins/index', $data);
	}
}