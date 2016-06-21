<?php

namespace Anchorcms\Mappers;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Anchorcms\Models\AbstractModel;

abstract class AbstractMapper {

	protected $db;

	protected $prototype;

	protected $prefix = '';

	public function __construct(Connection $db, AbstractModel $prototype) {
		$this->db = $db;
		$this->prototype = $prototype;
	}

	public function setTablePrefix(string $prefix) {
		$this->prefix = $prefix;
	}

	public function getTablePrefix(): string {
		return $this->prefix;
	}

	public function query(): QueryBuilder {
		return $this->db->createQueryBuilder()
			->select('*')
			->from($this->prefix.$this->name);
	}

	public function fetchByAttribute(string $key, string $value) {
		$query = $this->query()
			->where(sprintf('%s = :attr', $key))
			->setParameter('attr', $value);

		$row = $this->db->fetchAssoc($query->getSQL(), $query->getParameters());

		return false === $row ? false : (clone $this->prototype)->withAttributes($row);
	}

	public function fetchAll(QueryBuilder $query): array {
		$models = [];

		foreach($this->db->fetchAll($query->getSQL(), $query->getParameters()) as $row) {
			$models[] = (clone $this->prototype)->withAttributes($row);
		}

		return $models;
	}

}
