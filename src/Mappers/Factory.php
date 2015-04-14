<?php

namespace Mappers;

use DB\Row;

class Factory {

	public static function create($app, $name) {
		$proto = new Row;
		$class = '\\Mappers\\'.$name;
		$mapper = new $class($app['query'], $proto);

		// set table prefix
		$config = $app['config']->get('db');
		$mapper->setTablePrefix($config['table_prefix']);

		return $mapper;
	}

}
