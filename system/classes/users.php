<?php defined('IN_CMS') or die('No direct access allowed.');

class Users {

	public static function authed() {
		return Session::get('user');
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
			if(($user = Users::find(array('username' => $post['user']))) === false) {
				$errors[] = 'Incorrect details';
			} else {
				// check password
				if(crypt($post['pass'], $user->password) != $user->password) {
					$errors[] = 'Incorrect details';
				}
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

}
