<?php defined('IN_CMS') or die('No direct access allowed.');

class Curl {

	private $session, $error, $info;

	public function __construct() {
		$this->session = curl_init();
	}
	
	public static function support() {
		return function_exists('curl_init');
	}

	public function set_options($options) {
		curl_setopt_array($this->session, $options);
	}
	
	public function get_error() {
		return $this->error;
	}
	
	public function get_info() {
		return $this->info;
	}
	
	public function send() {
		if(($response = curl_exec($this->session)) === false) {
			$this->error = curl_errno($this->session) . ': ' . curl_error($this->session);
		}
		
		$this->info = curl_getinfo($this->session);
				
		return $response;
	}
	
	public function close() {
		curl_close($this->session);
	}
	
	public static function post($url, $data = array(), $headers = array()) {
		$session = new static;
		
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($data),
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true
		);
		
		if(count($headers)) {
			$options[CURLOPT_HTTPHEADER] = $headers;
		}
	
		$session->set_options($options);
		
		$response = $session->send();
		
		$session->close();
		
		return $response;
	}
	
	public static function get($url, $headers = array()) {
		$session = new static;
		
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true
		);
		
		if(count($headers)) {
			$options[CURLOPT_HTTPHEADER] = $headers;
		}
	
		$session->set_options($options);
		
		$response = $session->send();
		
		$session->close();
		
		return $response;
	}

}
