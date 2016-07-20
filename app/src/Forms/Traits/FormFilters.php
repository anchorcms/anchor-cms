<?php

namespace Anchorcms\Forms\Traits;

trait FormFilters
{
    protected $filters = [];

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }

    public function withFilters(array $filters)
    {
        $this->setFilters($filters);

        return $this;
    }

    public function pushFilter($key, $value)
    {
        $this->filters[$key] = $value;
    }
}
