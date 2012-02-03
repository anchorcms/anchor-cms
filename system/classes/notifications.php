<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Handle error, notice and success messages in the admin
*/
class Notifications {

	public static function set($type, $message) {
		$data = Session::get('notifications', array());
		
		if(!isset($data[$type])) {
			$data[$type] = array();
		}
		
		if(!is_array($message)) {
			$message = array($message);
		}
		
		$data[$type] = array_merge($data[$type], $message);
		
		Session::set('notifications', $data);
	}

	public static function read() {
		$data = Session::get('notifications', array());
		$html = '';
		
		foreach($data as $type => $messages) {
			$html .= '<p class="notification ' . $type . '">' . implode('<br>', $messages) . '</p>';
		}
		
		Session::forget('notifications');
		
		return $html;
	}

}
