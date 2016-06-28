<?php

namespace Anchorcms\Mappers;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use Anchorcms\Models\AbstractModel;

abstract class AbstractMapper implements MapperInterface
{
    protected $db;

    protected $prototype;

    protected $prefix = '';

    public function __construct(Connection $db, AbstractModel $prototype)
    {
        $this->db = $db;
        $this->prototype = $prototype;
    }

    public function setTablePrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function getTablePrefix(): string
    {
        return $this->prefix;
    }

    public function getTableName(): string
    {
        return $this->prefix.$this->name;
    }

    public function query(): QueryBuilder
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from($this->prefix.$this->name);
    }

    public function fetchByAttribute(string $key, string $value)
    {
        $query = $this->query()
            ->where(sprintf('%s = :attr', $key))
            ->setParameter('attr', $value);

        $row = $this->db->fetchAssoc($query->getSQL(), $query->getParameters());

        return false === $row ? false : (clone $this->prototype)->withAttributes($row);
    }

    public function fetchAll(QueryBuilder $query): array
    {
        $models = [];

        foreach ($this->db->fetchAll($query->getSQL(), $query->getParameters()) as $row) {
            $models[] = (clone $this->prototype)->withAttributes($row);
        }

        return $models;
    }

    public function count(QueryBuilder $query = null): int
    {
        $query = ($query ?: $this->query())->select('COUNT(*)');

        return $this->db->fetchColumn($query->getSQL(), $query->getParameters());
    }

    public function update(int $id, array $params): bool
    {
        return $this->db->update($this->getTableName(), $params, [$this->primary => $id]);
    }

    public function insert(array $params): int
    {
        $this->db->insert($this->getTableName(), $params);

        return $this->db->lastInsertId();
    }

	public function delete(int $id): bool
    {
        return $this->db->delete($this->getTableName(), [$this->primary => $id]);
    }
}
