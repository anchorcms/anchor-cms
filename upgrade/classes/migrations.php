<?php

class Migrations {

	private $queries = array();
	
	/*
		Database changes
	*/
	public function query($sql) {
		$this->queries[] = $sql;
	}

	/*
		Execute
	*/
	public function apply() {
		foreach($this->queries as $sql) {
			Db::query($sql);
		}
	}

}