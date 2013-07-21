<?php

class Updates {

	const UPDATE_CHECK_URL = 'http://anchorcms.com/version';

	public function check_version() {
		// first time
		if( ! $last = Config::meta('last_update_check')) {
			$last = $this->setup_version_check();
		}

		$today = new DateTime('now', new DateTimeZone('GMT'));
		$last = new DateTime($last, new DateTimeZone('GMT'));

		$interval = $today->diff($last)->format('%d');

		// was update in the last 30 days
		if($interval > 30) $this->renew_version();
	}

	public function setup_version_check() {
		$version = $this->get_latest_version();
		$today = gmdate('Y-m-d');

		Query::table('meta')->insert(array('key' => 'last_update_check', 'value' => $today));
		Query::table('meta')->insert(array('key' => 'update_version', 'value' => $version));

		// reload database metadata
		foreach(Query::table('meta')->get() as $item) {
			$meta[$item->key] = $item->value;
		}

		Config::set('meta', $meta);

		return $today;
	}

	public function renew_version() {
		$version = $this->get_latest_version();
		$today = gmdate('Y-m-d');

		Query::table('meta')->where('key', '=', 'last_update_check')->update(array('value' => $today));
		Query::table('meta')->where('key', '=', 'update_version')->update(array('value' => $version));

		// reload database metadata
		Anchor::meta();
	}

	public function get_latest_version() {
		$url = self::UPDATE_CHECK_URL;

		if(in_array(ini_get('allow_url_fopen'), array('true', '1', 'On'))) {
			try {
				$context = stream_context_create(array('http' => array('timeout' => 2)));
				$result = file_get_contents($url, false, $context);
			} catch(Exception $e) {}
		}
		else if(function_exists('curl_init')) {
			$session = curl_init();

			curl_setopt_array($session, array(
				CURLOPT_URL => $url,
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 2
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