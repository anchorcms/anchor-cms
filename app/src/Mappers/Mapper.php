<?php

namespace Mappers;

use DB\AbstractTable;

abstract class Mapper extends AbstractTable {

	public function setTablePrefix($prefix) {
		$this->name = $prefix.$this->name;
	}

}
