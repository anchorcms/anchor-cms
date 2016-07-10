<?php

namespace Anchorcms\Mappers;

class UserTokens extends AbstractMapper
{
    protected $primary = 'id';

    protected $name = 'user_tokens';

    public function fetchById($id)
    {
        return $this->fetchByAttribute('id', $id);
    }
}
