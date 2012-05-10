<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	Handle error, notice and success messages in the admin
*/
class Notifications {

	public static function set($type, $message, $group = 'default') {
		$data = Session::get('notifications', array());

		if(!isset($data[$group])) {
			$data[$group] = array();
		}
		
		if(!isset($data[$group][$type])) {
			$data[$group][$type] = array();
		}
		
		if(!is_array($message)) {
			$message = array($message);
		}
		
		$data[$group][$type] = array_merge($data[$group][$type], $message);
		
		Session::set('notifications', $data);
	}

	public static function read($group = 'default') {
		$data = Session::get('notifications', array());
		$html = '';
		
		if(isset($data[$group])) {
			foreach($data[$group] as $type => $messages) {
				$html .= '<p class="notification ' . $type . '">' . implode('.<br>', $messages) . '</p>';
			}

			unset($data[$group]);
		}

		if(empty($data)) {
			Session::forget('notifications');
		} else {
			Session::set('notifications', $data);
		}
		
		return $html;
	}

}
