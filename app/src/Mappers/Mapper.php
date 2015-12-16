<?php

namespace Mappers;

use DB\AbstractTable;

abstract class Mapper extends AbstractTable {

	protected $prefix;

	public function setTablePrefix($prefix) {
		$this->prefix = $prefix;
		$this->name = $prefix.$this->name;
	}

	public function getTablePrefix() {
		return $this->prefix;
	}

}
