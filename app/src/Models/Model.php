<?php

namespace Models;

use DB\Row;

abstract class Model extends Row {

	public function getAttribute($key) {
		return $this->attributes[$key];
	}

}
