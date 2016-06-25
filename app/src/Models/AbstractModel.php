<?php

namespace Anchorcms\Models;

abstract class AbstractModel
{

    protected $attributes;

    public function __construct(array $attributes = [])
    {
        $this->withAttributes($attributes);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function withAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function __get($key)
    {
        return $this->attributes[$key];
    }

    public function __isset($key)
    {
        return array_key_exists($key, $this->attributes);
    }

    public function __set($key, $value)
    {
        throw new \RuntimeException('Models are readonly');
    }
}
