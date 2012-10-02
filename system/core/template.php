<?php defined('IN_CMS') or die('No direct access allowed.');

class Template {

	private static $path;

	public static function path($path = '') {
		if(empty($path)) {
			return static::$path;
		}

		static::$path = $path;
	}

	private static function parse($file, $data) {
		// render content into response
		ob_start();
		
		// extract vars
		extract($data, EXTR_SKIP);
		
		require $file;
		
		Response::append(ob_get_contents());
		
		ob_end_clean();
	}
	
	public static function render($template, $data = array()) {
		// get default theme
		$theme = Config::get('metadata.theme');
		
		// load global theming functions but not for the admin template
		if(strpos(static::$path, 'system/admin/theme') === false) {
			$includes = array('articles', 'categories', 'comments', 'config', 'helpers', 'menus', 'metadata', 'pages', 'posts', 'search', 'users');

			foreach($includes as $file) {
			    require PATH . 'system/functions/' . $file . '.php';
			}
		}
		
		// load theme functions
		if(file_exists(static::$path . 'functions.php')) {
			require static::$path . 'functions.php';
		}
		
		//  Add custom homepage file type
		if($itm = IoC::resolve('page') and $itm->id == Config::get('metadata.home_page') and file_exists(static::$path . 'homepage.php') !== false) {
		    $template = 'homepage';
		}

		// render files
		foreach(array('includes/header', $template, 'includes/footer') as $file) {
			$filepath = static::$path . $file . '.php';

			//  Add custom page types
			if($file === 'page' and file_exists(static::$path . 'page-' . Request::uri() . '.php') !== false) {
				$filepath = static::$path . 'page-' . Request::uri() . '.php';
			}
			
			static::parse($filepath, $data);
		}
	}
	
}
