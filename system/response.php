<?php namespace System;

class Response {

	private $output, $headers = array();

	public static $statuses = array(
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

	public function __construct($output, $status = 200, $headers = array()) {
		$this->output = $output;
		$this->status = $status;

		foreach($headers as $key => $val) {
			$this->headers[strtolower($key)] = $val;
		}
	}

	public static function make($output, $status = 200, $headers = array()) {
		return new static($output, $status, $headers);
	}

	public static function error($code, $vars = array()) {
		return new static(View::make('error/'.$code, $vars), $code);
	}

	public static function redirect($uri, $status = 302) {
		return static::make('', $status)->header('Location', Uri::make($uri));
	}

	public function header($name, $value) {
		$this->headers[strtolower($name)] = $value;
		return $this;
	}

	public function status($status) {
		$this->status = $status;
		return $this;
	}

	public function message() {
		return static::$statuses[$this->status];
	}

	public function headers() {
		// If the server is using FastCGI, we need to send a slightly different
		// protocol and status header than we normally would. Otherwise it will
		// not call any custom scripts setup to handle 404 responses.
		//
		// The status header will contain both the code and the status message,
		// such as "OK" or "Not Found". For typical servers, the HTTP protocol
		// will also be included with the status.
		if(isset($_SERVER['FCGI_SERVER_VERSION'])) {
			header('Status: ' . $this->status . ' ' . $this->message());
		}
		else {
			header(Request::protocol() . ' ' . $this->status .  ' ' . $this->message());
		}

		// If the content type was not set by the developer, we will set the
		// header to a default value that indicates to the browser that the
		// response is HTML and that it uses the default encoding.
		if(!isset($this->headers['content-type'])) {
			$this->header('content-type', 'text/html; charset=' . Config::get('application.encoding'));
		}

		// Once the framework controlled headers have been sentm, we can
		// simply iterate over the developer's headers and send each one
		// back to the browser for the response.
		foreach($this->headers as $name => $value) {
			header($name . ': ' . $value, true);
		}
	}

	public function cookies() {
		// All of the cookies for the response are actually stored on the
		// Cookie class until we're ready to send the response back to
		// the browser. This allows our cookies to be set easily.
		foreach(Cookie::$jar as $cookie) {
			call_user_func_array('setcookie', array_values($cookie));
		}
	}

	public function send() {
		$this->headers();

		$this->cookies();

		echo $this->output;
	}

}