<?php namespace System;

class Request {

	public static function method() {
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	public static function protocol() {
		return $_SERVER['SERVER_PROTOCOL'];
	}

}