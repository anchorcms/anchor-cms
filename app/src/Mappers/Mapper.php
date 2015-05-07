<?php

namespace Mappers;

use DB\Table;

abstract class Mapper extends Table {

	public function setTablePrefix($prefix) {
		$this->name = $prefix.$this->name;
	}

}
