<?php namespace System\Session\Drivers;

use System\Str;

abstract class Driver {

	abstract public function load($id);

	abstract public function save($session, $config, $exists);

	public function id() {
		$session = array();

		// If the driver is an instance of the Cookie driver, we are able to
		// just return any string since the Cookie driver has no real idea
		// of a server side persisted session with an ID.
		if($this instanceof Cookie) {
			return Str::random(40);
		}

		// We'll containue generating random IDs until we find an ID that is
		// not currently assigned to a session. This is almost definitely
		// going to happen on the first iteration.
		do {

			$session = $this->load($id = Str::random(32));			

		} while ( ! is_null($session));

		return $id;
	}

	public function fresh() {
		// We will simply generate an empty session payload array, using an ID
		// that is not currently assigned to any existing session within the
		// application and return it to the driver.
		return array('id' => $this->id(), 'data' => array(
			':new:' => array(),
			':old:' => array()
		));
	}

}