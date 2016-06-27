<?php

namespace Anchorcms\Forms;

use Validation\AbstractRule;

class ValidateToken extends AbstractRule
{
    protected $token;

    protected $message = '%s is not a valid csrf token';

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function isValid(): bool
    {
        return hash_equals($this->getValue(), $this->token);
    }
}
