<?php namespace System\Session;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use System\Arr;
use System\Config;
use System\Cookie;

class Cargo {

	/**
	 * Instance of the session driver
	 *
	 * @var array
	 */
	public $driver;

	/**
	 * Session ID
	 *
	 * @var int
	 */
	public $id;

	/**
	 * Session data array
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * Create a new instance of the cargo session container
	 *
	 * @param object
	 */
	public function __construct($driver) {
		$this->driver = $driver;
	}

	/**
	 * Implementation of the read method, imports the current
	 * session if found and populates the data array
	 */
	public function read() {
		extract($this->driver->config);

		// read session ID from cookie
		$this->id = Cookie::read($cookie, 0);

		// make sure we have some data, if not lets start again
		if($data = $this->driver->read($this->id)) {
			// set the data to an empty array
			$this->data = $data;
		}
		else {
			// Cargo has expired lets create a new ID to prevent session fixation
			// @see https://www.owasp.org/index.php/Session_fixation
			$this->id = noise(32);
		}
	}

	/**
	 * Implementation of the write method, commits the session
	 * data using the storage driver
	 */
	public function write() {
		extract($this->driver->config);

		// if the session is set to never expire
		// we will set it to 1 year
		if($lifetime == 0) {
			$lifetime = (3600 * 24 * 365);
		}

		// save session ID
		Cookie::write($cookie, $this->id, ($expire_on_close ? 0 : $lifetime));

		// rotate flash data
		$this->put('_out', $this->get('_in', array()));
		$this->put('_in', array());

		// write payload to storage driver
		$this->driver->write($this->id, $this->data);
	}

	/**
	 * Get a item from the session
	 *
	 * @param string
	 * @param mixed
	 */
	public function get($key, $fallback = null) {
		return Arr::get($this->data, $key, $fallback);
	}

	/**
	 * Store a item in the session
	 *
	 * @param string
	 * @param mixed
	 */
	public function put($key, $value) {
		Arr::set($this->data, $key, $value);
	}

	/**
	 * Remove a item in the session
	 *
	 * @param string
	 * @param mixed
	 */
	public function erase($key) {
		Arr::erase($this->data, $key);
	}

	/**
	 * Gets and sets flash data which is only available for one request
	 *
	 * @param array|null
	 * @return mixed
	 */
	public function flash($data = null) {
		if(is_null($data)) {
			return $this->get('_out', array());
		}

		$this->put('_in', $data);
	}

}