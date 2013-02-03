<?php

class Update {

	public static function version() {
		// first time
		if( ! $last = Config::get('meta.last_update_check')) {
			$last = static::setup();
		}

		// was update in the last 30 days
		if(strtotime($last) < time() - (60 * 60 * 24 * 30)) {
			static::renew();
		}
	}

	public static function setup() {
		$table = Query::table('meta');
		$version = static::touch();
		$today = date('c');

		$table->insert(array('key' => 'last_update_check', 'value' => $today));
		Config::$items['meta']['last_update_check'] = $today;

		$table->insert(array('key' => 'update_version', 'value' => $version));
		Config::$items['meta']['update_version'] = $version;

		// reset cache
		Config::$cache = array();
	}

	public static function renew() {
		$table = Query::table('meta');
		$version = static::touch();
		$today = date('c');

		$table->where('key', '=', 'last_update_check')->update(array('value' => $today));
		Config::$items['meta']['last_update_check'] = $today;

		$table->where('key', '=', 'update_version')->update(array('value' => $version));
		Config::$items['meta']['update_version'] = $version;

		// reset cache
		Config::$cache = array();
	}

	public static function touch() {
		$url = 'http://anchorcms.com/version';

		if(in_array(ini_get('allow_url_fopen'), array('true', '1', 'On'))) {
			$result = file_get_contents($url);
		}
		else if(function_exists('curl_init')) {
			$session = curl_init();

			curl_setopt_array($session, array(
				CURLOPT_URL => $url,
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true
			));

			$result = curl_exec($session);

			curl_close($session);
		}
		else {
			$result = false;
		}

		return $result;
	}

}