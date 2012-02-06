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
			$post->url = Url::make($page->slug . '/' . $post->slug);
			return $post;
		}
		
		return false;
	}
	
	public static function list_all($params = array()) {
		$sql = "
			select

				posts.id,
				posts.title,
				posts.slug,
				posts.description,
				posts.html,
				posts.css,
				posts.js,
				posts.created,
				posts.custom_fields,
				coalesce(users.real_name, posts.author) as author,
				posts.status

			from posts 
			left join users on (users.id = posts.author) 
			where 1 = 1
		";
		$args = array();
		
		if(isset($params['status'])) {
			$sql .= " and posts.status = ?";
			$args[] = $params['status'];
		}
		
		if(isset($params['sortby'])) {
			$sql .= " order by posts." . $params['sortby'];
			
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
	
	public static function total_public() {
		$sql = "select count(*) from posts where posts.status = ?";
		$args = array('published');

		// return total
		return Db::query($sql, $args)->fetchColumn();
	}
	
	public static function list_public($params = array()) {
		$sql = "
			select

				posts.id,
				posts.title,
				posts.slug,
				posts.description,
				posts.html,
				posts.css,
				posts.js,
				posts.created,
				posts.custom_fields,
				coalesce(users.real_name, posts.author) as author,
				posts.status

			from posts 
			left join users on (users.id = posts.author) 
			where posts.status = ?
		";
		$args = array('published');

		if(isset($params['sortby'])) {
			$sql .= " order by posts." . $params['sortby'];
			
			if(isset($params['sortmode'])) {
				$sql .= " " . $params['sortmode'];
			}
		}
		
		if(isset($params['limit'])) {
			$sql .= " limit " . $params['limit'];
			
			if(isset($params['offset'])) {
				$sql .= " offset " . $params['offset'];
			}
		}
		
		$results = Db::results($sql, $args);
		
		// extend result set with post url
		$results = static::extend($results);

		// return items obj
		return new Items($results);
	}
	
	public static function find($where = array()) {
		$sql = "
			select

				posts.id,
				posts.title,
				posts.slug,
				posts.description,
				posts.html,
				posts.css,
				posts.js,
				posts.created,
				posts.custom_fields,
				coalesce(users.real_name, posts.author) as author,
				coalesce(users.bio, '') as bio,
				posts.status

			from posts 
			left join users on (users.id = posts.author) 
		";
		$args = array();
		
		if(count($where)) {
			$clause = array();
			foreach($where as $key => $value) {
				$clause[] = 'posts.' . $key . ' = ?';
				$args[] = $value;
			}
			$sql .= " where " . implode(' and ', $clause);
		}

		return static::extend(Db::row($sql, $args));
	}
	
	public static function search($term, $params = array()) {
		$sql = "
			select

				posts.id,
				posts.title,
				posts.slug,
				posts.description,
				posts.html,
				posts.css,
				posts.js,
				posts.created,
				posts.custom_fields,
				coalesce(users.real_name, posts.author) as author,
				posts.status

			from posts 
			left join users on (users.id = posts.author) 

			where (posts.title like :term or posts.description like :term or posts.html like :term)
		";
		$args = array('term' => '%' . $term . '%');
		
		if(isset($params['status'])) {
			$sql .= " and posts.status = :status";
			$args['status'] = $params['status'];
		}

		$results = Db::results($sql, $args);
		
		// extend result set with post url
		$results = static::extend($results);

		// return items obj
		return new Items($results);
	}
	
	public static function delete($id) {
		$sql = "delete from posts where posts.id = ?";
		Db::query($sql, array($id));
		
		Notifications::set('success', 'Your post has been deleted');
		
		return true;
	}
	
	public static function update($id) {
		$post = Input::post(array('title', 'slug', 'description', 'html', 'css', 'js', 'status', 'delete', 'key1', 'value1', 'key2', 'value2', 'key3', 'value3'));
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
		
		$custom = array();
		for($i = 1; $i <= 3; $i++) {
		    //  If there's a matching key-value pair
		    if(isset($post['key' . $i]) && isset($post['value' . $i])) {
		        //  Add the custom fields as a JSON string
		        $custom[] = array($post['key' . $i] => $post['value' . $i]);
		        
		        //  Remove them from the list
		        unset($post['key' . $i]);
		        unset($post['value' . $i]);
		    }
		}
		
		$post['custom_fields'] = json_encode($custom);
		
		$updates = array();
		$args = array();

		foreach($post as $key => $value) {
			$updates[] = '`' . $key . '` = ?';
			$args[] = $value;
		}
		
		$sql = "update posts set " . implode(', ', $updates) . " where posts.id = ?";
		$args[] = $id;		
		
		Db::query($sql, $args);
		
		$post = static::extend(static::find(array('id' => $id)));
		Notifications::set('success', 'Your post has been updated. <a href="' . $post->url . '">View post on your site</a>');
		
		return true;
	}
	
	public static function add() {
		$post = Input::post(array('title', 'slug', 'description', 'html', 'css', 'js', 'status', 'key1', 'value1', 'key2', 'value2', 'key3', 'value3'));
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
		
		$custom = array();
		for($i = 1; $i <= 3; $i++) {
		    //  If there's a matching key-value pair
		    if(isset($post['key' . $i]) && isset($post['value' . $i])) {
		        //  Add the custom fields as a JSON string
		        $custom[] = array($post['key' . $i] => $post['value' . $i]);
		        
		        //  Remove them from the list
		        unset($post['key' . $i]);
		        unset($post['value' . $i]);
		    }
		}
		
		$post['custom_fields'] = json_encode($custom);
		
		// set creation date
		$post['created'] = time();
		
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
