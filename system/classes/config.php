<?php defined('IN_CMS') or die('No direct access allowed.');

/*
	A static config class to manage all of 
	our config params
*/
class Config {

	private static $items = array();
	private static $cache = array();

	/*
		Load the default config file
	*/
	public static function load($file) {
		if(file_exists($file) === false) {
			return false;
		}
		
		static::$items = array_merge(static::$items, require $file);
		
		return true;
	}

	public static function write($file, $array) {
		$php = '<?php defined(\'IN_CMS\') or die(\'No direct access allowed.\');' . PHP_EOL . PHP_EOL;
		$php .= '/**' . PHP_EOL . "\t" . 'Auto generated config' . PHP_EOL . '*/' . PHP_EOL . PHP_EOL;

		$php .= 'return array(' . PHP_EOL;

		foreach($array as $key => $value) {
			$php .= "\t'" . $key . "' => ";

			if(is_array($value)) {
				$php .= 'array(' . PHP_EOL;
				foreach($value as $k => $v) {
					$php .= "\t\t'" . $k . "' => " . static::format($v) . "," .PHP_EOL;
				}
				$php .= "\t)," . PHP_EOL;
			} else {
				$php .= static::format($value) . ',' .PHP_EOL;
			}
		}

		$php .= ');';

		return file_put_contents(PATH . 'config.php', $php);
	}

	public static function format($value) {
		if(is_int($value)) {
			return $value;
		}
		if(is_bool($value)) {
			return $value ? 'true' : 'false';
		}
		if(is_array($value)) {
			$var = array_map(function($itm) {
				return Config::format($itm);
			}, $value);

			return "array("  . implode(",", $var) . ")";
		}
		return "'" . (string) $value . "'";
	}
	
	/*
		Set a config item
	*/
	public static function set($key, $value) {
		// array pointer for search
		$array =& static::$items;

		$keys = explode('.', $key);

		while(count($keys) > 1) {
			$key = array_shift($keys);

			if(!isset($array[$key]) or !is_array($array[$key])) {
				$array[$key] = array();
			}

			$array =& $array[$key];
		}

		$array[array_shift($keys)] = $value;
	}
	
	/*
		Retrive a config param
	*/
	public static function get($key = null, $default = false) {
		// return all items
		if(is_null($key)) return static::$items;

		// copy array for search
		$array = static::$items;

		// search array
		foreach(explode('.', $key) as $segment) {
			if (!is_array($array) or array_key_exists($segment, $array) === false) {
				return $default;
			}

			$array = $array[$segment];
		}

		return $array;
	}

	/*
		Remove a config param
	*/
	public static function forget($key) {
		// array pointer for search
		$array =& static::$items;

		$keys = explode('.', $key);

		while(count($keys) > 1) {
			$key = array_shift($keys);

			if(!isset($array[$key]) or !is_array($array[$key])) {
				return;
			}

			$array =& $array[$key];
		}

		unset($array[array_shift($keys)]);
	}
}
