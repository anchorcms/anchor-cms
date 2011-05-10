<?php
namespace ActiveRecord;

/**
 * Cache::get('the-cache-key', function() {
 *     # this gets executed when cache is stale
 *     return "your cacheable datas";
 * });
 */
class Cache
{
	static $adapter = null;
	static $options = array();

	/**
	 * Initializes the cache.
	 *
	 * With the $options array it's possible to define:
	 * - expiration of the key, (time in seconds)
	 * - a namespace for the key
	 *
	 * this last one is useful in the case two applications use
	 * a shared key/store (for instance a shared Memcached db)
	 *
	 * Ex:
	 * $cfg_ar = ActiveRecord\Config::instance();
	 * $cfg_ar->set_cache('memcache://localhost:11211',array('namespace' => 'my_cool_app',
	 *                                                       'expire'    => 120));
	 *
	 * $cfg_ar->set_cache(array(
	 *    'adapter' => 'memcache',
	 *    'servers' => array(
	 *        array('10.0.0.2'),
	 *        array('10.0.0.3', 'weight' => 2)
	 *     )
	 * ));
	 *
	 * In the example above all the keys expire after 120 seconds, and the
	 * all get a prefix 'my_cool_app'.
	 *
	 * (Note: expiring needs to be implemented in your cache store.)
	 *
	 * @param string $url URL to your cache server
	 * @param array $options Specify additional options
	 */
	public static function initialize($url, $options=array())
	{
		if ($url) {
			if (is_array($url) && empty($options)) {
				$defaults = array(
					'adapter' => 'memcache',
					'host' => 'localhost',
				);

				$url += $defaults;
				$options = $url + $options;
				$file = $url['adapter'];
			} else {
				$url = parse_url($url);
				$file = $url['scheme'];
			}
			$file = ucwords(Inflector::instance()->camelize($file));
			$class = "ActiveRecord\\$file";
			require_once __DIR__ . "/cache/$file.php";
			static::$adapter = new $class($url);
		} else {
			static::$adapter = null;
		}

		static::$options = array_merge(
			array('expire' => 30, 'namespace' => ''),
			$options
		);
	}

	public static function flush()
	{
		if (static::$adapter)
			return static::$adapter->flush();
	}

	public static function get($key, $closure)
	{
		$key = static::get_namespace() . $key;
		
		if (!static::$adapter)
			return $closure();

		if (!($value = static::$adapter->read($key)))
			static::$adapter->write($key,($value = $closure()),static::$options['expire']);

		return $value;
	}

	private static function get_namespace()
	{
		return (isset(static::$options['namespace']) && strlen(static::$options['namespace']) > 0) ? (static::$options['namespace'] . "::") : "";
	}
}
?>
