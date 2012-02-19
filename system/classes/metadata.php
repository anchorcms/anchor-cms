<?php defined('IN_CMS') or die('No direct access allowed.');

class Metadata {

	public static function update() {
		$post = Input::post(array('sitename', 'description', 'theme', 'twitter', 'home_page', 'posts_page', 'auto_published_comments', 'posts_per_page'));
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

		// auto publish comments
		$post['auto_published_comments'] = $post['auto_published_comments'] ? 1 : 0;

		// format posts per page, must be a whole number above 1 defaults to 10 if a invalid number is entered
		$post['posts_per_page'] = ($posts_per_page = intval($post['posts_per_page'])) > 0 ? $posts_per_page : 10;
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}

		foreach($post as $key => $value) {
		    Db::update('meta', array('value' => $value), array('key' => $key));
		}
				
		Notifications::set('success', 'Your metadata has been updated');
		
		return true;
	}

}