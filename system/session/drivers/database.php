<?php namespace System\Session\Drivers;

/**
 * Nano
 *
 * Just another php framework
 *
 * @package		nano
 * @link		http://madebykieron.co.uk
 * @copyright	http://unlicense.org/
 */

use System\Session\Driver;
use System\Database\Query;

class Database extends Driver {

	public $exists = false;

	public function read($id) {
		extract($this->config);

		// run garbage collection
		if(mt_rand(0, 100) > 90) {
			Query::table($table)->where('expire', '<', time())->delete();
		}

		// find session
		$query = Query::table($table)->where('id', '=', $id)->where('expire', '>', time());

		if($result = $query->fetch(array('data'))) {
			$this->exists = true;

			if($data = @unserialize($result->data)) {
				return $data;
			}
		}
	}

	public function write($id, $cargo) {
		extract($this->config);

		// if the session is set to never expire
		// we will set it to 1 year
		if($lifetime == 0) {
			$lifetime = (3600 * 24 * 365);
		}

		$expire = time() + $lifetime;
		$data = serialize($cargo);

		if($this->exists) {
			Query::table($table)->where('id', '=', $id)->update(array('expire' => $expire, 'data' => $data));
		}
		else {
			Query::table($table)->insert(array('id' => $id, 'expire' => $expire, 'data' => $data));
		}
	}

}