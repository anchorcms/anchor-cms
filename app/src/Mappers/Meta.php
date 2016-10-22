<?php

namespace Anchorcms\Mappers;

class Meta extends AbstractMapper
{
    protected $primary = 'id';

    protected $name = 'meta';

    protected $map = [];

    public function all(): array
    {
        foreach ($this->db->fetchAll($this->query()) as $row) {
            $this->map[$row['key']] = $row['value'];
        }

        return $this->map;
    }

    public function key(string $key, $default = ''): string
    {
        // if we have already loaded it
        if (array_key_exists($key, $this->map)) {
            return $this->map[$key];
        }

        $query = $this->query()->select('value')
            ->where('key = :key')
            ->setParameter('key', $key);

        $value = $this->db->fetchColumn($query->getSQL(), $query->getParameters());

        return false === $value ? $default : $this->map[$key] = $value;
    }

    public function put(string $key, string $value): bool
    {
        $this->map[$key] = $value;

        $query = $this->query()->select('value')
            ->where('key = :key')
            ->setParameter('key', $key);

        if ($this->count($query)) {
            return $this->db->update($this->getTableName(), ['value' => $value], ['key' => $key]);
        } else {
            return $this->db->insert($this->getTableName(), [
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
