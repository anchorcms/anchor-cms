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
		$sql = "select * from pages";
		$args = array();
		
		if(isset($params['status'])) {
			$sql .= " where status = ?";
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
		$sql = "select count(*) from pages";
		$args = array();

		if(isset($params['status'])) {
			$sql .= " where pages.status = ?";
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
		// verify Csrf token
		if(Csrf::verify(Input::post('token')) === false) {
			Notifications::set('error', 'Invalid token');
			return false;
		}
		
		Db::delete('pages', array('id' => $id));
		
		Notifications::set('success', Lang::line('pages.page_success_delete', 'Your page has been deleted'));
		
		return true;
	}
	
	public static function update($id) {
		// verify Csrf token
		if(Csrf::verify(Input::post('token')) === false) {
			Notifications::set('error', 'Invalid token');
			return false;
		}

		$post = Input::post(array('slug', 'name', 'title', 'content', 'redirect', 'status', 'delete'));
		$errors = array();

		// delete
		if($post['delete'] !== false) {
			// prevent the deletion of the posts page and home page
			if(in_array($id, array(Config::get('metadata.home_page'), Config::get('metadata.posts_page'))) === false) {
				return static::delete($id);
			} else {
				Notifications::set('error', Lang::line('pages.page_error_delete', 'Sorry, your can not delete you home page or posts page.'));
				return false;
			}
		} else {
			// remove it frm array
			unset($post['delete']);
		}
		
		if(empty($post['name'])) {
			$errors[] = Lang::line('pages.missing_name', 'Please enter a name');
		}
		
		if(empty($post['title'])) {
			$errors[] = Lang::line('pages.missing_title', 'Please enter a title');
		}

		// check for duplicate slug
		$sql = "select id from pages where slug = ? and id <> ?";
		if(Db::row($sql, array($post['slug'], $id))) {
			$errors[] = Lang::line('pages.duplicate_slug', 'A pages with the same slug already exists, please change your page slug.');
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = $post['name'];
		}

		$post['slug'] = Str::slug($post['slug']);
		
		Db::update('pages', $post, array('id' => $id));
		
		Notifications::set('success', Lang::line('pages.page_success_updated', 'Your page has been updated'));
		
		return true;
	}
	
	public static function add() {
		// verify Csrf token
		if(Csrf::verify(Input::post('token')) === false) {
			Notifications::set('error', 'Invalid token');
			return false;
		}

		$post = Input::post(array('slug', 'name', 'title', 'content', 'redirect', 'status'));
		$errors = array();
		
		if(empty($post['name'])) {
			$errors[] = Lang::line('pages.missing_name', 'Please enter a name');
		}
		
		if(empty($post['title'])) {
			$errors[] = Lang::line('pages.missing_title', 'Please enter a title');
		}

		// check for duplicate slug
		$sql = "select id from pages where slug = ?";
		if(Db::row($sql, array($post['slug']))) {
			$errors[] = Lang::line('pages.duplicate_slug', 'A pages with the same slug already exists, please change your page slug.');
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		if(empty($post['slug'])) {
			$post['slug'] = $post['name'];
		}

		$post['slug'] = Str::slug($post['slug']);

		Db::insert('pages', $post);
		
		Notifications::set('success', Lang::line('pages.page_success_created', 'Your new page has been added'));
		
		return true;
	}

}
