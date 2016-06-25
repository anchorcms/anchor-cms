<?php

namespace Anchorcms\Mappers;

use StdClass;

class Meta extends AbstractMapper
{

    protected $primary = 'key';

    protected $name = 'meta';

    public function all()
    {
        $meta = [];

        foreach ($this->db->fetchAll($this->query()) as $row) {
            $meta[$row['key']] = $row['value'];
        }

        return $meta;
    }

    public function key($key, $default = null)
    {
        $query = $this->query()->select('value')
            ->where('key = :key')
            ->setParameter('key', $key);

        $value = $this->db->fetchColumn($query->getSQL(), $query->getParameters());

        return false === $value ? $default : $value;
    }
}
