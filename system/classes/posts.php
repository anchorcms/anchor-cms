
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
			$page = IoC::resolve('posts_page');
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
				coalesce(comments.total, 0) as total_comments,
				posts.status

			from posts 
			left join users on (users.id = posts.author) 
			left join (
				select 
					count(comments.id) as total, comments.post 
				from comments 
				where status = 'published' 
				group by comments.post
			) as comments on (posts.id = comments.post)
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
	
	public static function count($params = array()) {
		$sql = "select count(*) from posts where 1 = 1";
		$args = array();

		if(isset($params['status'])) {
			$sql .= " and posts.status = ?";
			$args[] = $params['status'];
		}

		// return total
		return Db::query($sql, $args)->fetchColumn();
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
				posts.status,
				posts.comments

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

	public static function search_count($term, $params = array()) {
		$sql = "
			select count(*) from posts 
			where (posts.title like :term or posts.description like :term or posts.html like :term)
		";
		$args = array('term' => '%' . $term . '%');

		if(isset($params['status'])) {
			$sql .= " and posts.status = :status";
			$args['status'] = $params['status'];
		}

		// return total
		return Db::query($sql, $args)->fetchColumn();
	}
	
	public static function delete($id) {
		Db::delete('posts', array('id' => $id));
		Db::delete('comments', array('post' => $id));
		
		Notifications::set('success', 'Your post has been deleted');
		
		return true;
	}
	
	public static function update($id) {
		$post = Input::post(array('title', 'slug', 'description', 'html', 
			'css', 'js', 'status', 'delete', 'field', 'comments'));
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
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['title'])));
		}

		// check for duplicate slug
		$sql = "select id from posts where slug = ? and id <> ?";
		if(Db::row($sql, array($post['slug'], $id))) {
			$errors[] = 'A post with the same slug already exists, please change your post slug.';
		}

		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}

		$custom = array();
		
		if(is_array($post['field'])) {
			foreach($post['field'] as $keylabel => $value) {
				list($key, $label) = explode(':', $keylabel);
				$custom[$key] = array('label' => $label, 'value' => $value);
			}
		}
		
		// remove from update
		unset($post['field']);
		
		$post['custom_fields'] = json_encode($custom);

		// update row
		Db::update('posts', $post, array('id' => $id));

		Notifications::set('success', 'Your post has been updated.');
		
		return true;
	}
	
	public static function add() {
		$post = Input::post(array('title', 'slug', 'description', 'html', 
			'css', 'js', 'status', 'field', 'comments'));
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
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['title'])));
		}

		// check for duplicate slug
		$sql = "select id from posts where slug = ?";
		if(Db::row($sql, array($post['slug']))) {
			$errors[] = 'A post with the same slug already exists, please change your post slug.';
		}

		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}

		$custom = array();
		
		if(is_array($post['field'])) {
			foreach($post['field'] as $keylabel => $value) {
				list($key, $label) = explode(':', $keylabel);
				$custom[$key] = array('label' => $label, 'value' => $value);
			}
		}
		
		// remove from update
		unset($post['field']);
		
		$post['custom_fields'] = json_encode($custom);
		
		// set creation date
		$post['created'] = time();
		
		// set author
		$user = Users::authed();
		$post['author'] = $user->id;

		Db::insert('posts', $post);
		
		Notifications::set('success', 'Your new post has been added');
		
		return true;
	}
	
}
