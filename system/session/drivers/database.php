<?php namespace System\Session\Drivers;

/**
 * Nano
 *
 * Lightweight php framework
 *
 * @package		nano
 * @author		k. wilson
 * @link		http://madebykieron.co.uk
 */

use System\Database\Query;
use System\Config;

class Database extends Driver {

	public function load($id) {
		$session = Query::table(Config::get('session.table'))->where('id', '=', $id)->fetch();

		if($session) {
			return array(
				'id' => $session->id,
				'date' => $session->date,
				'data' => unserialize($session->data)
			);
		}
	}

	public function save($session, $config, $exists) {
		if($exists) {
			Query::table($config['table'])->where('id', '=', $session['id'])->update(array(
				'date' => $session['date'],
				'data' => serialize($session['data'])
			));
		}
		else {
			Query::table($config['table'])->insert(array(
				'id' => $session['id'],
				'date' => $session['date'],
				'data' => serialize($session['data'])
			));
		}
	}

}