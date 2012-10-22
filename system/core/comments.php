<?php defined('IN_CMS') or die('No direct access allowed.');

class Comments {

	public static function list_all($params = array()) {
		$sql = "select * from comments where 1 = 1";
		$args = array();

		if(isset($params['status'])) {
			$sql .= " and status = ?";
			$args[] = $params['status'];
		}

		if(isset($params['post'])) {
			$sql .= " and post = ?";
			$args[] = $params['post'];
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

	public static function add($post_id) {
		$post = Input::post(array('name', 'email', 'text'));
		$errors = array();

		if(empty($post['name'])) {
			$errors[] = 'Please enter your name';
		}

		if(filter_var($post['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'Please enter a valid email address';
		}

		if(empty($post['text'])) {
			$errors[] = 'Please enter your comments';
		}

		if(count($errors)) {
			Notifications::set('error', $errors, 'comments');
			return false;
		}

		$post['date'] = time();
		$post['status'] = Config::get('metadata.auto_published_comments', 0) ? 'published' : 'pending';
		$post['post'] = $post_id;

		// encode any html
		$post['text'] = strip_tags($post['text'], '<a>,<b>,<blockquote>,<code>,<em>,<i>,<p>,<pre>');

		Db::insert('comments', $post);

		Notifications::set('success', 'Your comment has been sent', 'comments');

		return true;
	}

	public static function update() {
		$post = Input::post(array('id', 'text', 'status'));
		$errors = array();

		if(empty($post['text'])) {
			$errors[] = 'Please enter comment text';
		}

		if(count($errors)) {
			$output = json_encode(array('result' => false, 'errors' => $errors));
			Response::content($output);
			return false;
		}

		$id = $post['id'];
		unset($post['id']);

		Db::update('comments', $post, array('id' => $id));

		$output = json_encode(array('result' => true));
		Response::content($output);
	}

	public static function update_status() {
		$post = Input::post(array('id', 'status'));
		$errors = array();

		if(in_array($post['status'], array('published', 'pending', 'spam')) === false) {
			$errors[] = 'Invalid comment status: ' . $post['status'];
		}

		if(count($errors)) {
			$output = json_encode(array('result' => false, 'errors' => $errors));
			Response::content($output);
			return false;
		}

		Db::update('comments', array('status' => $post['status']), array('id' => $post['id']));

		$output = json_encode(array('result' => true));
		Response::content($output);
	}

	public static function remove() {
		$id = Input::post('id');

		Db::delete('comments', array('id' => $id));

		$output = json_encode(array('result' => true));
		Response::content($output);
	}

}
