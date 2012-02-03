<?php defined('IN_CMS') or die('No direct access allowed.');

class Posts {

	public static function extend($post) {
		if(is_array($post)) {
			$posts = array();

			foreach($post as $itm) {
				$posts[] = static::extend($itm);
			}
			
			return $posts;
		}
	
		if(is_object($post)) {
			$page = IoC::resolve('postspage');
			$post->url = '/' . $page->slug . '/' . $post->slug;
			return $post;
		}
		
		return false;
	}
	
	public static function list_all($params = array()) {
		$sql = "select * from posts where 1 = 1";
		$args = array();
		
		if(isset($params['status'])) {
			$sql .= " and status = ?";
			$args[] = $params['status'];
		}
		
		if(isset($params['sortby'])) {
			$sql .= " order by " . $params['sortby'];
			
			if(isset($params['sortmode'])) {
				$sql .= " " . $params['sortmode'];
			}
		}
		
		$results = Db::results($sql, $args);
		
		// extend result set with post url
		$results = static::extend($results);

		// return items obj
		return new Items($results);
	}
	
	public static function find($where = array()) {
		$sql = "select * from posts";
		$args = array();
		
		if(count($where)) {
			$clause = array();
			foreach($where as $key => $value) {
				$clause[] = '`' . $key . '` = ?';
				$args[] = $value;
			}
			$sql .= " where " . implode(' and ', $clause);
		}

		return static::extend(Db::row($sql, $args));
	}
	
	public static function search($term, $params = array()) {
		$sql = "select * from posts where (posts.title like :term or posts.description like :term or posts.html like :term)";
		$args = array('term' => '%' . $term . '%');
		
		if(isset($params['status'])) {
			$sql .= " and posts.status = :status";
			$args['status'] = $params['status'];
		}

		return static::extend(Db::results($sql, $args));
	}
	
	public static function delete($id) {
		$sql = "delete from posts where id = ?";
		Db::query($sql, array($id));
		
		Notifications::set('success', 'Your post has been deleted');
		
		return true;
	}
	
	public static function update($id) {
		$post = Input::post(array('title', 'slug', 'description', 'html', 'css', 'js', 'status', 'delete'));
		$errors = array();

		// delete
		if($post['delete'] !== false) {
			return static::delete($id);
		} else {
			// remove it frm array
			unset($post['delete']);
		}
		
		if(empty($post['title'])) {
			$errors[] = 'Please enter a title';
		}
		
		if(empty($post['description'])) {
			$errors[] = 'Please enter a description';
		}
		
		if(empty($post['html'])) {
			$errors[] = 'Please enter your html';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['title'])));
		}
		
		$updates = array();
		$args = array();

		foreach($post as $key => $value) {
			$updates[] = '`' . $key . '` = ?';
			$args[] = $value;
		}
		
		$sql = "update posts set " . implode(', ', $updates) . " where id = ?";
		$args[] = $id;		
		
		Db::query($sql, $args);
		
		Notifications::set('success', 'Your post has been updated');
		
		return true;
	}
	
	public static function add() {
		$post = Input::post(array('title', 'slug', 'description', 'html', 'css', 'js', 'status'));
		$errors = array();
		
		if(empty($post['title'])) {
			$errors[] = 'Please enter a title';
		}
		
		if(empty($post['description'])) {
			$errors[] = 'Please enter a description';
		}
		
		if(empty($post['html'])) {
			$errors[] = 'Please enter your html';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['title'])));
		}
		
		// set creation date
		$post['created'] = date("c");
		
		// set author
		$user = Users::authed();
		$post['author'] = $user->id;
		
		$keys = array();
		$values = array();
		$args = array();
		
		foreach($post as $key => $value) {
			$keys[] = '`' . $key . '`';
			$values[] = '?';
			$args[] = $value;
		}
		
		$sql = "insert into posts (" . implode(', ', $keys) . ") values (" . implode(', ', $values) . ")";	
		
		Db::query($sql, $args);
		
		Notifications::set('success', 'Your new post has been added');
		
		return true;
	}
	
}
