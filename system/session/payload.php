<?php namespace System\Session;

use System\Config;
use System\Cookie;

class Payload {

	public $session, $driver;

	public $exists = true;

	public function __construct($driver) {
		$this->driver = $driver;
	}

	public function load($id) {
		if( ! is_null($id)) $this->session = $this->driver->load($id);

		// If the session doesn't exist or is invalid we will create a new session
		// array and mark the session as being non-existent. Some drivers, such as
		// the database driver, need to know whether it exists.
		if(is_null($this->session)) {
			$this->exists = false;

			$this->session = $this->driver->fresh();
		}
	}

	public function has($key) {
		return ! is_null($this->get($key));
	}

	public function get($key, $default = null) {
		return array_get($this->session['data'], $key, $default);
	}

	public function put($key, $value) {
		array_set($this->session['data'], $key, $value);
	}

	public function forget($key) {
		array_forget($this->session['data'], $key);
	}

	public function regenerate() {
		$this->session['id'] = $this->driver->id();

		$this->exists = false;
	}

	public function save() {
		$this->session['date'] = date('c');

		// Session flash data is only available during the request in which it
		// was flashed and the following request. We will age the data so that
		// it expires at the end of the user's next request.
		$this->age();

		$config = Config::get('session');

		// The responsibility of actually storing the session information in
		// persistent storage is delegated to the driver instance being used
		// by the session payload.
		//
		// This allows us to keep the payload very generic, while moving the
		// platform or storage mechanism code into the specialized drivers,
		// keeping our code very dry and organized.
		$this->driver->save($this->session, $config, $this->exists);

		// Next we'll write out the session cookie. This cookie contains the
		// ID of the session, and will be used to determine the owner of the
		// session on the user's subsequent requests to the application.
		$this->cookie($config);
	}

	protected function cookie($config) {
		extract($config, EXTR_SKIP);

		$lifetime = ( ! $expire_on_close) ? $lifetime : 0;

		Cookie::put($cookie, $this->session['id'], $lifetime, $path, $domain, $secure);	
	}

	protected function age() {
		$this->session['data'][':old:'] = $this->session['data'][':new:'];

		$this->session['data'][':new:'] = array();
	}

}