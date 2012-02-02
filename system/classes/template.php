<?php defined('IN_CMS') or die('No direct access allowed.');

class Template {

	private static $path, $funcs;
	
	public static function theme_funcs($file) {
		// include theming functions
		if(file_exists($file)) {
			static::$funcs = $file;
			return true;
		}
		
		return false;
	}

	public static function path($path = '') {
		if(empty($path)) {
			return static::$path;
		}

		static::$path = $path;
	}

	private static function parse($file) {
		// render content into response
		ob_start();
		
		require $file;
		
		Response::append(ob_get_contents());
		
		ob_end_clean();
	}
	
	public static function render($template) {
		// get default theme
		$theme = Config::get('theme');
		
		// load theme functions
		if(static::$funcs) {
			require static::$funcs;
		}

		// render files
		foreach(array('includes/header', $template, 'includes/footer') as $file) {
			$filepath = static::$path . $file . '.php';

			if(file_exists($filepath) === false) {
				throw new ErrorException('Theme file <strong>themes/' . $theme . '/' . $file . '.php</strong> not found.');
			}
			
			static::parse($filepath);
		}
	}
	
}
