<?php

namespace Mappers;

use DB\Row;

class Factory {

	public static function create($app, $name) {
		$proto = new Row;
		$class = '\\Mappers\\'.$name;

		$query = clone $app['query'];

		$mapper = new $class($query->reset(), $proto);

		// set table prefix
		$config = $app['config']->get('db');
		$mapper->setTablePrefix($config['table_prefix']);

		return $mapper;
	}

}
