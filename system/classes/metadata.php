<?php defined('IN_CMS') or die('No direct access allowed.');

class Metadata {

	public static function update() {
		$post = Input::post(array('sitename', 'description', 'theme', 'twitter'));
		$errors = array();
		
		if(empty($post['sitename'])) {
			$errors[] = 'You need a site sitename';
		}
		
		if(empty($post['description'])) {
			$errors[] = 'You need a site description';
		}
		
		if(empty($post['theme'])) {
			$errors[] = 'You need a theme';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		$post['sitename'] = htmlentities($post['sitename']);
		$post['description'] = htmlentities($post['description']);
		
		$updates = array();
		$args = array();

		foreach($post as $key => $value) {
		    DB::query("update meta set `value` = ? where `key` = ?", array($value, $key));
		}
				
		Notifications::set('success', 'Your metadata has been updated');
		
		return true;
	}
}
