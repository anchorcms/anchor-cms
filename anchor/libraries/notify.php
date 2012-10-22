<?php

class Notify {

	public static function read() {
		$html = '';

		$types = Session::get('messages', array());

		Session::forget('messages');

		if(count($types)) {

			$html .= '<div class="notifications">';

			foreach($types as $type => $fields) {

				$html .= '<div class="' . $type . '">';

				foreach($fields as $field => $messages) {
					if(is_array($messages)) {
						foreach($messages as $message) {
							$html .= '<p>' . $message . '</p>';
						}
					}
					else {
						$html .= '<p>' . $messages . '</p>';
					}
				}

				$html .= '</div>';
			}

			$html .= '</div>';
		}

		return $html;
	}

	public static function __callStatic($method, $paramaters = array()) {
		$current = Session::get('messages', array());

		$messages = array_shift($paramaters);

		if( ! is_array($messages)) $messages = array($messages);

		if( ! isset($current[$method])) {
			$current[$method] = array();
		}

		$current[$method] = array_merge($current[$method], $messages);

		Session::put('messages', $current);
	}

}