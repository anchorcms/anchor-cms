<?php defined('IN_CMS') or die('No direct access allowed.');

class Users {

	public static function authed() {
		return Session::get('user');
	}
	
	public static function list_all($params = array()) {
		$sql = "select * from users where 1 = 1";
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

		return new Items(Db::results($sql, $args));
	}
	
	public static function find($where = array()) {
		$sql = "select * from users";
		$args = array();
		
		if(count($where)) {
			$clause = array();
			foreach($where as $key => $value) {
				$clause[] = '`' . $key . '` = ?';
				$args[] = $value;
			}
			$sql .= " where " . implode(' and ', $clause);
		}
		
		return Db::row($sql, $args);
	}

	public static function login() {
		// get posted data
		$post = Input::post(array('user', 'pass', 'remember'));
		$errors = array();
		
		if(empty($post['user'])) {
			$errors[] = 'Please enter your username';
		}
		
		if(empty($post['pass'])) {
			$errors[] = 'Please enter your password';
		}

		if(empty($errors)) {
			// find user
			if($user = Users::find(array('username' => $post['user']))) {
				// check password
				if(crypt($post['pass'], $user->password) != $user->password) {
					$errors[] = 'Incorrect details';
				}
			} else {
				$errors[] = 'Incorrect details';
			}
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		// if we made it this far that means we have a winner
		Session::set('user', $user);
		
		return true;
	}

	public static function logout() {
		Session::forget('user');
	}
	
	public static function delete($id) {
		$sql = "delete from users where id = ?";
		Db::query($sql, array($id));
		
		Notifications::set('success', 'User has been deleted');
		
		return true;
	}
	
	public static function update($id) {
		$post = Input::post(array('username', 'password', 'real_name', 'bio', 'status', 'role', 'delete'));
		$errors = array();

		// delete
		if($post['delete'] !== false) {
			return static::delete($id);
		} else {
			// remove it frm array
			unset($post['delete']);
		}
		
		if(empty($post['username'])) {
			$errors[] = 'Please enter a username';
		} else {
			if(($user = static::find(array('username' => $post['username']))) and $user->id != $id) {
				$errors[] = 'Username is already being used';
			}
		}

		if(empty($post['real_name'])) {
			$errors[] = 'Please enter a display name';
		}
		
		if(strlen($post['password'])) {
			// encrypt new password
			$post['password'] = crypt($post['password']);
		} else {
			// remove it and leave it unchanged
			unset($post['password']);
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		$updates = array();
		$args = array();

		foreach($post as $key => $value) {
			$updates[] = '`' . $key . '` = ?';
			$args[] = $value;
		}
		
		$sql = "update users set " . implode(', ', $updates) . " where id = ?";
		$args[] = $id;		
		
		Db::query($sql, $args);
		
		// update user session?
		if(Users::authed()->id == $id) {
			Session::set('user', static::find(array('id' => $id)));
		}
		
		Notifications::set('success', 'User has been updated');
		
		return true;
	}

	public static function add() {
		$post = Input::post(array('username', 'password', 'real_name', 'bio', 'status', 'role'));
		$errors = array();
		
		if(empty($post['username'])) {
			$errors[] = 'Please enter a username';
		} else {
			if(static::find(array('username' => $post['username']))) {
				$errors[] = 'Username is already being used';
			}
		}
		
		if(empty($post['password'])) {
			$errors[] = 'Please enter a password';
		}
		
		if(empty($post['real_name'])) {
			$errors[] = 'Please enter a display name';
		}
		
		if(count($errors)) {
			Notifications::set('error', $errors);
			return false;
		}
		
		// encrypt password
		$post['password'] = crypt($post['password']);
		
		$keys = array();
		$values = array();
		$args = array();
		
		foreach($post as $key => $value) {
			$keys[] = '`' . $key . '`';
			$values[] = '?';
			$args[] = $value;
		}
		
		$sql = "insert into users (" . implode(', ', $keys) . ") values (" . implode(', ', $values) . ")";	
		
		Db::query($sql, $args);
		
		Notifications::set('success', 'A new user has been added');
		
		return true;
	}

}
