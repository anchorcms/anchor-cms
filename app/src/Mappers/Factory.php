<?php

namespace Mappers;

class Factory {

	public static function create($app, $name) {
		$class = '\\Mappers\\'.$name;

		$query = clone $app['query'];

		$mapper = new $class($query->reset(), new \DB\Row);

		// set table prefix
		$prefix = $app['config']->get('db.table_prefix');

		$mapper->setTablePrefix($prefix);

		return $mapper;
	}

}
