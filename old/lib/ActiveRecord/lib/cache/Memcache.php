<?php
namespace ActiveRecord;

class Memcache
{
	const DEFAULT_PORT = 11211;

	private $memcache;

	/**
	 * Creates a Memcache instance.
	 *
	 * Takes an $options array w/ the following parameters:
	 *
	 * <ul>
	 * <li><b>host:</b> host for the memcache server </li>
	 * <li><b>port:</b> port for the memcache server </li>
	 * </ul>
	 * @param array $options
	 */
	public function __construct($options)
	{
		$this->memcache = new \Memcache();
		$options['port'] = isset($options['port']) ? $options['port'] : self::DEFAULT_PORT;

		if (isset($options['servers'])) {
			foreach ($options['servers'] as $server) {
				$defaults = array(
					'weight' => 1,
					'port' => self::DEFAULT_PORT,
					'persistent' => true,
				);
				$server = parse_url($server[0]) + $server + $defaults;
				if (!isset($server['host'])) {
					$server['host'] = $server['path'];
					unset($server['path']);
				}
				$this->memcache->addServer(
					$server['host'],
					$server['port'],
					$server['persistent'],
					$server['weight']
				);
			}
		} else {
			$this->memcache->addServer($options['host'], $options['port']);
		}
	}

	public function flush()
	{
		return $this->memcache->flush();
	}

	public function read($key)
	{
		return $this->memcache->get($key);
	}

	public function write($key, $value, $expire)
	{
		return $this->memcache->set($key,$value,null,$expire);
	}
}
?>