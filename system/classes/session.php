<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	This will handle our native sessions for now 
	but provides somes flexibility in the future 
	if we decide to use other methods for 
	session management
*/

class Session {
	
	private static $id, $data = array();
	
	private static function generate($length = 32) {
		$pool = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 1);
		$value = '';

		for ($i = 0; $i < $length; $i++)  {
			$value .= $pool[mt_rand(0, 61)];
		}

		return $value;
	}

	private static function gc() {
		// dont run gc on every request
		if(mt_rand(1, 100) <= 10) {
			$sql = 'delete from sessions where date < ?';
			$expire = time() - Config::get('session.expire', 86400);

			Db::query($sql, array(date(DATE_ISO8601, $expire)));
		}
	}

	public static function start() {
		// run gc
		static::gc();
		
		// get session id
		$name = Config::get('session.name', 'anchorcms');
		static::$id = Cookie::get($name);

		if(static::$id === false) {
			Log::info('Session cookie not found: ' . $name);
			static::$id = static::generate();
		}
		
		// load session data
		$sql = "select data from sessions where id = ? and ip = ? and ua = ? limit 1";
		$args = array(static::$id, Input::ip_address(), Input::user_agent());
		
		if($session = Db::row($sql, $args)) {
			static::$data = unserialize($session->data);
		} else {
			// reset ID
			static::$id = static::generate();
			
			Db::insert('sessions', array(
				'id' => static::$id,
				'date' => date(DATE_ISO8601),
				'ip' => Input::ip_address(),
				'ua' => Input::user_agent(),
				'data' => serialize(static::$data)
			));
		}
	}
	
	public static function end() {
		// cookie details
		$name = Config::get('session.name', 'anchorcms');
		$expire = time() + Config::get('session.expire', 86400);
		$path = Config::get('session.path', '/');
		$domain = Config::get('session.domain', '');

		// update db session
		Db::update('sessions', array(
			'date' => date(DATE_ISO8601),
			'ip' => Input::ip_address(),
			'ua' => Input::user_agent(),
			'data' => serialize(static::$data)
		), array(
			'id' => static::$id
		));

		// create cookie with ID
		if(!Cookie::write($name, static::$id, $expire, $path, $domain)) {
			Log::error('Cound not write session cookie: ' . static::$id);
		}
	}
	
	public static function get($key, $default = false) {
		return isset(static::$data[$key]) ? static::$data[$key] : $default;
	}
	
	public static function set($key, $value) {
		static::$data[$key] = $value;
	}
	
	public static function forget($key) {
		if(isset(static::$data[$key])) {
			unset(static::$data[$key]);
		}
	}
	
}
