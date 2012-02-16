<?php defined('IN_CMS') or die('No direct access allowed.');

class Pages {

	public static function extend($page) {
		if(is_array($page)) {
			$pages = array();

			foreach($page as $itm) {
				$pages[] = static::extend($itm);
			}
			
			return $pages;
		}
	
		if(is_object($page)) {
			$uri = Request::uri();
			$page->url = Url::make($page->slug);

			$page->active = false;
			
			if($current = IoC::resolve('page')) {
				if($current->id == $page->id) {
					$page->active = true;
				}
			}

			return $page;
		}
		
		return false;
	}

	public static function list_all($params = array()) {
		$sql = "select * from pages where 1 = 1";
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

		// extend data set
		$pages = static::extend(Db::results($sql, $args));

		// create iterable object
		return new Items($pages);
	}

	public static function count($params = array()) {
		$sql = "select count(*) from pages where 1 = 1";
		$args = array();

		if(isset($params['status'])) {
			$sql .= " and pages.status = ?";
			$args[] = $params['status'];
		}

		// get total
		return Db::query($sql, $args)->fetchColumn();
	}

	public static function find($where = array()) {
		$sql = "select * from pages";
		$args = array();
		
		if(count($where)) {
			$clause = array();
			foreach($where as $key => $value) {
				$clause[] = '`' . $key . '` = ?';
				$args[] = $value;
			}
			$sql .= " where " . implode(' and ', $clause);
		}

		// extend page object
		return static::extend(Db::row($sql, $args));
	}
	
	public static function delete($id) {
		Db::delete('pages', array('id' => $id));
		
		Notifications::set('success', 'Your page has been deleted');
		
		return true;
	}
	
	public static function update($id) {
		$post = Input::post(array('slug', 'name', 'title', 'content', 'status', 'delete'));
		$errors = array();

		// delete
		if($post['delete'] !== false) {
			// prevent the deletion of the posts page and home page
			if(in_array($id, array(Config::get('metadata.home_page'), Config::get('metadata.posts_page'))) === false) {
				return static::delete($id);
			} else {
				Notifications::set('error', 'Sorry, you can not delete you home page or posts page.');
				return false;
			}
		} else {
			// remove it frm array
			unset($post['delete']);
		}
		
		if(empty($post['name'])) {
			$errors[] = 'Please enter a name';
		}
		
		if(empty($post['title'])) {
			$errors[] = 'Please enter a title';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['name'])));
		}
		
		Db::update('pages', $post, array('id' => $id));
		
		Notifications::set('success', 'Your page has been updated');
		
		return true;
	}
	
	public static function add() {
		$post = Input::post(array('slug', 'name', 'title', 'content', 'status'));
		$errors = array();
		
		if(empty($post['name'])) {
			$errors[] = 'Please enter a name';
		}
		
		if(empty($post['title'])) {
			$errors[] = 'Please enter a title';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = preg_replace('/\W+/', '-', trim(strtolower($post['name'])));
		}

		Db::insert('pages', $post);
		
		Notifications::set('success', 'Your new page has been added');
		
		return true;
	}

}
