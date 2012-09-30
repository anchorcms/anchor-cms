<?php defined('IN_CMS') or die('No direct access allowed.');

class Metadata {

	public static function update() {
		// verify Csrf token
		if(Csrf::verify(Input::post('token')) === false) {
			Notifications::set('error', 'Invalid token');
			return false;
		}
		
		$post = Input::post(array('sitename', 'description', 'theme', 'twitter', 'home_page', 'posts_page', 'auto_published_comments', 'posts_per_page'));
		$errors = array();
		
		if(empty($post['sitename'])) {
			$errors[] = Lang::line('metadata.missing_sitename', 'You need a site sitename');
		}
		
		if(empty($post['description'])) {
			$errors[] = Lang::line('metadata.missing_sitedescription', 'You need a site description');
		}
		
		if(empty($post['theme'])) {
			$errors[] = Lang::line('metadata.missing_theme', 'You need a theme');
		}
		
		if(substr($post['twitter'], 0, 1) === '@') {
		    $post['twitter'] = substr($post['twitter'], 1);
		}

		// auto publish comments
		$post['auto_published_comments'] = (int) !!$post['auto_published_comments'];

		// format posts per page, must be a whole number above 1 defaults to 10 if a invalid number is entered
		$post['posts_per_page'] = ($posts_per_page = intval($post['posts_per_page'])) > 0 ? $posts_per_page : 10;
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}

		foreach($post as $key => $value) {
		    Db::update('meta', array('value' => $value), array('key' => $key));
		}
				
		Notifications::set('success', Lang::line('metadata.meta_success_updated', 'Your metadata has been updated'));
		
		return true;
	}

}