<?php

namespace Anchorcms\Forms;

interface ValidatableInterface
{
    public function getFilters(): array;

    public function getRules(): array;
}
