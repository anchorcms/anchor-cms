<?php
namespace ActiveRecord;

class Memcached
{
	const DEFAULT_PORT = 11211;

	private $memcached;

	/**
	 * Creates a Memcached instance.
	 *
	 * Takes an $options array w/ the following parameters:
	 *
	 * <ul>
	 * <li><b>host:</b> host for the memcached server </li>
	 * <li><b>port:</b> port for the memcached server </li>
	 * </ul>
	 * @param array $options
	 */
	public function __construct($options)
	{
		$this->memcached = new \Memcached();

		if (isset($options['servers'])) {
			$servers = array();
			foreach ($options['servers'] as $server) {
				$defaults = array(
					'weight' => 1,
					'port' => self::DEFAULT_PORT,
				);
				$server = parse_url($server[0]) + $server + $defaults;
				if (!isset($server['host'])) {
					$server['host'] = $server['path'];
					unset($server['path']);
				}
				$servers[] = array(
					$server['host'],
					$server['port'],
					$server['weight']
				);
			}
			$this->memcached->addServers($servers);
		} else {
			$options['port'] = isset($options['port']) ? $options['port'] : self::DEFAULT_PORT;
			$this->memcached->addServer($options['host'],$options['port']);
		}

		if (isset($options['options'])) {
			foreach ($options['options'] as $option => $value) {
				$this->memcached->setOption($option, $value);
			}
		}
	}

	public function flush()
	{
		$this->memcached->flush();
	}

	public function read($key)
	{
		return $this->memcached->get($key);
	}

	public function write($key, $value, $expire)
	{
		$this->memcached->set($key,$value,$expire);
	}
}
?>