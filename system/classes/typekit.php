<?php defined('IN_CMS') or die('No direct access allowed.');

class Typekit {

	private static $uri = 'https://typekit.com/api/v1/json/kits/{id}/published';
	private static $fonts;
	
	public static function load() {
		$id = Config::get('metadata.typekit');
		
		if(empty($id)) {
			return false;
		}
		
		$uri = str_replace('{id}', $id, static::$uri);
		
		// fopen to url
		if(($content = file_get_contents($uri)) === false) {
			return false;
		}
		
		// decode content
		if(($type = json_decode($content)) === false) {
			return false;
		}
		
		if(isset($type->kit->families)) {
			static::$fonts = $type->kit->families;
		}
	}
	
	public static function fonts() {
		return static::$fonts;
	}

}
