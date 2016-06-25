<?php

namespace Anchorcms\Mappers;

class Meta extends AbstractMapper
{
    protected $primary = 'key';

    protected $name = 'meta';

    public function all(): array
    {
        $meta = [];

        foreach ($this->db->fetchAll($this->query()) as $row) {
            $meta[$row['key']] = $row['value'];
        }

        return $meta;
    }

    public function key(string $key, $default = null): string
    {
        $query = $this->query()->select('value')
            ->where('key = :key')
            ->setParameter('key', $key);

        $value = $this->db->fetchColumn($query->getSQL(), $query->getParameters());

        return false === $value ? $default : $value;
    }
}
