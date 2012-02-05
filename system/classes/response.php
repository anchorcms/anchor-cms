<?php defined('IN_CMS') or die('No direct access allowed.');

class Response {
	
	private static $content = '';
	private static $headers = array();
	private static $status = 200;
	
	private static $statuses = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded'
	);
	
	public static function header($name, $value) {
		static::$headers[$name] = $value;
	}
	
	public static function content($str) {
		static::$content = $str;
	}
	
	public static function append($str) {
		static::$content .= $str;
	}
	
	public static function error($code = 500) {
		if(isset(static::$statuses[$code])) {
			static::$status = $code;
		}

		switch($code) {
			case 404:
				Template::render(404);
		}
	}
	
	public static function send() {
		// set content type
		if(array_key_exists('Content-Type', static::$headers) === false) {
			static::$headers['Content-Type'] = 'text/html; charset=UTF-8';
		}

		// send headers
		if(headers_sent() === false) {
		
			$protocol = Input::server('server_protocol', 'HTTP/1.1');

			header($protocol . ' ' . static::$status . ' ' . static::$statuses[static::$status]);

			foreach(static::$headers as $name => $value) {
				header($name . ': ' . $value, true);
			}
		}

		// Send it to the browser!
		echo static::$content;
	}
	
	public static function redirect($url) {
		static::header('Location', Url::make($url));
		static::$status = 302;
		static::$content = '';
	}
	
}
