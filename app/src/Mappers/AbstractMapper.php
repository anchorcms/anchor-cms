<?php

namespace Anchorcms\Mappers;

abstract class AbstractMapper {

	protected $db;

	protected $prototype;

	protected $prefix = '';

	public function __construct($db, $prototype) {
		$this->db = $db;
		$this->prototype = $prototype;
	}

	public function setTablePrefix($prefix) {
		$this->prefix = $prefix;
	}

	public function getTablePrefix() {
		return $this->prefix;
	}

	public function query() {
		return $this->db->createQueryBuilder()
			->select('*')
			->from($this->prefix.$this->name);
	}

	public function fetchByAttribute($key, $value) {
		$sql = $this->query()->where($key.' = ?');
		$row = $this->db->fetchAssoc($sql, [$value]);

		return false === $row ? false : (clone $this->prototype)->withAttributes($row);
	}

	public function fetchAll($query) {
		$models = [];

		foreach($this->db->fetchAll($query) as $row) {
			$models[] = (clone $this->prototype)->withAttributes($row);
		}

		return $models;
	}

}
