<?php defined('IN_CMS') or die('No direct access allowed.');

class Categories {

	public static function extend($category) {
		if(is_array($category)) {
			$categories = array();

			foreach($category as $itm) {
				$categories[] = static::extend($itm);
			}
			
			return $categories;
		}
	
		if(is_object($category)) {
			$uri = Request::uri();
			$category->url = Url::make($category->slug);

			$category->active = false;
			
			if($current = IoC::resolve('category')) {
				if($current->id == $category->id) {
					$category->active = true;
				}
			}

			return $category;
		}
		
		return false;
	}

	public static function list_all($params = array()) {
		$sql = "select * from categories where 1 = 1";
		$args = array();
		
		if(isset($params['visible'])) {
			$sql .= " and visible = ?";
			$args[] = (int) $params['visible'] == '1';
		}

				
		if(isset($params['sortby'])) {
			$sql .= " order by " . $params['sortby'];
			
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

		$result = Db::results($sql, $args);
		
		return new Items($result);
	}
	
	public static function find($where = array()) {
		$sql = "select * from categories";
		
		$args = array();
		
		if(count($where)) {
			$clause = array();
			foreach($where as $key => $value) {
				$clause[] = 'categories.' . $key . ' = ?';
				$args[] = $value;
			}
			$sql .= " where " . implode(' and ', $clause);
		}
		
		return static::extend(Db::row($sql, $args));
	}

	public static function add() {
		$fields = array('title', 'slug', 'description');
		$post = Input::post(array_merge($fields, array('visible')));
		$errors = array();


		foreach($fields as $field) {
			if(empty($post[$field])) {
				$errors[] = 'Please enter a category ' . $field;
			}
		}

		if(count($errors) > 0) {
			Notifications::set('error', $errors, 'categories');
			return false;
		}

		//  Make sure the visible count is right, yo
		$post['visible'] = (int) strtolower($post['visible']) === 'on';

		//  Add it
		Db::insert('categories', $post);
		
		Notifications::set('success', 'New category added!', 'categories');
		
		return true;
	}
	
	public static function update($id) {
		// verify Csrf token
		if(Csrf::verify(Input::post('token')) === false) {
			Notifications::set('error', 'Invalid token');
			return false;
		}
		
		$fields = array('title', 'slug', 'description');
		$post = Input::post(array_merge($fields, array('visible')));
		$errors = array();
	
	
		foreach($fields as $field) {
			if(empty($post[$field])) {
				$errors[] = 'Please enter a category ' . $field;
			}
		}
	
		if(count($errors) > 0) {
			Notifications::set('error', $errors, 'categories');
			return false;
		}
	
		//  Make sure the visible count is right, yo
		$post['visible'] = (int) strtolower($post['visible']) === 'on';
		
		// use title as fallback
		if(empty($post['slug'])) {
			$post['slug'] = $post['title'];
		}

		// format slug
		$post['slug'] = Str::slug($post['slug']);
		
		// check for duplicate slug
		$sql = "select id from categories where slug = ? and id <> ?";
		if(Db::row($sql, array($post['slug'], $id))) {
			$errors[] = Lang::line('categories.duplicate_slug', 'A category is already using that slug.');
		}

		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}

		// update row
		Db::update('categories', $post, array('id' => $id));

		Notifications::set('success', Lang::line('categories.updated_success', 'Your category has been updated.'));
		
		return true;
	}
	
	public static function remove() {
		$id = Input::post('id');

		Db::delete('categories', array('id' => $id));

		$output = json_encode(array('result' => true));
		Response::content($output);
	}

}
